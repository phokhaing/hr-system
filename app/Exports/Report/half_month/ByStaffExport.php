<?php

namespace App\Exports\Report\half_month;


use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Modules\Payroll\Entities\Payroll;
use Modules\Payroll\Entities\TempPayroll;

class ByStaffExport implements WithMultipleSheets
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
                $sheets[] = new ByStaffPerCompanySheet($this->transaction_date, (int)$code, $this->isTempPayroll);
            }

            $payroll_array = [];
            foreach ($this->company_codes as $key => $code) {
                if ($this->isTempPayroll) {
                    $payroll_array[] = TempPayroll::query()
                        ->with(['staff_personal_info', 'contract'])
                        ->search($code, null, null)
                        ->where('transaction_code_id', '=', TRANSACTION_CODE['HALF_SALARY'])
                        ->get();
                } else {
                    $payroll_array[] = Payroll::searchByCompany((int)$code, $this->transaction_date)
                        ->where('transaction_code_id', '=', TRANSACTION_CODE['HALF_SALARY'])
                        ->whereRaw("(transaction_object->>'$.is_block' is null or transaction_object->>'$.is_block'='false' or transaction_object->>'$.is_block'='null')")
                        ->get();

                }
            }
            $payroll_object = collect($payroll_array)->collapse(); // merge array of collection

            $sheets[] = new ByStaffConsolidateSheet($payroll_object);
        } else {
            $sheets[] = new ByStaffConsolidateSheet(null);
        }

        return $sheets;
    }

}