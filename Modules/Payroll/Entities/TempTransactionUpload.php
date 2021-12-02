<?php

namespace Modules\Payroll\Entities;

use App\Contract;
use App\StaffInfoModel\StaffPersonalInfo;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Payroll\Traits\CRUDable;

class TempTransactionUpload extends Model
{
    use CRUDable;

    protected $table = 'temp_transaction_uploads';

    protected $casts = ['transaction_object' => 'object'];

    protected $fillable = [
        'id',
        'staff_personal_info_id',
        'contract_id',
        'transaction_code_id',
        'transaction_object',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function staff_personal_info(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StaffPersonalInfo::class, 'staff_personal_info_id', 'id');
    }

    public function transaction_code(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TransactionCode::class, 'transaction_code_id', 'id');
    }

    public function contract(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    public function scopeFindCurrentTempTransactionUpload($query, $staffPersonalInfoId, $contractId, $transactionCodeId)
    {
        return $query->where('contract_id', $contractId)
            ->where('staff_personal_info_id', $staffPersonalInfoId)
            ->where('transaction_code_id', $transactionCodeId);
    }
}
