<?php

namespace App\Report;

use App\StaffInfoModel\StaffMovement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReportStaffMovement extends Model
{
    /**
     * View staff movement
     *
     * @param $keyword
     * @param $company
     * @param $branch
     * @param $department
     * @param $position
     * @param $start_date
     * @param $end_date
     * @param $gender
     * @return StaffResign|\Illuminate\Database\Eloquent\Builder
     */
    public function view_movement($keyword, $company, $branch, $department, $position, $gender, $start_date, $end_date)
    {
        $query = StaffMovement::with(['personalInfo', 'personalInfo.profile']);

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
        if ($gender != "") {
            $query->whereHas('personalInfo', function ($q) use ($gender) {
                $q->where('gender', '=', $gender);
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
        if (isset($start_date) && isset($end_date)) {
            $query->whereBetween('effective_date', [$start_date, $end_date]);
        } elseif (isset($start_date) && ($end_date == null)) {
            $query->where('effective_date', '>=', $start_date);
        } elseif (($start_date == null) && isset($end_date)) {
            $query->where('effective_date', '<=', $end_date);
        }

        return $query;
    }
}
