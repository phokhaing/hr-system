<?php

namespace App;

use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\HRTraining\Entities\Trainees;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;
use Spatie\Permission\Models\Role;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use SoftDeletes;
    use LaravelPermissionToVueJS;

    protected $casts = [
        'company_object' => 'array',
        'branch_object' => 'array'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'created_by',
        'updated_by',
        'full_name',
        'company_code',
        'branch_code',
        'is_admin',
        'branch_object',
        'company_object',
        'staff_personal_info_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_code', 'company_code');
    }

    public function staffPersonalInfo(){
        return  $this->belongsTo(StaffPersonalInfo::class,'staff_personal_info_id', 'id');
    }

    /**
     * Get company from current user.
     *
     * @return mixed
     */
    public static function getCurrentCompanyName()
    {
        $company_code = \auth()->user()->company_code;
        $query = DB::table('users AS u')
            ->leftJoin('companies AS c', 'c.company_code', 'u.company_code')
            ->select('c.short_name')
            ->where('u.company_code', '=', $company_code)
            ->first();
        return $query;
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
            return $query->where('deleted_at', '=', null)->where('company_code', (int)$company_code);
        }
    }
}
