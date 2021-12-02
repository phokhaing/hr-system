<?php

namespace Modules\HRTraining\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Modules\HRTraining\Traits\CRUDable;

class Enrollments extends Model
{
    use SoftDeletes, CRUDable;

    protected $table = 'enrollments';

    protected $casts = ['json_data' => 'object'];

    protected $fillable = [
        'id',
        'course_id',
        'json_data',
        'class_type',
        'status',
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

    public function trainees()
    {
        return $this->hasMany(Trainees::class, 'enrollment_id', 'id');
    }

    public function trainee()
    {
        return $this->hasOne(Trainees::class, 'enrollment_id', 'id');
    }

    public function scopeTraineeWithCurrentStaff($query, $staffPersonalInfoId){
        return $query->with(['trainee' => function ($query) use($staffPersonalInfoId) {
            $query->where('staff_personal_id', $staffPersonalInfoId)
                ->latest();
        }]);
    }

    /**
     * Get all trainee which added to join this enrollment from admin
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function traineesAddedFromAdmin()
    {
        return $this->hasMany(Trainees::class, 'enrollment_id', 'id')
            ->where('status_from', TRAINING_CONSTANT_TYPE['TRAINEE_STATUS_FROM_ADMIN']);
    }

    /**
     * Get all trainee which request to join this enrollment own self
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function traineesRequested()
    {
        return $this->hasMany(Trainees::class, 'enrollment_id', 'id')
            ->where('status_from', TRAINING_CONSTANT_TYPE['TRAINEE_STATUS_FROM_REQUEST_JOIN']);
    }

    //***End Relationship block

    /**
     * Current Training in this enrollment with from staff login
     */
    public function scopeWithCurrentTraineeFromLoginStaff($q)
    {
        return $q->with(['trainee' => function ($query) {
            $query->where('staff_personal_id', getStaffIdFromCurrentAuth())
                ->latest();
        }]);
    }

    /**
     * Condition to get only trainee in enrollment event, trainee has status approved
     * @param $q
     * @return mixed
     */
    public function scopeWhereCurrentTraineeApprove($q)
    {
        return $q->whereHas('trainee', function ($query) {
            $query->where('staff_personal_id', getStaffIdFromCurrentAuth())
                ->where('request_join_status', TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_APPROVED']);
        });
    }

    public function scopeGetEnrollmentByUser($query)
    {
        $user = Auth::user();
        if (!@$user->is_admin && !@$user->can('manage_all_training_company')) {
            $query->where('created_by', @$user->id);
        }
        return $query;
    }
    //***End Scope block
}
