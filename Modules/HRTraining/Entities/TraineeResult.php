<?php

namespace Modules\HRTraining\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRTraining\Traits\CRUDable;

class TraineeResult extends Model
{
    use SoftDeletes, CRUDable;

    protected $table = 'trainee_results';

    protected $casts = ['json_data' => 'object'];

    protected $fillable = [
        'id',
        'enrollment_id',
        'trainee_id',
        'json_data',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    //***Relationship block
    public function trainee()
    {
        return $this->belongsTo(Trainees::class, 'trainee_id');
    }
    //***End Relationship block

    //***Scope block
    //***End Scope block
}
