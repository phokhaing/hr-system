<?php

namespace App\Exports;

use App\Report\ReportStaffProfile;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class StaffPersonalExport implements FromView, WithHeadings, ShouldAutoSize, WithTitle
{
//    use Exportable;

    private $key_word, $company, $branch, $department, $position, $gender, $start_date, $end_date;

    /**
     * StaffPersonalExport constructor.
     * @param $key_word
     * @param $company
     * @param $branch
     * @param $department
     * @param $position
     * @param $gender
     * @param $start_date
     * @param $end_date
     */
    public function __construct($key_word, $company, $branch, $department, $position, $gender, $start_date, $end_date)
    {
        $this->key_word = $key_word;
        $this->company = $company;
        $this->branch = $branch;
        $this->department = $department;
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
        $report = new ReportStaffProfile();
        return view('reports.staff_profile.report_excel', [
            'profiles' => $report->export_profile(
                $this->key_word, $this->company, $this->branch, $this->department,
                $this->position, $this->gender, $this->start_date, $this->end_date
            )->get()
        ]);
    }

    /**
     * Add header to each column on excel file
     *
     * @return array
     */
    public function headings(): array
    {
        return [
//            '#',
//            'First_name_en',
//            'Last_name_en',
//            'First_name_kh',
//            'Last_name_kh',
//            'Marital_status',
//            'Gender',
//            'Identify type',
//            'Identify code',
//            'Data of birth',
//            'Place of birth',
//            'Bank name',
//            'Bank account number',
//            'Height',
//            'Driver license',
//            'Province',
//            'District',
//            'Commune',
//            'Village',
//            'House number',
//            'Street number',
//            'Other location',
//            'Email',
//            'Phone',
//            'Emergency contact',
//            'Photo',
//            'Noted',
//            'Flag',
//            'Created_by',
//            'Updated_by',
        ];
    }

    /**
     * Title of sheet
     *
     * @return string
     */
    public function title(): string
    {
        return 'Staff profile';
    }
}
