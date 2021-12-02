<?php

namespace App\Exports;

use App\Report\ReportStaffContract;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class StaffActiveExport implements FromView, WithHeadings, ShouldAutoSize, WithTitle, WithColumnFormatting
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
        return view('reports.report_staff_active_excel', [
            'contracts' => $report->advanceFilter(
                $this->key_word, $this->company, $this->branch,
                $this->position, $this->gender, $this->start_date, $this->end_date, $this->contract_type
            )
                ->where("contract_object->contract_last_date", '>=', date("Y-m-d"))
                ->whereIn('contract_type', [
                    CONTRACT_ACTIVE_TYPE['FDC'],
                    CONTRACT_ACTIVE_TYPE['UDC'],
                    CONTRACT_ACTIVE_TYPE['DEMOTE'],
                    CONTRACT_ACTIVE_TYPE['PROMOTE'],
                    CONTRACT_ACTIVE_TYPE['CHANGE_LOCATION'],
                ])
                ->orderBy('id', 'DESC')->get()
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'J' => '####-####-####-####',
        ];
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
        return 'Staff Active';
    }
}
