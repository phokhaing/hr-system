<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Modules\HRTraining\Entities\Courses;
use Modules\HRTraining\Entities\Enrollments;

class BranchesAndDepartments extends Model
{
    use SoftDeletes;

    protected $table = 'branches_and_departments';

    protected $fillable = [
        'id',
        'company_code',
        'code',
        'short_name',
        'name_en',
        'name_km',
        'detail',
        'parent_id',
        'rank',
        'order_by',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function courses(){
        return $this->hasMany(Courses::class, 'branch_department_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_code', 'company_code');
    }

    /**
     * @param $query
     * @param $company_code
     * @return mixed
     */
    public function scopeGetByCompanyCode($query, $company_code)
    {
        return $query->where('deleted_at', '=', null)->where('company_code', $company_code);
    }

    /**
     * For optional promote with branch/department can be replace from current contract
     */
    public function scopeGetDataPromote($query, $currentDataPromote = null, $company_code = null){
        if (isset($company_code)){
            $newBranchDepartmentCode = $this->scopeGetByCompanyCode($query, $company_code)->first();
            $data = [
                "id" => (int)@$newBranchDepartmentCode->id,
                "code" => @$newBranchDepartmentCode->code,
                "short_name" => @$newBranchDepartmentCode->short_name,
                "name_en" => @$newBranchDepartmentCode->name_en,
                "name_kh" => @$newBranchDepartmentCode->name_km
            ];
            return $data;
        }else{
            return $currentDataPromote;
        }
    }

    /**
     * @param $query
     * @param $code
     * @return mixed
     */
    public function scopeGetByCode($query, $code)
    {
        return $query->where('deleted_at', '=', null)->where('code', $code);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeGetDependOnUser($query)
    {
        $user = Auth::user();
        $company_code = @$user->company_code;
        $is_admin = $user->is_admin;
        if ($is_admin) {
            return $query->where('deleted_at', '=', null);
        } else {
            return $query->where('deleted_at', '=', null)->where('company_code', $company_code);
        }
    }

    /**
     * Get last code from branch depend on company.
     *
     * @param $query
     * @param $company_code
     * @param $min_department_code
     * @return mixed
     */
    public function scopeGetLastBranchCode($query, $company_code, $min_department_code)
    {
        return $query->where('company_code', $company_code)
            ->where('code', '<', $min_department_code);
    }

    /**
     * @param $query
     * @param $company_code
     * @param $min_department_code
     * @return mixed
     */
    public function scopeGetLastDepartmentCode($query, $company_code, $min_department_code)
    {
        return $query->where('company_code', $company_code)
            ->where('code', '>=', $min_department_code);
    }
}
