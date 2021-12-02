<?php

namespace App\Report\Summary;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class ContractStatusModel extends Model
{
    /**
     * Get constract status.
     * 
     * @param $company_code
     * @param $branch_code
     * @param $date
     * 
     * @return mixed
     */
    public function getContractStatus($company_code, $branch_code, $date)
    {
        $sql = "SELECT
                    com.name_en as company_name,
                    br.name_en as branch_name,
                    br.code as branch_code,
                    if((DATEDIFF(curdate(), min(c.start_date))/ 30) <= 3, count(spi.id), 0) as probation,
                    if((DATEDIFF(curdate(), min(c.start_date))/ 30) >= 4 and (DATEDIFF(curdate(), min(c.start_date))/ 30) <= 12 , count(spi.id), 0) as one_year,
                    if((DATEDIFF(curdate(), min(c.start_date))/ 30) >= 13 and (DATEDIFF(curdate(), min(c.start_date))/ 30) <= 24 , count(spi.id), 0) as two_year,
                    if((DATEDIFF(curdate(), min(c.start_date))/ 30) >= 25, count(spi.id), 0) as regular,
                    if(spi.gender = 1, count(spi.id), 0) as total_female,
                    count(spi.id) as total_staff
                from
                    contracts c
                inner join staff_personal_info spi on
                    spi.id = c.staff_personal_info_id
                right join branches_and_departments br on
                    c.contract_object->>'$.branch_department.id' = br.id
                right join companies com on
                    com.company_code = br.company_code
                where
                    spi.deleted_at is null
                    and c.deleted_at is null
                    and br.deleted_at is null
                    and c.contract_type in(?,?,?,?) ";
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
                group by
                br.id;
            ";

            if ($date !== NULL) {
                $array = DB::select($sql, [
                                        CONTRACT_ACTIVE_TYPE['FDC'],
                                        CONTRACT_ACTIVE_TYPE['UDC'],
                                        CONTRACT_ACTIVE_TYPE['DEMOTE'],
                                        CONTRACT_ACTIVE_TYPE['PROMOTE'],
                                        CONTRACT_ACTIVE_TYPE['CHANGE_LOCATION'],
                                        $date, $date]);
            } else {
                $array = DB::select($sql,
                                        [CONTRACT_ACTIVE_TYPE['FDC'],
                                        CONTRACT_ACTIVE_TYPE['UDC'],
                                        CONTRACT_ACTIVE_TYPE['DEMOTE'],
                                        CONTRACT_ACTIVE_TYPE['PROMOTE'],
                                        CONTRACT_ACTIVE_TYPE['CHANGE_LOCATION'],
                                        ]);
            }
            
            return $array;
    }

    /**
     * Get constract status.
     * 
     * @param $company_code
     * @param $branch_code
     * @param $date
     * 
     * @return mixed
     */
    public function getContractStatusByPositionLevel($company_code, $branch_code, $date)
    {
        $sql = "SELECT
                    com.name_en as company_name,
                    pos.group_level as position_level,
                    if((DATEDIFF(curdate(), min(c.start_date))/ 30) <= 3, count(spi.id), 0) as probation,
                    if((DATEDIFF(curdate(), min(c.start_date))/ 30) >= 4 and (DATEDIFF(curdate(), min(c.start_date))/ 30) <= 12 , count(spi.id), 0) as one_year,
                    if((DATEDIFF(curdate(), min(c.start_date))/ 30) >= 13 and (DATEDIFF(curdate(), min(c.start_date))/ 30) <= 24 , count(spi.id), 0) as two_year,
                    if((DATEDIFF(curdate(), min(c.start_date))/ 30) >= 25, count(spi.id), 0) as regular,
                    if(spi.gender = 1, count(spi.id), 0) as total_female,
                    count(spi.id) as total_staff
                from
                    contracts c
                inner join staff_personal_info spi on
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
                    and c.contract_type in(?,?,?,?) ";
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
                $array = DB::select($sql, [
                                        CONTRACT_ACTIVE_TYPE['FDC'],
                                        CONTRACT_ACTIVE_TYPE['UDC'],
                                        CONTRACT_ACTIVE_TYPE['DEMOTE'],
                                        CONTRACT_ACTIVE_TYPE['PROMOTE'],
                                        CONTRACT_ACTIVE_TYPE['CHANGE_LOCATION'],
                                        $date, $date]);
            } else {
                $array = DB::select($sql,
                                        [CONTRACT_ACTIVE_TYPE['FDC'],
                                        CONTRACT_ACTIVE_TYPE['UDC'],
                                        CONTRACT_ACTIVE_TYPE['DEMOTE'],
                                        CONTRACT_ACTIVE_TYPE['PROMOTE'],
                                        CONTRACT_ACTIVE_TYPE['CHANGE_LOCATION'],
                                        ]);
            }
            
            return $array;
    }
}
