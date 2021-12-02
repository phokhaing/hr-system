<?php

namespace App\StaffInfoModel;

use App\Branch;
use App\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class StaffResign extends Model
{
    use SoftDeletes;
    use HasRoles;

    protected $table = 'staff_resigns';

    protected $attributes = ['flag' => 1];

    protected $fillable = [
        'staff_personal_info_id',
        'resign_date',
        'approved_date',
        'last_day',
        'reject_date',
        'staff_id_replaced_1',
        'staff_name_replaced_1',
        'staff_id_replaced_2',
        'staff_name_replaced_2',
        'reason_company_id',
        'file_reference',
        'reason',
        'is_fraud',
        'flag',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function personalInfo()
    {
        return $this->belongsTo(StaffPersonalInfo::class, 'staff_personal_info_id', 'id')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
//    public function staffInfo()
//    {
//        return $this->belongsTo(StaffProfile::class, 'staff_personal_info_id')->withTrashed();
//    }
}
