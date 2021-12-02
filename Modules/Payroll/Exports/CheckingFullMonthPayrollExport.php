<?php


namespace Modules\Payroll\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CheckingFullMonthPayrollExport implements FromView, ShouldAutoSize
{

    private $results;

    public function __construct($results)
    {
        $this->results = $results;
    }

    public function view(): View
    {
        return view('reports.payroll.checking_full_payroll_excel', [
            'temp_payrolls' => $this->results
        ]);
    }
}