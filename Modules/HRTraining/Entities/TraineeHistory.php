<?php

namespace Modules\HRTraining\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRTraining\Traits\CRUDable;

class TraineeHistory extends Model
{
    use SoftDeletes, CRUDable;

    protected $table = 'trainee_histories';

    protected $fillable = [
        'id',
        'enrollment_id',
        'trainee_id',
        'json_data',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    //***Relationship block
    //***End Relationship block

    //***Scope block
    public function scopeSaveTraineeHistory($q, $data)
    {
        $q->createRecord($data);
    }

    /**
     * Validate before save trainee history to avoid duplicate data with current enrollment event with history type
     * @param $q
     * @param array $data ['enrollment_id', 'trainee_id', 'status']
     */
    public function scopeValidateSaveTraineeHistory($q, array $data)
    {
        $existTrainee = $q->where('enrollment_id', @$data['enrollment_id'])
            ->where('trainee_id', @$data['trainee_id'])
            ->where('status', @$data['status'])
            ->first();
        if (!$existTrainee) {
            TraineeHistory::createRecord($data);
        }
    }

    //***End Scope block
}
