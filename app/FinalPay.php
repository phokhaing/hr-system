<?php

namespace App;

use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRTraining\Traits\CRUDable;

class FinalPay extends Model
{
    use SoftDeletes, CRUDable;

    protected $table = 'final_pay';


    protected $casts = ["json_data" => 'object'];

    protected $fillable = [
        'id',
        'staff_personal_info_id',
        'contract_id',
        'json_data',
        'deleted_at',
        'deleted_by',
        'created_at',
        'created_by',
        'updated_at',
        'update_by'
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }
    
    public function staffPersonalInfo(){
        return $this->belongsTo(StaffPersonalInfo::class, 'staff_personal_info_id', 'id');
    }
}
