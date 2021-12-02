<?php

namespace App\StaffInfoModel;

use App\Branch;
use App\Company;
use App\Department;
use App\Position;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class StaffProfile extends Model
{
    use SoftDeletes;
    use HasRoles;

    protected $table = 'staff_info';

    protected $attributes = ['flag' => 1];

    protected $dates = ['deleted_at']; // Soft delete

    protected $fillable = [
        'staff_personal_info_id',
        'emp_id_card',
        'probation_duration',
        'contract_duration',
        'branch_id',
        'company_id',
        'dpt_id',
        'position_id',
        'base_salary',
        'currency',
        'employment_date',
        'probation_end_date',
        'contract_end_date',
        'manager',
        'home_visit',
        'email',
        'phone',
        'mobile',
        'flag',
        'created_by',
        'updated_by',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function personalInfo()
    {
        return $this->belongsTo(StaffPersonalInfo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'dpt_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
//    public function resign()
//    {
//        return $this->hasOne(StaffResign::class);
//    }
}
