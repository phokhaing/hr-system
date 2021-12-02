<?php


namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CheckingHalfPayrollExport implements FromView, ShouldAutoSize
{
    private $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function view(): View
    {
        return view('reports.payroll.checking_half_payroll_excel', [
            'temp_payrolls' => $this->items
        ]);
    }
}
