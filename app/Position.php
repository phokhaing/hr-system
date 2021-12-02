<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'code',
        'short_name',
        'name_en',
        'name_kh',
        'desc_en',
        'desc_kh',
        'range',
        'company_code',
        'branch_department_code',
        'created_by',
        'updated_by',
        'deleted_at',
        'group_level'
    ];

    /**
     * @param $query
     * @param $company_code
     * @return mixed
     */
    public function scopeGetByCompanyCode($query, $company_code)
    {
        return $query->where('deleted_at', '=', null)->where('company_code', $company_code);
    }

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(){
        return $this->belongsTo(Company::class, 'company_code', 'company_code');
    }

    public function scopeGetByLevel($query, $level)
    {
        return $query->where('group_level', $level);
    }
}
