<?php

namespace App\Http\Controllers;

use App\BranchesAndDepartments;
use App\Company;
use App\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BranchesAndDepartmentsController extends Controller
{
    /**
     * BranchesAndDepartmentsController constructor.
     */
    public function __construct()
    {
        $this->middleware('permission:view_branch_department', ['except' => ['getByCompany']]);
        $this->middleware('permission:add_branch_department', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_branch_department', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_branch_department', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $branchesDepartments = BranchesAndDepartments::getDependOnUser();

        $keyword = $request->get('keyword');
        if (@$keyword) {
            @$branchesDepartments->where('name_en', 'like', '%' . @$keyword . '%')
                ->orWhere('name_km', 'like', '%' . @$keyword . '%')
                ->orWhere('short_name', 'like', '%' . @$keyword . '%');
        }

        if (@$request->company) {
            $branchesDepartments->GetByCompanyCode(@$request->company);
        }

        $companies = Company::getCompanyDependOnUser()->orderBy('company_code', 'asc')->get();
        $branchesDepartments = $branchesDepartments->orderBy('company_code', 'asc')->orderBy('id', 'desc')->paginate(PER_PAGE);

        return view('branch_and_departments.index', compact('branchesDepartments', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::getCompanyDependOnUser()->latest()->get();
        return view('branch_and_departments.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $validation = Validator::make($input, [
                // 'code' => 'min:3|max:4|unique:branches_and_departments',
                'short_name' => 'nullable|min:2|max:15',
                'name_en' => 'required|min:2|max:190',
                'name_km' => 'required|min:2|max:190',
                'company_code' => 'required|min:3|max:4',
                'branch_or_department' => 'required'
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $isExist = BranchesAndDepartments::where(function ($query) use ($request) {
                $query->where('name_km', $request->get('name_km'))
                    ->OrWhere('name_en', $request->get('name_en'));
            })
                ->where('company_code', $request->get('company_code'))
                ->first();
            if ($isExist) {
                return redirect()->back()->withErrors(['0' => 'Name En or KH is already token with current company code[' . $request->get('company_code') . '], Please check again!']);
            }

            // Check user level One Company Or Many Company
            $company_code = '';
            if (!$request->input('company_code')) {
                $company_code = \auth()->user()->company_code;
            } else {
                $company_code = $request->input('company_code');
            }
            $min_department_code = $company_code + MIN_DEPARTMENT_CODE;
            if ($request->input('branch_or_department') == BRANCH) {
                // Find max code of branch and then plus 1 for new branch code.
                $max_code = BranchesAndDepartments::getLastBranchCode($company_code, $min_department_code)->max('code');
                if(!@$max_code){
                    $input['code'] = $request->get('company_code') + 1;
                }else{
                    $input['code'] = $max_code + 1;
                }
                
            } elseif ($request->input('branch_or_department') == DEPARTMENT) {
                // Find max code of department and then plus 1 for new department code.
                $max_code = BranchesAndDepartments::getLastDepartmentCode($company_code, $min_department_code)->max('code');
                if(!@$max_code){
                    $input['code'] = $min_department_code;
                }else{
                    $input['code'] = $max_code + 1;
                }
            } else {
                $isExist = BranchesAndDepartments::where('code', $request->get('company_code'))
                    ->first();
                if ($isExist) {
                    return redirect()->back()->withErrors(['0' => 'Head Office is already created with company coded[' . $request->get('company_code') . '], Please check again!']);
                }
                $input['code'] = $request->get('company_code');
            }

            $input['created_by'] = Auth::id();
            $input['updated_by'] = Auth::id();
            BranchesAndDepartments::create($input);
            return redirect('/branch-and-department')->with(['success' => 1]);
        } catch (\Exception $e) {
            return $e->getMessage();
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
        $branchOrDepartment = BranchesAndDepartments::find($id);
        return view('branch_and_departments.show', compact('branchOrDepartment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $branchOrDepartment = BranchesAndDepartments::find($id);
        $companies = Company::getCompanyDependOnUser()->latest()->get();
        return view('branch_and_departments.edit', compact('branchOrDepartment', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), [
                // 'code' => 'min:3|max:4|unique:branches_and_departments,code,'.$id,
                'short_name' => 'nullable|min:2|max:15',
                'name_en' => 'required|min:2|max:190',
                'name_km' => 'required|min:2|max:190',
                'company_code' => 'required|min:3|max:4'
            ]);
            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }            
            $isExist = BranchesAndDepartments::where(function ($query) use ($request) {
                $query->where('name_km', $request->get('name_km'))
                    ->OrWhere('name_en', $request->get('name_en'));
            })
                ->where('id', '!=', $id)
                ->where('company_code', $request->get('company_code'))
                ->first();
            if ($isExist) {
                return redirect()->back()->withErrors(['0' => 'Name En or KH is already token with current company code[' . $request->get('company_code') . '], Please check again!']);
            }

            $obj = BranchesAndDepartments::find($id);
            $input = $request->all();
            $input['updated_by'] = Auth::id();
            $update = $obj->update($input);
            if ($update) {
                return redirect('/branch-and-department')->with(['success' => 1]);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $obj = BranchesAndDepartments::find($id);
        BranchesAndDepartments::find($id)->update(['deleted_by' => Auth::id()]);
        if ($obj->delete()) {
            return redirect()->back()->with(['success' => 1]);
        }
    }

    /**
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByCompany($code)
    {
        $data = BranchesAndDepartments::getByCompanyCode($code)->get();
        return response()->json(['data' => $data]);
    }

    /**
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStaffInBranchDepartment($companyCode, $branchDepartmentCode)
    {
        $data = Contract::getAllStaffActiveByDepartmentBranch($companyCode, $branchDepartmentCode)
            ->orderBy('id', 'desc')
            ->get();

        $data->map(function ($contract) {
            $contract['staff_full_name'] = @$contract->staffPersonalInfo->last_name_en . ' ' . @$contract->staffPersonalInfo->first_name_en
                . ' (' . @$contract->staffPersonalInfo->staff_id . ')';
        });

        return response()->json(['data' => $data]);
    }
}
