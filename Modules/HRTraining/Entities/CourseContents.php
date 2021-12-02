<?php

namespace Modules\HRTraining\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Modules\HRTraining\Traits\CRUDable;
use Modules\HRTraining\Entities\Exam;

class CourseContents extends Model
{
    use SoftDeletes, CRUDable;

    protected $casts = ['json_data' => 'object'];

    protected $table = 'course_contents';

    protected $fillable = [
        'id',
        'course_id',
        'json_data',
        'type',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    //***Relationship block
    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id', 'id');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'course_contents_id', 'id');
    }
    //***End Relationship block

    //***Scope block
    public function scopeWithCourse($q)
    {
        return $q->with('course')
            ->select(
                'id',
                'course_id',
                'type',
                'created_at',
                'created_by',
                DB::raw('JSON_LENGTH(json_data, "$.sections") total_section')
            );
    }

    public function scopeDetail($q, $contentId)
    {
        return $q->with('course')->find($contentId);
    }
    //***End Scope block
}
