<?php


namespace App\Imports;
use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class PersonalInfoSheetImport implements ToCollection, WithBatchInserts, WithChunkReading
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
        DB::beginTransaction();
        try {
            $name_row = $rows[0];
            unset($rows[0]);
            unset($rows[1]);
            foreach ($rows as $key => $col) {

                if ($col->filter()->isNotEmpty()) {
//                    $ms = isset($col[5]) ? $col[5] : null;
//                    $marital_status = DB::table('marital_status')->where('name_en', $ms)->pluck('id')->first();

//                    $idt = isset($col[7]) ? $col[7] : null;
//                    $id_types = DB::table('id_types')->where('name_en', $idt)->pluck('id')->first();

                    $bn = isset($col[11]) ? $col[11] : null;
                    $bank = DB::table('banks')->where('name_en', $bn)->pluck('id')->first();

                    $p = isset($col[15]) ? $col[15] : null;
                    $pro = DB::table('provinces')->where('name_kh', $p)->pluck('id')->first();

                    $d = isset($col[16]) ? $col[16] : null;
                    $dis = DB::table('districts')->where('name_kh', $d)->pluck('id')->first();

                    $c = isset($col[17]) ? $col[17] : null;
                    $com = DB::table('communes')->where('name_kh', $c)->pluck('id')->first();

                    $v = isset($col[18]) ? $col[18] : null;
                    $vil = DB::table('villages')->where('name_kh', $v)->pluck('id')->first();

                    $br = isset($col[31]) ? $col[31] : null;
                    $brane = DB::table('branches')->where('name_kh', 'LIKE', '%'.$br.'%')->pluck('id')->first();

                    $personal = StaffPersonalInfo::updateOrCreate([
                        'first_name_en' => $col[1],
                        'last_name_en'  => $col[2],
                        'first_name_kh' => $col[3],
                        'last_name_kh'  => $col[4],
                        'marital_status'=> (int)$col[5],
                        'gender'        => $col[6],
                        'id_type'       => (int)$col[7],
                        'id_code'       => $col[8],
                        'dob'           => ($col[9] != "") ? $this->transformDate($col[9]) : NULL,
                        'pob'           => $col[10],
                        'bank_name'     => $bank,
                        'bank_acc_no'   => str_replace("-", "", $col[12]),
                        'height'        => $col[13],
                        'driver_license'=> $col[14],
                        'province_id'   => $pro,
                        'district_id'   => $dis,
                        'commune_id'    => $com,
                        'village_id'    => $vil,
                        'house_no'      => $col[19],
                        'street_no'     => $col[20],
                        'other_location'=> $col[21].", ".$col[15].", ".$col[16].", ".$col[17].", ".$col[18],
                        'email'         => $col[22],
                        'phone'         => str_replace(" ","",$col[23]),
                        'emergency_contact'=> $col[24],
//                        'photo'         => $col[25],
                        'noted'         => $col[26],
                        'flag'          => (int)$col[27],
                        'created_by'    => Auth::id(),
                    ]);

                    try {
                        $personal->profile()->create([
//                        'staff_personal_info_id',
                            'emp_id_card' => $col[29],
                            'probation_duration' => $col[38],
                            'contract_duration' => $col[41],
                            'branch_id' => $brane,
                            'company_id'=> isset($col[30]) ? (int)$col[30] : 0,
                            'dpt_id'    => isset($col[32]) ? (int)$col[32] : 0,
                            'position_id' => isset($col[33]) ? (int)$col[33] : 0,
                            'base_salary' => isset($col[35]) ? (int)$col[35] : 0,
                            'currency' => $col[36],
                            'employment_date' => ($col[37] != "") ? $this->transformDate($col[37]) : NULL,
                            'probation_end_date' => ($col[39] != "") ? $this->transformDate($col[39]) : NULL,
                            'contract_end_date' => ($col[40] != "") ? $this->transformDate($col[40]) : NULL,
                            'manager' => ' ',
//                        'home_visit' => '',
//                        'email' => '',
//                        'phone' => '099898935',
//                        'mobile' => '098929986',
                            'flag' => 1,
                            'created_by' => Auth::id(),
                        ]);
                    } catch (\Exception $e) {
                        dd($e);
                    }

                    for($i = 46; $i <= 64; $i++) {
                        if ($col[$i]) {
                           try{
                               $docTypeId = DB::table('staff_document_types')->where('name_kh', 'LIKE', "$name_row[$i]")->first();
                               $personal->documents()->create([
                                   'staff_document_type_id' => $docTypeId->id,
//                                   'src' => null,
//                                   'name' => null,
                                   'not_have' => ((int)$col[$i] == 2) ? 1 : NULL,
                                   'check' => ((int)$col[$i] == 1) ? 1 : NULL,
                                   'created_by' => Auth::id(),
                               ]);
                           } catch (\Exception $e) {
                               dd($e);
                           }
                        }
                    }

                    for ($g = 66; $g <= 67; $g++) {
                        if (!empty($col[$g])) {
                            $guarantor = json_decode($col[$g]);
                            try {
                                if (!empty($guarantor->name)) {
                                    $name_kh = explode(" ", $guarantor->name);

                                    $relationShip = "";
                                    if (isset($guarantor->relationship)) {
                                        $relationShip = DB::table('relationship')->where('name_kh', 'LIKE', "$guarantor->relationship%")->pluck('id')->first();
                                    }
                                    $g_pro = '';
                                    if ($guarantor->province) {
                                        $g_pro = DB::table('provinces')->where('name_kh', 'LIKE', $guarantor->province)->pluck('id')->first();
                                    }
                                    $g_dis = '';
                                    if ($guarantor->district) {
                                        $g_dis = DB::table('districts')->where('name_kh', 'LIKE', $guarantor->district)->pluck('id')->first();
                                    }
                                    $g_com = '';
                                    if ($guarantor->commune) {
                                        $g_com = DB::table('communes')->where('name_kh', 'LIKE', $guarantor->commune)->pluck('id')->first();
                                    }
                                    $g_vil = '';
                                    if ($guarantor->village) {
                                        $g_vil = DB::table('villages')->where('name_kh', 'LIKE', $guarantor->village)->pluck('id')->first();
                                    }

                                    $personal->guarantors()->create([
                                        'first_name_kh' => $name_kh[1] ?? '',
                                        'last_name_kh' => $name_kh[0] ?? '',
//                                'first_name_en' => '',
//                                'last_name_en' => '',
                                        'gender' => (int)$guarantor->sex,
//                                'dob' ,
//                                'pob',
//                                'id_type',
//                                'id_code',
//                                'career_id',
//                                'marital_status',
                                        'related_id' => $relationShip,
//                                'children_no',
                                        'province_id' => (int)$g_pro,
                                        'district_id' => (int)$g_dis,
                                        'commune_id' => (int)$g_com,
                                        'village_id' => (int)$g_vil,
//                                'house_no',
//                                'street_no',
                                'other_location' => ($guarantor->province.", ".$guarantor->district.", ".$guarantor->commune.", ".$guarantor->village),
//                                'email',
//                                'phone',
                                        'flag' => 1,
                                        'created_by' => Auth::id(),
                                    ]);
                                }
                            } catch (\Exception $e) {
                                throw $e;
                            }
                        }
                    }


                } // If not empty
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