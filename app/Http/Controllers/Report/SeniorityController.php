<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Report\Summary\SeniorityModel;
use App\Exports\Report\Summary\SeniorityExport;
use App\Http\Resources\Report\Summary\SeniorityResource;
use App\Exports\Report\Summary\SeniorityByPositionLevelExport;
use App\Http\Resources\Report\Summary\SeniorityByPositionLevelResource;

class SeniorityController extends Controller
{
    /**
     * Return data to list.
     * 
     * @return mixed
     */
    public function index(Request $request)
    {       
        $contract_type = "";

        /// Summary report by Branches
        if ($request->report_type === "branch") {
            if ($request->contract_type === "active") {
                $contract_type = array_keys(array_flip(CONTRACT_ACTIVE_TYPE));
            } else {
                $contract_type = array_keys(array_flip(CONTRACT_END_TYPE));
            }
    
            $array = (new SeniorityModel())->getSeniority($request->company_code, $request->branch_code, $request->filter_date, $contract_type);
    
            return SeniorityResource::collection(collect($array));
        }

        if ($request->contract_type === "active") {
            $contract_type = array_keys(array_flip(CONTRACT_ACTIVE_TYPE));
        } else {
            $contract_type = array_keys(array_flip(CONTRACT_END_TYPE));
        }

        $array = (new SeniorityModel())->getSeniorityByPositionLevel($request->company_code, $request->branch_code, $request->filter_date, $contract_type);

        return SeniorityByPositionLevelResource::collection(collect($array));

    }

    /**
     * Export data as excel file.
     * 
     * @return mixed
     */
    public function export(Request $request)
    {
        /// Summary report by Branches
        if ($request->report_type === "branch") {
            try {
                $contract_type = "";
                if ($request->contract_type === "active") {
                    $contract_type = array_keys(array_flip(CONTRACT_ACTIVE_TYPE));
                } else {
                    $contract_type = array_keys(array_flip(CONTRACT_END_TYPE));
                }
    
                return Excel::download(new SeniorityExport(
                    $request->company_code,
                    $request->branch_code,
                    $request->filter_date,
                    $contract_type
                ), 'summary_seniority.xlsx');
    
            } catch (\Exception $e) {
                return $e;
            }
        }

        try {
            $contract_type = "";
            if ($request->contract_type === "active") {
                $contract_type = array_keys(array_flip(CONTRACT_ACTIVE_TYPE));
            } else {
                $contract_type = array_keys(array_flip(CONTRACT_END_TYPE));
            }

            return Excel::download(new SeniorityByPositionLevelExport(
                $request->company_code,
                $request->branch_code,
                $request->filter_date,
                $contract_type
            ), 'summary_seniority_by_position_level.xlsx');

        } catch (\Exception $e) {
            return $e;
        }
    }
}
