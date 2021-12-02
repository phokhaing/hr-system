<?php

namespace App\Report;

use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReportStaffProfile extends Model
{

    /**
     * Query to show on web
     *
     * @param $keyword
     * @param $company
     * @param $branch
     * @param $department
     * @param $position
     * @param $gender
     * @param $start_date
     * @param $end_date
     * @return mixed
     */
    public function view_profile($keyword, $company, $branch, $department, $position, $gender, $start_date, $end_date)
    {
        $query = StaffPersonalInfo::with(['profile']); // ['profile'] that's mean get with staff profile

        if (isset($keyword)) {
            $query->where(DB::raw('CONCAT(last_name_en,first_name_en)'), 'LIKE', "%$keyword%")
                ->orWhere(DB::raw('CONCAT(last_name_kh,first_name_kh)'), 'LIKE', "%$keyword%")
                ->orWhere('email', 'like', "%$keyword%")
//                ->orWhere('phone', 'like', "%$keyword%")
                ->orWhere('id_code', $keyword)
                ->orWhere('bank_acc_no', $keyword)
                ->orWhereHas('profile', function ($q) use ($keyword) {
                    $q->where('emp_id_card', $keyword);
                });
        }
        if ($gender != "") {
            $query->where('gender', '=', $gender);
        }

        $query->whereHas('profile', function ($q) use ($company, $branch, $department, $position, $start_date, $end_date) {
            if ($company) {
                $q->where('company_id', '=', $company);
            }
            if ($branch) {
                $q->where('branch_id', '=', $branch);
            }
            if ($department) {
                $q->where('department_id', '=', $department);
            }
            if ($position) {
                $q->where('position_id', '=', $position);
            }
            if (isset($start_date) && isset($end_date)) {
                $q->whereBetween('employment_date', [$start_date, $end_date]);
            } elseif (isset($start_date) && ($end_date == null)) {
                $q->where('employment_date', '>=', $start_date);
            } elseif (($start_date == null) && isset($end_date)) {
                $q->where('employment_date', '<=', $end_date);
            }

        });

        return $query;

    }

    /**
     * Query export to excel file
     *
     * @param $keyword
     * @param $company
     * @param $branch
     * @param $department
     * @param $position
     * @param $gender
     * @param $start_date
     * @param $end_date
     * @return StaffPersonalInfo|\Illuminate\Database\Eloquent\Builder
     */
    public function export_profile($keyword, $company, $branch, $department, $position, $gender, $start_date, $end_date)
    {
        $query = StaffPersonalInfo::with(['profile']);
        if (isset($keyword)) {
            $query->where(DB::raw('CONCAT(last_name_en,first_name_en)'), 'LIKE', "%$keyword%")
                ->orWhere(DB::raw('CONCAT(last_name_kh,first_name_kh)'), 'LIKE', "%$keyword%")
                ->orWhere('email', 'like', "%$keyword%")
                ->orWhere('phone', 'like', "%$keyword%")
                ->orWhere('id_code', 'like', "%$keyword%")
                ->orWhere('bank_acc_no', 'like', "%$keyword%")
                ->orWhereHas('profile', function ($q) use ($keyword) {
                    $q->where('emp_id_card', 'like', "%$keyword%");
                });
        }
        if ($gender != "") {
            $query->where('gender', '=', $gender);
        }

        $query->whereHas('profile', function ($q) use ($company, $branch, $department, $position, $gender, $start_date, $end_date) {
            if ($company) {
                $q->where('company_id', '=', $company);
            }
            if ($branch) {
                $q->where('branch_id', '=', $branch);
            }
            if ($department) {
                $q->where('department_id', '=', $department);
            }
            if ($position) {
                $q->where('position_id', '=', $position);
            }
            if (isset($start_date) && isset($end_date)) {
                $q->whereBetween('employment_date', [$start_date, $end_date]);
            } elseif (isset($start_date) && ($end_date == null)) {
                $q->where('employment_date', '>=', $start_date);
            } elseif (($start_date == null) && isset($end_date)) {
                $q->where('employment_date', '<=', $end_date);
            }

        });

        return $query;
    }
}
