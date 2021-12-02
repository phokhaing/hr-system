<?php

namespace Modules\HRTraining\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StaffTrainingExport implements FromView, WithHeadings, ShouldAutoSize
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
        return view('hrtraining::export.staff_training_report', [
            'items' => $this->items
        ]);
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
    }
}
