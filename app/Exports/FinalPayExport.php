<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class FinalPayExport implements FromView, WithHeadings, ShouldAutoSize, WithDrawings
{
    private $finalPay;
    private $contract;
    private $newDateRange;
    private $staffPersonalInfo;

    /**
     * PensionFundClaimRequestExport constructor.
     * @param $items
     */
    public function __construct($finalPay,
                                $contract,
                                $newDateRange,
                                $staffPersonalInfo)
    {
        $this->finalPay = $finalPay;
        $this->contract = $contract;
        $this->newDateRange = $newDateRange;
        $this->staffPersonalInfo = $staffPersonalInfo;
    }

    /**
     * Export from table view
     *
     * @return View
     */
    public function view(): View
    {
        return view('final_pay.export_excel', [
            'finalPay' => $this->finalPay,
            'contract' => @$this->contract,
            'newDateRange' => @$this->newDateRange,
            'staffPersonalInfo' => @$this->staffPersonalInfo,
        ]);
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Sahakrin');
        $drawing->setPath(public_path('/images/stsk_banner.png'));
        $drawing->setHeight(90);
        $drawing->setOffsetY(1);
        $drawing->setOffsetX(1);
        return $drawing;
    }
}
