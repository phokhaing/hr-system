<?php

namespace App\Http\Controllers\StaffInfo;

use App\Contract;
use App\Http\Controllers\Controller;
use App\Http\Requests\StaffInfo\StaffPersonalInfoStore;
use App\Http\Requests\StaffInfo\StaffPersonalInfoUpdate;
use App\Imports\StaffImport;
use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Image;
use Maatwebsite\Excel\Facades\Excel;
use Modules\HRTraining\Entities\Categories;
use Modules\HRTraining\Entities\Courses;
use Modules\HRTraining\Entities\Enrollments;
use Modules\HRTraining\Entities\Trainees;
use Modules\PensionFund\Entities\AutoCalculatePensionFund;
use Validator;

class StaffPersonalInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:view_staff');
        $this->middleware('permission:add_staff', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_staff', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_staff', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $param = $request->all();
            $staffs = StaffPersonalInfo::with('firstContract')
                ->where('flag', '=', 1)->with(['profile', 'contract'])
                ->latest()
                ->paginate(PER_PAGE);
            $page = ($request->page == "") ? 1 : $request->page;
            $i = ($page - 1) * PER_PAGE;

            return view('staffs.index', compact(
                'staffs', 'i', 'param'
            ));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $genders = ['0' => 'Male', '1' => 'Female'];
            $idType = DB::table('id_types')->pluck('name_kh', 'id');
            $bankNames = DB::table('banks')->pluck('name_kh', 'id');
            $provinces = DB::table('provinces')->orderBy('name_kh')->get();
            $currency = ['1' => 'រៀល', '2' => 'ដុល្លារ​អាមេរិក'];
            $homeVisit = ['0' => 'No', '1' => 'Yes'];
            $marital_status = DB::table('marital_status')->pluck('name_kh', 'id');

            return view('staffs.create', compact(
                'idType', 'bankNames', 'genders', 'provinces', 'currency', 'homeVisit', 'marital_status'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StaffPersonalInfoStore $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(StaffPersonalInfoStore $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->except(['staffProfile']);
            $input['pob'] = preg_replace('/[ ]{2,}|[\t]/', ' ', @trim($request->get('pob')));
            $input['other_location'] = preg_replace('/[ ]{2,}|[\t]/', ' ', @trim($request->get('other_location')));
            $input['flag'] = 1;
            $input['created_by'] = Auth::id();
            $staff_id_card = DB::table('staff_personal_info')->max('staff_id');
            $input['staff_id'] = $staff_id_card + 1;
            // Check file and push to array input
            if ($request->hasFile('staffProfile')) {
                $image = $request->file('staffProfile');
                $fileName = $image->getClientOriginalName();
                $renameFile = time() . '_' . date('d-M-Y') . '_' . $fileName;
                $input['photo'] = $renameFile;
            }

            $create = StaffPersonalInfo::create($input);

            /**
             * IF create success will move image to folder,
             * And then commit transaction
             */
            if ($create == true) {

                // Move image to folder
                if ($request->hasFile('staffProfile')) {

                    $destinationPath = public_path('/images/staff/thumbnail/');
                    $img = Image::make($image->getRealPath()); // Get real path when user upload

                    $img->fit(150, 250, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->save($destinationPath . $renameFile);

                    $image->move(public_path('/images/staff/'), $renameFile);
                }

                DB::commit();

                $staff_id = encrypt($create->id);
                $newContract = $request->input('is_new_contract');
                if ($newContract) {
                    return redirect()->route('contract.index', ["staff_id" => $staff_id, "is_new_contract" => $newContract])->with(['success' => 1]);
                } else {
                    return redirect()->route('staff-personal-info.edit', $staff_id)->with(['success' => 1]);
                }

            } else {
                DB::rollBack();
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $ex->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $uid = decrypt($id);
            $staff = StaffPersonalInfo::with('firstContract')->findOrFail($uid);
            $marital_status = DB::table("marital_status")->where('id', $staff->marital_status)->first();
            $id_type = DB::table("id_types")->where('id', $staff->id_type)->first();
            $bank = DB::table("banks")->where('id', $staff->bank_name)->first();
            $province_id = DB::table("provinces")->where('id', $staff->province_id)->first();
            $district_id = DB::table("districts")->where('id', $staff->district_id)->first();
            $commune_id = DB::table("communes")->where('id', $staff->commune_id)->first();
            $village_id = DB::table("villages")->where('id', $staff->village_id)->first();

            $edu_data = DB::table('staff_education AS edu')
                ->where('edu.staff_personal_info_id', '=', $uid)
                ->where('edu.deleted_at', '=', null)
                ->join('degree AS deg', 'deg.id', '=', 'edu.degree_id')
                ->join('study_year AS sty', 'sty.id', '=', 'edu.study_year')
                ->join('provinces AS pro', 'pro.id', '=', 'edu.province_id')
                ->select('sty.name_kh AS study_year', 'deg.name_kh AS degree', 'pro.name_kh AS province')
                ->get();

            $train_data = DB::table('staff_training AS stn')
                ->where('stn.staff_personal_info_id', '=', $uid)
                ->where('stn.deleted_at', '=', null)
                ->join('provinces AS pro', 'pro.id', '=', 'stn.province_id')
                ->select('pro.name_kh AS province')
                ->get();

            $experience = DB::table('staff_experience AS sex')
                ->where('sex.staff_personal_info_id', '=', $uid)
                ->where('sex.deleted_at', '=', null)
                ->join('level_position AS lvp', 'lvp.id', '=', 'sex.level_id')
                ->join('provinces AS pro', 'pro.id', '=', 'sex.province_id')
                ->select('position AS position', 'lvp.name_kh AS level_position', 'pro.name_kh AS province')
                ->get();

            $spouse = DB::table('staff_spouse AS sp')
                ->where('sp.staff_personal_info_id', '=', $uid)
                ->where('sp.deleted_at', '=', null)
                ->get();

            $guarantor = DB::table('staff_guarantor AS gat')
                ->where('gat.staff_personal_info_id', '=', $uid)
                ->where('gat.deleted_at', '=', null)
                ->join('id_types AS idt', 'idt.id', '=', 'gat.id_type')
                ->join('marital_status AS mat', 'mat.id', '=', 'gat.marital_status')
                ->join('relationship AS res', 'res.id', '=', 'gat.related_id')
                ->select('mat.name_kh AS marital_status', 'idt.name_kh AS id_type', 'res.name_kh AS relationship')
                ->get();


//        $profile = DB::table('staff_info AS info')
//            ->where('info.staff_personal_info_id', '=', $uid)
//            ->where('info.deleted_at', '=', null)
//            ->join('companies AS com', 'com.id', 'info.company_id')
//            ->join('branches AS bra', 'bra.id', 'info.branch_id')
//            ->join('departments AS dpt', 'dpt.id', 'info.dpt_id')
//            ->join('positions AS pos', 'pos.id', 'info.position_id')
//            ->select('com.name_kh AS company', 'bra.name_kh As branch', 'dpt.name_kh AS department', 'pos.name_kh AS position')
//            ->get();

            $document = DB::table('staff_document AS stdoc')
                ->where('stdoc.staff_personal_info_id', '=', $uid)
                ->where('stdoc.deleted_at', '=', null)
                ->join('staff_document_types AS doc', 'doc.id', 'stdoc.staff_document_type_id')
                ->select('doc.name_kh AS document_name')
                ->get();

            $data_selected = (object)[
                'marital_status' => $marital_status,
                'id_type' => $id_type,
                'bank_name' => $bank,
                'province' => $province_id,
                'district' => $district_id,
                'commune' => $commune_id,
                'village' => $village_id,
                'education_data' => $edu_data,
                'training_data' => $train_data,
                'experience' => $experience,
                'spouse' => $spouse,
                'guarantor' => $guarantor,
//            'profile'   => $profile,
                'document' => $document,
            ];

            return view('staffs.show', compact('staff', 'data_selected'));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    public function edit($id, Request $request)
    {
        try {
            $staff = StaffPersonalInfo::findOrFail(decrypt($id));

            $genders = ["0" => 'Male', "1" => 'Female'];
            $idType = DB::table('id_types')->pluck('name_kh', 'id');
            $bankNames = DB::table('banks')->pluck('name_kh', 'id');
            $marital_status = DB::table('marital_status')->pluck('name_kh', 'id');
            $homeVisit = ['0' => 'No', '1' => 'Yes'];

            $provinces = DB::table('provinces')->orderBy('name_kh')->get();
            $districts = DB::table('districts')->where('province_id', $staff->province_id)->orderBy('name_kh')->get();
            $communes = DB::table('communes')->where('district_id', $staff->district_id)->orderBy('name_kh')->get();
            $villages = DB::table('villages')->where('commune_id', $staff->commune_id)->orderBy('name_kh')->get();

            return view('staffs.edit-personal-info', compact(
                'idType', 'bankNames', 'genders', 'provinces', 'districts', 'communes', 'villages', 'staff',
                'marital_status'
            ));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StaffPersonalInfoUpdate $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(StaffPersonalInfoUpdate $request)
    {

        DB::beginTransaction();
        try {

            $staff_id = decrypt($request->staff_token);
            $staff = StaffPersonalInfo::findOrFail($staff_id);
            $auth_id = Auth::id();

            /**
             * Staff personal info
             * ================================
             */
            $input = $request->except(['dob', 'staffProfile', '_token', 'staff_token']);   // Get only personal info
            $input['dob'] = date('Y-m-d', strtotime($request->dob));
            $input['updated_by'] = $auth_id;

            // Check file and push to array input
            if ($request->hasFile('staffProfile')) {
                $image = $request->file('staffProfile');
                $fileName = $image->getClientOriginalName();
                $renameFile = time() . '_' . date('d-M-Y') . '_' . $fileName;
                $input['photo'] = $renameFile;
            }

            $update = StaffPersonalInfo::where('flag', 1)->where('id', $staff_id)->update($input);

            /**
             * IF create success will move image to folder,
             * And then commit transaction
             */
            if ($update == true) {
                // Move image to folder
                if ($request->hasFile('staffProfile')) {
                    $destinationPath = public_path('/images/staff/thumbnail/');

                    // Check if have old file and then will delete
                    if (\File::exists(public_path('/images/staff/' . $staff->photo))) {

                        \File::delete(public_path('/images/staff/' . $staff->photo));
                        \File::delete(public_path('/images/staff/thumbnail/' . $staff->photo));
                    }

                    $img = Image::make($image->getRealPath()); // Get real path when user upload
                    $img->fit(150, 250, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->save($destinationPath . $renameFile);

                    $image->move(public_path('/images/staff/'), $renameFile);
                }

                DB::commit();
                return redirect()->back()->with(['success' => 1]);

            } else {
                DB::rollBack();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        try {
            $staff_id = decrypt($request->staff_id);
            $staff = StaffPersonalInfo::findOrFail($staff_id);
            $staff->delete();
            return response()->json(['success' => 1]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }

    }


    /**
     * Filter data
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function filter(Request $request)
    {
        try {
            $param = $request->all();
            $query = StaffPersonalInfo::orderBy('id', 'DESC');
            $keyWord = str_replace(' ', '', $request->key_word);
            $gender = $request->gender;
            $marital_status = $request->marital_status;

            if ($keyWord) {
                $query->where(function ($q) use ($keyWord) {
                    $q->where(DB::raw('CONCAT(last_name_en,first_name_en)'), 'LIKE', "%$keyWord%")
                        ->orWhere(DB::raw('CONCAT(last_name_kh,first_name_kh)'), 'LIKE', "%$keyWord%")
                        ->orWhere('email', 'like', "%$keyWord%")
                        ->orWhere('phone', 'like', "%$keyWord%")
                        ->orWhere('staff_id', 'like', "%$keyWord%")
                        ->orWhere('bank_acc_no', 'like', "%$keyWord%");
                });
            }

            if ($gender != null) {
                $query->where('gender', '=', $gender);
            }
            if ($marital_status) {
                $query->where('marital_status', '=', $marital_status);
            }

            $staffs = $query->paginate(PER_PAGE);

            $page = ($request->page == "") ? 1 : $request->page;
            $i = ($page - 1) * PER_PAGE;

            return view('staffs.index', compact(
                'staffs', 'i', 'param'
            ));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    /**
     * Show form import staff
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function import()
    {
        return view('staffs.import');
    }

    public function importFile(Request $request)
    {
        ini_set('memory_limit', '256M');
        try {
            if (!$request->hasFile('import_file')) {
                return redirect()->back()->withErrors(['0' => 'File empty or something wrong validation !']);
            }
            $validator = Validator::make(
                [
                    'import_file' => $request->file('import_file'),
                    'extension' => strtolower($request->file('import_file')->getClientOriginalExtension()),
                ],
                [
                    'import_file' => 'required|max:60000', //size:20000
                    'extension' => 'required|in:xlsx,xls,xlsm',
                ]
            );
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            Excel::import(new StaffImport, $request->file('import_file'));

//            return redirect()->back()->with(['success' => 1]);

        } catch (\Exception $e) {

            $error = \Illuminate\Validation\ValidationException::withMessages([
                'import_file' => ['Something wrong !'],
            ]);
            throw $error;
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function staffDetailJson($id)
    {
        $data = StaffPersonalInfo::select('id', 'last_name_en', 'first_name_en', 'email')->where('id', $id)->first();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function viewPensionFund($staffPersonalInfoId)
    {
        $staff = StaffPersonalInfo::with('currentPensionFund')->findOrFail(decrypt($staffPersonalInfoId));
        //Find Pension fund acr 5% from company
        $calculatePF = new AutoCalculatePensionFund($staff->id);
        $contractCurrency = Contract::selectRaw(
            "contract_object->>'$.currency' as currency, contract_object->>'$.company.code' as company_code, staff_id_card"
        )
            ->where('id', @$staff->currentPensionFund->contract_id)
            ->first();
        $totalPf = $calculatePF->calculatePFFromCompany(@$contractCurrency->staff_id_card, @$contractCurrency->company_code);
        return view('staffs.view_pension_fund', compact('staff', 'totalPf', 'contractCurrency'));
    }


    public function viewTraining($staffPersonalInfoId)
    {
        $decryptId = decrypt($staffPersonalInfoId);
        $staff = StaffPersonalInfo::with(['currentPensionFund', 'currentContract'])->findOrFail($decryptId);
        $sql = "select
                    en.course_id,
                    count(en.course_id) as total_training,
                   
                    co.json_data->>'$.title' as course_title,   
                    ca.json_data->>'$.title_en' as category_title

                from enrollments en
                inner join trainees tr
                    on en.id = tr.enrollment_id
                inner join courses co
                    on en.course_id=co.id
                inner join categories ca
                    on ca.id=co.category_id
                where en.deleted_at is null
                      and tr.staff_personal_id=$decryptId and tr.training_status is not null
                group by en.course_id";

        $courses = DB::select($sql);
        return view('staffs.view_training', compact('staff', 'courses'));
    }
}
