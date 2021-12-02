<?php

namespace App\Report\Summary;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class StaffTurnOverModel extends Model
{
    /**
     * getStaffTurnOver.
     * 
     * @param $company_code
     * @param $branch_code
     * @param $date
     * 
     * @return array
     */
    public function getStaffTurnOver($company_code, $branch_code, $date)
    {
        $active = CONTRACT_ACTIVE_TYPE['FDC'];
        $active1= CONTRACT_ACTIVE_TYPE['DEMOTE'];
        $active2 = CONTRACT_ACTIVE_TYPE['PROMOTE'];
        $active3 = CONTRACT_ACTIVE_TYPE['UDC'];
        $active4 = CONTRACT_ACTIVE_TYPE['CHANGE_LOCATION'];

        $inactive = CONTRACT_END_TYPE['RESIGN'];
        $inactive1 = CONTRACT_END_TYPE['DEATH'];
        $inactive2 = CONTRACT_END_TYPE['TERMINATE'];
        $inactive3 = CONTRACT_END_TYPE['LAY_OFF'];

        $sql = "SELECT 
                com.name_en as company_name,
                br.name_en as branch_name,
                br.code as branch_code,
                if(c.contract_type = $active 
                    or c.contract_type = $active1 
                    or c.contract_type = $active2 
                    or c.contract_type = $active3
                    or c.contract_type = $active4 , count(distinct(spi.id)), 0) as total_active,
                if(c.contract_type = $inactive 
                    or c.contract_type = $inactive1 
                    or c.contract_type = $inactive2 
                    or c.contract_type = $inactive3 , count(distinct(spi.id)), 0) as total_resigned,
                if(c.contract_type = $inactive 
                    or c.contract_type = $inactive1 
                    or c.contract_type = $inactive2 
                    or c.contract_type = $inactive3 , if(spi.gender = 1, count(distinct(spi.id)), 0), 0) as resign_female
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

            $sql .= " 
                GROUP BY
                br.id
                ORDER BY br.code ASC;
            ";

            if ($date !== NULL) {
                $array = DB::select($sql, [$date, $date]);
            } else {
                $array = DB::select($sql);
            }
            return $array;
    }


    /**
     * getStaffTurnOver.
     * 
     * @param $company_code
     * @param $branch_code
     * @param $date
     * 
     * @return array
     */
    public function getStaffTurnOverByPositionLevel($company_code, $branch_code, $date)
    {
        $active = CONTRACT_ACTIVE_TYPE['FDC'];
        $active1= CONTRACT_ACTIVE_TYPE['DEMOTE'];
        $active2 = CONTRACT_ACTIVE_TYPE['PROMOTE'];
        $active3 = CONTRACT_ACTIVE_TYPE['UDC'];
        $active4 = CONTRACT_ACTIVE_TYPE['CHANGE_LOCATION'];

        $inactive = CONTRACT_END_TYPE['RESIGN'];
        $inactive1 = CONTRACT_END_TYPE['DEATH'];
        $inactive2 = CONTRACT_END_TYPE['TERMINATE'];
        $inactive3 = CONTRACT_END_TYPE['LAY_OFF'];

        $sql = "SELECT 
                com.name_en as company_name,
                pos.group_level as position_level,
                if(c.contract_type = $active 
                    or c.contract_type = $active1 
                    or c.contract_type = $active2 
                    or c.contract_type = $active3
                    or c.contract_type = $active4 , count(distinct(spi.id)), 0) as total_active,
                if(c.contract_type = $inactive 
                    or c.contract_type = $inactive1 
                    or c.contract_type = $inactive2 
                    or c.contract_type = $inactive3 , count(distinct(spi.id)), 0) as total_resigned,
                if(c.contract_type = $inactive 
                    or c.contract_type = $inactive1 
                    or c.contract_type = $inactive2 
                    or c.contract_type = $inactive3 , if(spi.gender = 1, count(distinct(spi.id)), 0), 0) as resign_female
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

            $sql .= " GROUP BY pos.group_level; ";

            if ($date !== NULL) {
                $array = DB::select($sql, [$date, $date]);
            } else {
                $array = DB::select($sql);
            }
            return $array;
    }

}
