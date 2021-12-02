<?php

namespace Modules\HRTraining\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRTraining\Traits\CRUDable;

class Categories extends Model
{
    use SoftDeletes, CRUDable;

    protected $table = "categories";

    protected $casts = ["json_data" => 'object'];

    protected $fillable = [
        'id',
        'json_data', // title, slug,
        'deleted_at',
        'deleted_by',
        'created_at',
        'created_by',
        'updated_at',
        'update_by'
    ];

    public function courses()
    {
        return $this->hasMany(Courses::class, 'category_id', 'id');
    }
}
