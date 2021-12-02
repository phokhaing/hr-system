<?php

namespace App\StaffInfoModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class StaffDocument extends Model
{
    use SoftDeletes;
    use HasRoles;

    protected $table = 'staff_document';

    protected $dates = ['deleted_at']; // Soft delete

//    protected $attributes = ['flag' => 1];

    protected $fillable = [
        'staff_personal_info_id',
        'staff_document_type_id',
        'src',
        'name',
        'check',
        'not_have',
        'created_by',
        'updated_by',
        'created_at',
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
