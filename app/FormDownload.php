<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormDownload extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'pdf_name',
        'doc_name',
        'pdf_src',
        'doc_src',
        'created_by',
        'updated_by',
        'deleted_at',
    ];
}
