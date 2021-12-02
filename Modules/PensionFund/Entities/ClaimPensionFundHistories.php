<?php

namespace Modules\PensionFund\Entities;

use App\Contract;
use App\StaffInfoModel\StaffPersonalInfo;
use App\Traits\CrudGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimPensionFundHistories extends Model
{
    use SoftDeletes, CrudGenerator;

    protected $table = "claim_pf_histories";

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

    public function staffPersonalInfo(){
        return $this->belongsTo(StaffPersonalInfo::class, 'staff_personal_info_id', 'id');
    }

    public function contract(){
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }
}
