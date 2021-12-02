<?php

namespace App\Http\Controllers\StaffInfo;

use App\Branch;
use App\Company;
use App\Department;
use App\Position;
use App\StaffInfoModel\StaffMovement;
use App\StaffInfoModel\StaffPersonalInfo;
use App\StaffInfoModel\StaffProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class StaffMovementController extends Controller
{
    /**
     * StaffMovementController constructor.
     */
    public function __construct()
    {
        $this->middleware('permission:view_staff_movement');
        $this->middleware('permission:add_staff_movement', ['only' => ['create','store']]);
        $this->middleware('permission:edit_staff_movement', ['only' => ['edit','update']]);
        $this->middleware('permission:delete_staff_movement', ['only' => ['destroy']]);
    }

    /**
     * List all source staff movement
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $param = $request->all();
            $companies = Company::orderBy('name_kh')->get();
            $branches = Branch::orderBy('name_kh')->get();
            $departments = Department::orderBy('name_kh')->get();
            $positions = Position::orderBy('name_kh')->get();

            $staffs = StaffMovement::with('personalInfo')
                ->orderBy('flag', 'desc')
                ->orderBy('effective_date', 'desc')
                ->paginate(15);
                
            $page = isset($request->page) ? $request->page : 1;
            $i =  ($page - 1) * 15;

            return view('staff_movement.index', compact(
                'staffs', 'i', 'companies', 'branches', 'departments', 'positions', 'param'
            ));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    /**
     * Show form create new
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create ()
    {
        try {
            $companies = Company::where('deleted_at', '=', null)->orderBy('name_kh')->get();
            $branches = Branch::where('deleted_at', '=', null)->orderBy('name_kh')->get();
            $departments = Department::where('deleted_at', '=', null)->orderBy('name_kh')->get();
            $positions = Position::where('deleted_at', '=', null)->orderBy('name_kh')->get();
            return view('staff_movement.create', compact('companies', 'branches', 'departments', 'positions'));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    /**
     * Save staff movement
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();
            $staff_id = decrypt($input['staff_token']);

            $raw1 = $input["old_salary"];
            if (isset($raw1)) {
                $explode1 = str_replace(",", "", $raw1);
                $salary1 = explode(".", $explode1);
                $input['old_salary'] = $salary1[0];
            }

            $raw = $input["new_salary"];
            if (isset($raw)) {
                $explode = str_replace(",", "", $raw);
                $salary = explode(".", $explode);
                $input['new_salary'] = $salary[0];
            }
            
            $validation = Validator::make($input, [
                'staff_id'      => 'required|exists:staff_info,emp_id_card',
                'old_company'    => 'required',
                'old_branch'     => 'required',
                'old_department' => 'required',
                'old_position'   => 'required',
                'company_id'    => 'required',
                'branch_id'     => 'required',
                'department_id' => 'required',
                'position_id'   => 'required',
                'old_salary'    => 'numeric|nullable',
                'new_salary'    => 'required|numeric',
                'effective_date'=> 'required',
                'file_reference'=> 'required|max:10000|mimes:pdf, doc, docx',
                'transfer_to_id'=> 'different:staff_id',
                'get_work_form_id'=> 'different:staff_id',
            ]);
            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            /**
             * If staff use to movement, so we need to delete old record.
             */
            $hasMove = StaffMovement::where('staff_personal_info_id', '=', $staff_id)
                ->where('deleted_at', '=', null)->first();
            if (isset($hasMove)) {
                $hasMove->update(['updated_by' => Auth::id()]);
                $hasMove->delete();
            }

            /**
             * - We need to get old staff profile to store in table staff_movement.
             * - No need check $profile have or not because we check already when user input.
             */
            $profile = StaffProfile::where('staff_personal_info_id', '=', $staff_id)
                ->where('deleted_at', '=', null)->first();

            $input['staff_personal_info_id'] = $staff_id;
            $input['company_id']    = $profile->company_id;
            $input['branch_id']     = $profile->branch_id;
            $input['department_id'] = $profile->dpt_id;
            $input['position_id']   = $profile->position_id;
            $input['to_company_id']    = $request->company_id;
            $input['to_branch_id']     = $request->branch_id;
            $input['to_department_id'] = $request->department_id;
            $input['to_position_id']   = $request->position_id;
            if (!empty($input['effective_date'])) {
                $input['effective_date'] = date('y-m-d', strtotime($input['effective_date']));
            }
            $input['flag'] = 1;
            $input['created_by'] = Auth::id();

            // Save file reference
            $staffInfo = StaffPersonalInfo::findOrFail($staff_id)->first();
            if (isset($input['file_reference'])) {
                $ext = $request->file('file_reference')->extension();
                $fileName = $staffInfo->last_name_en.'_'.$staffInfo->first_name_en.'_'.$staffInfo->id.'.'.$ext;
                $input['file_reference'] = $request->file('file_reference')->storeAs('public/movement_form', $fileName);
            }

            $saveMove = StaffMovement::create($input); // Save staff movement

            if ($saveMove == true) {

                // We need to update staff profile like as
                $new_input = $request->only([
                    'new_salary', 'company_id', 'branch_id', 'department_id', 'position_id'
                ]);
                $raw = $new_input["new_salary"];
                $explode = str_replace(",", "", $raw);
                $salary = explode(".", $explode);
                $new_salary = $salary[0];

                $new_input['base_salary']  = $new_salary;
                $new_input['dpt_id']       = $new_input['department_id'];
                $new_input['updated_by']   = Auth::id();
                $update = $profile->update($new_input);

                if ($update == true) {
                    DB::commit();
                    return redirect()->route('movement.index')->with(['success' => 1]);

                } else {
                    DB::rollBack();
                    return redirect()->back(['success' => 0]);
                }

            } else {
                DB::rollBack();
                return redirect()->back(['success' => 0]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $s_id = decrypt($id);
        $move = StaffMovement::where('staff_personal_info_id', '=', $s_id)
            ->with(['profile'])->where('deleted_at', '=', null)->first();
        $companies = DB::table('companies')->where('deleted_at', '=', null)->orderBy('name_kh')->get();
        $branches = DB::table('branches')->where('deleted_at', '=', null)->orderBy('name_kh')->get();
        $departments = DB::table('departments')->where('deleted_at', '=', null)->orderBy('name_kh')->get();
        $positions = DB::table('positions')->where('deleted_at', '=', null)->get();

        return view('staff_movement.edit', compact('move', 'companies', 'branches', 'departments', 'positions'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();
            $staff_id = decrypt($input['staff_token']);

            $raw1 = $input["old_salary"];
            if (isset($raw1)) {
                $explode1 = str_replace(",", "", $raw1);
                $salary1 = explode(".", $explode1);
                $input['old_salary'] = $salary1[0];
            }

            $raw = $input["new_salary"];
            if (isset($raw)) {
                $explode = str_replace(",", "", $raw);
                $salary = explode(".", $explode);
                $input['new_salary'] = $salary[0];
            }

            $validation = Validator::make($input, [
                'staff_id'      => 'required|exists:staff_info,emp_id_card',
                'old_company'    => 'required',
                'old_branch'     => 'required',
                'old_department' => 'required',
                'old_position'   => 'required',
                'company_id'    => 'required',
                'branch_id'     => 'required',
                'department_id' => 'required',
                'position_id'   => 'required',
                'old_salary'    => 'numeric|nullable',
                'new_salary'    => 'required|numeric',
                'effective_date'=> 'required',
                'file_reference'=> 'max:10000|mimes:pdf, doc, docx',
                'transfer_to_id'=> 'different:staff_id',
                'get_work_form_id'=> 'different:staff_id',
            ]);
            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $move = StaffMovement::where('staff_personal_info_id', '=', $staff_id)
                ->where('deleted_at', '=', null)->first();

            $input['staff_personal_info_id'] = $staff_id;
            $input['company_id']    = $move->company_id;
            $input['branch_id']     = $move->branch_id;
            $input['department_id'] = $move->department_id;
            $input['position_id']   = $move->position_id;
            $input['to_company_id']    = $request->company_id;
            $input['to_branch_id']     = $request->branch_id;
            $input['to_department_id'] = $request->department_id;
            $input['to_position_id']   = $request->position_id;
            if (!empty($input['effective_date'])) {
                $input['effective_date'] = date('y-m-d', strtotime($input['effective_date']));
            }
            $input['flag'] = 1;
            $input['updated_by'] = Auth::id();

            // Save file reference
            $staffInfo = StaffPersonalInfo::findOrFail($staff_id)->first();
            if (isset($input['file_reference'])) {
                $ext = $request->file('file_reference')->extension();
                $fileName = $staffInfo->last_name_en.'_'.$staffInfo->first_name_en.'_'.$staffInfo->id.'.'.$ext;
                $input['file_reference'] = $request->file('file_reference')->storeAs('public/movement_form', $fileName);
            }
            // Update staff movement
            $moveUpdate = $move->update($input);

            if ($moveUpdate == true) {

                // We need to update staff profile like as
                $new_input = $request->only(['new_salary', 'company_id', 'branch_id', 'department_id', 'position_id']);

                $raw = $new_input["new_salary"];
                $explode = str_replace(",", "", $raw);
                $salary = explode(".", $explode);
                $new_salary = $salary[0];

                $new_input['base_salary']  = $new_salary;
                $new_input['dpt_id']       = $new_input['department_id'];
                $new_input['updated_by']   = Auth::id();

                $profile = StaffProfile::where('staff_personal_info_id', '=', $staff_id)
                    ->where('deleted_at', '=', null)->first();

                $update = $profile->update($new_input);

                if ($update == true) {
                    DB::commit();
                    return redirect()->route('movement.index')->with(['success' => 1]);

                } else {
                    DB::rollBack();
                    return redirect()->back(['success' => 0]);
                }

            } else {
                DB::rollBack();
                return redirect()->back(['success' => 0]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $s_id = decrypt($id);
            $move = StaffMovement::where('staff_personal_info_id', '=', $s_id)->where('deleted_at', '=', null)->first();
            $move->update(['updated_by' => Auth::id()]);
            $deleted = $move->delete();

            if ($deleted == true) {
                DB::commit();
                return response()->json(['flag' => 1]);
            } else {
                DB::rollBack();
                return response()->json(['flag' => 0]);
            }

        } catch (\Exception $e) {
            BD::rollBack();
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    /**
     * Find employee id
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function find(Request $request)
    {
        try {
            $emp_id = $request->emp_id;
            $staff_id = StaffProfile::where('emp_id_card', '=', $emp_id)->pluck('staff_personal_info_id')->first();

            if (!isset($staff_id)) {
                return \response()->json(['flag' => 204]); // Wrong id card
            }

            $data = StaffPersonalInfo::with([
                'profile.company', 'profile.branch', 'profile.department', 'profile.position'
            ])->findOrFail($staff_id);

            if (!empty($data)) {
                $data->profile->staff_personal_info_id = encrypt($data->profile->staff_personal_info_id);
            }

            return \response()->json(['staff' => $data, 'flag' => 1]); //Successfully

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    /**
     * Filter staff movement
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function filter(Request $request)
    {
        try {
            $param = $request->all();
            $query = StaffMovement::withTrashed()
                ->orderBy('flag', 'desc')
                ->orderBy('effective_date', 'desc')
                ->with(['profile', 'personalInfo']);
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
//                        ->orWhere('email', 'like', "%$keyWord%")
//                        ->orWhere('phone', 'like', "%$keyWord%")
                        ->orWhere('id_code', $keyWord)
                        ->orWhere('bank_acc_no', $keyWord);
                });
                $query->orWhereHas('profile', function ($q) use ($keyWord) {
                    $q->where('emp_id_card', $keyWord);
                });
            }

            if ($company) {
                $query->whereHas('profile', function ($q) use ($company){
                    $q->where('company_id', '=', $company);
                });
            }
            if ($branch) {
                $query->whereHas('profile', function ($q) use ($branch){
                    $q->where('branch_id', '=', $branch);
                });
            }
            if ($department) {
                $query->whereHas('profile', function ($q) use ($department){
                    $q->where('dpt_id', '=', $department);
                });
            }
            if ($position) {
                $query->whereHas('profile', function ($q) use ($position){
                    $q->where('position_id', '=', $position);
                });
            }

            $staffs = $query->paginate(15);
            $page = isset($request->page) ? $request->page : 1;
            $i =  ($page - 1) * 15;
            return view('staff_movement.index', compact(
                'staffs', 'i', 'companies', 'branches', 'departments', 'positions', 'param'
            ));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    /**
     * Show staff movement
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $did = decrypt($id);
        $movements = StaffMovement::withTrashed()->where('staff_personal_info_id', $did)
            ->orderBy('id', 'asc')
            ->get();

        return view('staff_movement.show', compact('movements'));
    }
}
