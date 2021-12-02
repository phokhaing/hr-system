<?php

namespace App\Report;

use App\Contract;
use Illuminate\Database\Eloquent\Model;

class ReportStaffContract extends Model
{

    /**
     * - Concatenate with staff active. This is use in case filter ACTIVE contract report.
     * - The reason that we can't use this function for all because
     *   after filter report we add all that that active in now day.
     *
     * @param $keyword
     * @param $company
     * @param $branch
     * @param $position
     * @param $gender
     * @param $date_from
     * @param $date_to
     * @param null $contractType
     * @return mixed
     */
    public function advanceFilter($keyword, $company, $branch, $position, $gender, $date_from, $date_to, $contractType = null)
    {
        $query = Contract::whereHas('staffPersonalInfo');
        if ($contractType) {
            $query = $query->where('contract_type', $contractType);
        }

        if ($keyword) {
            $query = $query->whereHas('staffPersonalInfo', function ($q) use ($keyword) {
                $q->whereRaw('LOWER(CONCAT(last_name_kh,first_name_kh)) LIKE ?', ["%$keyword%"])
                    ->orWhereRaw('LOWER(CONCAT(last_name_en,first_name_en)) LIKE ?', ["%$keyword%"])
                    ->orWhereRaw('staff_id LIKE ?', ["%$keyword%"])
                    ->orWhereRaw('phone LIKE ?', ["%$keyword%"]);
            });
        }

        if ($date_from && $date_to) {
            $query->where('end_date', '>=', date('Y-m-d', strtotime($date_from)))
                ->where('end_date', '<=', date('Y-m-d', strtotime($date_to)));

            // Concatenate with staff active. This is use in case filter ACTIVE contract report.
            $query->orWhere('end_date', '>=', date('Y-m-d'));
        }

        if ($date_from) {
            $query->where('end_date', '>=', date('Y-m-d', strtotime($date_from)));

            // Concatenate with staff active. This is use in case filter ACTIVE contract report.
            $query->orWhere('end_date', '>=', date('Y-m-d'));
        }

        if ($date_to) {
            $query->where('end_date', '<=', date('Y-m-d', strtotime($date_to)));

            // Concatenate with staff active. This is use in case filter ACTIVE contract report.
            $query->orWhere('end_date', '>=', date('Y-m-d'));
        }

        if ($company) {
            $query->where('contract_object->company->code', (int)$company);
        }

        if ($branch) {
            $query->where('contract_object->branch_department->code', (int)$branch);
        }

        if ($position) {
            $query->where('contract_object->position->code', (int)$position);
        }

        if ($gender=='0' || $gender=='1') {
            $query = $query->whereHas('staffPersonalInfo', function ($q) use ($gender) {
                $q->where('gender', '=', $gender);
            });
        }
        $query->getDependOnUser();
        return $query;
    }

    /**
     * - Can use all contract except ACTIVE contract.
     *
     * @param $keyword
     * @param $company
     * @param $branch
     * @param $position
     * @param $gender
     * @param $date_from
     * @param $date_to
     * @param null $contractType
     * @return mixed
     */
    public function advanceFilterExceptActiveReport($keyword, $company, $branch, $position, $gender, $date_from, $date_to, $contractType = null)
    {
        $query = Contract::whereHas('staffPersonalInfo');
        if ($contractType) {
            $query = $query->where('contract_type', $contractType);
        }

        if ($keyword) {
            $query = $query->whereHas('staffPersonalInfo', function ($q) use ($keyword) {
                $q->whereRaw('LOWER(CONCAT(last_name_kh,first_name_kh)) LIKE ?', ["%$keyword%"])
                    ->orWhereRaw('LOWER(CONCAT(last_name_en,first_name_en)) LIKE ?', ["%$keyword%"])
                    ->orWhereRaw('staff_id LIKE ?', ["%$keyword%"])
                    ->orWhereRaw('phone LIKE ?', ["%$keyword%"]);
            });
        }

        if ($date_from && $date_to) {
            $query->where('end_date', '>=', date('Y-m-d', strtotime($date_from)))
                ->where('end_date', '<=', date('Y-m-d', strtotime($date_to)));
        }

        if ($date_from) {
            $query->where('end_date', '>=', date('Y-m-d', strtotime($date_from)));
        }

        if ($date_to) {
            $query->where('end_date', '<=', date('Y-m-d', strtotime($date_to)));
        }

        if ($company) {
            $query->where('contract_object->company->code', (int)$company);
        }

        if ($branch) {
            $query->where('contract_object->branch_department->code', (int)$branch);
        }

        if ($position) {
            $query->where('contract_object->position->code', (int)$position);
        }

        if ($gender=='0' || $gender=='1') {
            $query = $query->whereHas('staffPersonalInfo', function ($q) use ($gender) {
                $q->where('gender', '=', $gender);
            });
        }
        $query->getDependOnUser();
        return $query;
    }
}
