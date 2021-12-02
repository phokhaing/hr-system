<?php

namespace App\StaffInfoModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class StaffTraining extends Model
{
    use SoftDeletes;
    use HasRoles;

    protected $table = 'staff_training';

    protected $dates = ['deleted_at']; // Soft delete

    protected $fillable = [
        'staff_personal_info_id',
        'subject',
        'trainer',
        'school',
        'place',
        'start_date',
        'end_date',
        'province_id',
        'district_id',
        'commune_id',
        'village_id',
        'house_no',
        'street_no',
        'other_location',
        'description',
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
}
