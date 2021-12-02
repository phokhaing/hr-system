<?php

namespace Modules\HRTraining\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRTraining\Traits\CRUDable;

class Question extends Model
{
    use SoftDeletes, CRUDable;

    protected $table = "questions";

    protected $casts = ['json_data' => 'object'];

    protected $fillable = [
        'id',
        'exam_id',
        'json_data',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    public function scopeGetByExamId($query, $id) {
        return $query->where('exam_id', '=', $id);
    }
}
