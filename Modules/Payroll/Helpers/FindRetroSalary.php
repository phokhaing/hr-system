<?php


namespace Modules\Payroll\Helpers;


use Carbon\Carbon;

/**
 * Class FindRetroSalary
 * Retro Salary will open salary in next month of full month salary
 * Condition staff who join from 20th to end of month
 * Formula (working_day * rate_perday)
 * @package Modules\Payroll\Helpers
 */
class FindRetroSalary
{
    private $contract;
    private $checkingDate;

    public function __construct($contract, $checkingDate)
    {
        $this->contract = $contract;
        $this->checkingDate = $checkingDate;
    }

    /**
     * is able to find retro salary
     */
    function isAbleToFindRetroSalary($contractDate, $checkingDate)
    {
        if (
            ($this->contract->contract_type == CONTRACT_ACTIVE_TYPE['FDC'] || $this->contract->contract_type == CONTRACT_ACTIVE_TYPE['UDC'])
            && $contractDate->year == $checkingDate->year
            && $contractDate->month == $checkingDate->month
            && ($contractDate->day >= NEW_STAFF_START_FROM_IN_20_OF_MONTH && $contractDate->day <= $checkingDate->day)
        ) {
            return true;
        }
        return false;
    }

    /**
     * Staff start between 20 to end of month => retrovative to open full month in next month and calculate salary base on total working days in that previous month
     * Find gross salary from contract base on total working days in current month
     * Formula = (working_day * rate_perday)
     * rate_perday = (base_salary / total_days_of_month)
     * working_day = parse_to_number_of_days(date_one - date_two)
     */
    function getRetroSalaryOfNewStaff()
    {
        $startDate = Carbon::parse(@$this->contract->start_date);
        $previousMonth = Carbon::parse($this->checkingDate)->subMonth(1)->endOfMonth();

        if ($this->isAbleToFindRetroSalary($startDate, $previousMonth)) {
            $totalWorkingDays = $startDate->diffInDays($previousMonth) + 1; // plus 1 because this function get only between value, ex: 20-30 = 9, but actual=10
            $salary = @$this->contract->contract_object['salary'] ?? '0';
            if (str_contains($salary, ',')) {
                $salary = floatval(str_replace(",", "", @$salary));
            }

            $grossSalary = (float)$salary;
            $salaryPerDay = $grossSalary / $previousMonth->day;
            $amount = $salaryPerDay * $totalWorkingDays;

            //Round only currency is KHR
            if (@$this->contract->contract_object['currency'] == STORE_CURRENCY_KHR) {
                $amount = round($amount, -2);
            }
            return $amount;
        } else {
            return 0;
        }
    }

    function getTotalDays(){
        $startDate = Carbon::parse(@$this->contract->start_date);
        $previousMonth = Carbon::parse($this->checkingDate)->subMonth(1)->endOfMonth();
        return $startDate->diffInDays($previousMonth);
    }

}