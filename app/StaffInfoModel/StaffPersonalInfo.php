<?php

namespace App\StaffInfoModel;

use App\Contract;
use App\Flags;
use App\RequestResign;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Payroll\Entities\TempTransactionUpload;
use Modules\PensionFund\Entities\PensionFunds;
use Spatie\Permission\Traits\HasRoles;

class StaffPersonalInfo extends Model
{
    use SoftDeletes;
    use HasRoles;

    /**
     * Name of table
     *
     * @var string
     */
    protected $table = 'staff_personal_info';

    protected $fillable = [
        'first_name_en',
        'last_name_en',
        'first_name_kh',
        'last_name_kh',
        'marital_status',
        'gender',
        'id_type',
        'id_code',
        'dob',
        'pob',
        'bank_name',
        'bank_acc_no',
        'height',
        'driver_license',
        'province_id',
        'district_id',
        'commune_id',
        'village_id',
        'house_no',
        'street_no',
        'other_location',
        'email',
        'phone',
        'emergency_contact',
        'photo',
        'noted',
        'flag',
        'created_by',
        'updated_by',
        'staff_id' // ID number that show on top of Staff ID Card.
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class, "bank_name");
    }

    public function currentPensionFund()
    {
        return $this->hasOne(PensionFunds::class, 'staff_personal_info_id', 'id')
            ->orderBy('id', 'DESC');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function educations()
    {
        return $this->hasMany(StaffEducation::class, 'staff_personal_info_id')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainings()
    {
        return $this->hasMany(StaffTraining::class, 'staff_personal_info_id')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function experiences()
    {
        return $this->hasMany(StaffExperience::class, 'staff_personal_info_id')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function spouses()
    {
        return $this->hasMany(StaffSpouse::class, 'staff_personal_info_id')->withTrashed();
    }

    public function spouse()
    {
        return $this->hasOne(StaffSpouse::class, 'staff_personal_info_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function guarantors()
    {
        return $this->hasMany(StaffGuarantor::class, 'staff_personal_info_id')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(StaffProfile::class, 'staff_personal_info_id')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents()
    {
        return $this->hasMany(StaffDocument::class, 'staff_personal_info_id')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function flagTitle()
    {
        return $this->hasOne(Flags::class, 'code', 'flag');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function resign()
    {
        return $this->hasOne(StaffResign::class, 'staff_personal_info_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function requestResign()
    {
        return $this->hasOne(RequestResign::class, 'staff_personal_info_id');
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the full name english.
     *
     * @return string
     */
    public function getFullNameEnglishAttribute()
    {
        return "{$this->last_name_en} {$this->first_name_en}";
    }

    /**
     * Get the full name khmer.
     *
     * @return string
     */
    public function getFullNameKhmerAttribute()
    {
        return "{$this->last_name_kh} {$this->first_name_kh}";
    }

    /*
    * override existing delete method.
    * invoke when we call $personal_info->delete(), softdelete.
    */
    public function delete()
    {
        // delete all associated educations
        $this->educations()->delete();

        // delete all associated trainings
        $this->trainings()->delete();

        // delete all associated experiences
        $this->experiences()->delete();

        // delete all associated spouse
        $this->spouse()->delete();

        // delete all associated guarantors
        $this->guarantors()->delete();

        // delete all associated profile
        $this->profile()->delete();

        // delete all associated documents
        $this->documents()->delete();

        // delete the user
        return parent::delete();
    }

    public function contract()
    {
        return $this->hasOne(Contract::class, 'staff_personal_info_id');
    }

    public function currentContract()
    {
        return $this->hasOne(Contract::class, 'staff_personal_info_id')
            ->where("contract_object->contract_last_date", '>=', date("Y-m-d"));
    }


    public function firstContract()
    {
        return $this->hasOne(Contract::class, 'staff_personal_info_id')
            ->orderBy("start_date", 'ASC');
    }

    public function contractsType()
    {
        return $this->hasMany(Contract::class, 'staff_personal_info_id', 'id');
    }

    public function tempTransactionUploads()
    {
        return $this->hasMany(TempTransactionUpload::class, 'staff_personal_info_id', 'id');
    }

    public function scopeFindActiveStaffByContract($query, $staffIdCard, $contractType = null)
    {
//        if ($contractType == CONTRACT_TYPE['PROBATION'] || $contractType == CONTRACT_TYPE['REGULAR']) {
//            $query->where('id_card', $staffIdCard)
//                ->whereHas('contract', function ($q) use ($staffIdCard) {
//                    return $q->whereNotIn('id_card', $staffIdCard);
//                });
//        } else {
//            $query->whereHas('contract', function ($q) use ($staffIdCard) {
//                return $q->where('id_card', $staffIdCard);
//            });
//        }
        return $query->where();

    }
}
