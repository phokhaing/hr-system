<?php

namespace App\Exports;

use App\Report\ReportStaffResign;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class StaffResignExport implements FromView, ShouldAutoSize, WithTitle
{

    private $key_word, $company, $branch, $department, $position;

    /**
     * StaffPersonalExport constructor.
     * @param $key_word
     * @param $company
     * @param $branch
     * @param $department
     * @param $position
     */
    public function __construct($key_word, $company, $branch, $department, $position)
    {
        $this->key_word = $key_word;
        $this->company = $company;
        $this->branch = $branch;
        $this->department = $department;
        $this->position = $position;
    }

    /**
     * Export data to excel from blade
     *
     * @return View
     */
    public function view(): View
    {
        $report = new ReportStaffResign();
        return view('reports.staff_resign.report_excel', [
            'resigns' => $report->view_resign(
                $this->key_word, $this->company,
                $this->branch, $this->department,
                $this->position
            )->get()
        ]);
    }

    /**
     * Title of sheet
     *
     * @return string
     */
    public function title(): string
    {
        return 'Staff resign';
    }
}
