<?php

namespace App\Exports\Report\Summary;

use App\Report\Summary\SeniorityModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SeniorityByPositionLevelExport implements FromView, ShouldAutoSize
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
        $object = new SeniorityModel();
        return view('reports.summary.seniority_by_position_level', [
            'contract_object' => $object->getSeniorityByPositionLevel($this->company, $this->branch, $this->date, $this->contract_type)
        ]);
    }
}
