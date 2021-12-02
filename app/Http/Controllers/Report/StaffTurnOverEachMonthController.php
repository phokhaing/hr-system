<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Report\Summary\StaffTurnOverEachMonthModel;
use App\Exports\Report\Summary\StaffTurnOverEachMonthExport;
use App\Http\Resources\Report\Summary\StaffTurnOverEachMonthResource;
use App\Exports\Report\Summary\StaffTurnOverEachMonthByPositionLevelExport;
use App\Http\Resources\Report\Summary\StaffTurnOverEachMonthByPositionLevelResource;

class StaffTurnOverEachMonthController extends Controller
{
    /**
     * Return data to list.
     * 
     * @return mixed
     */
    public function index(Request $request)
    {       
        if ($request->report_type === "branch") {
            $contract_type = array_keys(array_flip(CONTRACT_END_TYPE));
            $array = (new StaffTurnOverEachMonthModel())->getStaffTurnOverEachMonth($request->company_code, $request->branch_code, $request->filter_date, $contract_type);
            return StaffTurnOverEachMonthResource::collection(collect($array));
        }

        $contract_type = array_keys(array_flip(CONTRACT_END_TYPE));
        $array = (new StaffTurnOverEachMonthModel())->getStaffTurnOverEachMonthByPositionLevel($request->company_code, $request->branch_code, $request->filter_date, $contract_type);
        return StaffTurnOverEachMonthByPositionLevelResource::collection(collect($array));

    }

    /**
     * Export data as excel file.
     * 
     * @return mixed
     */
    public function export(Request $request)
    {
        if ($request->report_type === "branch") {
            try {
                $contract_type = array_keys(array_flip(CONTRACT_END_TYPE));
    
                return Excel::download(new StaffTurnOverEachMonthExport(
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
            $contract_type = array_keys(array_flip(CONTRACT_END_TYPE));

            return Excel::download(new StaffTurnOverEachMonthByPositionLevelExport(
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
