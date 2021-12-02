<?php

namespace App\Report;

use App\StaffInfoModel\StaffResign;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReportStaffResign extends Model
{
    /**
     * View staff resign
     *
     * @param $keyword
     * @param $company
     * @param $branch
     * @param $department
     * @param $position
     * @param $gender
     * @return StaffResign|\Illuminate\Database\Eloquent\Builder
     */
    public function view_resign($keyword, $company, $branch, $department, $position)
    {
        $query = StaffResign::with(['personalInfo', 'personalInfo.profile']);

        if ($keyword) {
            $query->whereHas('personalInfo', function ($q) use ($keyword) {

                $q->where(DB::raw('CONCAT(last_name_en,first_name_en)'), 'LIKE', "%$keyword%")
                    ->orWhere(DB::raw('CONCAT(last_name_kh,first_name_kh)'), 'LIKE', "%$keyword%")
                    ->orWhere('id_code', $keyword)
                    ->orWhere('bank_acc_no', $keyword);

            });
            $query->orWhereHas('personalInfo.profile', function ($q) use ($keyword){
                $q->where('emp_id_card', $keyword);
            });
        }

        if ($company) {
            $query->whereHas('personalInfo.profile', function ($q) use ($company){
                $q->where('company_id', '=', $company);
            });
        }
        if ($branch) {
            $query->whereHas('personalInfo.profile', function ($q) use ($branch){
                $q->where('branch_id', '=', $branch);
            });
        }
        if ($department) {
            $query->whereHas('personalInfo.profile', function ($q) use ($department){
                $q->where('dpt_id', '=', $department);
            });
        }
        if ($position) {
            $query->whereHas('personalInfo.profile', function ($q) use ($position){
                $q->where('position_id', '=', $position);
            });
        }

        return $query;
    }

}
