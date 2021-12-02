<?php

namespace Modules\HRTraining\Entities;

use App\BranchesAndDepartments;
use App\Company;
use App\Contract;
use App\Position;
use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRTraining\Traits\CRUDable;

class Trainees extends Model
{
    use SoftDeletes, CRUDable;

    protected $table = 'trainees';

    protected $casts = ['json_data' => 'object'];

    protected $fillable = [
        'id',
        'enrollment_id',
        'staff_personal_id',
        'contract_id',
        'json_data',
        'status_from',
        'request_join_status',
        'training_status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    //***Relationship block
    public function staff()
    {
        return $this->belongsTo(StaffPersonalInfo::class, 'staff_personal_id', 'id');
    }

    public function contract(){
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollments::class, 'enrollment_id', 'id');
    }

    public function traineeResult(){
        return $this->hasOne(TraineeResult::class, 'trainee_id', 'id');
    }
    //***End Relationship block

    //***Scope block

    /**
     * Get trainee link to staff login and base on enrollment id
     * @param $q
     * @param $enrollmentId
     * @return mixed
     */
    public function scopeGetCurrentTraineeLoginBaseOnEnrollment($q, $enrollmentId)
    {
        return $q->where('enrollment_id', $enrollmentId)
            ->where('staff_personal_id', getStaffIdFromCurrentAuth())
            ->latest();
    }

    public function scopeWithTraineeExamResult($q, $enrollment_id){
        return $q->with(['traineeResult' => function($query) use ($enrollment_id){
            return $query->where('enrollment_id', $enrollment_id);
        }]);
    }
    //***End Scope block
}
