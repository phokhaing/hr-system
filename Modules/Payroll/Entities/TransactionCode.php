<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;

class TransactionCode extends Model
{
    protected $table = 'transaction_code';

    protected $fillable = [
        'id',
        'name_kh',
        'name_en',
        'addition_or_deduction',
        'before_or_after_tax'
    ];
}
