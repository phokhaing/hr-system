<?php

namespace App\Http\Controllers\StaffInfo;

use App\StaffInfoModel\StaffGuarantor;
use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Waavi\Sanitizer\Sanitizer;
use Validator;

class StaffGuarantorController extends Controller
{
    /**
     * Show form edit OR create new guarantor
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $staff = StaffPersonalInfo::findOrFail(decrypt($id));
            $idType = DB::table('id_types')->pluck('name_kh', 'id');
            $genders = ['0' => 'Male', '1' => 'Female'];
            $marital_status = DB::table('marital_status')->pluck('name_kh', 'id');
            $occupations = DB::table('occupations')->pluck('name_en', 'id');
            $relationships = DB::table('relationship')->pluck('name_kh', 'id');
            $provinces = DB::table('provinces')->orderBy('name_kh')->get();
            $districts = DB::table('districts')->orderBy('name_kh')->get();
            $communes = DB::table('communes')->orderBy('name_kh')->get();
            $villages = DB::table('villages')->orderBy('name_kh')->get();

            return view('staffs.edit-guarantor', compact(
                'staff', 'idType', 'genders', 'marital_status', 'occupations', 'relationships', 'provinces',
                'districts', 'communes', 'villages'
            ));
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
            $input = $request->except(['_token', 'staff_token', 'num_row']);
            $staff_id = decrypt($request->staff_token);

            $validation = Validator::make($input, [
                'guarantor.first_name_kh.*' => 'required|max:25',
                'guarantor.last_name_kh.*'  => 'required|max:25',
                'guarantor.first_name_en.*' => 'required|max:25',
                'guarantor.last_name_en.*'  => 'required|max:25',
                'guarantor.gender.*'        => 'required',
                'guarantor.guarantor_dob.*' => 'required',
                'guarantor.dob.*'           => 'required',
                'guarantor.pob.*'           => 'required|min:5',
                'guarantor.id_type.*'       => 'required',
                'guarantor.id_code.*'       => 'required|min:3',
                'guarantor.career_id.*'     => 'required',
                'guarantor.marital_status.*'=> 'required',
                'guarantor.related_id.*'    => 'required',
                'guarantor.house_no.*'      => 'nullable|max:10',
                'guarantor.street_no.*'     => 'nullable|max:20',
                'guarantor.other_location.*'=> 'string|nullable',
                'guarantor.email.*'         => 'nullable|email',
                'guarantor.phone.*'         => 'nullable|max:10|min:9',
            ], $this->messages());

            if ($validation->fails()) {
                return redirect()->back()->withInput()->withErrors($validation);
            }

            $filters = [
                'guarantor.first_name_kh.*' => 'trim|escape',
                'guarantor.last_name_kh.*'  => 'trim|escape',
                'guarantor.first_name_en.*' => 'trim|escape|capitalize',
                'guarantor.last_name_en.*'  => 'trim|escape|capitalize',
                'guarantor.pob.*'       => 'trim|escape',
                'guarantor.id_code.*'   => 'trim|escape',
                'guarantor.house_no.*'      => 'trim|escape',
                'guarantor.street_no.*'     => 'trim|escape',
                'guarantor.other_location.*'=> 'trim|escape',
                'guarantor.email.*'         => 'trim|escape',
                'guarantor.phone.*'         => 'trim|escape|digit',
            ];
            $sanitizer  = new Sanitizer($input, $filters);
            $input = $sanitizer->sanitize();

            $input = $input['guarantor'];
            $num_recorde = $input['first_name_kh'];

            $save = false;
            foreach ($num_recorde as $key => $value) {
                $save = StaffGuarantor::create([
                    'staff_personal_info_id' => $staff_id,
                    'first_name_kh' => $input['first_name_kh'][$key],
                    'last_name_kh'  => $input['last_name_kh'][$key],
                    'first_name_en' => $input['first_name_en'][$key],
                    'last_name_en'  => $input['last_name_en'][$key],
                    'gender'    => $input['gender'][$key],
                    'dob'       => date('Y-m-d', strtotime($input['dob'][$key])),
                    'pob'       => $input['pob'][$key],
                    'id_type'   => $input['id_type'][$key],
                    'id_code'   => $input['id_code'][$key],
                    'career_id' => $input['career_id'][$key],
                    'marital_status'    => $input['marital_status'][$key],
                    'related_id'    => (int)$input['related_id'][$key],
                    'province_id'   => $input['province_id'][$key],
                    'district_id'   => $input['district_id'][$key],
                    'commune_id'    => $input['commune_id'][$key],
                    'village_id'    => $input['village_id'][$key],
                    'house_no'      => $input['house_no'][$key],
                    'street_no'     => $input['street_no'][$key],
                    'other_location'=> $input['other_location'][$key],
                    'email'     => $input['email'][$key],
                    'phone'     => $input['phone'][$key],
                    'created_by'=> Auth::id(),
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
     * Update Guarantor
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->except(['_token', 'staff_token', 'num_row']);
            $staff_id = decrypt($request->staff_token);

            $validation = Validator::make($input, [
                'guarantor.first_name_kh.*' => 'required|max:25',
                'guarantor.last_name_kh.*'  => 'required|max:25',
                'guarantor.first_name_en.*' => 'required|max:25',
                'guarantor.last_name_en.*'  => 'required|max:25',
                'guarantor.gender.*'        => 'required',
                'guarantor.guarantor_dob.*' => 'required',
                'guarantor.dob.*'           => 'required',
                'guarantor.pob.*'           => 'required|min:5',
                'guarantor.id_type.*'       => 'required',
                'guarantor.id_code.*'       => 'required|min:3',
                'guarantor.career_id.*'     => 'required',
                'guarantor.marital_status.*'=> 'required',
                'guarantor.related_id.*'    => 'required',
                'guarantor.house_no.*'      => 'nullable|max:10',
                'guarantor.street_no.*'     => 'nullable|max:20',
                'guarantor.other_location.*'=> 'string|nullable',
                'guarantor.email.*'         => 'nullable|email',
                'guarantor.phone.*'         => 'nullable|max:10|min:9',
            ], $this->messages());

            if ($validation->fails()) {
                return redirect()->back()->withInput()->withErrors($validation);
            }

            $filters = [
                'guarantor.first_name_kh.*' => 'trim|escape',
                'guarantor.last_name_kh.*'  => 'trim|escape',
                'guarantor.first_name_en.*' => 'trim|escape|capitalize',
                'guarantor.last_name_en.*'  => 'trim|escape|capitalize',
                'guarantor.pob.*'       => 'trim|escape',
                'guarantor.id_code.*'   => 'trim|escape',
                'guarantor.children_no.*'   => 'trim|digit',
                'guarantor.house_no.*'      => 'trim|escape',
                'guarantor.street_no.*'     => 'trim|escape',
                'guarantor.other_location.*'=> 'trim|escape',
                'guarantor.email.*'         => 'trim|escape',
                'guarantor.phone.*'         => 'trim|escape|digit',
            ];
            $sanitizer  = new Sanitizer($input, $filters);
            $input = $sanitizer->sanitize();

            $input = $input['guarantor'];
            $num_recorde = $input['first_name_kh'];
            StaffGuarantor::where('staff_personal_info_id', $staff_id)->delete();

            $save = false;
            foreach ($num_recorde as $key => $value) {
                $save = StaffGuarantor::create([
                    'staff_personal_info_id' => $staff_id,
                    'first_name_kh' => $input['first_name_kh'][$key],
                    'last_name_kh'  => $input['last_name_kh'][$key],
                    'first_name_en' => $input['first_name_en'][$key],
                    'last_name_en'  => $input['last_name_en'][$key],
                    'gender'    => $input['gender'][$key],
                    'dob'       => date('Y-m-d', strtotime($input['dob'][$key])),
                    'pob'       => $input['pob'][$key],
                    'id_type'   => $input['id_type'][$key],
                    'id_code'   => $input['id_code'][$key],
                    'career_id' => $input['career_id'][$key],
                    'marital_status'    => $input['marital_status'][$key],
                    'related_id'    => (int)$input['related_id'][$key],
                    'province_id'   => $input['province_id'][$key],
                    'district_id'   => $input['district_id'][$key],
                    'commune_id'    => $input['commune_id'][$key],
                    'village_id'    => $input['village_id'][$key],
                    'house_no'      => $input['house_no'][$key],
                    'street_no'     => $input['street_no'][$key],
                    'other_location'=> $input['other_location'][$key],
                    'email'     => $input['email'][$key],
                    'phone'     => $input['phone'][$key],
                    'created_by'=> Auth::id(),
                    'updated_by'=> Auth::id(),
                ]);

                if ($save == true) {
                    DB::commit();
                    return redirect()->back()->with(['success' => 1]);
                }
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    public function messages()
    {
        return [
            'guarantor.first_name_kh.*.required' => 'First name kh is required',
            'guarantor.first_name_kh.*.max' => 'First name kh max length is 25',
            'guarantor.last_name_kh.*.required'  => 'Last name kh is required',
            'guarantor.last_name_kh.*.max'  => 'Last name kh max length is 25',
            'guarantor.first_name_en.*.required' => 'First name en is required',
            'guarantor.first_name_en.*.max' => 'First name kh max length is 25',
            'guarantor.last_name_en.*.required'  => 'Last name en is required',
            'guarantor.last_name_en.*.max'  => 'Last name kh max length is 25',
            'guarantor.gender.*'        => 'Gender is required',
            'guarantor.guarantor_dob.*' => 'Date of birth is required',
            'guarantor.dob.*'           => ' Date of birth is required',
            'guarantor.pob.*.required'           => 'Place of birth is required',
            'guarantor.pob.*.min'       => 'Place of birth min length 5 character.',
            'guarantor.id_type.*'       => 'ID type is required',
            'guarantor.id_code.*.required'       => 'ID code is required',
            'guarantor.id_code.*.min'       => 'ID code min 3',
            'guarantor.career_id.*'     => 'Career is required',
            'guarantor.marital_status.*'=> 'Marital status is required',
            'guarantor.related_id.*'    => 'Relation ship is required',
            'guarantor.house_no.*.max'      => 'House number max is 10 character',
            'guarantor.street_no.*.max'     => 'Street number max is 20 character',
            'guarantor.other_location.*.string'=> 'Other location must string',
            'guarantor.email.*.email'         => 'Email must valid email',
            'guarantor.phone.*.min'         => 'Phone min length is 9 character',
            'guarantor.phone.*.max'         => 'Phone max length is 10 character',
        ];
    }
}
