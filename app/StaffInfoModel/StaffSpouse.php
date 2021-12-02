<?php

namespace App\StaffInfoModel;

use App\Traits\CrudGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class StaffSpouse extends Model
{
    use SoftDeletes, HasRoles, CrudGenerator;

    protected $table = 'staff_spouse';

    protected $attributes = ['flag' => 1];

    protected $dates = ['deleted_at']; // Soft delete

    protected $fillable = [
        'id',
        'staff_personal_info_id',
        'full_name',
        'dob',
        'gender',
        'occupation_id',
        'children_no',
        'children_tax',
        'spouse_tax',
        'province_id',
        'district_id',
        'commune_id',
        'village_id',
        'house_no',
        'street_no',
        'phone',
        'other_location',
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

    public function scopeSpouseTax($query, $staffPersonalInfoId)
    {
        return $query->where('staff_personal_info_id', $staffPersonalInfoId)
            ->where(function ($query) {
                $query->where('spouse_tax', 0)
                    ->orWhere('children_tax', '>', 0);
            });
    }
}
