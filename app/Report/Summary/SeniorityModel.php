<?php

namespace App\Report\Summary;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class SeniorityModel extends Model
{
    /**
     * 
     * @param $company_code
     * @param $branch_code
     * @param $date
     * @param $contract_type
     * 
     * 
     * @return array
     */
    public function getSeniority($company_code, $branch_code, $date, $contract_type)
    {
        $sql = "SELECT
                br.name_en as branch_name,
                br.code as branch_code,
                if((DATEDIFF(curdate(), min(c.start_date))/ 30) <= 3, count(distinct(spi.id)), 0) as less_than_3m,
                if((DATEDIFF(curdate(), min(c.start_date))/ 30) > 3 and (DATEDIFF(curdate(), min(c.start_date))/ 30) <= 6, count(distinct(spi.id)), 0) as month_3_to_6,
                if((DATEDIFF(curdate(), min(c.start_date))/ 30) > 6 and (DATEDIFF(curdate(), min(c.start_date))/ 30) <= 12, count(distinct(spi.id)), 0) as month_6_to_12,
                if((DATEDIFF(curdate(), min(c.start_date))/ 30) > 12 and (DATEDIFF(curdate(), min(c.start_date))/ 30) <= 24, count(distinct(spi.id)), 0) as year_1_to_2,
                if((DATEDIFF(curdate(), min(c.start_date))/ 30) > 24 and (DATEDIFF(curdate(), min(c.start_date))/ 30) <= 60, count(distinct(spi.id)), 0) as year_2_to_5,
                if((DATEDIFF(curdate(), min(c.start_date))/ 30) > 60 and (DATEDIFF(curdate(), min(c.start_date))/ 30) <= 120, count(distinct(spi.id)), 0) as year_5_to_10,
                if((DATEDIFF(curdate(), min(c.start_date))/ 60) > 120, count(distinct(spi.id)), 0) as greater_than_10y,
                count(distinct(spi.id)) as total_staff,
                if(spi.gender = 1, count(distinct(spi.id)), 0) as total_female
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
                group by
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
     * 
     * @param $company_code
     * @param $branch_code
     * @param $date
     * @param $contract_type
     * 
     * 
     * @return array
     */
    public function getSeniorityByPositionLevel($company_code, $branch_code, $date, $contract_type)
    {
        $sql = "SELECT
                pos.group_level as position_level,
                if((DATEDIFF(curdate(), min(c.start_date))/ 30) <= 3, count(distinct(spi.id)), 0) as less_than_3m,
                if((DATEDIFF(curdate(), min(c.start_date))/ 30) > 3 and (DATEDIFF(curdate(), min(c.start_date))/ 30) <= 6, count(distinct(spi.id)), 0) as month_3_to_6,
                if((DATEDIFF(curdate(), min(c.start_date))/ 30) > 6 and (DATEDIFF(curdate(), min(c.start_date))/ 30) <= 12, count(distinct(spi.id)), 0) as month_6_to_12,
                if((DATEDIFF(curdate(), min(c.start_date))/ 30) > 12 and (DATEDIFF(curdate(), min(c.start_date))/ 30) <= 24, count(distinct(spi.id)), 0) as year_1_to_2,
                if((DATEDIFF(curdate(), min(c.start_date))/ 30) > 24 and (DATEDIFF(curdate(), min(c.start_date))/ 30) <= 60, count(distinct(spi.id)), 0) as year_2_to_5,
                if((DATEDIFF(curdate(), min(c.start_date))/ 30) > 60 and (DATEDIFF(curdate(), min(c.start_date))/ 30) <= 120, count(distinct(spi.id)), 0) as year_5_to_10,
                if((DATEDIFF(curdate(), min(c.start_date))/ 60) > 120, count(distinct(spi.id)), 0) as greater_than_10y,
                count(distinct(spi.id)) as total_staff,
                if(spi.gender = 1, count(distinct(spi.id)), 0) as total_female
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

            $sql .= " GROUP BY pos.group_level; ";

            if ($date !== NULL) {
                $array = DB::select($sql, [$date, $date]);
            } else {
                $array = DB::select($sql);
            }
            return $array;
    }
}
