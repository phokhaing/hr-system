<?php

namespace App\Http\Controllers\StaffInfo;

use App\StaffInfoModel\StaffEducation;
use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StaffEducationController extends Controller
{

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $staff = StaffPersonalInfo::findOrFail(decrypt($id));
            $degree = DB::table('degree')->pluck('name_kh', 'id');
            $provinces = DB::table('provinces')->orderBy('name_kh')->get();
            $study_year = DB::table('study_year')->pluck('name_kh', 'id');

            return view('staffs.edit-education', compact('staff', 'degree', 'provinces', 'study_year'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }

    }

    /**
     * Store a newly created resource in storage.
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
                    'education.school_name.*'     => 'required',
                    'education.subject.*'         => 'required',
                    'education.start_date.*'      => 'required',
                    'education.end_date.*'        => 'required',
                    'education.other_location.*'  => 'nullable',
                    'education.noted.*' => 'nullable',
                ], $this->messages()
            );

            if ($validation->fails()) {
                DB::rollBack();
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $input = $input['education'];
            $num_recorde = $input['school_name'];

            $save = false;
            foreach($num_recorde as $key => $item) {
                $save = StaffEducation::create([
                    'school_name'   => $input['school_name'][$key],
                    'subject'       => $input['subject'][$key],
                    'start_date'    => date('Y-m-d', strtotime($input['start_date'][$key])),
                    'end_date'      => date('Y-m-d', strtotime($input['end_date'][$key])),
                    'degree_id'     => $input['degree_id'][$key],
                    'study_year'    => $input['study_year'][$key],
                    'province_id'   => $input['province_id'][$key],
                    'other_location'=> $input['other_location'][$key],
                    'noted'         => $input['noted'][$key],
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
     * Update the specified resource in storage.
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
                    'education.school_name.*'     => 'required',
                    'education.subject.*'         => 'required',
                    'education.start_date.*'      => 'required',
                    'education.end_date.*'        => 'required',
                    'education.other_location.*'  => 'nullable',
                    'education.noted.*' => 'nullable',
                ], $this->messages()
            );

            if ($validation->fails()) {
                DB::rollBack();
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $input = $input['education'];
            $num_recorde = $input['school_name'];
            StaffEducation::where('staff_personal_info_id', $staff_id)->delete();

            $save = false;
            foreach($num_recorde as $key => $item) {
                $save = StaffEducation::create([
                    'school_name'   => $input['school_name'][$key],
                    'subject'       => $input['subject'][$key],
                    'start_date'    => date('Y-m-d', strtotime($input['start_date'][$key])),
                    'end_date'      => date('Y-m-d', strtotime($input['end_date'][$key])),
                    'degree_id'     => $input['degree_id'][$key],
                    'study_year'    => $input['study_year'][$key],
                    'province_id'   => $input['province_id'][$key],
                    'other_location'=> $input['other_location'][$key],
                    'noted'         => $input['noted'][$key],
                    'staff_personal_info_id' => $staff_id,
                    'created_by'    => Auth::id(),
                    'updated_by'    => Auth::id()
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function messages()
    {
        return [
            'education.school_name.*'   => 'School is required',
            'education.subject.*'       => 'Subject is required',
            'education.start_date.*'    => 'Start date is required',
            'education.end_date.*'      => 'End date is required',
        ];
    }
}
