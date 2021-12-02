<?php

namespace Modules\PensionFund\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PensionFundStaffNoInSystemExport implements FromArray, WithHeadings, ShouldAutoSize
{
    use Exportable;

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            "#",
            "System Code",
            "Name In English",
            "Sex",
        ];
    }
}
