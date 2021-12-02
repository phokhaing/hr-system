<?php

namespace App\Http\Controllers\StaffInfo;

use App\Branch;
use App\Company;
use App\Department;
use App\Http\Controllers\Controller;
use App\Position;
use App\StaffInfoModel\StaffMovement;
use App\StaffInfoModel\StaffPersonalInfo;
use App\StaffInfoModel\StaffProfile;
use App\StaffInfoModel\StaffResign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Cast\Object_;
use Ramsey\Uuid\Uuid;

class StaffResignController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:view_resign');
        $this->middleware('permission:add_resign', ['only' => ['create','store']]);
        $this->middleware('permission:edit_resign', ['only' => ['edit','update']]);
        $this->middleware('permission:delete_resign', ['only' => ['destroy']]);
    }

    /**
     * List all staff resign
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $param = $request->all();
        $companies = Company::orderBy('name_kh')->get();
        $branches = Branch::orderBy('name_kh')->get();
        $departments = Department::orderBy('name_kh')->get();
        $positions = Position::orderBy('name_kh')->get();

        $staffResigns = StaffResign::latest()->paginate(10);
        $page = ($request->page == "") ? 1 : $request->page;
        $i = ($page - 1) * 10;

        return view('staff_resign.index', compact(
            'staffResigns', 'i', 'companies', 'branches', 'departments', 'positions', 'param'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $reasons = DB::table('reason_company_note')->get();
        $staffResign = StaffPersonalInfo::all();

        return view('staff_resign.create', compact('staffResign', 'reasons'));
    }

    /**
     * Store staff resigned
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $input = $request->all();

            $validation = Validator::make($input, [
                'staff_id'  => 'required|exists:staff_info,emp_id_card',
                'reason'    => 'required',
                'reason_id' => 'required',
                'staff_id_replace_1'=> 'different:staff_id',
                'staff_id_replace_2'=> 'different:staff_id',
                'resign_date'       => 'required_without:is_fraud',
                'file_reference'    => 'required|max:10000|mimes:pdf, doc,docx',
            ]);

            if (!empty($input['approve_date'])) {
                $input['approved_date'] = date('y-m-d', strtotime($input['approve_date']));
            }
            if (!empty($input['last_day'])) {
                $input['last_day'] = date('y-m-d', strtotime($input['last_day']));
            }
            if (!empty($input['resign_date'])) {
                $input['resign_date'] = date('y-m-d', strtotime($input['resign_date']));
            }
            $input['reason_company_id'] = $input["reason_id"];
            $input['staff_personal_info_id'] = decrypt($input['staff_token']);
            $input['staff_id_replaced_1']     = $input['staff_id_replace_1'];
            $input['staff_name_replaced_1']   = $input['staff_replace_name_1'];
            $input['staff_id_replaced_2']     = $input['staff_id_replace_2'];
            $input['staff_name_replaced_2']   = $input['staff_replace_name_2'];
            $input['created_by'] = Auth::id();

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $staff_id = decrypt($input['staff_token']);
            $staffInfo = StaffPersonalInfo::findOrFail($staff_id);
            $staffMovement = StaffMovement::where('staff_personal_info_id', $staff_id)
                ->where('branch_id', $staffInfo->profile->branch_id)->first();

            // Save file reference
            if (isset($input['file_reference'])) {
                $ext = $request->file('file_reference')->extension();
                $fileName = $staffInfo->last_name_en.'_'.$staffInfo->first_name_en.'_'.Uuid::uuid4().'.'.$ext;
                $input['file_reference'] = $request->file('file_reference')->storeAs('public/resign_form', $fileName);
            }

            // If staff already have last day
            if (!empty($input['last_day'])) {
                $updated = $staffInfo->update(['flag' => '3']); // code 3 was approved
                $staffInfo->delete();
                if (isset($staffMovement)) {
                    $staffMovement->delete();
                }

            } else {

                if (!empty($input['is_fraud'])) {
                    $updated = $staffInfo->update(['flag' => '5']); // code 5 waiting the last day (If fraud)
                } else {
                    $updated = $staffInfo->update(['flag' => '4']); // code 4 is padding request (If not fraud)
                }
            }

            if ($updated == true) {
                StaffResign::create($input);
                DB::commit();
                return redirect()->route('resign.index')->with(['success' => 1]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update approved date and last day
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function edit(Request $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->except(['staff_token', '_token']);

            $staff_id = decrypt($request->staff_token);
            $resigned = StaffResign::where('staff_personal_info_id', '=', $staff_id)
                ->where('deleted_at', '=', null)
                ->select('is_fraud')->first();

            // Check input if staff fraud
            if ($resigned->is_fraud == 1) {
                $validation = Validator::make($input, [
                    'last_day'      => 'required',
                ]);

            } else {
                $validation = Validator::make($input, [
                    'approved_date' => 'required_with:last_day',
//                    'last_day'      => 'required',
                ]);
            }

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            if (!empty($input['approved_date'])) {
                $input['approved_date'] = date('y-m-d', strtotime($input['approved_date']));
            }
            if (!empty($input['last_day'])) {
                $input['last_day'] = date('y-m-d', strtotime($input['last_day']));
            }
            $input['updated_by'] = Auth::id();
            $updated = StaffResign::where('staff_personal_info_id', '=', $staff_id)->update($input);

            if ($updated == true) {
                $staffInfo = StaffPersonalInfo::with('profile')->findOrFail($staff_id);
                $branch = $staffInfo->profile->branch_id;
                $staffMovement = StaffMovement::where('staff_personal_info_id', $staff_id)
                    ->where('to_branch_id', $branch)->first();

                // Check input have the last day
                if (isset($input['last_day'])) {
                    $save = $staffInfo->update(['flag' => '3', 'updated_by' => Auth::id() ]); // code 3 was approved
                    $staffInfo->delete();

                    if (isset($staffMovement)) {
                        $staffMovement->delete();
                    }
                    
                } else {
                    $save = $staffInfo->update(['flag' => '5', 'updated_by' => Auth::id() ]); // code 5 waiting the last day
                }

                if ($save == true) {
                    DB::commit();
                    return redirect()->back()->with(['success' => 1]);
                }
            }

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    /**
     * Search employee id card
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function findId(Request $request)
    {
        $emp_id = $request->emp_id;
        $profile = StaffProfile::where('emp_id_card', '=', $emp_id)->select('staff_personal_info_id')->first();

        if (!empty($profile)) {

            $staff_id = $profile->staff_personal_info_id;
            $resign = StaffResign::where('staff_personal_info_id', '=', $staff_id)->where('deleted_at', '=', null)->first();

            // IF staff never resign
            if (empty($resign)) {
                $staff = StaffPersonalInfo::with([
                    'profile' ,'profile.company', 'profile.branch', 'profile.department', 'profile.position'
                ])->findOrFail($staff_id);

                $data = [];
                if (!empty($staff)) {
                    $data = [
                        "full_name_kh"      => $staff->last_name_kh." ".$staff->first_name_kh,
                        "full_name_en"      => $staff->last_name_en." ".$staff->first_name_en,
                        "gender"            => $staff->gender,
                        'staff_personal_info_id' => encrypt($staff->profile->staff_personal_info_id),
                        "company_name"      => isset($staff->profile->company->short_name) ? $staff->profile->company->short_name : '',
                        "branch_name"       => isset($staff->profile->branch->name_kh) ? $staff->profile->company->short_name : '',
                        "department_name"   => isset($staff->profile->department->name_kh) ? $staff->profile->department->name_kh : '',
                        "position_name"     => isset($staff->profile->position->name_kh) ? $staff->profile->position->name_kh: '',
                        "employment_date"   => isset($staff->profile->employment_date)
                            ? date('d-M-Y', strtotime($staff->profile->employment_date)) : ''
                    ];
                }
                return response()->json(['staff' => $data, 'flag' => 1]);
            }

            return response()->json(['flag' => 0]);  // User already resign
        }

        return response()->json(['flag' => 204]);  // status 204 is wrong ID
    }

    /**
     * Staff reject resign
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function reject($id)
    {
        DB::beginTransaction();
        try {

            $staff_id = decrypt($id);
            $resign = StaffResign::where('staff_personal_info_id', '=', $staff_id)->first();

            $reject = [
                'reject_date' => date('Y-m-d', strtotime(now())),
                'updated_by' => Auth::id()
            ];

            $resign->update($reject); // Update reject date
            $deleted = $resign->delete(); // Delete record that reject

            if ($deleted == true) {
                $staffInfo = StaffPersonalInfo::findOrFail($staff_id);
                $staffInfo->update([
                    'flag' => 1,
                    'updated_by' => Auth::id()
                ]);

                DB::commit();
                return response()->json(['flag' => 1]);
            }
            DB::rollBack();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Staff resign filter.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function filter(Request $request)
    {
        try {
            $param = $request->all();
            $query = StaffResign::with(['personalInfo', 'personalInfo.profile']);
            $keyWord = str_replace(' ', '', $request->key_word);
            $company = $request->company;
            $branch = $request->branch;
            $department = $request->department;
            $position = $request->position;
            $companies = Company::all();
            $branches = Branch::all();
            $departments = Department::all();
            $positions = Position::all();

            if ($keyWord) {
                $query->whereHas('personalInfo', function ($q) use ($keyWord){
                    $q->where(DB::raw('CONCAT(last_name_en,first_name_en)') , 'LIKE' , "%$keyWord%")
                        ->orWhere(DB::raw('CONCAT(last_name_kh,first_name_kh)') , 'LIKE' , "%$keyWord%")
//                    ->orWhere('email', 'like', "%$keyWord%")
//                    ->orWhere('phone', 'like', "%$keyWord%")
                        ->orWhere('id_code', $keyWord)
                        ->orWhere('bank_acc_no', $keyWord);
                });
                $query->orWhereHas('personalInfo.profile', function ($q) use ($keyWord) {
                    $q->where('emp_id_card', $keyWord);
                });
            }

            if ($company) {
                $query->whereHas('personalInfo.profile', function ($q) use ($company){
                    $q->where('company_id', '=', $company);
                });
            }
            if ($branch) {
                $query->whereHas('personalInfo.profile', function ($q) use ($branch){
                    $q->where('branch_id', '=', $branch);
                });
            }
            if ($department) {
                $query->whereHas('personalInfo.profile', function ($q) use ($department){
                    $q->where('dpt_id', '=', $department);
                });
            }
            if ($position) {
                $query->whereHas('personalInfo.profile', function ($q) use ($position){
                    $q->where('position_id', '=', $position);
                });
            }

            $staffResigns = $query->paginate(10);
            $page = ($request->page == "") ? 1 : $request->page;
            $i = ($page - 1) * 10;

            return view('staff_resign.index', compact(
                'staffResigns', 'i', 'companies', 'branches', 'departments', 'positions', 'param'
            ));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    /**
     * Staff show resign
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        try {
            $s_id = decrypt($id);
            $resigns = StaffResign::where('staff_personal_info_id', $s_id)->get();
            return view('staff_resign.show', compact('resigns'));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
//            throw $e;
        }
    }
}
