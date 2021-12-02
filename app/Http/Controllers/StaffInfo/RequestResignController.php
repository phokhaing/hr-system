<?php

namespace App\Http\Controllers\StaffInfo;

use App\BranchesAndDepartments;
use App\Company;
use App\Contract;
use App\Exports\RequestResignExport;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\DepartmentBranchResource;
use App\Http\Resources\PositionResource;
use App\Position;
use App\RequestResign;
use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RequestResignController extends Controller
{
    /**
     * Edit request resign
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|string
     */
    public function edit($id)
    {
        try {
            $contract = Contract::currentContract(decrypt($id))->first();
            $staff = StaffPersonalInfo::findOrFail(decrypt($id));
            $current_company = substr(@$contract->company_profile,0,3);
            $current_branch = substr(@$contract->company_profile,3,3);
            $current_position = substr(@$contract->company_profile,6,3);

            if ($current_company != Auth::user()->company->company_code)
                return redirect()->back()->withErrors(['0' => 'មិនអាចធ្វើការ Request Resign ឆ្លងក្រុមហ៊ុនបានទេ!']);

            $contract_active = array_key_exists(@$contract->contract_type, array_flip(CONTRACT_ACTIVE_TYPE));

            if (! $contract_active)
                return redirect()->back()->withErrors(['0' => 'កុងត្រាដែលអាចធ្វើការស្នើរសុំឈប់បានត្រូវតែជាប្រភេទ Active Contract.']);

            $companies = Company::GetCompanyByCode(\auth()->user()->company_code)->get();
            $branchesDepartments = BranchesAndDepartments::getByCompanyCode(auth()->user()->company_code)->get();
            $positions = Position::getByCompanyCode(auth()->user()->company_code)->get();

            return view('staffs.edit-request-resign', compact(
                'contract',
                'staff',
                'current_branch',
                'current_company',
                'current_position',
                'companies',
                'branchesDepartments',
                'positions'
            ));

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 1- Get current contract
     * 2- Staff can resign must have active contract
     *
     * @param Request $request
     * @return string
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $contract = Contract::currentContract(decrypt($input['staff_personal_info_id']))->first();

        $staff_active = array_key_exists($contract->contract_type, array_flip(CONTRACT_ACTIVE_TYPE));
        if (! $staff_active)
            return redirect()->back()->withErrors(['0' => 'កុងត្រាដែលអាចធ្វើការស្នើរសុំឈប់បានត្រូវតែជាប្រភេទ Active Contract.']);

        $company = Company::getCompanyByCode($input['company_code'])->first();
        $branch_department = BranchesAndDepartments::GetByCode($input['branch_department_code'])->first();
        $position = Position::getByCompanyCode($input['company_code'])->where('code', $input['position_code'])->first();

        $obj_resign = [
            'request_date' => (date( 'Y-m-d', strtotime($input['request_date']))),
            "company" => new CompanyResource($company),
            "branch_department" => new DepartmentBranchResource($branch_department),
            "position" => new PositionResource($position),
            "reason" => $input["reason"]
        ];

        $data = [
            'staff_personal_info_id' => decrypt($input['staff_personal_info_id']),
            'resign_object' => $obj_resign,
            'created_by' => Auth::user()->full_name
        ];

        $request_resign = new RequestResign();
        $save = $request_resign->createRecord($data);
        if ($save) {
            return redirect()->back()->with(['success' => 1]);
        }
    }

    /**
     * Update request resign
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $input = $request->all();
        $contract = Contract::currentContract(decrypt($input['staff_personal_info_id']))->first();

        $staff_active = array_key_exists($contract->contract_type, array_flip(CONTRACT_ACTIVE_TYPE));
        if (! $staff_active)
            return redirect()->back()->withErrors(['0' => 'កុងត្រាដែលអាចធ្វើការស្នើរសុំឈប់បានត្រូវតែជាប្រភេទ Active Contract.']);

        $company = Company::getCompanyByCode($input['company_code'])->first();
        $branch_department = BranchesAndDepartments::GetByCode($input['branch_department_code'])->first();
        $position = Position::getByCompanyCode($input['company_code'])->where('code', $input['position_code'])->first();

        $obj_resign = [
            'request_date' => (date( 'Y-m-d', strtotime($input['request_date']))),
            "company" => new CompanyResource($company),
            "branch_department" => new DepartmentBranchResource($branch_department),
            "position" => new PositionResource($position),
            "reason" => $input["reason"]
        ];

        $data = [
            'staff_personal_info_id' => decrypt($input['staff_personal_info_id']),
            'resign_object' => $obj_resign,
            'created_by' => Auth::user()->full_name,
            'updated_by' => Auth::user()->full_name,
        ];

        $request_resign = new RequestResign();
        $update = $request_resign->updateRecord( decrypt($input['resign_id']), $data);
        if ($update) {
            return redirect()->back()->with(['success' => 1]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function list(Request $request)
    {
        $company_code = (int) \auth()->user()->company->company_code;
        $param = $request->except(['download']);
        $keyword = $request->input('keyword');
        $gender = $request->input('gender');

        if ($request->input('download')) {
            return Excel::download(new RequestResignExport($keyword, $gender), 'staff_request_resign.xlsx');
        }
//dd(\auth()->user()->company->company_code);
        $request_resigns = RequestResign::with('staffPersonalInfo')
            ->search($keyword, '', '', '', $gender)
            ->byCompanyCode($company_code)
            ->latest()
            ->paginate(PER_PAGE);
        $page = ($request->page == "") ? 1 : $request->page;
        $i = ($page - 1) * PER_PAGE;

        return view('staffs.request-resign-list', compact(
            'request_resigns', 'i', 'param'
        ));
    }
}
