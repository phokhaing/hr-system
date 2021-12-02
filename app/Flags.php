<?php

namespace App;

use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Database\Eloquent\Model;

class Flags extends Model
{
    protected $table = 'flags';

    protected $fillable = [
        'code',
        'name_kh',
        'name_en',
        'des',
        'created_by',
        'updated_by',
    ];

    public function personalInfo()
    {
        return $this->belongsTo(StaffPersonalInfo::class);
    }
}
