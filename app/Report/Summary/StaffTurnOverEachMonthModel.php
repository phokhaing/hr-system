<?php

namespace App\Report\Summary;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class StaffTurnOverEachMonthModel extends Model
{
    /**
     * getStaffTurnOverEachMonth.
     * 
     * @param $company_code
     * @param $branch_code
     * @param $date
     * @param $contract_type
     * 
     * @return array
     */
    public function getStaffTurnOverEachMonth($company_code, $branch_code, $date, $contract_type)
    {
        $sql = "SELECT
                br.name_en as branch_name,
                br.name_km as branch_name_kh,
                br.code as branch_code,
                if(month(c.end_date) = 1, count(distinct(spi.id)), 0) as january,
                if(month(c.end_date) = 2, count(distinct(spi.id)), 0) as february,
                if(month(c.end_date) = 3, count(distinct(spi.id)), 0) as march,
                if(month(c.end_date) = 4, count(distinct(spi.id)), 0) as april,
                if(month(c.end_date) = 5, count(distinct(spi.id)), 0) as may,
                if(month(c.end_date) = 6, count(distinct(spi.id)), 0) as june,
                if(month(c.end_date) = 7, count(distinct(spi.id)), 0) as july,
                if(month(c.end_date) = 8, count(distinct(spi.id)), 0) as auguest,
                if(month(c.end_date) = 9, count(distinct(spi.id)), 0) as september,
                if(month(c.end_date) = 10, count(distinct(spi.id)), 0) as october,
                if(month(c.end_date) = 11, count(distinct(spi.id)), 0) as november,
                if(month(c.end_date) = 12, count(distinct(spi.id)), 0) as december,
                if(spi.gender = 1, count(distinct(spi.id)), 0) as total_female,
                count(distinct(spi.id)) as total_staff
            from
                contracts c
            join staff_personal_info spi on
                spi.id = c.staff_personal_info_id
            right join branches_and_departments br on
                c.contract_object->>'$.branch_department.id' = br.id
            right join companies com on
                com.company_code = br.company_code
            where
                spi.deleted_at is null
                and c.deleted_at is null
                and br.deleted_at is null
            ";

            if ($date !== NULL) {
                $sql .= " and c.start_date  <= ? and c.end_date >= ?";
            }

            $user = \Auth::user();

            if (@$user->is_admin) {
                if(@$company_code){
                    $sql .= " and com.company_code=$company_code";
                }
            } else {
                $sql .= " and com.company_code=$user->company_code";
            }

            if (@$branch_code !== NULL) {
                $sql .= " and br.code=$branch_code";
            }

            if ($contract_type !== NULL) {
                $sql .= " and c.contract_type in(".implode(',', $contract_type).")";
            }

            $sql .= " 
                GROUP BY
                br.id;
            ";

            if ($date !== NULL) {
                $array = DB::select($sql, [$date, $date]);
            } else {
                $array = DB::select($sql);
            }
            return $array;
    }

    /**
     * getStaffTurnOverEachMonth.
     * 
     * @param $company_code
     * @param $branch_code
     * @param $date
     * @param $contract_type
     * 
     * @return array
     */
    public function getStaffTurnOverEachMonthByPositionLevel($company_code, $branch_code, $date, $contract_type)
    {
        $sql = "SELECT 
                pos.group_level as position_level,
                if(month(c.end_date) = 1, count(distinct(spi.id)), 0) as january,
                if(month(c.end_date) = 2, count(distinct(spi.id)), 0) as february,
                if(month(c.end_date) = 3, count(distinct(spi.id)), 0) as march,
                if(month(c.end_date) = 4, count(distinct(spi.id)), 0) as april,
                if(month(c.end_date) = 5, count(distinct(spi.id)), 0) as may,
                if(month(c.end_date) = 6, count(distinct(spi.id)), 0) as june,
                if(month(c.end_date) = 7, count(distinct(spi.id)), 0) as july,
                if(month(c.end_date) = 8, count(distinct(spi.id)), 0) as auguest,
                if(month(c.end_date) = 9, count(distinct(spi.id)), 0) as september,
                if(month(c.end_date) = 10, count(distinct(spi.id)), 0) as october,
                if(month(c.end_date) = 11, count(distinct(spi.id)), 0) as november,
                if(month(c.end_date) = 12, count(distinct(spi.id)), 0) as december,
                if(spi.gender = 1, count(distinct(spi.id)), 0) as total_female,
                count(distinct(spi.id)) as total_staff
            from
                contracts c
            join staff_personal_info spi on
                spi.id = c.staff_personal_info_id
            inner join positions pos on 
                pos.id = c.contract_object->>'$.position.id'
            right join branches_and_departments br on
                c.contract_object->>'$.branch_department.id' = br.id
            right join companies com on
                com.company_code = br.company_code
            where
                spi.deleted_at is null
                and c.deleted_at is null
                and br.deleted_at is null
            ";

            if ($date !== NULL) {
                $sql .= " and c.start_date  <= ? and c.end_date >= ?";
            }

            $user = \Auth::user();

            if (@$user->is_admin) {
                if(@$company_code){
                    $sql .= " and com.company_code=$company_code";
                }
            } else {
                $sql .= " and com.company_code=$user->company_code";
            }

            if (@$branch_code !== NULL) {
                $sql .= " and br.code=$branch_code";
            }

            if ($contract_type !== NULL) {
                $sql .= " and c.contract_type in(".implode(',', $contract_type).")";
            }

            $sql .= "  GROUP BY pos.group_level; ";

            if ($date !== NULL) {
                $array = DB::select($sql, [$date, $date]);
            } else {
                $array = DB::select($sql);
            }
            return $array;
    }
}
