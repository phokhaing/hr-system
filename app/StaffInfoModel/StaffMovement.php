<?php

namespace App\StaffInfoModel;

use App\Branch;
use App\Company;
use App\Department;
use App\Flags;
use App\Position;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class StaffMovement extends Model
{
    use SoftDeletes;
    use HasRoles;

    protected $table = 'staff_movement';

    protected $fillable = [
        'staff_personal_info_id',
        'company_id',
        'branch_id',
        'department_id',
        'position_id',
        'to_company_id',
        'to_branch_id',
        'to_department_id',
        'to_position_id',
        'old_salary',
        'new_salary',
        'effective_date',
        'reject_date',
        'file_reference',
        'transfer_to_id',
        'transfer_to_name',
        'get_work_form_id',
        'get_work_form_name',
        'flag',
        'created_by',
        'updated_by',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function personalInfo()
    {
        return $this->belongsTo(StaffPersonalInfo::class, 'staff_personal_info_id');
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
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
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
    public function status()
    {
        return $this->belongsTo(Flags::class,'flag', 'code');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(StaffProfile::class, 'staff_personal_info_id', 'staff_personal_info_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function new_company()
    {
        return $this->belongsTo(Company::class, 'to_company_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function new_branch()
    {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function new_department()
    {
        return $this->belongsTo(Department::class, 'to_department_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function new_position()
    {
        return $this->belongsTo(Position::class, 'to_position_id');
    }
}
