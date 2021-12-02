<?php

namespace Modules\Payroll\Entities;

use App\Contract;
use App\StaffInfoModel\StaffPersonalInfo;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Payroll\Traits\CRUDable;

class Payroll extends Model
{
    use SoftDeletes, CRUDable;

    protected $table = 'transactions';

    protected $casts = ['transaction_object' => 'object'];

    protected $fillable = [
        'id',
        'staff_personal_info_id',
        'contract_id',
        'transaction_code_id',
        'transaction_date',
        'transaction_object',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->deleted_by = Auth::id();
            $model->save();
        });
    }

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

    /**
     * @param $query
     * @param $company_code
     * @param $branch_department_code
     * @param $year_month
     * @return mixed
     */
    public function scopeSearch($query, $company_code, $branch_department_code, $year_month, $staff_personal_info_id = null)
    {
        $is_admin = \auth()->user()->is_admin;
        $current_company = \auth()->user()->company_code;
        $query->whereHas('contract', function ($q) use ($company_code, $branch_department_code, $is_admin, $current_company) {

            if (@$is_admin) {
                // dd($company_code);
                if (@$company_code) {
                    $q->where('contract_object->company->code', (int)$company_code);
                }
            } else {
                $q->where('contract_object->company->code', (int)$current_company);
            }

            if ($branch_department_code) {
                $q->where('contract_object->branch_department->code', (int)$branch_department_code);
            }
        });
        if ($year_month) {
            $query->whereYear('transaction_date', '=', date('Y', strtotime($year_month)))
                ->whereMonth('transaction_date', '=', date('m', strtotime($year_month)));
        }

        if (@$staff_personal_info_id) {
            $query->where('staff_personal_info_id', $staff_personal_info_id);
        }
        return $query;
    }

    /**
     * @param $company_code
     * @param $branch_code
     * @param $year_month
     * @param bool $isCheckBlock for report will check this field to make sure all blocking payroll will not show in report
     * @return mixed
     */
    public function payrollReport($company_code, $branch_code, $staff_personal_info_id, $year_month, $isCheckBlock = true, $keyword = null)
    {
        $unpaidLeave = TRANSACTION_CODE['UNPAID_LEAVE'];
        $staffLoanPaid = TRANSACTION_CODE['STAFF_LOAN_PAID'];
        $insurancePay = TRANSACTION_CODE['INSURANCE_PAY'];
        $maternityLeave = TRANSACTION_CODE['MATERNITY_LEAVE'];
        $salaryDeduction = TRANSACTION_CODE['SALARY_DEDUCTION'];
        $netSalary = TRANSACTION_CODE['NET_SALARY'];
        $fullPay = TRANSACTION_CODE['FULL_SALARY'];
        $pensionFund = TRANSACTION_CODE['PENSION_FUND'];
        $salaryBeforeTax = TRANSACTION_CODE['SALARY_BEFORE_TAX'];
        $taxOnSalary = TRANSACTION_CODE['TAX_ON_SALARY'];
        $salaryAfterTax = TRANSACTION_CODE['SALARY_AFTER_TAX'];

        $overtime = TRANSACTION_CODE['OVERTIME'];
        $spouse = TRANSACTION_CODE['SPOUSE'];
        $pchumAndNewYearBonue = TRANSACTION_CODE['BONUS_PCHUM_BEN_AND_NEW_YEAR'];
        $incentive = TRANSACTION_CODE['INCENTIVE'];
        $locationAllowance = TRANSACTION_CODE['LOCATION_ALLOWANCE'];
        $foodAllowance = TRANSACTION_CODE['FOOD_ALLOWANCE'];
        $thirdSalaryBonus = TRANSACTION_CODE['THIRD_SALARY_BONUS'];
        $degreeAllowance = TRANSACTION_CODE['DEGREE_ALLOWANCE'];
        $positionAllowance = TRANSACTION_CODE['POSITION_ALLOWANCE'];
        $attendanceAllowance = TRANSACTION_CODE['ATTENDANCE_ALLOWANCE'];
        $fringeAllowance = TRANSACTION_CODE['FRINGE_ALLOWANCE'];
        $taxOnFringeAllowance = TRANSACTION_CODE['TAX_ON_FRINGE_ALLOWANCE'];

        $retroactiveSalary = TRANSACTION_CODE['RETROACTIVE_SALARY'];
        $seniorityPay = TRANSACTION_CODE['SENIORITY_PAY'];
        $nssf = TRANSACTION_CODE['NSSF'];

        $year = date('Y', strtotime($year_month));
        $month = date('m', strtotime($year_month));

        $sql = "select 
                    tt1.id,
                    tt1.staff_personal_info_id,
                    tt1.contract_id,
                    tt1.transaction_code_id,
                    tt1.transaction_object,
                    tt1.created_at,
                    tt1.transaction_object->>'$.amount' as net_salary,                                                                    
                    tt1.transaction_object->>'$.half_pay' as half_salary, 
                    tt1.transaction_object->>'$.is_block' as is_block,      
                    
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id in ($unpaidLeave,$maternityLeave,$salaryDeduction)) as total_deduction,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id in ($overtime,$pchumAndNewYearBonue,$incentive,$locationAllowance, $foodAllowance, $thirdSalaryBonus, $degreeAllowance, $positionAllowance, $attendanceAllowance)) as total_allowance,
                    
                    (select transaction_object->>'$.amount' from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$salaryBeforeTax limit 1) as salary_before_tax,
                    (select transaction_object->>'$.amount' from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$taxOnSalary limit 1) as tax_on_salary,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id in ($taxOnSalary, $taxOnFringeAllowance)) as total_tax_payable,
                    (select transaction_object->>'$.amount' from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$salaryAfterTax limit 1) as salary_after_tax,
                    (select transaction_object->>'$.amount' from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$fullPay limit 1) as full_salary,
                    
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$spouse) as spouse,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$retroactiveSalary) as retroactive_salary,
                    
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$nssf) as nssf,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$staffLoanPaid) as staff_loan_paid,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$insurancePay) as insurance_pay,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$pensionFund) as pension_fund,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$maternityLeave) as maternity_leave,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$unpaidLeave) as unpaid_leave,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$salaryDeduction) as salary_deduction,
                    
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$seniorityPay) as seniority_pay,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$fringeAllowance) as fringe_allowance,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$taxOnFringeAllowance) as tax_on_fringe_allowance,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$overtime) as overtime,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$pchumAndNewYearBonue) as pchumben_and_newyear_bonus,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$incentive) as incentive,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$locationAllowance) as location_allowance,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$foodAllowance) as food_allowance,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$thirdSalaryBonus) as third_salary_bonus,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$degreeAllowance) as degree_allowance,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$positionAllowance) as position_allowance,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$attendanceAllowance) as attendance_allowance,
                    
                    c.staff_id_card,                                                                         
                    tt1.transaction_object->>'$.gross_base_salary' as base_salary,
                    c.contract_object->>'$.position.name_kh' as position,
                    c.contract_object->>'$.company.name_kh' as company,                                                              
                    c.contract_object->>'$.branch_department.name_kh' as branch_department,                                                              
                    c.start_date,
                    
                    spi.first_name_en,
                    spi.last_name_en,
                    spi.staff_id
                    
                from transactions tt1
                inner join contracts c
                    on c.id=tt1.contract_id
                inner join staff_personal_info spi
                    on spi.id=tt1.staff_personal_info_id
                where tt1.deleted_at is null ";

        if (@$company_code) {
            $sql .= " and c.contract_object->>'$.company.code'=$company_code";
        }

        if (@$branch_code) {
            $sql .= " and c.contract_object->>'$.branch_department.code'=$branch_code";
        }

        if (@$staff_personal_info_id) {
            $sql .= " and c.staff_personal_info_id=$staff_personal_info_id";
        }

        if ($year_month) {
            $sql .= " and YEAR(tt1.transaction_date) = " . $year . " 
                          AND MONTH(tt1.transaction_date) = " . $month;
        }

        if (@$keyword) {
            $sql .= " and ( 
                CONCAT(spi.last_name_en, ' ' , spi.first_name_en) LIKE '%" . $keyword . "%' 
                or CONCAT(spi.last_name_kh, ' ' , spi.first_name_kh) LIKE '%" . $keyword . "%'
                or spi.staff_id LIKE '%" . $keyword . "%'
                )";
        }

        $user = Auth::user();
        if (!@$user->is_admin) {
            $sql .= " and c.contract_object->>'$.company.code'=$user->company_code";
        }

        if ($isCheckBlock) {
            $sql .= " and (tt1.transaction_object->>'$.is_block' is null or tt1.transaction_object->>'$.is_block'='false' or tt1.transaction_object->>'$.is_block'='null')";
        }

        $sql .= " and tt1.transaction_code_id=$netSalary";
