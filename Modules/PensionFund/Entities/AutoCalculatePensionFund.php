<?php


namespace Modules\PensionFund\Entities;


use App\Contract;
use Carbon\Carbon;
use Modules\Payroll\Entities\PayrollSettings;

class AutoCalculatePensionFund
{
    const THREE_MONTH = 3;
    const PENSION_FUND_START_FROM_DATE = '2016-04-01';

    private $staffId;

    public function __construct($staffId = null)
    {
        $this->staffId = $staffId;
    }

    /**
     * Find total pension fund that provide by company back base on working time in year
     *  Syntax calculate = total_acr_staff * rate
     * @return array
     */
    public function calculatePFFromCompany($staffIdCard, $companyCode): array
    {
        $firstContract = $this->findLastContractOfCurrentActiveCompany($staffIdCard, $companyCode);
        if ($firstContract) {
            //Find params to calculate
            $contractStartDate = Carbon::parse($firstContract->start_date);
            $days = $this->findDaysBetweenDate($contractStartDate);
            $rate = $this->findInterestRateByDays($days);

            $lastPensionFund = PensionFunds::selectRaw("
                MAX(CAST(json_data->>'$.acr_balance_staff' as SIGNED)) as acr_balance_staff
            ")
                ->findLastPensionFundByStaff($this->staffId)
                ->first();
            $totalAcrStaff = @$lastPensionFund->acr_balance_staff;

            //Calculate give by company
            $totalAcrCompany = @$totalAcrStaff * @$rate;
            $balanceToPaid = @$totalAcrStaff + @$totalAcrCompany;

            return [
                'total_acr_company' => @$totalAcrCompany,
                'balance_to_paid' => @$balanceToPaid,
                'contract_start_date' => @$firstContract->start_date,
                'rate' => $rate
            ];
        }
        return [];
    }

    /**
     * Pension Fund interest rate by on total working year
     * @param $year
     */
    private function findInterestRate($year): float
    {
        $rate = PensionFundRate::where('json_data->year_start', '<=', $year)
            ->where('json_data->year_end', '>=', $year)
            ->first();
        return @$rate->json_data->rate;
    }

    /**
     * Pension Fund interest rate by on total working year
     * @param $days
     */
    private function findInterestRateByDays($days)
    {
        $rate = PensionFundRate::where('json_data->days_from', '<=', $days)
            ->where('json_data->days_end', '>=', $days)
            ->first();
        return @$rate->json_data->rate;
    }

    /**
     * Find total working time in year to calculate interest rate give by company
     * @param Carbon $contractStartDate => first contract start date
     * @return int
     */
    private function findYearsBetweenDate(Carbon $contractStartDate): int
    {
        $pfStartFromDate = Carbon::parse(self::PENSION_FUND_START_FROM_DATE);
        $now = Carbon::now();

        if (
            $contractStartDate->greaterThan($pfStartFromDate)
            || $contractStartDate->equalTo($pfStartFromDate)
        ) {
            $totalYear = $contractStartDate->diffInYears($now);
        } else {
            $totalYear = $pfStartFromDate->diffInYears($now);
        }
        return $totalYear;
    }

    /**
     * Find total working time in days to calculate interest rate give by company
     * @param Carbon $contractStartDate => first contract start date
     * @return int
     */
    private function findDaysBetweenDate(Carbon $contractStartDate): int
    {
        $pfStartFromDate = Carbon::parse(self::PENSION_FUND_START_FROM_DATE);
        $now = Carbon::now();
        if (
            $contractStartDate->greaterThan($pfStartFromDate)
            || $contractStartDate->equalTo($pfStartFromDate)
        ) {
            $totalYear = $contractStartDate->diffInDays($now);
        } else {
            $totalYear = $pfStartFromDate->diffInDays($now);
        }
        return $totalYear;
    }

    /**
     * Post Pension Fund for staff 5% by contract
     * Deduct on pension fund is auto calculate base on salary, no need to upload from excel
     * @param $grossSalary
     * @return float
     */
    public function calculatePensionFundDeduction($staffIdCard, $companyCode, $grossSalary, $payrollDate): float
    {
        //Staff to post pension fund must working date over 3months
        $isAvailableToPost = $this->checkStaffIsAvailableToPostPf($staffIdCard, $companyCode, $payrollDate);
        if ($isAvailableToPost) {
            $pension_fund = PayrollSettings::where('type', PAYROLL_SETTING_TYPE['PENSION_FOUND'])->first();
            return $grossSalary * @$pension_fund->json_data->rate;
        }
        return 0;
    }

    /**
     * Calculate current acr_balance_staff amount base on previous acr_balance_staff amount
     * @param $staffId
     * @param $pensionFundAmount
     * @retrun float
     */
    public function calculateAcrBalanceStaff($staffId, $pensionFundAmount): float
    {
        $acrBalanceStaff = $pensionFundAmount;
        $lastPensionFund = PensionFunds::findLastPensionFundByStaff($staffId)
            ->first();
        if ($lastPensionFund) {
            $acrBalanceStaff += $lastPensionFund->json_data->acr_balance_staff;
        }
        return $acrBalanceStaff;
    }

    /**
     * Check when ever staff starts working time in month is greater than 3 months
     * @return bool
     */
    private function checkStaffIsAvailableToPostPf($staffIdCard, $companyCode, $payrollDate): bool
    {
        $firstContractOfLastActive = $this->findLastContractOfCurrentActiveCompany($staffIdCard, $companyCode);
        $startDate = Carbon::parse($firstContractOfLastActive->start_date)->addMonthsNoOverflow(self::THREE_MONTH);
        $endOfMonthDate = Carbon::parse($payrollDate)->endOfMonth();

        return $endOfMonthDate->greaterThanOrEqualTo($startDate);
    }

    public function findLastContractOfCurrentActiveCompany($staffIdCard, $companyCode)
    {
        $contract = Contract::select([
            'start_date',
            'contract_type'
        ])->allContractWithCurrentCompany($staffIdCard, $companyCode)->get();
        /***
         * Logic to find first contract of the last active by current company to calculate pension fund,
         * do this because sometime, one staff used to active with this current company and resigned, then go to other company and come back to current company
         * Check with staff id = 1068 (cheng sotha) for en example
         */
        $firstContractOfLastActive = null;
        foreach ($contract as $key => $value) {
            if ($value->contract_type == CONTRACT_TYPE['RESIGN']
                || $value->contract_type == CONTRACT_TYPE['TERMINATE']
                || $value->contract_type == CONTRACT_TYPE['LAY_OFF']) {
                break;
            }
            $firstContractOfLastActive = $value;
        }
        return $firstContractOfLastActive;
    }
}
