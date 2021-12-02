<?php

namespace App\Report\Summary;

use DB;
use Illuminate\Database\Eloquent\Model;


class AgeRangAndGenderModel extends Model
{
    /**
     * get Age and Gender
     * 
     * @param $company_code
     * @param $branch_code
     * @param $date
     * 
     * @return mixed
     */
    public function getAgeRangAndGender($company_code, $branch_code, $date)
    {
        $sql = "SELECT
                com.short_name company_name,
                br.name_en branch_name,
                br.code branch_code,
                count(spi.id) as total_staff,
                sum(if(spi.gender = 1, 1, 0)) as total_female,
                count(if((DATEDIFF(curdate(), spi.dob)/ 365) <= 20, spi.id, null)) as below_or_equal_20_y,
                count(if((DATEDIFF(curdate(), spi.dob)/ 365) > 20 and (DATEDIFF(curdate(), spi.dob)/ 365) <= 30, spi.id, null)) as between_21_and_30_y,
                count(if((DATEDIFF(curdate(), spi.dob)/ 365) > 30 and (DATEDIFF(curdate(), spi.dob)/ 365) <= 40, spi.id, null)) as between_31_and_40_y,
                count(if((DATEDIFF(curdate(), spi.dob)/ 365) > 40 and (DATEDIFF(curdate(), spi.dob)/ 365) <= 50, spi.id, null)) as between_41_and_50_y,
                count(if((DATEDIFF(curdate(), spi.dob)/ 365) > 50 and (DATEDIFF(curdate(), spi.dob)/ 365) <= 55, spi.id, null)) as between_51_and_55_y,
                count(if((DATEDIFF(curdate(), spi.dob)/ 365) > 55, spi.id, null)) as over_55_y
            from
                staff_personal_info spi
            inner join contracts c on
                c.staff_personal_info_id = spi.id
            right join branches_and_departments br on
                c.contract_object->>'$.branch_department.id' = br.id
            right join companies com on
                com.company_code = br.company_code
            where
                spi.deleted_at is null
                and c.deleted_at is null
                and br.deleted_at is null 
                and c.contract_type in(?,?,?,?)
            ";

            if ($date !== NULL) {
                $sql .= " and c.start_date  <= ? and c.end_date >= ?";
            }

            $user = \Auth::user();

            if ($user->is_admin) {
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

            $collection = "";

            if ($date !== NULL) {
                $collection = DB::select($sql, [
                                        CONTRACT_ACTIVE_TYPE['FDC'],
                                        CONTRACT_ACTIVE_TYPE['UDC'],
                                        CONTRACT_ACTIVE_TYPE['DEMOTE'],
                                        CONTRACT_ACTIVE_TYPE['PROMOTE'],
                                        CONTRACT_ACTIVE_TYPE['CHANGE_LOCATION'],
                                        $date, $date]);
            } else {
                $collection = DB::select($sql,
                                            [CONTRACT_ACTIVE_TYPE['FDC'],
                                            CONTRACT_ACTIVE_TYPE['UDC'],
                                            CONTRACT_ACTIVE_TYPE['DEMOTE'],
                                            CONTRACT_ACTIVE_TYPE['PROMOTE'],
                                            CONTRACT_ACTIVE_TYPE['CHANGE_LOCATION'],
                                            ]);
            }
            
            return $collection;
    }

    public function getAgeRangAndGenderByPositionLevel($company_code, $branch_code, $date)
    {
        $sql = "SELECT
                com.short_name company_name,
                pos.group_level as position_level,
                count(spi.id) as total_staff,
                sum(if(spi.gender = 1, 1, 0)) as total_female,
                count(if((DATEDIFF(curdate(), spi.dob)/ 365) <= 20, spi.id, null)) as below_or_equal_20_y,
                count(if((DATEDIFF(curdate(), spi.dob)/ 365) > 20 and (DATEDIFF(curdate(), spi.dob)/ 365) <= 30, spi.id, null)) as between_21_and_30_y,
                count(if((DATEDIFF(curdate(), spi.dob)/ 365) > 30 and (DATEDIFF(curdate(), spi.dob)/ 365) <= 40, spi.id, null)) as between_31_and_40_y,
                count(if((DATEDIFF(curdate(), spi.dob)/ 365) > 40 and (DATEDIFF(curdate(), spi.dob)/ 365) <= 50, spi.id, null)) as between_41_and_50_y,
                count(if((DATEDIFF(curdate(), spi.dob)/ 365) > 50 and (DATEDIFF(curdate(), spi.dob)/ 365) <= 55, spi.id, null)) as between_51_and_55_y,
                count(if((DATEDIFF(curdate(), spi.dob)/ 365) > 55, spi.id, null)) as over_55_y
            from
                staff_personal_info spi
            inner join contracts c on
                c.staff_personal_info_id = spi.id
            inner join positions pos on pos.id = c.contract_object->>'$.position.id'
            right join branches_and_departments br on
                c.contract_object->>'$.branch_department.id' = br.id
            right join companies com on
                com.company_code = br.company_code
            where
                spi.deleted_at is null
                and c.deleted_at is null
                and br.deleted_at is null 
                and c.contract_type in(?,?,?,?) 
        ";
        if ($date !== NULL) {
            $sql .= " and c.start_date  <= ? and c.end_date >= ?";
        }

        $user = \Auth::user();

        if ($user->is_admin) {
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

        $collection = "";

        if ($date !== NULL) {
            $collection = DB::select($sql, [
                                    CONTRACT_ACTIVE_TYPE['FDC'],
                                    CONTRACT_ACTIVE_TYPE['UDC'],
                                    CONTRACT_ACTIVE_TYPE['DEMOTE'],
                                    CONTRACT_ACTIVE_TYPE['PROMOTE'],
                                    CONTRACT_ACTIVE_TYPE['CHANGE_LOCATION'],
                                    $date, $date]);
        } else {
            $collection = DB::select($sql,
                                        [CONTRACT_ACTIVE_TYPE['FDC'],
                                        CONTRACT_ACTIVE_TYPE['UDC'],
                                        CONTRACT_ACTIVE_TYPE['DEMOTE'],
                                        CONTRACT_ACTIVE_TYPE['PROMOTE'],
                                        CONTRACT_ACTIVE_TYPE['CHANGE_LOCATION'],
                                        ]);
        }
        
        return $collection;

    }
}
