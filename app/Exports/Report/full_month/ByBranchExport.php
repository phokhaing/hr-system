<?php

namespace App\Exports\Report\full_month;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Modules\Payroll\Entities\Payroll;
use Modules\Payroll\Entities\TempPayroll;

class ByBranchExport implements WithMultipleSheets
{
    use Exportable;

    /** @var array */
    private $company_codes;

    /** @var year-month */
    private $transaction_date;
    private $isTempPayroll;

    /**
     * @param year-month $transaction_date
     * @param array $companys
     */
    public function __construct($transaction_date, $company_codes, $isTempPayroll)
    {
        $this->transaction_date = $transaction_date;
        $this->company_codes = $company_codes;
        $this->isTempPayroll = $isTempPayroll;
    }

    public function sheets(): array
    {
        $sheets = [];
        if (@$this->company_codes && count($this->company_codes)) {
            foreach ($this->company_codes as $key => $code) {
                $sheets[] = new ByBranchPerCompanySheet($this->transaction_date, (int)$code, $this->isTempPayroll);
            }

            $payroll_array = [];
            foreach ($this->company_codes as $key => $code) {
                if (@$this->isTempPayroll) {
                    $payroll_array[] = TempPayroll::exportTempPayrollByBranch((int)$code, date('Y-m-d'));
                } else {
                    $payroll_array[] = Payroll::exportPayrollByBranch((int)$code, $this->transaction_date);
                }
            }
            $payroll_object = collect($payroll_array)->collapse(); // merge array of collection

            $sheets[] = new ByBranchConsolidateSheet($payroll_object);
        } else {
            $sheets[] = new ByBranchConsolidateSheet(null);
        }

        return $sheets;
    }

}