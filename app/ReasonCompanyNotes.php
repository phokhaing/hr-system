<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReasonCompanyNotes extends Model
{
    protected $table = 'reason_company_note';

    protected $fillable = [
        'id',
        'name_kh',
        'name_en'
    ];
}
