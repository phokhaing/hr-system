<?php

namespace App\Exports\Report\Summary;

use App\Report\Summary\ContractStatusModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class ContractStatusExport implements FromView, ShouldAutoSize
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
        return view('reports.summary.contract_status', [
            'contract_object' => $object->getContractStatus($this->company, $this->branch, $this->date)
        ]);
    }
}
