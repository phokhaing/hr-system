<?php

namespace App\Http\Controllers\StaffInfo;

use App\StaffInfoModel\StaffExperience;
use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use \Waavi\Sanitizer\Sanitizer;

class StaffExperienceController extends Controller
{

    public function edit($id)
    {
        try {
            $staff = StaffPersonalInfo::findOrFail(decrypt($id));
            $level_positions = DB::table('level_position')->get();
            $provinces = DB::table('provinces')->orderBy('name_kh')->get();
            return view('staffs.edit-experience', compact('staff', 'level_positions', 'provinces'));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->except(['num_row', 'staff_token', '_token']);

            $validation = Validator::make($input, [
                    'experience.position.*'          => 'required',
                    'experience.level_id.*'          => 'required',
                    'experience.company_name_en.*'   => 'required',
                    'experience.company_name_kh.*'   => 'required',
                    'experience.start_date.*'        => 'required',
                    'experience.end_date.*'          => 'required',
                    'experience.house_no.*'          => 'nullable',
                    'experience.street_no.*'         => 'nullable',
                    'experience.other_location.*'    => 'nullable',
                    'experience.noted.*'             => 'nullable',
                ], $this->messages()
            );

            $filters = [
                'experience.company_name_en.*' => 'trim|escape|capitalize',
                'experience.company_name_kh.*' => 'trim|escape',
                'experience.house_no.*'        => 'trim|escape',
                'experience.street_no.*'       => 'trim|escape',
                'experience.other_location.*'  => 'trim|escape',
                'experience.noted.*'           => 'trim|escape',
            ];

            $sanitizer  = new Sanitizer($input, $filters);
            $input = $sanitizer->sanitize();

            if ($validation->fails()) {
                DB::rollBack();
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $input = $input['experience'];
            $num_recorde = $input['company_name_en'];

            $save = false;
            foreach($num_recorde as $key => $item) {
                $save = StaffExperience::create([
                    'position'          => $input['position'][$key],
                    'level_id'          => $input['level_id'][$key],
                    'start_date'        => date('Y-m-d', strtotime($input['start_date'][$key])),
                    'end_date'          => date('Y-m-d', strtotime($input['end_date'][$key])),
                    'company_name_en'   => $input['company_name_en'][$key],
                    'company_name_kh'   => $input['company_name_kh'][$key],
                    'province_id'       => $input['province_id'][$key],
                    'house_no'          => $input['house_no'][$key],
                    'street_no'         => $input['street_no'][$key],
                    'other_location'    => $input['other_location'][$key],
                    'noted'             => $input['noted'][$key],
                    'staff_personal_info_id' => decrypt($request->staff_token),
                    'created_by'        => Auth::id()
                ]);
            }

            if($save == true) {
                DB::commit();
                return redirect()->back()->with(['success' => 1]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    /**
     * Update Experience
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->except(['num_row', 'staff_token', '_token']);
            $staff_id = decrypt($request->staff_token);

            $validation = Validator::make($input, [
                    'experience.position.*'          => 'required',
                    'experience.level_id.*'          => 'required',
                    'experience.company_name_en.*'   => 'required',
                    'experience.company_name_kh.*'   => 'required',
                    'experience.start_date.*'        => 'required',
                    'experience.end_date.*'          => 'required',
                    'experience.house_no.*'          => 'nullable',
                    'experience.street_no.*'         => 'nullable',
                    'experience.other_location.*'    => 'nullable',
                    'experience.noted.*'             => 'nullable',
                ], $this->messages()
            );

            $filters = [
                'experience.company_name_en.*' => 'trim|escape|capitalize',
                'experience.company_name_kh.*' => 'trim|escape',
                'experience.house_no.*'        => 'trim|escape',
                'experience.street_no.*'       => 'trim|escape',
                'experience.other_location.*'  => 'trim|escape',
                'experience.noted.*'           => 'trim|escape',
            ];

            $sanitizer  = new Sanitizer($input, $filters);
            $input = $sanitizer->sanitize();

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $input = $input['experience'];
            $num_recorde = $input['company_name_en'];

            StaffExperience::where('staff_personal_info_id', $staff_id)->delete();

            $save = false;
            foreach($num_recorde as $key => $item) {
                $save = StaffExperience::create([
                    'position'          => $input['position'][$key],
                    'level_id'          => $input['level_id'][$key],
                    'start_date'        => date('Y-m-d', strtotime($input['start_date'][$key])),
                    'end_date'          => date('Y-m-d', strtotime($input['end_date'][$key])),
                    'company_name_en'   => $input['company_name_en'][$key],
                    'company_name_kh'   => $input['company_name_kh'][$key],
                    'province_id'       => $input['province_id'][$key],
                    'house_no'          => $input['house_no'][$key],
                    'street_no'         => $input['street_no'][$key],
                    'other_location'    => $input['other_location'][$key],
                    'noted'             => $input['noted'][$key],
                    'staff_personal_info_id' => $staff_id,
                    'created_by'        => Auth::id(),
                    'updated_bys'        => Auth::id()
                ]);
            }

            if ($save == true) {
                DB::commit();
                return redirect()->back()->with(['success' => 1]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    public function messages()
    {
        return [
            'experience.position.*'          => 'Position is required',
            'experience.level_id.*'          => 'Level is required',
            'experience.company_name_en.*'   => 'Company name english is required',
            'experience.company_name_kh.*'   => 'Company name khmer is required',
            'experience.start_date.*'        => 'Start date is required',
            'experience.end_date.*'          => 'End date is required',
        ];
    }
}
