<?php

namespace App\Exports\Report\Summary;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Report\Summary\StaffTurnOverEachMonthModel;

class StaffTurnOverEachMonthExport implements ShouldAutoSize, FromView
{
    private $company, $branch, $date, $contract_type;

    public function __construct($company, $branch, $date, $contract_type)
    {
        $this->company = $company;
        $this->branch = $branch;
        $this->date = $date;
        $this->contract_type = $contract_type;
    }

    public function view(): View
    {
        $object = new StaffTurnOverEachMonthModel();
        return view('reports.summary.staff_turn_over_each_month', [
            'contract_object' => $object->getStaffTurnOverEachMonth($this->company, $this->branch, $this->date, $this->contract_type)
        ]);
    }
}
