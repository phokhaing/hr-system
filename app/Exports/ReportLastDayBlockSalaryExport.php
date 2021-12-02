<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ReportLastDayBlockSalaryExport implements FromView, WithHeadings, ShouldAutoSize, WithDrawings
{
    private $items;
    private $dateFrom;
    private $dateEnd;
    private $company;

    /**
     * PensionFundClaimRequestExport constructor.
     * @param $items
     */
    public function __construct($items, $dateFrom, $dateEnd, $company)
    {
        $this->items = $items;
        $this->dateFrom = $dateFrom;
        $this->dateEnd = $dateEnd;
        $this->company = $company;
    }

    /**
     * Export from table view
     *
     * @return View
     */
    public function view(): View
    {
        return view('reports.report_last_day_block_salary', [
            'items' => $this->items,
            'date_from' => @$this->dateFrom,
            'date_end' => @$this->dateEnd,
            'company' => $this->company
        ]);
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
    }

    public function drawings()
    {
        $banner = '/images/stsk_banner.png';
        if (!is_null(@$this->company) && @$this->company->company_code == COMPANY_CODE['MFI']) {
            $banner = '/images/sahakrin_banner.png';
        }
        $drawing = new Drawing();
        $drawing->setName('Sahakrin');
        $drawing->setPath(public_path($banner));
        $drawing->setHeight(90);
        $drawing->setOffsetY(1);
        $drawing->setOffsetX(1);
        return $drawing;
    }
}
