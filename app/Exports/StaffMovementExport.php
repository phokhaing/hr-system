<?php

namespace App\Exports;

use App\Report\ReportStaffContract;
use App\Report\ReportStaffMovement;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class StaffMovementExport implements FromView, ShouldAutoSize, WithTitle
{

    private $keyword, $company, $branch, $position, $gender, $start_date, $end_date;

    /**
     * StaffMovementExport constructor.
     * @param $keyword
     * @param $company
     * @param $branch
     * @param $department
     * @param $position
     * @param $gender
     * @param $start_date
     * @param $end_date
     */
    public function __construct($keyword, $company, $branch, $position, $gender, $start_date, $end_date)
    {
        $this->keyword = $keyword;
        $this->company = $company;
        $this->branch = $branch;
        $this->position = $position;
        $this->gender = $gender;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    /**
     * Export data to excel from blade
     *
     * @return View
     */
    public function view(): View
    {
        $report = new ReportStaffContract();
        return view('reports.report_staff_end_contract_movement_excel', [
            'contracts' => $report->advanceFilterExceptActiveReport(
                $this->keyword,
                $this->company,
                $this->branch,
                $this->position,
                $this->gender,
                $this->start_date,
                $this->end_date,
                CONTRACT_TYPE['CHANGE_LOCATION']
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
        return 'Staff movement';
    }
}
