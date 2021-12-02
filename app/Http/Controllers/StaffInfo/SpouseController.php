<?php

namespace App\Http\Controllers\StaffInfo;

use App\Http\Controllers\Controller;
use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use App\StaffInfoModel\StaffSpouse;
use \Waavi\Sanitizer\Sanitizer;

class SpouseController extends Controller
{

    /**
     * Show form edit OR create new spouse
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $staff = StaffPersonalInfo::findOrFail(decrypt($id));
        $genders = ['0' => 'Male', '1' => 'Female'];
        $provinces = DB::table('provinces')->orderBy('name_kh')->get();
        $districts = DB::table('districts')->orderBy('name_kh')->get();
        $communes = DB::table('communes')->orderBy('name_kh')->get();
        $villages = DB::table('villages')->orderBy('name_kh')->get();
        $spouses = ['0' => 'Include', '1' => 'Exclude'];
        $occupations = DB::table('occupations')->pluck('name_en', 'id');

        return view('staffs.edit-spouse', compact(
            'staff', 'genders', 'provinces', 'spouses', 'occupations', 'districts', 'communes', 'villages'
        ));
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

            $validator = Validator::make($input, [
                'spouse.full_name.*'     => 'required',
                'spouse.gender.*'        => 'required',
                'spouse.dob.*'           => 'required',
                'spouse.children_no.*'   => 'required_with:children_tax|numeric|max:20',
                'spouse.children_tax.*'  => 'nullable|numeric|max:15',
                'spouse.spouse_tax.*'    => 'required',
                'spouse.phone.*'         => 'nullable|min:9|max:10',
            ], $this->messages());

            if ($validator->fails()) {
                DB::rollBack();
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $filters = [
                'spouse.full_name.*'       => 'trim|escape',
                'spouse.other_location.*'  => 'trim|escape',
            ];
            $sanitizer  = new Sanitizer($input, $filters);
            $input = $sanitizer->sanitize();

            $input = $input['spouse'];
            $nums = $input['full_name'];

            $save = false;
            foreach ($nums as $key => $value) {
                $save = StaffSpouse::create([
                    "full_name" => $input["full_name" ][$key],
                    "gender" => $input["gender" ][$key],
                    "children_no" => $input["children_no" ][$key],
                    "province_id" => $input["province_id" ][$key],
                    "district_id" => $input["district_id" ][$key],
                    "commune_id" => $input["commune_id" ][$key],
                    "village_id" => $input["village_id" ][$key],
                    "house_no" => $input["house_no" ][$key],
                    "street_no" => $input["street_no" ][$key],
                    "other_location" => $input["other_location" ][$key],
                    "phone" => $input["phone" ][$key],
                    "spouse_tax" => $input["spouse_tax" ][$key],
                    "children_tax" => $input["children_tax" ][$key],
                    "occupation_id" => $input["occupation_id" ][$key],
                    "dob" => date('Y-m-d', strtotime($input['dob'][$key])), // use it to override DOB
                    "staff_personal_info_id" => decrypt($request->staff_token),
                    "created_by"  => Auth::id(),
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
     * Update Spouse
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

            $validator = Validator::make($input, [
                'full_name'     => 'required',
                'gender'        => 'required',
                'dob'           => 'required',
                'children_no'   => 'required_with:children_tax|numeric|max:20',
                'children_tax'  => 'nullable|numeric|max:15',
                'spouse_tax'    => 'required',
                'phone'         => 'nullable|min:9|max:10',
            ], $this->messages());

            if($validator->fails()) {
                DB::rollBack();
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $staff_id = decrypt($request->staff_token);
            $spouseId = decrypt($request->spouse_id);
            
            $save = false;
            $data = [
                "full_name" => $input["full_name" ],
                "gender" => $input["gender" ],
                "children_no" => $input["children_no" ],
                "province_id" => $input["province_id" ],
                "district_id" => $input["district_id" ],
                "commune_id" => $input["commune_id" ],
                "village_id" => $input["village_id" ],
                "house_no" => $input["house_no" ],
                "street_no" => $input["street_no" ],
                "other_location" => $input["other_location" ],
                "phone" => $input["phone" ],
                "spouse_tax" => $input["spouse_tax" ],
                "children_tax" => $input["children_tax" ],
                "occupation_id" => $input["occupation_id" ],
                "dob" => date('Y-m-d', strtotime($input['dob'])), // use it to override DOB
                "staff_personal_info_id" => $staff_id,
            ];
            $spouse = StaffSpouse::find($spouseId); 
            $save = $spouse->updateMyRecord($data);
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
            'spouse.full_name.*'     => 'Spouse full name is required',
            'spouse.gender.*'        => 'Spouse gender required',
            'spouse.dob.*'           => 'Spouse date of birth required',
            'spouse.children_no.*.required_with'   => 'Spouse children must have when input number of child tax',
            'spouse.children_no.*.numeric'   => 'Spouse children must in number',
            'spouse.children_no.*.max'   => 'Maximum length is 20',
            'spouse.children_tax.*'  => 'Spouse children tax must in number',
            'spouse.spouse_tax.*'    => 'Spouse tax is required',
            'spouse.phone.*.max'         => 'Spouse phone is max length is 10',
            'spouse.phone.*.min'         => 'Spouse phone is max length is 9',
        ];
    }
}
