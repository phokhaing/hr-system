<?php

namespace App;

use App\Traits\CrudGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractActions extends Model
{
    use SoftDeletes, CrudGenerator;

    protected $table = 'contract_actions';

    protected $casts = [
        'objects' => 'array',
    ];

    protected $fillable = [
        'id',
        'staff_personal_id',
        'contract_id',
        'objects',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
