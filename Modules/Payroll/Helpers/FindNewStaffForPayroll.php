<?php


namespace Modules\Payroll\Helpers;

use App\Contract;
use Carbon\Carbon;

class FindNewStaffForPayroll
{

    /**
     * There are three condition to calculate payroll for new staff
     * 1 - start between 01 to 05 (in current month) => open half month normal (50%)
     * 2 - start between 06 to 19 (in current month) => open full month and calculate base salary from total working days
     * 3 - start between 20 to end of month => retrovative to open full month in next month and calculate salary base on total working days in that previous month
     */

    private $contract;
    private $payrollDate;

    function __construct(Contract $contract, $payrollDate)
    {
        $this->contract = $contract;
        $this->payrollDate = $payrollDate;
    }

    /**
     * Check whenever staff has promote,depromote that contract start date (effective date) greather than current day must get previous contract base salary to calculate
     */
    function checkIsOldStaffHasNewContract()
    {
        $startDate = Carbon::parse(@$this->contract->start_date);
        // $currentDate = Carbon::now();
        $currentDate = Carbon::parse($this->payrollDate);

        if (
            $startDate->greaterThan($currentDate)
            && ($this->contract->contract_type == CONTRACT_ACTIVE_TYPE['DEMOTE']
                || $this->contract->contract_type == CONTRACT_ACTIVE_TYPE['PROMOTE']
                || $this->contract->contract_type == CONTRACT_ACTIVE_TYPE['CHANGE_LOCATION'])
        ) {
            return true;
        }
        return false;
    }


    /**
     * Check whenever has contract incoming in next future, will not available to post now,
     * Case: Sometime user book new staff's contract before staff start working
     */
    function checkIsContractIncoming()
    {
        $startDate = Carbon::parse(@$this->contract->start_date);
        $currentDate = Carbon::parse($this->payrollDate);

        if (
        $startDate->greaterThan($currentDate)
        ) {
            return true;
        }
        return false;
    }


    /**
     * start between 06 to 19 (in current month) => open full month and calculate base salary from total working days
     */
    function isNotAvailableToCheckHalfMonth()
    {

        $startDate = Carbon::parse(@$this->contract->start_date);
        // $currentDate = Carbon::now();
        $currentDate = Carbon::parse($this->payrollDate);

        // $q = $startDate->year . '-' . $startDate->month . '-' . $startDate->day;
        // $qO = $currentDate->year . '-' . $currentDate->month . '-' . $currentDate->day;
        // dd($q, $qO);

        if (
            ($this->contract->contract_type == CONTRACT_ACTIVE_TYPE['FDC'] || $this->contract->contract_type == CONTRACT_ACTIVE_TYPE['UDC'])
            && $startDate->year == $currentDate->year
            && $startDate->month == $currentDate->month
            && ($startDate->day >= START_DATE_FULL_PAYROLL && $startDate->day <= END_DATE_FULL_PAYROLL)
        ) {
            return true;
        }
        //Staff start working after payroll not avaialble to check
        // else if ($startDate->greaterThan($currentDate)) {
        //     return true;
        // }

        // else if (($startDate->year == $currentDate->year)
        //     && ($startDate->month == ($currentDate->month - 1)
        //         && ($startDate->day >= NEW_STAFF_FROM_PREVIOUS_MONTH)
        //         && ($this->contract->contract_type != CONTRACT_ACTIVE_TYPE['DEMOTE']
        //             && $this->contract->contract_type != CONTRACT_ACTIVE_TYPE['PROMOTE']))
        // ) {
        //     return true;
        // }

        return false;
    }

    /**
     * start between 20 to end of month => retrovative to open full month in next month and calculate salary base on total working days in that previous month
     */
    function isNotAvailableToCheckFullMonth()
    {
        $startDate = @$this->contract->start_date;
        $startDate = Carbon::parse($startDate);
        // $currentDate = Carbon::now()->endOfMonth();
        $currentDate = Carbon::parse($this->payrollDate)->endOfMonth();

        if (
            ($this->contract->contract_type == CONTRACT_ACTIVE_TYPE['FDC'] || $this->contract->contract_type == CONTRACT_ACTIVE_TYPE['UDC'])
            && $startDate->year == $currentDate->year
            && $startDate->month == $currentDate->month
            && ($startDate->day >= NEW_STAFF_START_FROM_IN_20_OF_MONTH && $startDate->day <= $currentDate->day)
        ) {
            return true;
        }

        return false;
    }

    function isNewStaffInMonth()
    {
        $startDate = Carbon::parse(@$this->contract->start_date);
        // $currentDate = Carbon::now();
        $currentDate = Carbon::parse($this->payrollDate);

        if (
            ($this->contract->contract_type == CONTRACT_ACTIVE_TYPE['FDC'] || $this->contract->contract_type == CONTRACT_ACTIVE_TYPE['UDC'])
            && $startDate->year == $currentDate->year
            && $startDate->month == $currentDate->month
        ) {
            return true;
        }

        return false;
    }
}
