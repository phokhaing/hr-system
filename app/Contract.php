<?php

namespace App;

use App\StaffInfoModel\StaffPersonalInfo;
use App\Traits\CrudGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Payroll\Entities\Payroll;
use Modules\Payroll\Entities\TempPayroll;
use Modules\Payroll\Entities\TempTransactionUpload;

class Contract extends Model
{
    use SoftDeletes, CrudGenerator;

    protected $table = 'contracts';

    protected $casts = [
        'contract_object' => 'array',
    ];

    protected $fillable = [
        'id',
        'staff_personal_info_id',
        'staff_id_card',
        'company_profile',
        'contract_object',
        'created_by',
        'updated_by',
        'deleted_by',
        'contract_type',
        'start_date',
        'end_date'
    ];

    /**
     * Select staff profile from contracts.
     * Define if have no contracts it can't get to manager.
     *
     * @param $company_code
     * @return mixed
     */
    public function scopeGetManager()
    {
        $user = Auth::user();
        $company_code = $user->company_code;
        $isAdmin = $user->is_admin;
        return DB::table("staff_personal_info AS sp")
            ->select(DB::raw("id, CONCAT(sp.last_name_en, ' ',sp.first_name_en) AS full_name_en, CONCAT(sp.last_name_kh, ' ',sp.first_name_kh) AS full_name_kh "))
            ->where('deleted_at', '=', NULL)
            ->whereExists(function ($query) use ($company_code, $isAdmin) {
                $query->select('company_profile')
                    ->from('contracts AS con')
                    ->whereRaw('con.staff_personal_info_id = sp.id');
                if (!$isAdmin) {
                    $query->whereRaw("(SUBSTRING(company_profile, 1,3)) = $company_code");
                }
            });
    }

    /**
     * Get staff profile except staff that already have contracts.
     *
     * @return mixed
     */
    public function getStaffProfile()
    {
        return DB::table("staff_personal_info AS sp")
            ->select(DB::raw("sp.id, CONCAT(sp.last_name_en, ' ',sp.first_name_en) AS full_name_en, CONCAT(sp.last_name_kh, ' ',sp.first_name_kh) AS full_name_kh "))
            ->where('sp.deleted_at', '=', NULL)
            ->get();
    }

