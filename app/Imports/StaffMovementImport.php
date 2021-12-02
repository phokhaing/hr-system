<?php

namespace App\Imports;

use App\Branch;
use App\Position;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\StaffInfoModel\StaffMovement;
use App\StaffInfoModel\StaffPersonalInfo;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class StaffMovementImport implements ToCollection, WithBatchInserts, WithChunkReading
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

    /**
     * @param Collection $rows
     * @throws \Exception
     */
    public function collection(Collection $rows)
    {
        /**
         * $col[*]
         * 
         * 1- Full name
         * 2- Old position
         * 3- New position
         * 4- Old location
         * 5- New location
         * 6- Current salary 
         * 7- New salary
         * 8- Date transfer
         */
        DB::beginTransaction();
        try {
            $movements = DB::table('import_staff_movement')->get();

            foreach ($movements as $key => $movement) {

//                Branch::firstOrCreate(['name_kh' => $movement->old_location]);
//                Position::firstOrCreate(['name_kh' => $movement->old_position]);
//                Branch::firstOrCreate(['name_kh' => $movement->new_location]);
//                Position::firstOrCreate(['name_kh' => $movement->new_position]);

                $old_branch = Branch::where('name_kh', 'like', $movement->old_location)->pluck('id')->first();
                $old_position = Position::where('name_kh', 'like', $movement->old_position)->pluck('id')->first();
                $new_location = Branch::where('name_kh', 'like', $movement->new_location)->pluck('id')->first();
                $new_position = Position::where('name_kh', 'like', $movement->new_position)->pluck('id')->first();

                $staff_name = trim($movement->full_name);
                $name = explode(" ", $staff_name);

                $staff = StaffPersonalInfo::where('first_name_kh', 'LIKE', "%$name[1]")
                        ->where('last_name_kh', 'LIKE', "%$name[0]")->first();

                if (isset($staff)) {
                    /**
                     * If staff use to movement, so we need to delete old record.
                     */
                    $hasMove = StaffMovement::where('staff_personal_info_id', '=', $staff->id)
                        ->where('deleted_at', '=', null)->first();
                    if (isset($hasMove)) {
                        $hasMove->update(['updated_by' => Auth::id(), 'flag' => '-1']);
                        $hasMove->delete();
                    }

                    /**
                     * - We need to get old staff profile to store in table staff_movement.
                     * - No need check $profile have or not because we check already when user input.
                     */
                    if (isset($old_branch) && isset($old_position) && isset($new_location) && isset($new_position)) {
                        if ($staff->id) {
                            $saveMove = StaffMovement::updateOrCreate([
                                'staff_personal_info_id' => $staff->id,
                                'company_id' => NULL,
                                'branch_id' => $old_branch,
                                'to_branch_id' => $new_location,
                                'department_id' => NULL,
                                'position_id' => $old_position,
                                'to_position_id' => $new_position,
                                'old_salary' => isset($movement->old_salary) ? $movement->old_salary : NULL,
                                'new_salary' => isset($movement->new_salary) ? $movement->new_salary : NULL,
                                'effective_date' => $movement->date_transfer,
                                'flag' => ($staff->trashed()) ? '-1' : 1,
                                'created_by' => Auth::id()
                            ]); // Save staff movement

                        }
                    }
                }

            }

            /* *******************************
             * Import staff movement by excel
             * ******************************/
//            unset($rows[0]);
//            unset($rows[1]);
//            foreach ($rows as $key => $col) {

                // Import staff movement by table
//                DB::table('import_staff_movement')->insert([
//                    'full_name' => $col[1],
//                    'old_position' => $col[2],
//                    'new_position' => $col[3],
//                    'old_location' => $col[4],
//                    'new_location' => $col[5],
//                    'old_salary' => (isset($col[6])) ? (int)$col[6] : "",
//                    'new_salary' => (isset($col[7])) ? (int)$col[7] : "",
//                    'date_transfer' => $this->transformDate($col[8])
//                ]);

//                if ($col->filter()->isNotEmpty()) {
//
//                    if ( isset($col[1]) && isset($col[2]) && isset($col[3]) && isset($col[4]) && isset($col[5]) && isset($col[8]) )
//                    {
//                        $staff_name = trim($col[1]);
//                        $name = explode(" ", $staff_name);
//
//                        $staff_id = StaffPersonalInfo::where('first_name_kh', 'LIKE', "%$name[1]")
//                            ->where('last_name_kh', 'LIKE', "%$name[0]")->pluck('id')->first();

                        /**
                         * If staff use to movement, so we need to delete old record.
                         */
//                        $hasMove = StaffMovement::where('staff_personal_info_id', '=', $staff_id)
//                            ->where('deleted_at', '=', null)->first();
//                        if (isset($hasMove)) {
//                            $hasMove->update(['updated_by' => Auth::id(), 'flag' => '-1']);
//                            $hasMove->delete();
//                        }

                        /**
                         * - We need to get old staff profile to store in table staff_movement.
                         * - No need check $profile have or not because we check already when user input.
                         */
//                        $this->branch_id = DB::table('branches')->where('name_kh', 'LIKE', "%$col[4]")->pluck('id')->first();
//                        $this->position_id = DB::table('positions')->where('name_kh', 'LIKE', "%$col[2]")->pluck('id')->first();

//                        if ( isset($this->branch_id) && isset($this->position_id) ) {
//                            if ($staff_id) {
//                                $saveMove = StaffMovement::updateOrCreate([
//                                    'staff_personal_info_id' => $staff_id,
//                                    'company_id'    => NULL,
//                                    'branch_id'     => $this->branch_id,
//                                    'department_id' => NULL,
//                                    'position_id'   => $this->position_id,
//                                    'old_salary'    => (isset($col[6])) ? (int)$col[6] : "",
//                                    'new_salary'    => (isset($col[7])) ? (int)$col[7] : "",
//                                    'effective_date' => $this->transformDate($col[8]),
//                                    'flag' => 1,
//                                    'created_by' => Auth::id(),
//                                ]); // Save staff movement
//                            }
//                        }
//                    }
//
//                }
//            }
        } catch (\Exception $th) {
            DB::rollBack();
            dd($th);
            throw $th;
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