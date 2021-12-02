<?php

namespace App\Http\Controllers\StaffInfo;

use App\StaffInfoModel\StaffPersonalInfo;
use App\StaffInfoModel\StaffTraining;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\DB;
use \Waavi\Sanitizer\Sanitizer;

class StaffTrainingController extends Controller
{

    public function edit($id)
    {
        try {
            $staff = StaffPersonalInfo::findOrFail(decrypt($id));
            $provinces = DB::table('provinces')->orderBy('name_kh')->get();

            return view('staffs.edit-training', compact('staff', 'provinces'));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    /**
     * Store training course
     *
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
                'training.subject.*'       => 'required|regex:/^[\pL\s\-]+$/u',
                'training.school.*'        => 'required|regex:/^[\pL\s\-]+$/u',
                'training.place.*'        => 'required|regex:/^[\pL\s\-]+$/u',
                'training.start_date.*'    => 'required',
                'training.end_date.*'      => 'required',
                'training.province_id.*'   => 'nullable',
                'training.other_location.*'=> 'nullable',
                'training.description.*'   => 'nullable',
            ], $this->messages());

            $filters = [
                'training.subject.*'        => 'trim|escape',
                'training.school.*'         => 'trim|escape',
                'training.place.*'          => 'trim|escape',
                'training.other_location.*' => 'trim|escape',
                'training.description.*'    => 'trim|escape',
            ];
            $sanitizer  = new Sanitizer($input, $filters);
            $input = $sanitizer->sanitize();

            if ($validation->fails()) {
                DB::rollBack();
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $input = $input['training'];
            $num_recorde = $input['subject'];

            $save = false;
            foreach($num_recorde as $key => $item) {
                $save = StaffTraining::create([
                    'subject'       => $input['subject'][$key],
                    'school'       => $input['school'][$key],
                    'place'       => $input['place'][$key],
                    'start_date'    => date('Y-m-d', strtotime($input['start_date'][$key])),
                    'end_date'      => date('Y-m-d', strtotime($input['end_date'][$key])),
                    'province_id'   => $input['province_id'][$key],
                    'other_location'=> $input['other_location'][$key],
                    'description'   => $input['description'][$key],
                    'staff_personal_info_id' => decrypt($request->staff_token),
                    'created_by'    => Auth::id()
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

    /**
     * Update training course
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
                'training.subject.*'        => 'required|regex:/^[\pL\s\-]+$/u',
                'training.school.*'         => 'required|regex:/^[\pL\s\-]+$/u',
                'training.place.*'          => 'required|regex:/^[\pL\s\-]+$/u',
                'training.start_date.*'    => 'required',
                'training.end_date.*'      => 'required',
                'training.province_id.*'   => 'nullable',
                'training.other_location.*'=> 'nullable',
                'training.description.*'   => 'nullable',
            ], $this->messages());

            $filters = [
                'training.subject.*'        => 'trim|escape',
                'training.school.*'         => 'trim|escape',
                'training.place.*'          => 'trim|escape',
                'training.other_location.*' => 'trim|escape',
                'training.description.*'    => 'trim|escape',
            ];
            $sanitizer  = new Sanitizer($input, $filters);
            $input = $sanitizer->sanitize();

            if ($validation->fails()) {
                DB::rollBack();
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $input = $input['training'];
            $num_recorde = $input['subject'];

            StaffTraining::where('staff_personal_info_id', $staff_id)->delete();

            $save = false;
            foreach($num_recorde as $key => $item) {
                $save = StaffTraining::create([
                    'subject'       => $input['subject'][$key],
                    'school'       => $input['school'][$key],
                    'place'       => $input['place'][$key],
                    'start_date'    => date('Y-m-d', strtotime($input['start_date'][$key])),
                    'end_date'      => date('Y-m-d', strtotime($input['end_date'][$key])),
                    'province_id'   => $input['province_id'][$key],
                    'other_location'=> $input['other_location'][$key],
                    'description'   => $input['description'][$key],
                    'staff_personal_info_id' => $staff_id,
                    'created_by'    => Auth::id(),
                    'updated_by'    => Auth::id()
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
            'training.subject.*'        => 'Subject is required',
            'training.school.*'         => 'School is required',
            'training.place.*'          => 'Place is required',
            'training.start_date.*'     => 'Start date is required',
            'training.end_date.*'       => 'End date is required',
        ];
    }
}
