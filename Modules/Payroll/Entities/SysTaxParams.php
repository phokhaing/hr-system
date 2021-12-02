<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Payroll\Traits\CRUDable;

class SysTaxParams extends Model{
    use CRUDable, SoftDeletes;

    protected $table = 'sys_tax_params';

    protected $casts = ['tax_object' => 'object'];

    protected $fillable = [
        'id',
        'tax_object',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];
}