<?php

namespace App\Http\Controllers\Report;

use App\BranchesAndDepartments;
use App\Company;
use App\Exports\RequestResignExport;
use App\Exports\StaffActiveExport;
use App\Exports\StaffEndContractExport;
use App\Exports\StaffMovementExport;
use App\Exports\StaffPersonalExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReportActiveStaffResource;
use App\Http\Resources\ReportStaffEndContractResource;
use App\Http\Resources\ReportStaffMovementResource;
use App\Http\Resources\ReportStaffRequestResignResource;
use App\Position;
use App\Report\ReportStaffContract;
use App\Report\ReportStaffProfile;
use App\Report\StaffProfile;
use App\RequestResign;
use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Staff profile. Show interface filter staff
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile()
    {
        try {
            $companies = Company::orderBy('short_name')->get();
            $branches = BranchesAndDepartments::orderBy('name_kh')->get();
            $positions = Position::orderBy('name_kh')->get();

            return view('reports.staff_profile.index', compact(
                'companies', 'branches', 'departments', 'positions'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }

    /**
     * View filter staff profile on web page
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function action(Request $request)
    {
        try {
            $key_word = $request->key_word;
            $company = $request->company;
            $branch = $request->branch;
            $department = $request->department;
            $position = $request->position;
            $gender = $request->gender;
            $start_date = isset($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : null;
            $end_date = isset($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : null;

            switch ($request->input('action')) {
                case 'view':
                    $report = new ReportStaffProfile();
                    $query = $report->view_profile($key_word, $company, $branch, $department, $position, $gender, $start_date, $end_date);
                    $profiles = $query->paginate(20);

                    $companies = Company::orderBy('short_name')->get();
                    $branches = BranchesAndDepartments::orderBy('name_kh')->get();
                    $positions = Position::orderBy('name_kh')->get();

                    $page = $request->all();
                    return view('reports.staff_profile.index', compact(
                        'profiles', 'companies', 'branches', 'departments', 'positions', 'page'
                    ));
                    break;

                case 'download':

                    return Excel::download(new StaffPersonalExport(
                        $key_word, $company, $branch, $department, $position, $gender, $start_date, $end_date
                    ), 'staff_profile.xlsx');

                    break;
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['0' => 'Something thing wrong !!!', '1' => $e->getMessage()]);
        }
    }



    /**
     * Show staff lack information
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lack_info()
    {
        return view('reports.lack_info.index');
    }

    public function get_lack_info()
    {
        $lackInfo = StaffPersonalInfo::with([
            'educations',
            'trainings',
            'experiences',
            'spouse',
            'guarantors',
            'profile',
            'documents'
        ])->get();

        return response()->json(['data' => $lackInfo]);
    }


    /**
     * Get report staff active.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|string
     */
    public function getActiveStaffApi(Request $request) {
        try {
            $contracts = (new ReportStaffContract())->advanceFilter(
                $request->key_word,
                $request->company_code,
                $request->branch_department_code,
                $request->position_code,
                $request->gender,
                $request->contract_start_date,
                $request->contract_end_date,
                $request->contract_type
            )
            ->where("contract_object->contract_last_date", '>=', date("Y-m-d"))
            ->whereIn('contract_type', [
                CONTRACT_ACTIVE_TYPE['FDC'],
                CONTRACT_ACTIVE_TYPE['UDC'],
                CONTRACT_ACTIVE_TYPE['DEMOTE'],
                CONTRACT_ACTIVE_TYPE['PROMOTE'],
                CONTRACT_ACTIVE_TYPE['CHANGE_LOCATION'],
            ])
            ->orderBy('id', 'DESC')
            ->get();

            return ReportActiveStaffResource::collection($contracts);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Donwload file excel
     */
    public function downloadStaffActive(Request $request)
    {
        try {
            return Excel::download(new StaffActiveExport(
                $request->key_word,
                $request->company_code,
                $request->branch_department_code,
                $request->position_code,
                $request->gender,
                $request->contract_start_date,
                $request->contract_end_date,
                $request->contract_type
            ), 'staff_active.xlsx');

        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Get report staff movement.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|string
     */
    public function getMovementStaffApi(Request $request)
    {
        try {
            $query = new ReportStaffContract();
            $contracts = $query->advanceFilterExceptActiveReport(
                $request->key_word,
                $request->company_code,
                $request->branch_department_code,
                $request->position_code,
                $request->gender,
                $request->contract_start_date,
                $request->contract_end_date,
                CONTRACT_TYPE['CHANGE_LOCATION']
            )
            ->orderBy('id', 'DESC')
            ->get();

            return ReportStaffMovementResource::collection($contracts);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Download report for file excel.
     */
    public function downloadMovementStaff(Request $request)
    {
        try {
            return Excel::download(new StaffMovementExport(
                $request->key_word,
                $request->company_code,
                $request->branch_department_code,
                $request->position_code,
                $request->gender,
                $request->contract_start_date,
                $request->contract_end_date
            ), 'staff_movement.xlsx');

        } catch (\Exception $e) {
            return $e->getMessage();
        }
     }

    /**
     * Get report staff end contract.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|string
     */
    public function getStaffEndContractApi(Request $request)
    {
        try {
            $query = new ReportStaffContract();
            $contracts = $query->advanceFilterExceptActiveReport(
                $request->key_word,
                $request->company_code,
                $request->branch_department_code,
                $request->position_code,
                $request->gender,
                $request->contract_start_date,
                $request->contract_end_date,
                $request->contract_type
            )
            ->whereIn('contract_type', [
                CONTRACT_END_TYPE['RESIGN'],
                CONTRACT_END_TYPE['DEATH'],
                CONTRACT_END_TYPE['TERMINATE'],
                CONTRACT_END_TYPE['LAY_OFF'],
            ])
            ->orderBy('id', 'DESC')
            ->get();

            return ReportStaffEndContractResource::collection($contracts);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Download staff end contract to file excel.
     */
    public function downloadStaffEndContract(Request $request)
    {
        try {
            return Excel::download(new StaffEndContractExport(
                $request->key_word,
                $request->company_code,
                $request->branch_department_code,
                $request->position_code,
                $request->gender,
                $request->contract_start_date,
                $request->contract_end_date,
                $request->contract_type
            ), 'staff_end_contract.xlsx');
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getStaffRequestResignApi(Request $request)
    {
        try {
            $query = new RequestResign();
            $resigns = $query->search(
                $request->input('keyword'),
                $request->input('company_code'),
                $request->input('branch_department_code'),
                $request->input('position_code'),
                $request->input('gender'),
                $request->input('request_resign_from'),
                $request->input('request_resign_to')
            )
            ->latest()
            ->get();

            return ReportStaffRequestResignResource::collection($resigns);

        } catch (\Exception $e) {
            return $e;
        }
    }

    public function downloadStaffRequestResign(Request $request)
    {
        try {
            return Excel::download(new RequestResignExport(
                $request->input('keyword'),
                $request->input('company_code'),
                $request->input('branch_department_code'),
                $request->input('position_code'),
                $request->input('gender'),
                $request->input('request_resign_from'),
                $request->input('request_resign_to')
            ), 'staff_request_resign.xlsx');

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
