<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportFinalPayExport implements FromView, WithHeadings, ShouldAutoSize
{
    private $items;

    /**
     * PensionFundClaimRequestExport constructor.
     * @param $items
     */
    public function __construct($items)
    {
        $this->items = $items;
    }

    /**
     * Export from table view
     *
     * @return View
     */
    public function view(): View
    {
        return view('reports.report_final_pay', [
            'items' => $this->items
        ]);
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
    }
}
