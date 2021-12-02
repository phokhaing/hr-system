<?php

namespace App\Exports\Report\half_month;


use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ToBankExport implements WithMultipleSheets
{
    use Exportable;

    /** @var array */
    private $company_codes;

    /** @var year-month */
    private $transaction_date;

    private $transactionCode;

    /**
     * @param year-month $transaction_date
     * @param array $companys
     */
    public function __construct($transaction_date, $company_codes, $transactionCode)
    {
        $this->transaction_date = $transaction_date;
        $this->company_codes = $company_codes;
        $this->transactionCode = $transactionCode;
    }

    public function sheets(): array
    {
        $sheets = [];
        if (@$this->company_codes && count($this->company_codes)) {
            foreach (@$this->company_codes as $key => $code) {
                $sheets[] = new ToBankPerCompanySheet(@$this->transaction_date, (int)$code, $this->transactionCode);
            }
        } else {
            $sheets[] = new ToBankPerCompanySheet(null, null, null);
        }

        return $sheets;
    }

}