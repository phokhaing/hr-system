<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Unity extends Model
{

    /**
     * Get District
     *
     * @param $province_id
     * @return mixed
     */
    public static function getDistrict($province_id)
    {
        $districts = DB::table('districts')->where('province_id', '=', $province_id)->orderBy('name_kh')->get();
        return $districts;
    }

    /**
     * Get Commune
     *
     * @param $district_id
     * @return mixed
     */
    public static function getCommune($district_id)
    {
        $communes = DB::table('communes')->where('district_id', '=', $district_id)->orderBy('name_kh')->get();
        return $communes;
    }

    /**
     * Get Village
     *
     * @param $commune_id
     * @return mixed
     */
    public static function getVillage($commune_id)
    {
        $villages = DB::table('villages')->where('commune_id', '=', $commune_id)->orderBy('name_kh')->get();
        return $villages;
    }

    /**
     * Get degree
     *
     * @param $id
     * @return mixed
     */
    public static function getDegree($id)
    {
        $degree = DB::table('degree')->where('id', '=', $id)->orderBy('name_kh')->get();
        return $degree;
    }

    /**
     * Get branches
     *
     * @param $company_id
     * @return mixed
     */
    public static function getBranch($company_id)
    {
        $branches = DB::table('branches')->where('id', '=', $company_id)->orderBy('name_kh')->get();
        return $branches;
    }

    /**
     * Get department
     *
     * @param $branch_id
     * @return mixed
     */
    public static function getDepartment($branch_id)
    {
        $departments = DB::table('departments')->where('id', '=', $branch_id)->orderBy('name_kh')->get();
        return $departments;
    }

    /**
     * Get position
     *
     * @param $department_id
     * @return mixed
     */
    public static function getPosition($department_id)
    {
        $positions = DB::table('positions')->where('id', '=', $department_id)->orderBy('name_kh')->get();
        return $positions;
    }

    /**
     * Show occupation
     *
     * @param $occupation_id
     * @return mixed
     */
    public static function showOccupation($id)
    {
        $occupation = DB::table('occupations')->find($id);
        if ($occupation) return $occupation->name_kh;

    }

    /**
     * Show province
     *
     * @param $id
     * @return mixed
     */
    public static function showProvince($id)
    {
        $province = DB::table('provinces')->find($id);
        if ($province) return $province->name_kh;
    }

    /**
     * Show district
     *
     * @param $id
     * @return mixed
     */
    public static function showDistrict($id)
    {
        $district = DB::table('districts')->find($id);
        if ($district) return $district->name_kh;
    }

    /**
     * Show commune
     *
     * @param $id
     * @return mixed
     */
    public static function showCommune($id)
    {
        $commune = DB::table('communes')->find($id);
        if ($commune) return $commune->name_kh;
    }

    /**
     * Show village
     *
     * @param $id
     * @return mixed
     */
    public static function showVillage($id)
    {
        $village = DB::table('villages')->find($id);
        if ($village) return $village->name_kh;
    }

    /**
     * Show degree
     *
     * @param $id
     * @return mixed
     */
    public static function showDegree($id)
    {
        $degree = DB::table('degree')->find($id);
        if ($degree) return $degree->name_kh;
    }

    /**
     * Show study year
     *
     * @param $id
     * @return mixed
     */
    public static function showStudyYear($id)
    {
        $degree = DB::table('study_year')->find($id);
        if ($degree) return $degree->name_kh;
    }

    /**
     * Show level position
     *
     * @param $id
     * @return mixed
     */
    public static function showLevel($id)
    {
        $level_pos = DB::table('level_position')->find($id);
        if ($level_pos) return $level_pos->name_kh;
    }

    /**
     * @param $position_id
     * @return mixed
     */
    public static function showPosition($position_id)
    {
        $positions = DB::table('positions')->find($position_id);
        if ($positions) return $positions->name_kh;
    }

    /**
     * @param $idType
     * @return mixed
     */
    public static function showIdType($idType)
    {
        $id_type = DB::table('id_types')->find($idType);
        if ($id_type) return $id_type->name_kh;
    }

    /**
     * @param $company_id
     * @return mixed
     */
    public static function showCompany($company_id)
    {
        $company = DB::table('companies')->find($company_id);
        if ($company) return $company->short_name;
    }

    /**
     * @param $branch_id
     * @return mixed
     */
    public static function showBranch($branch_id)
    {
        $branch = DB::table('branches')->find($branch_id);
        if ($branch) return $branch->name_kh;
    }

}
