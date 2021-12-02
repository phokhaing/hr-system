<?php


namespace Modules\Payroll\Exports;


use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Payroll\Entities\TempPayroll;
use Modules\Payroll\Entities\TransactionCode;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class TemplateDeductionExport implements FromView, ShouldAutoSize, WithEvents
{

    private $company, $branch, $transaction_type;

    /**
     * TemplateDeductionExport constructor.
     * @param $company
     * @param $branch
     * @param $transaction_type
     */
    public function __construct($company, $branch, $transaction_type)
    {
        $this->company = $company;
        $this->branch = $branch;
        $this->transaction_type = $transaction_type;
    }

    public function view(): View
    {
        $object = new TempPayroll();
        return view('reports.payroll.deduction_template_excel', [
            'temp_payrolls' => $object->search($this->company, $this->branch)->where('transaction_code_id', $this->transaction_type)->get()
        ]);
    }

    public function registerEvents(): array
    {
        return [
            // handle by a closure.
            AfterSheet::class => function(AfterSheet $event) {

                // get layout counts (add 1 to rows for heading row)
                $row_count = (new TempPayroll())->search($this->company, $this->branch)->where('transaction_code_id', $this->transaction_type)->count() + 1;
                $column_count = count((new TempPayroll())->search($this->company, $this->branch)->where('transaction_code_id', $this->transaction_type)->get()->toArray());

                // set dropdown column
                $drop_column = 'D'; //Transaction Type
                $currency = 'H'; //Currency

                // set dropdown options
                $transaction_code = TransactionCode::whereIn('addition_or_deduction', [TRANSACTION_CALCULATE_TYPE['ADDITION'], TRANSACTION_CALCULATE_TYPE['DEDUCTION'], TRANSACTION_CALCULATE_TYPE['SPACIAL']])
                    ->where('id', '!=', TRANSACTION_CODE['PENSION_FUND'])
                    ->get();
                $options = $transaction_code->pluck('id')->all();

                $currency_option = [
                    'KHR',
                    'USD'
                ];


                // set dropdown list for first data row
                $validation = $event->sheet->getCell("{$drop_column}2")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST );
                $validation->setErrorStyle(DataValidation::STYLE_INFORMATION );
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Input error');
                $validation->setError('Value is not in list.');
                $validation->setPromptTitle('Pick from list');
                $validation->setPrompt('Please pick a value from the drop-down list.');
                $validation->setFormula1(sprintf('"%s"',implode(',',$options)));

                $validation1 = $event->sheet->getCell("{$currency}2")->getDataValidation();
                $validation1->setType(DataValidation::TYPE_LIST );
                $validation1->setErrorStyle(DataValidation::STYLE_INFORMATION );
                $validation1->setAllowBlank(false);
                $validation1->setShowInputMessage(true);
                $validation1->setShowErrorMessage(true);
                $validation1->setShowDropDown(true);
                $validation1->setErrorTitle('Input error');
                $validation1->setError('Value is not in list.');
                $validation1->setPromptTitle('Pick from list');
                $validation1->setPrompt('Please pick a value from the drop-down list.');
                $validation1->setFormula1(sprintf('"%s"',implode(',',$currency_option)));

                // clone validation to remaining rows
                for ($i = 3; $i <= $row_count; $i++) {
                    $event->sheet->getCell("{$drop_column}{$i}")->setDataValidation(clone $validation);
                    $event->sheet->getCell("{$currency}{$i}")->setDataValidation(clone $validation1);
                }

                // set columns to autosize
                for ($i = 1; $i <= $column_count; $i++) {
                    $column = Coordinate::stringFromColumnIndex($i);
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}