<?php

namespace Modules\HRTraining\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRTraining\Traits\CRUDable;

class ExamHistory extends Model
{
    use SoftDeletes, CRUDable;

    protected $table = 'exam_histories';
    protected $casts = ['json_data' => 'object'];

    protected $fillable = [
        'id',
        'enrollment_id',
        'course_id',
        'exam_id',
        'trainee_id',
        'question_id',
        'json_data',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
