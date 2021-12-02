<?php

namespace Modules\Payroll\Traits;


use App\Contract;
use Carbon\Carbon;

trait PayrollGenerator
{

    /**
     * Get New Staff For Full Payroll ( Just Create Contract in this month )
     *
     * @return mixed
     */
    public function getNewStaffForFullPayroll()
    {
        $collection = Contract::getAllStaffActive()
            ->whereYear('start_date', '=', Carbon::now()->year)
            ->whereMonth('start_date', '=', Carbon::now()->month)
            ->whereRaw('DAY(start_date) BETWEEN ? AND ?', [START_DATE_FULL_PAYROLL, END_DATE_FULL_PAYROLL])
            ->orderBy('start_date', 'asc')
            ->get();

        return $this->resourcePayrollFullMonth($collection);
    }

    /**
     * Get Old Staff For Full Payroll.
     *
     * @return mixed
     */
    public function getOldStaffForFullPayroll()
    {
        $collection = Contract::getAllStaffActive()
            ->whereDate('start_date', '<=', Carbon::now()->subMonth(1))
            ->orderBy('start_date', 'asc')
            ->get();
        return $this->resourcePayrollFullMonth($collection);
    }

    /**
     * Get Staff Resign For Full Payroll
     *
     * @return mixed
     */
    public function getStaffResignForFullPayroll()
    {
        $collection = Contract::getAllEndContract()
            ->whereYear('start_date', '=', Carbon::now()->year)
            ->whereMonth('start_date', '=', Carbon::now()->month)
            ->orderBy('start_date', 'asc')
            ->get();

        return $this->resourcePayrollFullMonth($collection);
    }

    /**
     * Get new staff for payroll.
     *
     * @return mixed
     */
    public function halfPayrollForNewStaff()
    {
        $collection = Contract::getAllStaffActive()
            ->whereYear('start_date', '=', Carbon::now()->year)
            ->whereMonth('start_date', '=', Carbon::now()->month)
            ->whereRaw('DAY(start_date) NOT BETWEEN ? AND ?', [START_DATE_FULL_PAYROLL, END_DATE_FULL_PAYROLL])
            ->checkingContractDidNotPostPayrollYet(date('m'), date('Y'), TRANSACTION_CODE['HALF_SALARY'])
            ->orderBy('start_date', 'asc')
            ->get();

        return $this->resourcePayroll($collection);
    }

    /**
     * Get old staff for payroll.
     *
     * @return mixed
     */
    public function halfPayrollForOldStaff()
    {
        $collection = Contract::getAllStaffActive()
            ->whereDate('start_date', '<=', Carbon::now()->subMonth(1))
            ->checkingContractDidNotPostPayrollYet(date('m'), date('Y'), TRANSACTION_CODE['HALF_SALARY'])
            ->orderBy('start_date', 'asc')
            ->get();
        return $this->resourcePayroll($collection);
    }

    /**
     * Response resource.
     *
     * @param $collection
     * @return mixed
     */
    private function resourcePayroll($collection)
    {
        $payroll = $collection->map(function ($item, $key) {
            $amount = 0;
            (@$item->contract_object['salary'] != "") ? $amount = str_replace(',', '', $item->contract_object['salary']) : "0";
            $amount = $amount / 2;
            $currency = @$item->contract_object['currency'];
            if ($currency == STORE_CURRENCY_KHR && @$item->contract_object['company']['code'] != COMPANY_CODE['MMI']) {
                $amount = round($amount, -2);
            }

            return [
                'contract_id' => $item->id,
                'staff_personal_info_id' => $item->staff_personal_info_id,
                'transaction_object' => [
                    'company' => @$item->contract_object['company']['name_en'],
                    'branch_department' => @$item->contract_object['branch_department']['name_en'],
                    'amount' => $amount,
                    'ccy' => @$currency,
                ]
            ];
        });
        return $payroll->all();
    }

    /**
     * Response payroll full month.
     *
     * @param $collection
     * @return mixed
     */
    private function resourcePayrollFullMonth($collection)
    {
        $payroll = $collection->map(function ($item, $key) {
            return [
                'contract_id' => $item->id,
                'staff_personal_info_id' => $item->staff_personal_info_id,
                'transaction_object' => [
                    'company' => @$item->contract_object['company'],
                    'branch_department' => @$item->contract_object['branch_department'],
                    'amount' => @($item->contract_object['salary']) ?: 'N/A',
                    'ccy' => @$item->contract_object['currency'],
                ]
            ];
        });
        return $payroll->all();
    }
}
