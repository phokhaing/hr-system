<?php

namespace App\Exports\Report\Summary;

use App\Report\Summary\ContractStatusModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class ContractStatusByPositionLevelExport implements FromView, ShouldAutoSize
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
        $object = new ContractStatusModel();
        return view('reports.summary.contract_status_by_position_level', [
            'contract_object' => $object->getContractStatusByPositionLevel($this->company, $this->branch, $this->date)
        ]);
    }
}