    /**
     * Get manager by ID.
     *
     * @param array $ids
     * @return mixed
     */
    public function getManagerById(array $ids = [])
    {
        return DB::table("staff_personal_info AS sp")
            ->select(DB::raw("
                sp.id, 
                CONCAT(sp.last_name_en, ' ',sp.first_name_en) AS full_name_en, 
                CONCAT(sp.last_name_kh, ' ',sp.first_name_kh) AS full_name_kh, 
                con.contract_object->>\"$.email\" AS email, con.contract_object->>\"$.phone_number\" AS phone
            "))
            ->join('contracts AS con', 'con.staff_personal_info_id', '=', 'sp.id')
            ->where('sp.deleted_at', '=', NULL)
            ->whereIn('con.staff_personal_info_id', $ids)
            ->get();
    }


    public function staffPersonalInfo()
    {
        return $this->belongsTo(StaffPersonalInfo::class, 'staff_personal_info_id', 'id');
    }

    public function newSalary()
    {
        return $this->hasOne(NewSalary::class, 'contract_id', 'id');
    }

    public function scopeHasNewSalary($query, $payrollDate)
    {
        return $query->with(['newSalary' => function ($q) use ($payrollDate) {
            return $q->whereMonth('effective_date', '=', date('m', strtotime($payrollDate)))
                ->whereYear('effective_date', '=', date('Y', strtotime($payrollDate)));
        }]);
    }

    /**
     * @param string $keyword
     * @param null $company_code
     * @param null $branch_department_code
     * @param null $position_code
     * @param null $contract_type
     * @param string $contract_start_date
     * @param string $contract_end_date
     * @return mixed
     */
    public function advanceSearch(
        $keyword = '',
        $company_code = null,
        $branch_department_code = null,
        $position_code = null,
        $contract_type = null,
        $contract_start_date = '',
        $contract_end_date = ''
    )
    {
        $keyword = str_replace(' ', '', $keyword);
        $query = DB::table('contracts AS con')->selectRaw("
            con.id, con.staff_personal_info_id, con.company_profile, con.contract_object,
            con.start_date, con.end_date, con.contract_type, con.staff_id_card, 
             spi.staff_id,spi.phone, CONCAT(spi.last_name_en, ' ',spi.first_name_en) AS full_name_en, 
                CONCAT(spi.last_name_kh, ' ',spi.first_name_kh) AS full_name_kh, con.contract_object->\"$.email\"
        ")->join('staff_personal_info AS spi', 'spi.id', '=', 'con.staff_personal_info_id');

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->whereRaw('LOWER(CONCAT(spi.last_name_kh,spi.first_name_kh)) LIKE ?', ["%$keyword%"]);
                $q->orWhereRaw('LOWER(CONCAT(spi.last_name_en,spi.first_name_en)) LIKE ?', ["%$keyword%"]);
                $q->orWhereRaw('spi.staff_id LIKE ?', ["%$keyword%"]);
                $q->orWhereRaw('spi.phone LIKE ?', ["%$keyword%"]);
            });
        }
        $is_admin = \auth()->user()->is_admin;
        if ($is_admin) {
            if (@$company_code) {
                $query->whereRaw($this->queryByCompany($company_code), [$company_code]);
            }
        } else {
            $query->whereRaw($this->queryByCompany(auth()->user()->company_code), [auth()->user()->company_code]);
        }

        //    if (@$branch_department_code) {
        //         $query->whereRaw('con.contract_object->>"$.branch_department.code" = ?', [$branch_department_code]);
        //    }

        //        if ($position_code) {
        //            $query->where(function ($q) use ($position_code) {
        //                $q->whereRaw('con.contract_object->"$.position.code" = ?', [$position_code]);
        //            });
        //        }
        if ($contract_type) {
            $query->whereRaw('con.contract_type=?', [$contract_type]);
        }

        $query->where('con.deleted_at', null);
        //        if ($contract_start_date) {
        //            $query->where(function ($q) use ($contract_start_date) {
        //                $q->whereRaw('con.start_date = ?', [$contract_start_date]);
        //            });
        //        }
        //        if ($contract_end_date) {
        //            $query->where(function ($q) use ($contract_end_date) {
        //                $q->whereRaw('con.end_date = ?', [$contract_end_date]);
        //            });
        //        }
        return $query;
    }

    /**
     * Check company code length whenever company code start from 1000
     */
    private function queryByCompany($companyCode)
    {
        $companySubStrlen = strlen($companyCode);
        // return $query->whereRaw('substring(company_profile, 1, '.$companySubStrlen.') = ?', [$coumpanyCode]);
        return 'substring(company_profile, 1, ' . $companySubStrlen . ') = ?';
    }

    private function queryByCompanyAndBranchDepartment($coumpanyCode)
    {
        $companySubStrlen = strlen($coumpanyCode) * 2;
        return 'substring(company_profile, 1, ' . $companySubStrlen . ') = ?';
    }

    /**
     * Get current contract.
     *
     * @param $query
     * @param $staffPersonalId
     * @return mixed
     */
    public function scopeCurrentContract($query, $staffPersonalId)
    {
        return $query->where("staff_personal_info_id", "=", $staffPersonalId)
            ->where("contract_object->contract_last_date", '>=', date("Y-m-d"));
    }


    /**
     * Get current contract.
     *
     * @param $query
     * @param $staffPersonalId
     * @return mixed
     */
    public function scopeFirstContract($query, $staffPersonalId)
    {
        return $query->where("staff_personal_info_id", "=", $staffPersonalId)
            ->orderBy("start_date", 'ASC');
    }

    public function scopeFirstContractWithCurrentCompany($query, $staffIdCard)
    {
        return $query->where("staff_id_card", "=", $staffIdCard)
            ->whereNotIn("contract_type", [
                CONTRACT_TYPE['RESIGN'],
                CONTRACT_TYPE['TERMINATE'],
                CONTRACT_TYPE['LAY_OFF']
            ])
            ->orderBy("start_date", 'ASC');
    }

    public function scopeAllContractWithCurrentCompany($query, $staffIdCard, $companyCode)
    {
        return $query->where("staff_id_card", "=", $staffIdCard)
            ->where('contract_object->company->code', $companyCode)
            ->orderBy("start_date", 'DESC');
    }


