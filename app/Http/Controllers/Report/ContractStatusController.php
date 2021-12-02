<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Report\Summary\ContractStatusModel;
use App\Exports\Report\Summary\ContractStatusExport;
use App\Http\Resources\Report\Summary\ContractStatusResource;
use App\Exports\Report\Summary\ContractStatusByPositionLevelExport;
use App\Http\Resources\Report\Summary\ContractStatusByPositionLevelResource;


class ContractStatusController extends Controller
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
            $array = (new ContractStatusModel())->getContractStatus($request->company_code, $request->branch_code, $request->filter_date);
            return ContractStatusResource::collection(collect($array));
        }

        $array = (new ContractStatusModel())->getContractStatusByPositionLevel($request->company_code, $request->branch_code, $request->filter_date);
        return ContractStatusByPositionLevelResource::collection(collect($array));

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
                return Excel::download(new ContractStatusExport(
                    $request->company_code,
                    $request->branch_code,
                    $request->filter_date
                ), 'summary_constract_status.xlsx');
    
            } catch (\Exception $e) {
                return $e;
            }
        }

        try {
            return Excel::download(new ContractStatusByPositionLevelExport(
                $request->company_code,
                $request->branch_code,
                $request->filter_date
            ), 'summary_constract_status_by_position_level.xlsx');

        } catch (\Exception $e) {
            return $e;
        }
        
    }
}
