<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Report\Summary\StaffTurnOverModel;
use App\Exports\Report\Summary\StaffTurnOverExport;
use App\Http\Resources\Report\Summary\StaffTurnOverResource;
use App\Exports\Report\Summary\StaffTurnOverByPositionLevelExport;
use App\Http\Resources\Report\Summary\StaffTurnOverByPositionLevelResource;

class StaffTurnOverController extends Controller
{
    /**
     * Return data to list.
     * 
     * @return mixed
     */
    public function index(Request $request)
    {       
        /// Summary report by Branches
        if ($request->report_type === "branch") {
            $array = (new StaffTurnOverModel())->getStaffTurnOver($request->company_code, $request->branch_code, $request->filter_date);
            return StaffTurnOverResource::collection(collect($array));
        }
        
        $array = (new StaffTurnOverModel())->getStaffTurnOverByPositionLevel($request->company_code, $request->branch_code, $request->filter_date);
        return StaffTurnOverByPositionLevelResource::collection(collect($array));
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
                return Excel::download(new StaffTurnOverExport(
                    $request->company_code,
                    $request->branch_code,
                    $request->filter_date
                ), 'summary_staff_turn_over.xlsx');
    
            } catch (\Exception $e) {
                return $e;
            }
        }

        try {
            return Excel::download(new StaffTurnOverByPositionLevelExport(
                $request->company_code,
                $request->branch_code,
                $request->filter_date
            ), 'summary_staff_turn_over_by_position_level.xlsx');

        } catch (\Exception $e) {
            return $e;
        }
    }
}