// print_r($sql);
        return DB::select($sql);
    }

    public function scopeByKeyword($query, $keyword)
    {
        return $query->whereHas('staff_personal_info', function ($q) use ($keyword) {
            $q->whereRaw('LOWER(CONCAT(last_name_kh, " ", first_name_kh)) LIKE ?', ["%$keyword%"]);
            $q->orWhereRaw('LOWER(CONCAT(last_name_en, " ", first_name_en)) LIKE ?', ["%$keyword%"]);
            $q->orWhereRaw('staff_id LIKE ?', ["%$keyword%"]);
        });
    }

    
    /**
     * @param $company_code
     * @param $year_month
     */
    public function scopeSearchByCompany($query, $company_code, $year_month)
    {
        $is_admin = \auth()->user()->is_admin;
        $current_company = \auth()->user()->company_code;
        $query->whereHas('contract', function ($q) use ($company_code, $is_admin, $current_company) {

            if (@$is_admin) {
                // dd($company_code);
                if (@$company_code) {
                    $q->where('contract_object->company->code', $company_code);
                }
            } else {
                $q->where('contract_object->company->code', (int)$current_company);
            }
        });
        if ($year_month) {
            $query->whereYear('transaction_date', '=', date('Y', strtotime($year_month)))
                ->whereMonth('transaction_date', '=', date('m', strtotime($year_month)));
        }
        return $query;
    }

    /**
     * @param $company_code
     * @param $year_month
     */
    public static function exportByBranch(int $company_code, $year_month)
    {
        $sql = "SELECT
                    transaction_object->>'$.branch_department.code' as branch_code,
                    transaction_object->>'$.branch_department.short_name' as branch_short_name,
                    transaction_object->>'$.branch_department.name_en' as branch_name_en,
                    transaction_object->>'$.company.short_name' as company_short_name,
                    transaction_date,
                    count(transactions.staff_personal_info_id) as num_of_staff,
                    sum(c.contract_object->>'$.salary') as gross_base_salary,
                    (sum(c.contract_object->>'$.salary')/2) as half_month,
                    sum(transaction_object->>'$.gross_base_salary') as gross_basic_salary,
                    sum(transaction_object->>'$.amount') as net_half_month,
                    sum(if(spi.gender = 1, 1, 0)) as male,
                    sum(if(spi.gender = 0, 1, 0)) as female
                from
                    `transactions`
                join staff_personal_info spi on
                    spi.id = transactions.staff_personal_info_id
                join contracts c on c.id = contract_id
            where
                exists (
                    select
                        *
                    from
                        `contracts`
                    where
                        `transactions`.`contract_id` = `contracts`.`id`
                        and `contract_object`->'$.company.code' = ?
                        and `contracts`.`deleted_at` is null
                )
            and `transaction_code_id` = ".TRANSACTION_CODE['HALF_SALARY'];

            if ($year_month) {
                $sql .= " and year(`transaction_date`) = '".date('Y', strtotime($year_month)) ."'";
                $sql .= " and month(`transaction_date`) = '".date('m', strtotime($year_month)) ."'";
            }

            $sql .= " and (transaction_object->>'$.is_block' is null
                    or transaction_object->>'$.is_block' = 'false'
                    or transaction_object->>'$.is_block' = 'null')
                and `transactions`.`deleted_at` is null
                group by transaction_object->>'$.branch_department.code'";

        return DB::select(DB::raw($sql), [$company_code]);
    }


    /**
     * * Export payroll full month by company
     * @param int $company_code
     * @param string $year_month
     */
    public static function exportPayrollByCompany($company_code, $year_month)
    {
        $unpaidLeave = TRANSACTION_CODE['UNPAID_LEAVE'];
        $staffLoanPaid = TRANSACTION_CODE['STAFF_LOAN_PAID'];
        $insurancePay = TRANSACTION_CODE['INSURANCE_PAY'];
        $maternityLeave = TRANSACTION_CODE['MATERNITY_LEAVE'];
        $salaryDeduction = TRANSACTION_CODE['SALARY_DEDUCTION'];
        $netSalary = TRANSACTION_CODE['NET_SALARY'];
        $fullPay = TRANSACTION_CODE['FULL_SALARY'];
        $pensionFund = TRANSACTION_CODE['PENSION_FUND'];
        $salaryBeforeTax = TRANSACTION_CODE['SALARY_BEFORE_TAX'];
        $taxOnSalary = TRANSACTION_CODE['TAX_ON_SALARY'];
        $salaryAfterTax = TRANSACTION_CODE['SALARY_AFTER_TAX'];

        $overtime = TRANSACTION_CODE['OVERTIME'];
        $spouse = TRANSACTION_CODE['SPOUSE'];
        $pchumAndNewYearBonue = TRANSACTION_CODE['BONUS_PCHUM_BEN_AND_NEW_YEAR'];
        $incentive = TRANSACTION_CODE['INCENTIVE'];
        $locationAllowance = TRANSACTION_CODE['LOCATION_ALLOWANCE'];
        $foodAllowance = TRANSACTION_CODE['FOOD_ALLOWANCE'];
        $thirdSalaryBonus = TRANSACTION_CODE['THIRD_SALARY_BONUS'];
        $degreeAllowance = TRANSACTION_CODE['DEGREE_ALLOWANCE'];
        $positionAllowance = TRANSACTION_CODE['POSITION_ALLOWANCE'];
        $attendanceAllowance = TRANSACTION_CODE['ATTENDANCE_ALLOWANCE'];
        $fringeAllowance = TRANSACTION_CODE['FRINGE_ALLOWANCE'];
        $taxOnFringeAllowance = TRANSACTION_CODE['TAX_ON_FRINGE_ALLOWANCE'];

        $retroactiveSalary = TRANSACTION_CODE['RETROACTIVE_SALARY'];
        $seniorityPay = TRANSACTION_CODE['SENIORITY_PAY'];
        $nssf = TRANSACTION_CODE['NSSF'];

        $year = date('Y', strtotime($year_month));
        $month = date('m', strtotime($year_month));

        $sql = "SELECT
                    tt1.id,
                    concat(spi.last_name_en, ' ', spi.first_name_en) as staff_full_name,
                    if(spi.gender=0, 'M', 'F') as sex,
                    c.contract_object->>'$.position.name_en' as staff_position,
                    c.contract_object->>'$.salary' as contract_salary,
                    spi.bank_acc_no,
                    c.contract_object->>'$.branch_department.name_en' as staff_location,
                    spi.dob,
                    spi.staff_id,
                    c.start_date as staff_effective_date,
                    tt1.contract_id,
                    tt1.transaction_code_id,
                    tt1.transaction_object,
                    tt1.transaction_date,
                    tt1.transaction_object->>'$.amount' as net_salary,                                                                    
                    tt1.transaction_object->>'$.half_pay' as half_salary, 
                    tt1.transaction_object->>'$.is_block' as is_block,      
                    
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id in ($unpaidLeave,$maternityLeave,$salaryDeduction)) as total_deduction,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id in ($overtime,$pchumAndNewYearBonue,$incentive,$locationAllowance, $foodAllowance, $thirdSalaryBonus, $degreeAllowance, $positionAllowance, $attendanceAllowance)) as total_allowance,
                    
                    (select transaction_object->>'$.amount' from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$salaryBeforeTax limit 1) as salary_before_tax,
                    (select transaction_object->>'$.amount' from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$taxOnSalary limit 1) as tax_on_salary,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id in ($taxOnSalary, $taxOnFringeAllowance)) as total_tax_payable,
                    (select transaction_object->>'$.amount' from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$salaryAfterTax limit 1) as salary_after_tax,
                    (select transaction_object->>'$.amount' from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$fullPay limit 1) as full_salary,
                    
                    (select transaction_object from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$spouse) as spouse,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$retroactiveSalary) as retroactive_salary,
                    
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$nssf) as nssf,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$staffLoanPaid) as staff_loan_paid,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$insurancePay) as insurance_pay,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$pensionFund) as pension_fund,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$maternityLeave) as maternity_leave,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$unpaidLeave) as unpaid_leave,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$salaryDeduction) as salary_deduction,
                    
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$seniorityPay) as seniority_pay,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$fringeAllowance) as fringe_allowance,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$taxOnFringeAllowance) as tax_on_fringe_allowance,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$overtime) as overtime,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$pchumAndNewYearBonue) as pchumben_and_newyear_bonus,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$incentive) as incentive,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$locationAllowance) as location_allowance,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$foodAllowance) as food_allowance,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$thirdSalaryBonus) as third_salary_bonus,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$degreeAllowance) as degree_allowance,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$positionAllowance) as position_allowance,
                    (select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$attendanceAllowance) as attendance_allowance,
                    tt1.transaction_object->>'$.gross_base_salary' as gross_basic_salary
                    
                from transactions tt1
                inner join contracts c
                    on c.id=tt1.contract_id
                inner join staff_personal_info spi
                    on spi.id=tt1.staff_personal_info_id
                where tt1.deleted_at is null ";

        if (@$company_code) {
            $sql .= " and c.contract_object->>'$.company.code'=$company_code";
        }

        if ($year_month) {
            $sql .= " and YEAR(tt1.transaction_date) = " . $year . " 
                    AND MONTH(tt1.transaction_date) = " . $month; 
        }

        $sql .= " and (tt1.transaction_object->>'$.is_block' is null or tt1.transaction_object->>'$.is_block'='false' or tt1.transaction_object->>'$.is_block'='null')";
        $sql .= " and tt1.transaction_code_id=$netSalary";
