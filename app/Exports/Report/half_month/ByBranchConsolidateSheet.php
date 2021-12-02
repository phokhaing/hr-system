<?php

namespace App\Exports\Report\half_month;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ByBranchConsolidateSheet implements FromView, ShouldAutoSize, WithTitle, WithDrawings
{
    /** @var collection */
    private $payroll_object;

    public function __construct($payroll_object)
    {
        $this->payroll_object = $payroll_object;
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setPath(public_path('/images/stsk_banner.png'));
        $drawing->setHeight(90);
        $drawing->setOffsetY(1);
        $drawing->setOffsetX(1);

        return $drawing;
    }

    public function view(): View
    {
        $transactionDate = @$this->payroll_object[0]->transaction_date;
        return view('reports.payroll.half_month.payroll_half_month_get_by_branch_excel', [
            'payroll_object' => $this->payroll_object,
            'transaction_date' => @$transactionDate
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Consolidate';
    }

}