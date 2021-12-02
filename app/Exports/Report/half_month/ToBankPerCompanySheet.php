<?php

namespace App\Exports\Report\half_month;

use App\Company;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Modules\Payroll\Entities\Payroll;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ToBankPerCompanySheet implements FromView, ShouldAutoSize, WithTitle, WithDrawings, WithColumnFormatting
{
    /** @var int */
    private $company_code;

    /** @var year-month */
    private $transaction_date;
    private $transactionCode;

    public function __construct($transaction_date, $company_code, $transactionCode)
    {
        $this->transaction_date = $transaction_date;
        $this->company_code = $company_code;
        $this->transactionCode = $transactionCode;
    }

    public function drawings()
    {
        $banner = '/images/stsk_banner.png';
        if (@$this->company_code == COMPANY_CODE['MFI']) {
            $banner = '/images/sahakrin_banner.png';
        }

        $drawing = new Drawing();
        $drawing->setPath(public_path($banner));
        $drawing->setHeight(90);
        $drawing->setOffsetY(1);
        $drawing->setOffsetX(1);
        return $drawing;
    }

    public function view(): View
    {
        $payrollHalfMonth = null;
        if (!is_null(@$this->company_code)) {
            $payrollHalfMonth = Payroll::with(['staff_personal_info'])
                ->where('transaction_code_id', @$this->transactionCode)//Here get only half/full month base on user export from half or full month report
                ->whereYear('transaction_date', date('Y', strtotime(@$this->transaction_date)))
                ->whereMonth('transaction_date', date('m', strtotime(@$this->transaction_date)))
                ->whereHas('contract', function ($query) {
                    $query->where('contract_object->company->code', @$this->company_code);
                })
                ->get();
        }
        return view('reports.payroll.half_month.to_bank_excel', [
            'items' => @$payrollHalfMonth
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        $company = Company::where('company_code', @$this->company_code)->first();
        return @$company->short_name ?: 'Company';
    }

    public function columnFormats(): array
    {
        return [
            'C' => '####-####-####-####',
        ];
    }

}