    /**
     * Get last contract.
     *
     * @param $query
     * @param $staffIdCard
     * @return mixed
     */
    public function scopeLastContract($query, $staffIdCard)
    {
        return $query->where("staff_id_card", "=", $staffIdCard)
            ->where("contract_object->contract_last_date", '>=', date("Y-m-d"));
    }

    /**
     * Get all contract active.
     *
     * @param $query
     * @return mixed
     */
    public function scopeGetAllStaffActive($query)
    {
        $user = Auth::user();
        $company_code = @$user->company_code;
        $is_admin = $user->is_admin;
        $query->whereHas('staffPersonalInfo')
            ->where("contract_object->contract_last_date", '>=', date("Y-m-d"))
            ->whereIn('contract_type', [
                CONTRACT_ACTIVE_TYPE['FDC'],
                CONTRACT_ACTIVE_TYPE['UDC'],
                CONTRACT_ACTIVE_TYPE['DEMOTE'],
                CONTRACT_ACTIVE_TYPE['PROMOTE'],
                CONTRACT_ACTIVE_TYPE['CHANGE_LOCATION'],
            ]);
        if (!$is_admin) {
            $query->whereRaw($this->queryByCompany($company_code), [$company_code]);
        }
        return $query;
    }

    public function scopeActiveContract($query)
    {
        return $query->whereIn('contract_type', [
            CONTRACT_ACTIVE_TYPE['FDC'],
            CONTRACT_ACTIVE_TYPE['UDC'],
            CONTRACT_ACTIVE_TYPE['DEMOTE'],
            CONTRACT_ACTIVE_TYPE['PROMOTE'],
            CONTRACT_ACTIVE_TYPE['CHANGE_LOCATION'],
        ]);
    }

    /**
     * Get all contract active with department or branch.
     *
     * @param $query
     * @return mixed
     */
    public function scopeGetAllStaffActiveByDepartmentBranch($query, $companyCode, $departmentCode)
    {
        return $query
            ->where("contract_object->contract_last_date", '>=', date("Y-m-d"))
            ->with('staffPersonalInfo')
            ->whereRaw($this->queryByCompanyAndBranchDepartment($companyCode), [$companyCode . $departmentCode])
            ->whereIn('contract_type', [
                CONTRACT_ACTIVE_TYPE['FDC'],
                CONTRACT_ACTIVE_TYPE['UDC'],
                CONTRACT_ACTIVE_TYPE['DEMOTE'],
                CONTRACT_ACTIVE_TYPE['PROMOTE'],
                CONTRACT_ACTIVE_TYPE['CHANGE_LOCATION'],
            ]);
    }

    /**
     * Get all end contract.
     *
     * @param $query
     * @return mixed
     */
    public function scopeGetAllEndContract($query)
    {
        $user = Auth::user();
        $company_code = @$user->company_code;
        $is_admin = $user->is_admin;

        $query->whereHas('staffPersonalInfo')
            ->where("contract_object->contract_last_date", '>=', date("Y-m-d"))
            ->whereIn('contract_type', [
                CONTRACT_END_TYPE['RESIGN'],
                CONTRACT_END_TYPE['DEATH'],
                CONTRACT_END_TYPE['TERMINATE'],
                CONTRACT_END_TYPE['LAY_OFF']
            ]);

        if (!$is_admin) {
            $query->whereRaw($this->queryByCompany($company_code), [$company_code]);
        }
        return $query;
    }

    /**
     * Get all contract movement.
     *
     * @param $query
     * @return mixed
     */
    public function scopeGetAllMovementContract($query)
    {
        $user = Auth::user();
        $company_code = @$user->company_code;
        $is_admin = $user->is_admin;
        if ($is_admin) {
            return $query->whereHas('staffPersonalInfo')
                ->whereIn('contract_type', [
                    CONTRACT_TYPE['CHANGE_LOCATION']
                ]);
        } else {
            return $query->whereHas('staffPersonalInfo')
                ->whereRaw($this->queryByCompany($company_code), [$company_code])
                ->whereIn('contract_type', [
                    CONTRACT_TYPE['CHANGE_LOCATION']
                ]);
        }
    }

