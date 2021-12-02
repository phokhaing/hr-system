<?php

namespace App\Exports;

use App\Report\ReportStaffContract;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class StaffEndContractExport implements FromView, WithHeadings, ShouldAutoSize, WithTitle
{
//    use Exportable;

    private $key_word, $company, $branch, $position, $gender, $start_date, $end_date, $contract_type;

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
    public function __construct($key_word, $company, $branch, $position, $gender, $start_date, $end_date, $contract_type)
    {
        $this->key_word = $key_word;
        $this->company = $company;
        $this->branch = $branch;
        $this->position = $position;
        $this->gender = $gender;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->contract_type = $contract_type;
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
                $this->key_word,
                $this->company,
                $this->branch,
                $this->position,
                $this->gender,
                $this->start_date,
                $this->end_date,
                $this->contract_type
            )
            ->whereIn('contract_type', [
                CONTRACT_END_TYPE['RESIGN'],
                CONTRACT_END_TYPE['DEATH'],
                CONTRACT_END_TYPE['TERMINATE'],
                CONTRACT_END_TYPE['LAY_OFF'],
            ])
            ->orderBy('id', 'DESC')
            ->get()
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
        return 'Staff End Contract';
    }
}
