<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StaffImport implements WithMultipleSheets
{
    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
//            [Sheet Index] => [Class Import]
//            0 => new PersonalInfoSheetImport(),
//            1 => new StaffResignImport(),
            0 => new StaffMovementImport()
        ];
    }
}
