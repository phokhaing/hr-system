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
use Modules\Payroll\Entities\TempPayroll;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ByStaffPerCompanySheet implements FromView, ShouldAutoSize, WithTitle, WithDrawings, WithColumnFormatting
{
    /** @var int */
    private $company_code;

    /** @var year-month */
    private $transaction_date;
    private $company;
    private $isTempPayroll;

    public function __construct($transaction_date, $company_code, $isTempPayroll)
    {
        $this->transaction_date = $transaction_date;
        $this->company_code = $company_code;
        $this->isTempPayroll = $isTempPayroll;
        $this->company = Company::where('company_code', @$this->company_code)->first();
    }

    public function drawings()
    {
        $banner = '/images/stsk_banner.png';
        if (@$this->company_code == COMPANY_CODE['MFI']) {
            $banner = '/images/sahakrin_banner.png';
        }
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setPath(public_path($banner));
        $drawing->setHeight(90);
        $drawing->setOffsetY(1);
        $drawing->setOffsetX(1);

        return $drawing;
    }

    public function view(): View
    {
        if ($this->isTempPayroll) {
            $payroll_object = TempPayroll::query()
                ->with(['staff_personal_info', 'contract'])
                ->search($this->company_code, null, null)
                ->where('transaction_code_id', '=', TRANSACTION_CODE['HALF_SALARY'])
                ->get();
        } else {
            $payroll_object =
                Payroll::searchByCompany($this->company_code, $this->transaction_date)
                    ->where('transaction_code_id', '=', TRANSACTION_CODE['HALF_SALARY'])
                    ->whereRaw("(transaction_object->>'$.is_block' is null or transaction_object->>'$.is_block'='false' or transaction_object->>'$.is_block'='null')")
                    ->get();
        }
        $transactionDate = @$payroll_object[0]->transaction_date;
        return view('reports.payroll.half_month.payroll_half_month_get_by_staff_excel', [
            'payroll_object' => $payroll_object,
            'company' => @$this->company,
            'transaction_date' => @$transactionDate
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'F' => '####-####-####-####',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return @$this->company->short_name;
    }

}