<?php

namespace App\StaffInfoModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class StaffGuarantor extends Model
{
    use SoftDeletes;
    use HasRoles;

    protected $table = 'staff_guarantor';

    protected $attributes = ['flag' => 1];

    protected $dates = ['deleted_at']; // Soft delete

    protected $fillable = [
        'staff_personal_info_id',
        'first_name_kh',
        'last_name_kh',
        'first_name_en',
        'last_name_en',
        'gender',
        'dob',
        'pob',
        'id_type',
        'id_code',
        'career_id',
        'marital_status',
        'related_id',
        'children_no',
        'province_id',
        'district_id',
        'commune_id',
        'village_id',
        'house_no',
        'street_no',
        'other_location',
        'email',
        'phone',
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
