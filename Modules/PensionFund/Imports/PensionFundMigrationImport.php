<?php

namespace Modules\PensionFund\Imports;

use App\Contract;
use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Modules\PensionFund\Entities\PensionFunds;

class PensionFundMigrationImport implements ToCollection, WithCalculatedFormulas
{

    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            unset($rows[0]);

            foreach ($rows as $row) {

                //Get column from upload excel file
                $reportedDate = $this->transformDate($row[0]); //Equal pension fund by month
                $systemId = $row[1]; //Equal Staff personal info id
                $staffFullNameEn = $row[2];
                $gender = $this->getGender($row[3]);
                $location = $row[4];
                $locationShortName = $row[5];
                $dateOfEmployment = $this->transformDate($row[6]);
                $effectiveDate = $this->transformDate($row[7]);
                $grossBaseSalaryPerContract = $row[8];
                $grossBaseSalary = $row[9];
                $addition = $row[10];
                $deduction = $row[11];
                $acrBalanceStaff = $row[12];
                $acrBalanceSkp = $row[13];
                $balance = $row[14];

                $staffPersonalInfo = $this->findStaffInCurrentHRSystem($systemId, $staffFullNameEn, $gender);
                //Validate to check staff not yet register in current HR System
                if (is_null($staffPersonalInfo)) {
                    throw new \Exception("This staff: " . $staffFullNameEn . "-" . $systemId . ", Doesn't register in HR System yet. Please register first!");
                }

                if ($staffPersonalInfo != null) {
                    $contract = Contract::currentContract(@$staffPersonalInfo->id)->first();

                    //Debug
                    //dd($row, $staffPersonalInfo, $contract);

                    $pfObj = [
                        "date_of_employment" => $dateOfEmployment,
                        "effective_date" => $effectiveDate,
                        "gross_base_salary_per_contract" => $grossBaseSalaryPerContract,
                        "gross_base_salary" => $grossBaseSalary,
                        "addition" => $addition,
                        "deduction" => $deduction,
                        "acr_balance_staff" => $acrBalanceStaff,
                        "acr_balance_skp" => $acrBalanceSkp,
                        "balance" => $balance,
                        "report_date" => $reportedDate,
                        "location" => $location,
                        "location_short_name" => $locationShortName
                    ];

                    PensionFunds::create([
                        'staff_personal_info_id' => @$staffPersonalInfo->id,
                        'contract_id' => @$contract->id,
                        'json_data' => $pfObj,
                        'created_by' => Auth::id(),
                    ]);
                }
            }
            DB::commit();

        } catch (\Exception $ex) {
           DB::rollBack();
           dd($ex, $this->findStaffNotInSystem($rows));
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

    /**
     * Transform a date value into a Carbon object.
     *
     * @return \Carbon\Carbon|null
     */
    public function transformDate($value, $format = 'd-m-Y')
    {
        try {
            return (\Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format('d-m-Y'));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }


}