    public function scopeStaffReplace($query, $staffPersonalInfoId)
    {
        return $query->where('staff_personal_info_id', $staffPersonalInfoId)
            ->where("contract_object->contract_last_date", '>=', date("Y-m-d"))
            ->whereNotIn("contract_type", [
                CONTRACT_TYPE['RESIGN'],
                CONTRACT_TYPE['DEATH'],
                CONTRACT_TYPE['TERMINATE'],
                CONTRACT_TYPE['LAY_OFF']
            ]);
    }

    /**
     * Max staff ID in each company.
     *
     * @param $company
     * @return mixed
     */
    public function maxStaffIdCard($company)
    {
        $rightLen = strlen($company);
        $max = DB::table('contracts')
            ->selectRaw("
                MAX(
                    CONVERT(
                        RIGHT(
                            staff_id_card, (char_length(staff_id_card) - $rightLen)
                        ), 
                        DECIMAL 
                    )
                ) AS max_id
            ")
            ->whereRaw($this->queryByCompany($company), [$company])
            ->first();
        return $max->max_id;
    }

    /**
     * Set staff ID card new.
     *
     * @param $company_code
     * @return string
     */
    public function staffIdFormat($company_code)
    {
        return $company_code . ($this->maxStaffIdCard($company_code) + 1);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeGetDependOnUser($query)
    {
        $user = Auth::user();
        $company_code = @$user->company_code;
        $is_admin = $user->is_admin;
        if ($is_admin) {
            return $query->where('deleted_at', '=', null);
        } else {
            return $query->where('deleted_at', '=', null)->where('contract_object->company->code', (int)$company_code);
        }
    }

    public function scopeCurrentStaffForEnrollment($q)
    {
        return $q->with('staffPersonalInfo')
            ->getAllStaffActive()
            ->orderBy('id', 'DESC');
    }

    /**
     * Get active staff company
     */
    public function scopeGetAllStaffActiveByCompany($query, $company_code)
    {
        return $query->where("contract_object->contract_last_date", '>=', date("Y-m-d"))
            ->whereRaw($this->queryByCompany($company_code), [$company_code])
            ->whereIn('contract_type', [
                // CONTRACT_ACTIVE_TYPE['PROBATION'],
                CONTRACT_ACTIVE_TYPE['FDC'],
                CONTRACT_ACTIVE_TYPE['UDC'],
                CONTRACT_ACTIVE_TYPE['DEMOTE'],
                CONTRACT_ACTIVE_TYPE['PROMOTE'],
                CONTRACT_ACTIVE_TYPE['CHANGE_LOCATION'],
            ]);
    }

    /**
     * First employment date (Group employment)
     *
     * @param $query
     * @param $staff_personal_info_id
     * @return mixed
     */
    public function scopeGetFirstEmploymentDate($query, $staff_personal_info_id)
    {
        return $query->where("staff_personal_info_id", "=", $staff_personal_info_id)->first();
    }

    public function tempPayrolls()
    {
        return $this->hasMany(TempPayroll::class, 'contract_id', 'id');
    }

    public function tempTransactionUploads()
    {
        return $this->hasMany(TempTransactionUpload::class, 'contract_id', 'id');
    }

    public function payroll()
    {
        return $this->hasOne(Payroll::class, 'contract_id', 'id');
    }

    public function scopeGetAllStaffToPostPayrollFullMonth($query, $payrollDate)
    {
        $user = Auth::user();
        $company_code = @$user->company_code;
        $is_admin = $user->is_admin;

        $query->where(function ($query) use ($is_admin, $company_code, $payrollDate) {
            $query->where("contract_object->contract_last_date", '>=', $payrollDate);
            if (!$is_admin) {
                $query->where('contract_object->company->code', (int)$company_code);
            }

            $query->whereIn('contract_type', [
                // CONTRACT_ACTIVE_TYPE['PROBATION'],
                CONTRACT_ACTIVE_TYPE['FDC'],
                CONTRACT_ACTIVE_TYPE['UDC'],
                CONTRACT_ACTIVE_TYPE['DEMOTE'],
                CONTRACT_ACTIVE_TYPE['PROMOTE'],
                CONTRACT_ACTIVE_TYPE['CHANGE_LOCATION'],
            ]);

            // $query->where(function ($query) {
            //New Staffs
            // $query->where(function ($query) {
            //     $query->whereYear('start_date', '=', Carbon::now()->year)
            //         ->whereMonth('start_date', '=', Carbon::now()->month)
            //         ->whereRaw('DAY(start_date) BETWEEN ? AND ?', [START_DATE_FULL_PAYROLL, END_DATE_FULL_PAYROLL]);
            // });

            //Old Staffs
            //     $query->orWhereDate('start_date', '<=', Carbon::now()->subMonth(1));
            // });
        });
        //Staff End Contract in current month
//            ->orWhere(function ($query) use ($is_admin, $company_code, $payrollDate) {
//                $query->where("contract_object->contract_last_date", '>=', $payrollDate);
//                if (!$is_admin) {
//                    $query->whereRaw($this->queryByCompany($company_code), [$company_code]);
//                }
//
//                $query->whereIn('contract_type', [
//                    CONTRACT_END_TYPE['RESIGN'],
//                    CONTRACT_END_TYPE['DEATH'],
//                    CONTRACT_END_TYPE['TERMINATE'],
//                    CONTRACT_END_TYPE['LAY_OFF'],
//                ])
//                    ->whereYear('start_date', '=', date('Y', strtotime($payrollDate)))
//                    ->whereMonth('start_date', '=', date('m', strtotime($payrollDate)));
//            });
        return $query;
    }

    public function scopeGetAllStaffToPostPayrollHalfMonth($query, $payrollDate)
    {
        $user = Auth::user();
        $company_code = @$user->company_code;
        $is_admin = $user->is_admin;

        $query->where(function ($query) use ($is_admin, $company_code, $payrollDate) {
            $query->where("contract_object->contract_last_date", '>=', $payrollDate);
            if (!$is_admin) {
                $query->where('contract_object->company->code', (int)$company_code);
            }

            $query->whereIn('contract_type', [
                // CONTRACT_ACTIVE_TYPE['PROBATION'],
                CONTRACT_ACTIVE_TYPE['FDC'],
                CONTRACT_ACTIVE_TYPE['UDC'],
                CONTRACT_ACTIVE_TYPE['DEMOTE'],
                CONTRACT_ACTIVE_TYPE['PROMOTE'],
                CONTRACT_ACTIVE_TYPE['CHANGE_LOCATION']
            ]);

            // $query->where(function ($query) {
            // New Staffs start working between 06 to 19 not available to post half month (skp and mmi)
            // $query->where(function ($query) {
            //     $query->whereYear('start_date', '=', Carbon::now()->year)
            //         ->whereMonth('start_date', '=', Carbon::now()->month)
            //         ->whereRaw('DAY(start_date) NOT BETWEEN ? AND ?', [START_DATE_FULL_PAYROLL, END_DATE_FULL_PAYROLL]);
            // });

            //Old Staffs
            //     $query->orWhereDate('start_date', '<=', Carbon::now()->subMonth(1));
            // });
        });
        return $query;
    }

    /**
     * Checking payroll from contract that did not post yet by date
     * @param $query
     * @param $month
     * @param $year
     */
    public function scopeCheckingContractDidNotPostPayrollYet($query, $month, $year, $transactionCode)
    {
        return $query->whereDoesntHave('payroll', function ($query) use ($month, $year, $transactionCode) {
            $query
                ->whereMonth('transaction_date', '=', $month)
                ->whereYear('transaction_date', '=', $year)
                ->whereRaw("transaction_object->>'$.company.code'=?", [@auth::user()->company_code])
                ->where('transaction_code_id', '=', $transactionCode);
        });
    }

    /**
     * Retrieve all contracts have no blocked for final pay base on payroll_date (post date or back date)
     */
    public function scopeGetNoBlockForFinalyPay($query, $payrollDate)
    {
        return $query->where(function ($query) use ($payrollDate) {
            $query->whereRaw("contract_object->>'$.block_salary' = 'null'")
                ->orWhereRaw("contract_object->>'$.block_salary' is null")
                ->orWhereRaw("contract_object->>'$.block_salary.is_block' =?", 0)
                ->orWhereRaw("date(contract_object->>'$.block_salary.from_date') > '" . $payrollDate . "'");
        });
    }

    function finalPay(){
        return $this->hasOne(FinalPay::class, "contract_id", "id");
    }
}
