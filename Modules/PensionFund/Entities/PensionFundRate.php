<?php


namespace Modules\PensionFund\Entities;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Payroll\Traits\CRUDable;

class PensionFundRate extends Model
{
    use SoftDeletes, CRUDable;

    protected $table = "pension_fund_rate";

    protected $casts = ["json_data" => 'object'];

    protected $fillable = [
        'id',
        'json_data',
        'deleted_at',
        'deleted_by',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

}