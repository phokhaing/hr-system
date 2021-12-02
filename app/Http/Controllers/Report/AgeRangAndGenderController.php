<?php

namespace App\Http\Controllers\Report;


use DB;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Report\Summary\AgeRangAndGenderModel;
use App\Exports\Report\Summary\AgeRangAndGenderExport;
use App\Http\Resources\Report\Summary\GetAgeRangAndGenderResource;
use App\Exports\Report\Summary\AgeRangAndGenderByPositionLevelExport;
use App\Http\Resources\Report\Summary\GetAgeRangAndGenderByPositionLevelResource;



class AgeRangAndGenderController extends Controller 
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
            $collection = (new AgeRangAndGenderModel())->getAgeRangAndGender($request->company_code, $request->branch_code, $request->filter_date);
            return GetAgeRangAndGenderResource::collection(collect($collection));
        }

        /// Summary report by Position Level
        $collection = (new AgeRangAndGenderModel())->getAgeRangAndGenderByPositionLevel($request->company_code, $request->branch_code, $request->filter_date);
        return GetAgeRangAndGenderByPositionLevelResource::collection(collect($collection));

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
                return Excel::download(new AgeRangAndGenderExport(
                    $request->company_code,
                    $request->branch_code,
                    $request->filter_date
                ), 'age_range_and_gender.xlsx');
    
            } catch (\Exception $e) {
                return $e;
            }
        }
        
        /// Summary report by Position Level
        try {
            return Excel::download(new AgeRangAndGenderByPositionLevelExport(
                $request->company_code,
                $request->branch_code,
                $request->filter_date
            ), 'age_range_and_gender_by_postion_level.xlsx');

        } catch (\Exception $e) {
            return $e;
        }
    }
}

