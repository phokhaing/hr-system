<?php

namespace App\Imports;

use App\StaffInfoModel\StaffPersonalInfo;
use App\StaffInfoModel\StaffProfile;
use App\StaffInfoModel\StaffResign;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class StaffResignImport implements ToCollection, WithBatchInserts, WithChunkReading
{
    /**
     * Transform a date value into a Carbon object.
     *
     * @param $value
     * @param string $format
     * @return \Carbon\Carbon
     * @throws \Exception
     */
    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }


    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try{
            unset($rows[0]);
            unset($rows[1]);
            foreach ($rows as $key => $col) {
                if ($col->filter()->isNotEmpty()) {
                    // Check if have staff ID in Excel
                    if ($col[1] != "") {
                        $emp_id = (int)$col[1];
                        $company_id = (int)$col[9];
                        $staff_id = StaffProfile::withTrashed()->where('emp_id_card', $emp_id)
                            ->where('company_id', $company_id)
                            ->pluck('staff_personal_info_id')->first();

                        // Check if staff ID have in system
                        if ($staff_id != "") {
                            $staffInfo = StaffPersonalInfo::withTrashed()->where('id', $staff_id)->first();

                            if (!empty($staffInfo)) {
//                                dump($staff_id);
                                // If staff already have last day
                                if ($col[6] !="") {
                                    $updated = $staffInfo->update(['flag' => '3']); // code 3 was approved (If have last day)
                                    $staffInfo->delete();
                                } elseif ($col[2] != "") {
                                    $updated = $staffInfo->update(['flag' => '5']); // code 5 waiting the last day (If fraud)
                                } elseif ($col[3] != "") {
                                    $updated = $staffInfo->update(['flag' => '4']); // code 4 is padding request (If not fraud)
                                } else {
                                    // This line can edit flag base on real document that import
                                    $updated = $staffInfo->update(['flag' => '0']);
                                }

                                if ($updated == true) {
                                    StaffResign::updateOrCreate([
                                        'staff_personal_info_id' => $staff_id,
                                        'resign_date' => ($col[3] != "") ? $this->transformDate($col[3]) : NULL,
                                        'approved_date' => ($col[4] != "") ? $this->transformDate($col[4]) : NULL,
                                        'last_day' => ($col[6] != "") ? $this->transformDate($col[6]) : NULL,
                                        //                        'reject_date' => $col[],
                                        //                        'staff_id_replaced_1',
                                        'staff_name_replaced_1' => $col[7],
                                        //                        'staff_id_replaced_2',
                                        //                        'staff_name_replaced_2',
                                        //                        'reason_company_id',
                                        //                        'file_reference',
                                        'reason' => ($col[8] != "") ? $col[8] : '',
                                        'is_fraud' => (int)$col[2],
                                        'flag' => 1,
                                        'created_by' => Auth::id()
                                    ]);
                                }
                            }
                        }
                    } // End if
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
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
}
