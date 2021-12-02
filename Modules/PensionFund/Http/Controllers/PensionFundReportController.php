<?php


namespace Modules\PensionFund\Http\Controllers;

use App\Contract;
use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PensionFund\Entities\AutoCalculatePensionFund;
use Modules\PensionFund\Entities\ClaimPensionFundHistories;
use Modules\PensionFund\Entities\PensionFunds;
use Modules\PensionFund\Entities\Resources\ReportPensionFundCurrentStaffResource;
use Modules\PensionFund\Entities\Resources\ReportPensionFundSummaryResource;
use Modules\PensionFund\Entities\Resources\ReportStaffClaimRequestPensionFundResource;
use Modules\PensionFund\Entities\Resources\StaffPensionFundDetailResource;
use Modules\PensionFund\Entities\Resources\StaffPersonalInfoFundResource;
use Modules\PensionFund\Exports\PensionFundClaimRequestExport;
use Modules\PensionFund\Exports\PensionFundCurrentStaffExport;
use Modules\PensionFund\Exports\PensionFundStaffDetailExport;
use Modules\PensionFund\Exports\PensionFundSummaryExport;

class PensionFundReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_pension_fund_report');
    }

    public function index(Request $request)
    {
        $reportType = $request->report_type;
        $staff = StaffPersonalInfo::select('id', 'first_name_en', 'last_name_en', 'first_name_kh', 'last_name_kh', 'staff_id')->get();
        if ($reportType == PF_REPORT_TYPE['CLAIM']) {
            $items = ClaimPensionFundHistories::orderBy('id', ' desc')->get();
        }

        return view('pensionfund::reports.index', compact('staff', 'items', 'reportType'));
    }

    public function claimReportApi(Request $request)
    {
        try {
            $isDownload = $request->input('download');
            $companyCode = $request->input('company_code');
            $branchCode = $request->input('branch_department_code');
            $startDate = $request->input('request_date_from');
            $endDate = $request->input('request_date_to');
            $keyword = $request->input('keyword');

            $items = ClaimPensionFundHistories::orderBy('id', ' desc')
                ->with(['contract']);

            if ($keyword) {
                $items->whereHas('staffPersonalInfo', function ($q) use ($keyword) {
                    $q->where('staff_id', 'Like', '%' . $keyword . '%')
                        ->orWhere(DB::raw("CONCAT(last_name_kh, ' ', first_name_kh)"), 'LIKE', '%' . $keyword . '%')
                        ->orWhere(DB::raw("CONCAT(last_name_en, ' ', first_name_en)"), 'LIKE', '%' . $keyword . '%');
                })->orWhereHas('contract', function ($q) use ($keyword) {
                    $q->where("staff_id_card", 'LIKE', '%' . $keyword . '%');
                });
            }

            if ($startDate && $endDate) {
                $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
                $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
                $items->whereBetween('created_at', [$startDate, $endDate]);
            }

            if ($companyCode) {
                $items->whereHas('contract', function ($q) use ($companyCode) {
                    $q->where(DB::raw("contract_object->>'$.company.code'"), $companyCode);
                });
            }

            if ($branchCode) {
                $items->whereHas('contract', function ($q) use ($branchCode) {
                    $q->where(DB::raw("contract_object->>'$.branch_department.code'"), $branchCode);
                });
            }
            $items = $items->get();

            if ($isDownload) {
                return Excel::download(new PensionFundClaimRequestExport($items), 'pension_fund_claim_request.xlsx');
            } else {
                return ReportStaffClaimRequestPensionFundResource::collection($items);
            }

        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getStaffListApi()
    {
        try {
            $staff = StaffPersonalInfo::select('id', 'first_name_en', 'last_name_en', 'first_name_kh', 'last_name_kh', 'staff_id')
                ->whereHas('contract', function ($query) {
                    $is_admin = \auth()->user()->is_admin;
                    if (!@$is_admin) {
                        $current_company = \auth()->user()->company_code;
                        $query->whereRaw("contract_object->>'$.company.code'=?", $current_company);
                    }
                })
                ->get();
            return StaffPersonalInfoFundResource::collection($staff);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getStaffDetailApi(Request $request)
    {
        try {
            $staffPersonalId = $request->input('staff_id');
            $companyCode = $request->input('company_code');
            $branchDepartmentCode = $request->input('branch_department_code');
            $filterFromMonth = $request->input('filter_from_month');
            $filterEndMonth = $request->input('filter_end_month');
            $pensionFunds = PensionFunds::orderByRaw("staff_personal_info_id, str_to_date(json_data->>'$.report_date', '%d-%m-%Y') ASC");

            if ($staffPersonalId) {
                $pensionFunds->where('staff_personal_info_id', $staffPersonalId);
            }

            $is_admin = \auth()->user()->is_admin;
            if (@$is_admin) {
                if (@$companyCode) {
                    $pensionFunds->whereHas('contract', function ($query) use ($companyCode) {
                        return $query->where("contract_object->company->code", (int)$companyCode);
                    });
                }
            } else {
                $current_company = \auth()->user()->company_code;
                $pensionFunds->whereHas('contract', function ($query) use ($current_company) {
                    return $query->where("contract_object->company->code", (int)$current_company);
                });
            }

            if ($branchDepartmentCode) {
                $pensionFunds->whereHas('contract', function ($query) use ($branchDepartmentCode) {
                    return $query->where("contract_object->branch_department->code", (int)$branchDepartmentCode);
                });
            }

            if ($filterFromMonth && $filterEndMonth) {
                $filterFromMonth = date('Y-m', strtotime($filterFromMonth));
                $filterEndMonth = date('Y-m', strtotime($filterEndMonth));
                $pensionFunds->whereRaw(
                    "DATE_FORMAT(str_to_date(json_data->>'$.report_date', '%d-%m-%Y'), '%Y-%m') >= ?
                    and DATE_FORMAT(str_to_date(json_data->>'$.report_date', '%d-%m-%Y'), '%Y-%m') <= ?",
                    [$filterFromMonth, $filterEndMonth]
                );
            }
            $pensionFunds = $pensionFunds->get();

            if ($request->input("is_download")) {
                return Excel::download(new PensionFundStaffDetailExport($pensionFunds), 'pension_fund_staff_detail.xlsx');
            }

            //Define auto number
            $pensionFunds->map(function ($obj, $key) {
                $obj->no = $key + 1;
                return $obj;
            });

            return StaffPensionFundDetailResource::collection($pensionFunds);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function summary(Request $request)
    {
        try {

            $rawSql = "
                select
                    c.contract_object->'$.company.id' company_id,
                    c.contract_object->>'$.company.name_kh' company_name,
                    c.contract_object->>'$.company.code' company_code,
                    c.contract_object->>'$.branch_department.name_kh' department_branch,
                    
                    sum(pf.json_data->'$.acr_balance_staff') total_pension_fund
                    from pension_funds pf 
                    inner join contracts c
                      on c.id=pf.contract_id
                    where pf.deleted_at is null
            ";

            //Params from client
            $isDownload = $request->input('download');
            $companyCode = $request->input('company_code');
            $branchCode = $request->input('branch_department_code');
            $startDate = $request->input('request_date_from');
            $endDate = $request->input('request_date_to');

            if ($startDate && $endDate) {
                $start_date = date('Y-m-d 00:00:00', strtotime($startDate));
                $end_date = date('Y-m-d 23:59:59', strtotime($endDate));
                $rawSql .= " and pf.created_at >= '$start_date' and pf.created_at <= '$end_date'";
            }

            $is_admin = \auth()->user()->is_admin;
            if (@$is_admin) {
                if (@$companyCode) {
                    $rawSql .= " and c.contract_object->>'$.company.code' = '$companyCode'";
                }
            } else {
                $current_company = \auth()->user()->company_code;
                $rawSql .= " and c.contract_object->>'$.company.code' = '$current_company'";
            }

            if ($branchCode) {
                $rawSql .= " and c.contract_object->>'$.branch_department.code' = '$branchCode'";
            }

            $rawSql .= " group by company_id,company_name,department_branch,company_code";
            $pensionFunds = DB::select($rawSql);

            collect($pensionFunds)->map(function ($pf, $key) use ($companyCode) {
                $pf->no = $key + 1;
                $pf->c_code = $companyCode;
                return $pf;
            });

            if ($isDownload) {
                return Excel::download(new PensionFundSummaryExport($pensionFunds), 'pension_fund_summary.xlsx');
            } else {
                return ReportPensionFundSummaryResource::collection(collect($pensionFunds));
            }

        } catch (\Exception $e) {
            return $e;
        }
    }

    public function currentStaff(Request $request)
    {
        try {
            $rawSql = "select
                                spi.id as staff_personal_info_id,
                                spi.staff_id staff_id,
                                spi.first_name_kh first_name_kh,
                                spi.last_name_kh last_name_kh,
                                spi.first_name_en first_name_en,
                                spi.last_name_en last_name_en,
                                spi.gender gender,
                                c.id as contract_id,
                                c.staff_id_card staff_id_card, 
                                c.contract_object->>'$.company.id' company_id, 
                                c.contract_object->>'$.company.name_kh' company,
                                c.contract_object->>'$.company.code' company_code,
                                c.contract_object->>'$.branch_department.name_kh' department_branch,
                                c.contract_object->>'$.position.name_kh' position_name,
                                MAX(CAST(pf.json_data->>'$.acr_balance_staff' as SIGNED)) total_pension_fund_staff
                                
                            from pension_funds pf 
                            left join contracts c
                              on c.id=pf.contract_id
                            inner join staff_personal_info spi
                              on spi.id=pf.staff_personal_info_id
                            where pf.deleted_at is null  
                            and exists (select id from contracts where staff_personal_info_id=spi.id and date(contract_object->>'$.contract_last_date') >= NOW())
            ";

            //Params from client
            $isDownload = $request->input('download');
            $companyCode = $request->input('company_code');
            $branchCode = $request->input('branch_department_code');
            $keyword = $request->input('keyword');

            if ($keyword) {
                $rawSql .= " and (CONCAT(spi.last_name_en, ' ', spi.first_name_en) LIKE '%$keyword%'
                or CONCAT(spi.last_name_kh, ' ', spi.first_name_kh) LIKE '%$keyword%'
                or spi.staff_id LIKE '%$keyword%'
                or c.staff_id_card LIKE '%$keyword%'
                )";
            }

            $is_admin = \auth()->user()->is_admin;
            if (@$is_admin) {
                if (@$companyCode) {
                    $rawSql .= " and c.contract_object->>'$.company.code' = '$companyCode'";
                }
            } else {
                $current_company = \auth()->user()->company_code;
                $rawSql .= " and c.contract_object->>'$.company.code' = '$current_company'";
            }

            if ($branchCode) {
                $rawSql .= " and c.contract_object->>'$.branch_department.code' = '$branchCode'";
            }

            $rawSql .= " group by 
                              staff_personal_info_id,
                              staff_id,
                              company_id, 
                              company, 
                              department_branch,
                              first_name_kh,
                              last_name_kh,
                              first_name_en,
                              last_name_en,
                              staff_id_card,
                              gender,
                              position_name
                         order by company_id asc";
            $pensionFunds = DB::select($rawSql);

            foreach ($pensionFunds as $key => $pf) {
                $calculate = new AutoCalculatePensionFund($pf->staff_personal_info_id);
                $calculatePf = $calculate->calculatePFFromCompany($pf->staff_id_card, (int)$pf->company_code);

                $pf->total_acr_company = @$calculatePf['total_acr_company'];
                $pf->balance_to_paid = @$calculatePf['balance_to_paid'];
                $pf->contract_start_date = @$calculatePf['contract_start_date'];
                $pf->no = $key + 1;
            }

            if ($isDownload) {
                return Excel::download(new PensionFundCurrentStaffExport($pensionFunds), 'pension_fund_current_staff.xlsx');
            } else {
                return ReportPensionFundCurrentStaffResource::collection(collect($pensionFunds));
            }

        } catch (\Exception $e) {
            return $e;
        }
    }
}