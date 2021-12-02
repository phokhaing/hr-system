<?php

namespace App\StaffInfoModel;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'banks';

    protected $fillable = [
        'id',
        'name_kh',
        'name_en'
    ];
}