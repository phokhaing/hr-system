<?php

namespace App\StaffInfoModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class StaffEducation extends Model
{
    use SoftDeletes;
    use HasRoles;

    protected $table = 'staff_education';

    protected $attributes = [ 'flag' => 1 ];

    protected $dates = ['deleted_at']; // Soft delete

    protected $fillable = [
        'staff_personal_info_id',
        'school_name',
        'subject',
        'start_date',
        'end_date',
        'degree_id',
        'study_year',
        'province_id',
        'district_id',
        'commune_id',
        'village_id',
        'other_location',
        'noted',
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
