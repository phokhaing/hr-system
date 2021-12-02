<?php

namespace Modules\HRTraining\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Modules\HRTraining\Traits\CRUDable;

class Exam extends Model
{
    use SoftDeletes, CRUDable;

    protected $table = 'exams';

    protected $casts = ['json_data' => 'object'];

    protected $fillable = [
        'id',
        'course_id',
        'course_contents_id',
        'json_data',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    //Relationship block
    public function questionAnswers()
    {
        return $this->hasMany(Question::class, 'exam_id', 'id');
    }

    /**
     * One exam have many course contents.
     */
    public function courseContent()
    {
        return $this->belongsTo(CourseContents::class, 'course_contents_id');
    }

    public function examHistories()
    {
        return $this->hasMany(ExamHistory::class, 'exam_id', 'id');
    }
    //End Relationship block

    /**
     * Call with exam history's trainee.
     *
     * @param $q
     * @param $enrollmentId
     * @param $traineeId
     * @return mixed
     */
    public function scopeWithExamHistoryByTrainee($q, $enrollmentId, $traineeId)
    {
        return $q->with(['examHistories' => function ($query) use ($enrollmentId, $traineeId) {
            return $query->where('trainee_id', $traineeId)
                ->where('enrollment_id', $enrollmentId)
                ->orderBy('id', 'asc');
        }]);
    }

    public function scopeGetExamByUser($query)
    {
        $user = Auth::user();
        if (!@$user->is_admin && !@$user->can('manage_all_training_company')) {
            $query->where('created_by', @$user->id);
        }
        return $query;
    }
    //End Scope block
}
