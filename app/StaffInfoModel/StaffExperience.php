<?php

namespace App\StaffInfoModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class StaffExperience extends Model
{
    use SoftDeletes;
    use HasRoles;

    protected $table = 'staff_experience';

    protected $attributes = ['flag' => 1];

    protected $dates = ['deleted_at']; // Soft delete

    protected $fillable = [
        'staff_personal_info_id',
        'position',
        'level_id',
        'company_name_en',
        'company_name_kh',
        'start_date',
        'end_date',
        'province_id',
        'district_id',
        'commune_id',
        'village_id',
        'house_no',
        'street_no',
        'other_location',
        'noted',
        'flag',
        'created_at',
        'created_by',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function personalInfo()
    {
        return $this->belongsTo(StaffPersonalInfo::class);
    }
}
