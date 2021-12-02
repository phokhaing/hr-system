<?php

namespace App\Exports\Report\Summary;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Report\Summary\AgeRangAndGenderModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AgeRangAndGenderExport implements FromView, ShouldAutoSize
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
        $object = new AgeRangAndGenderModel();
        // $x =  $object->getAgeRangAndGender($this->company, $this->branch, $this->date);
        // dd($x);
        return view('reports.summary.age_rang_and_gender', [
            'age_gender_object' => $object->getAgeRangAndGender($this->company, $this->branch, $this->date)
        ]);
    }
}
