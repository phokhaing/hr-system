<?php

namespace App;

use App\StaffInfoModel\StaffProfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Company extends Model
{
    use SoftDeletes;

    protected $table = 'companies';

    protected $fillable = [
        'id',
        'company_code',
        'short_name',
        'name_en',
        'name_kh',
        'created_by',
        'updated_by',   
        'deleted_at',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function branchAndDepartments()
    {
        return $this->hasMany(BranchesAndDepartments::class, 'company_code', 'company_code');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function staffProfile()
    {
        return $this->hasOne(StaffProfile::class);
    }

    public function positions()
    {
        return $this->hasMany(Position::class, 'company_code', 'company_code');
    }

    public function scopeGetCompanyByCode($query, $company_code)
    {
        return $query->where('company_code', $company_code);
    }

    public function scopeGetCompanyDependOnUser($query)
    {
        $user = Auth::user();
        $company_code = @$user->company_code;
        $is_admin = $user->is_admin;
        if ($is_admin) {
            return $query->where('deleted_at', '=', null);
        } else {
            return $query->where('company_code', $company_code);
        }
    }
    
    /**
     * For optional promote with company can be replace from current contract
     */
    public function scopeGetDataPromote($query, $currentDataPromote = null, $company_code = null)
    {
        if (isset($company_code)) {
            $newCompany = $this->scopeGetCompanyByCode($query, $company_code)->first();
            $dataCompany = [
                "id" => (int)@$newCompany->id,
                "code" => @$newCompany->code,
                "short_name" => @$newCompany->short_name,
                "name_en" => @$newCompany->name_en,
                "name_kh" => @$newCompany->name_kh
            ];
            return $dataCompany;
        } else {
            return $currentDataPromote;
        }
    }
}
