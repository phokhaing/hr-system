<?php


namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Modules\Payroll\Entities\Payroll;

class ReportFullMonthPayrollExport implements FromView, ShouldAutoSize
{
    private $company, $branch, $transaction_date;

    public function __construct($company, $branch, $transaction_date)
    {
        $this->company = $company;
        $this->branch = $branch;
        $this->transaction_date = $transaction_date;
    }

    public function view(): View
    {
        $object = new Payroll();
        return view('reports.payroll.report_full_month_payroll_excel', [
            'temp_payrolls' => $object->payrollReport($this->company, $this->branch, 0, $this->transaction_date)
        ]);
    }

}