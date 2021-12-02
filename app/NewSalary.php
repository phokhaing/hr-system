<?php


namespace App;


use App\Traits\CrudGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewSalary extends Model
{
    use SoftDeletes, CrudGenerator;

    protected $table = 'new_salary';

    protected $casts = [
        'object' => 'object',
    ];

    protected $fillable = [
        'id',
        'contract_id',
        'object',
        'is_active',
        'effective_date',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

}