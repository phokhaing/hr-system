<?php

namespace Modules\PensionFund\Imports;

use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class PensionFundMigrationCheckStaffNotRegisterYetImport implements ToCollection, WithCalculatedFormulas
{

    public function collection(Collection $rows)
    {
        try {
            unset($rows[0]);
            dd($this->findStaffNotInSystem($rows));
        } catch (\Exception $ex) {
            dd($ex);
        }

    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 500;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 500;
    }

    /**
     * Find staff from HR (payroll) that not in HR system or (staff full name not the same)
     * @param $pensionFundList
     */
    private function findStaffNotInSystem($pensionFundList)
    {
        unset($pensionFundList[0]);
        $staffNoInSystem = [];
        foreach ($pensionFundList as $pensionFund) {

            //Get column from upload excel file
            $systemId = $pensionFund[1]; //Equal Staff personal info id
            $staffFullNameEn = $pensionFund[2];
            $gender = $this->getGender($pensionFund[3]);

            $staffPersonalInfo = $this->findStaffInCurrentHRSystem($systemId, $staffFullNameEn, $gender);
            if (is_null($staffPersonalInfo)) {
                $staffNoInSystem[] = [
                    $systemId,
                    $staffFullNameEn,
                    GENDER[$gender]
                ];
            }
        }
        return $staffNoInSystem;
    }

    /**
     * @param $pensionFundCol (F => 1, M => 0)
     */
    private function getGender($pensionFundCol)
    {
        if (is_null($pensionFundCol) || $pensionFundCol == "M") {
            return "0";
        } else {
            return "1";
        }
    }

    private function findStaffInCurrentHRSystem($systemCode, $fullNameEn, $gender)
    {
        $staff = $staffPersonalInfo = StaffPersonalInfo::where('staff_id', $systemCode)
            ->first();
        if (is_null($staff)) {
            $staff = StaffPersonalInfo::where(DB::raw("CONCAT(last_name_en, ' ', first_name_en)"), 'like', '%' . $fullNameEn . '%')
                ->where('gender', $gender)
                ->first();
        }

        return $staff;
    }

}
