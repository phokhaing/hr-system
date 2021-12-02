<?php


namespace Modules\Payroll\Entities;


use Illuminate\Database\Eloquent\Model;
use Modules\Payroll\Traits\CRUDable;

class PayrollSettings extends Model
{
    use CRUDable;

    protected $table = 'payroll_settings';

    protected $casts = ['json_data' => 'object'];

    protected $fillable = [
        'id',
        'type',
        'json_data',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

}