// print_r($sql);
        return DB::select($sql);
    }

    /**
     * * Export payroll full month by branch
     * @param int $company_code
     * @param string $year_month
     */
    public static function exportPayrollByBranch($company_code, $year_month)
    {
        $unpaidLeave = TRANSACTION_CODE['UNPAID_LEAVE'];
        $staffLoanPaid = TRANSACTION_CODE['STAFF_LOAN_PAID'];
        $insurancePay = TRANSACTION_CODE['INSURANCE_PAY'];
        $maternityLeave = TRANSACTION_CODE['MATERNITY_LEAVE'];
        $salaryDeduction = TRANSACTION_CODE['SALARY_DEDUCTION'];
        $netSalary = TRANSACTION_CODE['NET_SALARY'];
        $fullPay = TRANSACTION_CODE['FULL_SALARY'];
        $pensionFund = TRANSACTION_CODE['PENSION_FUND'];
        $salaryBeforeTax = TRANSACTION_CODE['SALARY_BEFORE_TAX'];
        $taxOnSalary = TRANSACTION_CODE['TAX_ON_SALARY'];
        $salaryAfterTax = TRANSACTION_CODE['SALARY_AFTER_TAX'];

        $overtime = TRANSACTION_CODE['OVERTIME'];
        $spouse = TRANSACTION_CODE['SPOUSE'];
        $pchumAndNewYearBonue = TRANSACTION_CODE['BONUS_PCHUM_BEN_AND_NEW_YEAR'];
        $incentive = TRANSACTION_CODE['INCENTIVE'];
        $locationAllowance = TRANSACTION_CODE['LOCATION_ALLOWANCE'];
        $foodAllowance = TRANSACTION_CODE['FOOD_ALLOWANCE'];
        $thirdSalaryBonus = TRANSACTION_CODE['THIRD_SALARY_BONUS'];
        $degreeAllowance = TRANSACTION_CODE['DEGREE_ALLOWANCE'];
        $positionAllowance = TRANSACTION_CODE['POSITION_ALLOWANCE'];
        $attendanceAllowance = TRANSACTION_CODE['ATTENDANCE_ALLOWANCE'];
        $fringeAllowance = TRANSACTION_CODE['FRINGE_ALLOWANCE'];
        $taxOnFringeAllowance = TRANSACTION_CODE['TAX_ON_FRINGE_ALLOWANCE'];

        $retroactiveSalary = TRANSACTION_CODE['RETROACTIVE_SALARY'];
        $seniorityPay = TRANSACTION_CODE['SENIORITY_PAY'];
        $nssf = TRANSACTION_CODE['NSSF'];

        $year = date('Y', strtotime($year_month));
        $month = date('m', strtotime($year_month));

        $sql = "SELECT
                    tt1.id,
                    tt1.transaction_object->>'$.branch_department.short_name' as branch_short_name,
                    tt1.transaction_object->>'$.branch_department.code' as branch_code,
                    tt1.transaction_object->>'$.branch_department.name_en' as branch_name,
                    tt1.transaction_object->>'$.company.short_name' as company_short_name,
                    tt1.transaction_date as transaction_date,
                    c.contract_object->>'$.salary' as contract_salary,
                    sum(if(spi.gender=0, 1,0)) as male,
                    sum(if(spi.gender=1, 1,0)) as female,
                    sum(tt1.transaction_object->>'$.amount') as net_salary,                                                                    
                    sum(tt1.transaction_object->>'$.half_pay') as half_salary, 

                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id in ($unpaidLeave,$maternityLeave,$salaryDeduction))) as total_deduction,
                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id in ($overtime,$pchumAndNewYearBonue,$incentive,$locationAllowance, $foodAllowance, $thirdSalaryBonus, $degreeAllowance, $positionAllowance, $attendanceAllowance))) as total_allowance,
                    
                    sum((select transaction_object->>'$.amount' from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$salaryBeforeTax limit 1)) as salary_before_tax,
                    sum((select transaction_object->>'$.amount' from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$taxOnSalary limit 1)) as tax_on_salary,
                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id in ($taxOnSalary, $taxOnFringeAllowance))) as total_tax_payable,
                    sum((select transaction_object->>'$.amount' from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$salaryAfterTax limit 1)) as salary_after_tax,
                    sum((select transaction_object->>'$.amount' from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$fullPay limit 1)) as full_salary,
                    
                    count((select transaction_object->>'$.spouse' from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$spouse)) as spouse,
                    sum((select transaction_object->>'$.spouse.children_no' from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$spouse)) as children_no,
                    sum((select transaction_object->>'$.amount' from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$spouse)) as spouse_amount,
                    
                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$retroactiveSalary)) as retroactive_salary,
                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$nssf)) as nssf,
                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$staffLoanPaid)) as staff_loan_paid,
                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$insurancePay)) as insurance_pay,
                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$pensionFund)) as pension_fund,
                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$maternityLeave)) as maternity_leave,
                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$unpaidLeave)) as unpaid_leave,
                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$salaryDeduction)) as salary_deduction,
                    
                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$seniorityPay)) as seniority_pay,
                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$fringeAllowance)) as fringe_allowance,
                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$taxOnFringeAllowance)) as tax_on_fringe_allowance,
                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$overtime)) as overtime,
                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$pchumAndNewYearBonue)) as pchumben_and_newyear_bonus,
                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$incentive)) as incentive,
                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$locationAllowance)) as location_allowance,
                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$foodAllowance)) as food_allowance,
                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$thirdSalaryBonus)) as third_salary_bonus,
                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$degreeAllowance)) as degree_allowance,
                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$positionAllowance)) as position_allowance,
                    sum((select sum(transaction_object->>'$.amount') from transactions t where t.deleted_at is null and YEAR(t.transaction_date)=$year and MONTH(t.transaction_date)=$month and t.contract_id=tt1.contract_id and t.transaction_code_id=$attendanceAllowance)) as attendance_allowance,
                    tt1.transaction_object->>'$.gross_base_salary' as gross_basic_salary
                    
                from transactions tt1
                inner join contracts c
                    on c.id=tt1.contract_id
                inner join staff_personal_info spi
                    on spi.id=tt1.staff_personal_info_id
                where tt1.deleted_at is null ";

        if (@$company_code) {
            $sql .= " and c.contract_object->>'$.company.code'=$company_code";
        }

        if ($year_month) {
            $sql .= " and YEAR(tt1.transaction_date) = " . $year . " 
                    AND MONTH(tt1.transaction_date) = " . $month; 
        }

        $sql .= " and (tt1.transaction_object->>'$.is_block' is null or tt1.transaction_object->>'$.is_block'='false' or tt1.transaction_object->>'$.is_block'='null')";
        $sql .= " and tt1.transaction_code_id=$netSalary group by tt1.transaction_object->>'$.branch_department.code' ";
// print_r($sql);
        return DB::select($sql);
    }

}
