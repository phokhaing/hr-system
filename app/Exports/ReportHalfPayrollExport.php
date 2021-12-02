<?php


namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Modules\Payroll\Entities\Payroll;

class ReportHalfPayrollExport implements FromView, ShouldAutoSize
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
        return view('reports.payroll.report_half_payroll_excel', [
            'temp_payrolls' => $object
                ->search($this->company, $this->branch, $this->transaction_date)
                ->where('transaction_code_id', '=', TRANSACTION_CODE['HALF_SALARY'])
                ->whereRaw("(transaction_object->>'$.is_block' is null or transaction_object->>'$.is_block'='false' or transaction_object->>'$.is_block'='null')")
                ->get()
        ]);
    }

}