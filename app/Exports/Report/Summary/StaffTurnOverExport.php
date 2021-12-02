<?php

namespace App\Exports\Report\Summary;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Report\Summary\StaffTurnOverModel;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StaffTurnOverExport implements ShouldAutoSize, FromView
{
    private $company, $branch, $date;

    public function __construct($company, $branch, $date)
    {
        $this->company = $company;
        $this->branch = $branch;
        $this->date = $date;
    }

    public function view(): View
    {
        $object = new StaffTurnOverModel();
        return view('reports.summary.staff_turn_over', [
            'contract_object' => $object->getStaffTurnOver($this->company, $this->branch, $this->date)
        ]);
    }
}
