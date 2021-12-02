<?php

namespace Modules\PensionFund\Entities;

use App\Contract;
use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Payroll\Traits\CRUDable;

class PensionFunds extends Model
{
    use SoftDeletes, CRUDable;

    protected $table = "pension_funds";

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
        'updated_by'
    ];

    public function contract(){
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    public function staffPersonalInfo(){
        return $this->belongsTo(StaffPersonalInfo::class, 'staff_personal_info_id', 'id');
    }

    public function scopeFindLastPensionFundByStaff($q, $staffPersonalInfoId){
        return $q->where('staff_personal_info_id', $staffPersonalInfoId)->orderBy('id', 'DESC');
    }
}
