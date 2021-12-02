<?php

namespace Modules\PensionFund\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PensionFundSummaryExport implements FromView, WithHeadings, ShouldAutoSize
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
        return view('pensionfund::export.pension_fund_summary', [
            'items' => $this->items
        ]);
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
    }
}
