<?php

namespace Modules\Payroll\Http\Controllers;

use App\Contract;
use App\Exports\Report\full_month\ByBranchExport;
use App\Exports\Report\full_month\ByStaffExport;
use App\Exports\Report\half_month\ToBankExport;
use App\Exports\ReportFullMonthPayrollExport;
use App\Helper\HTTPStatus;
use App\Http\Controllers\BaseResponseController;
use App\StaffInfoModel\StaffPersonalInfo;
use App\StaffInfoModel\StaffSpouse;
use App\Validations\ValidateResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Payroll\Entities\Payroll;
use Modules\Payroll\Entities\PayrollSettings;
use Modules\Payroll\Entities\TempPayroll;
use Modules\Payroll\Entities\TempTransactionUpload;
use Modules\Payroll\Exports\CheckingFullMonthPayrollExport;
use Modules\Payroll\Helpers\CalculateSalaryTax;
use Modules\Payroll\Helpers\FindNewStaffForPayroll;
use Modules\Payroll\Helpers\FindRetroSalary;
use Modules\Payroll\Traits\PayrollGenerator;
use Modules\Payroll\Transformers\PayrollFullMonthResource;
use Modules\Payroll\Transformers\TempTransactionResource;
use Modules\PensionFund\Entities\AutoCalculatePensionFund;
use Modules\PensionFund\Entities\PensionFunds;
use Permissions;

class PostFullMonthlyPayrollController extends BaseResponseController
{
    use PayrollGenerator;

    private $autoCalculatePf;
    private $validateResponse;

    public function __construct()
    {
        $this->middleware('auth');
        $this->autoCalculatePf = new AutoCalculatePensionFund();
        $this->validateResponse = new ValidateResponse();
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $payrollPeriodFullMonth = getPayrollFullMonthPeriod();
        if (@$payrollPeriodFullMonth) {
            $startDate = @$payrollPeriodFullMonth->start_date;
            $endDate = @$payrollPeriodFullMonth->end_date;
            $currentDate = date('d');
            $isAvailable = $currentDate >= $startDate && $currentDate <= $endDate;

            if (!$isAvailable) {
                return redirect()->back()->withErrors(['0' => 'Sorry, Payroll full month available to check only in the day between ' . @$startDate . ' and ' . @$endDate . ' of month.']);
            }
        }
        return view('payroll::index');
    }

    /**
     * Checking salary from contract to calculate all amount with tax deduction
     * @param Request $request
     */
    public function checkingContractPayroll(Request $request)
    {
        if (!$this->validateResponse->checkPermission(Permissions::CHECKING_PAYROLL_FULL_MONTH)) {
            return $this->response(
                null,
                HTTPStatus::HTTP_AUTHORIZATION,
                'Sorry, you are not available to checking payroll!'
            );
        }

        //Payroll date eqaul to post date or post back date
        if (@$request->payroll_date) {
            $payrollDate = date('Y-m-d', strtotime(@$request->payroll_date));
        } else {
            $payrollDate = date('Y-m-d');
        }

        $contractTempPayroll = Contract::with(['tempTransactionUploads' => function ($query) {
            return $query->where('created_by', auth::id());
        }])
            ->getNoBlockForFinalyPay($payrollDate)
            ->getAllStaffToPostPayrollFullMonth($payrollDate);

        //Checking by company    
        if (@$request->company_code) {
            $contractTempPayroll->whereRaw("contract_object->>'$.company.code'=$request->company_code");
        }

        //Checking by branch or department
        if (@$request->branch_department_code) {
            $contractTempPayroll->whereRaw("contract_object->>'$.branch_department.code'=$request->branch_department_code");
        }

        //Checking by staff
        if (@$request->staff_personal_info_id) {
            $contractTempPayroll->where('staff_personal_info_id', $request->staff_personal_info_id);
        }

        $contractTempPayroll = $contractTempPayroll
//            ->hasNewSalary($payrollDate) //New salary has no use any more, can remove this condition
            ->checkingContractDidNotPostPayrollYet(date('m', strtotime($payrollDate)), date('Y', strtotime($payrollDate)), TRANSACTION_CODE['NET_SALARY'])
            ->orderBy('start_date', 'asc')
            ->get();

        DB::beginTransaction();
        try {
            foreach ($contractTempPayroll as $contract) {
                $currency = @$contract->contract_object['currency'];

                //All new staffs match a condition are not available to check payroll full month
                $findNewStaff = new FindNewStaffForPayroll($contract, $payrollDate);
                if ($findNewStaff->isNotAvailableToCheckFullMonth()) {
                    continue;
                } //Check whenever staff has promote,depromote,change location that contract start date (effective date) greather than current day must get previous contract base salary to calculate
                else if ($findNewStaff->checkIsOldStaffHasNewContract()) {
                    //Find prevoius contract
                    $findPreviousContract = Contract::where('staff_personal_info_id', @$contract->staff_personal_info_id)
                        ->where('contract_object->company->code', @$contract->contract_object['company']['code'])
                        ->orderBy('id', 'DESC')
                        ->limit(2)
                        ->get();
                    if (@$findPreviousContract) {
                        $contract = @$findPreviousContract[1];
                    } else {
                        continue;
                    }
                } else if ($findNewStaff->checkIsContractIncoming()) {
                    continue;
                }

                //Find gross salary from each staff
                if (
                    $contract->contract_type == CONTRACT_ACTIVE_TYPE['FDC'] ||
                    $contract->contract_type == CONTRACT_ACTIVE_TYPE['UDC'] ||
                    $contract->contract_type == CONTRACT_ACTIVE_TYPE['DEMOTE'] ||
                    $contract->contract_type == CONTRACT_ACTIVE_TYPE['PROMOTE'] ||
                    $contract->contract_type == CONTRACT_ACTIVE_TYPE['CHANGE_LOCATION']
                ) {
                    //Find new staff in contract
                    if ($findNewStaff->isNewStaffInMonth()) {
                        //Find gross salary == (working_day * rate_perday)
                        $startDate = Carbon::parse($contract->start_date);
                        $endOfMonth = Carbon::parse($payrollDate)->endOfMonth();
                        $grossSalary = $this->calculateGrossSalaryOfWorkingDays(
                            $contract,
                            $startDate,
                            $endOfMonth
                        );
                    } //Otherwise is old staff
                    else {
                        //New salary has no use any more, can remove this condition
                        //Check whenever staff has increase salary in current month, gross_salary = old_salary + new_salary
//                        if (@$contract->newSalary) {
//                            $startOfMonth = Carbon::parse($payrollDate)->startOfMonth();
//                            $effectiveSalary = Carbon::parse(@$contract->newSalary->effective_date);
//                            $endOfMonth = Carbon::parse($payrollDate)->endOfMonth();
//
//                            $oldSalaryDays = $startOfMonth->diffInDays($effectiveSalary);
//                            $newSalaryDays = $effectiveSalary->diffInDays($endOfMonth) + 1; //Auto +1 day because the function diffInDays count between days ex: 16-30 = 14days, this will lost 1day
//
//                            $newSalaryObj = @$contract->newSalary->object;
//                            $oldSalary = (convertSalaryFromStrToFloatValue(@$newSalaryObj->old_salary) / $endOfMonth->day) * $oldSalaryDays;
//                            $newSalary = (@$newSalaryObj->new_salary / $endOfMonth->day) * $newSalaryDays;
//                            $grossSalary = @$oldSalary + @$newSalary;
//                            $grossSalary = checkToRoundValue($currency, $grossSalary);
//                        } else {
//                            $grossSalary = convertSalaryFromStrToFloatValue(@$contract->contract_object['salary']);
//                        }

                        $grossSalary = convertSalaryFromStrToFloatValue(@$contract->contract_object['salary']);
                    }
                }
                //Otherwise staff inactive (end contract) in current month
                //CONTRACT_END_TYPE['RESIGN'], CONTRACT_END_TYPE['DEATH'], CONTRACT_END_TYPE['TERMINATE'], CONTRACT_END_TYPE['LAY_OFF']
                else {
                    //Find gross salary == (working_day * rate_perday)
                    // $firstDateOfMonth = Carbon::now()->firstOfMonth();
                    $firstDateOfMonth = Carbon::parse($payrollDate)->firstOfMonth();
                    $contractEndDate = Carbon::parse($contract->end_date);
                    $grossSalary = $this->calculateGrossSalaryOfWorkingDays(
                        $contract,
                        $firstDateOfMonth,
                        $contractEndDate
                    );
                }

                //Half pay get from last payroll of half month
                $halfPay = $this->getHalfMonthTransaction($contract, $payrollDate);

                //Get retroactive salary from last month case new staff (20 to end of last month
                $findRetro = new FindRetroSalary($contract, $payrollDate);
                $retroactiveSalary = $findRetro->getRetroSalaryOfNewStaff();

                $this->calculateSalaryWithTax($contract, $grossSalary, $halfPay, $currency, @$retroactiveSalary, $payrollDate);
            }
            DB::commit();
            return $this->response(null, HTTPStatus::HTTP_SUCCESS, 'Checking payroll full monthly successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->response(null, HTTPStatus::HTTP_FAIL, $e->getMessage());
        }
    }

    public function getTempFullMonthPayroll(Request $request)
    {
        if (!$this->validateResponse->checkPermission(Permissions::VIEW_CHECKING_LIST_PAYROLL_FULL_MONTH)) {
            return $this->response(
                null,
                HTTPStatus::HTTP_AUTHORIZATION,
                'Sorry, you are not available to  view checking list payroll!'
            );
        }

        $unpaidLeave = TRANSACTION_CODE['UNPAID_LEAVE'];
        $staffLoanPaid = TRANSACTION_CODE['STAFF_LOAN_PAID'];
        $insurancePay = TRANSACTION_CODE['INSURANCE_PAY'];
        $maternityLeave = TRANSACTION_CODE['MATERNITY_LEAVE'];
        $salaryDeduction = TRANSACTION_CODE['SALARY_DEDUCTION'];
        $netSalary = TRANSACTION_CODE['NET_SALARY'];
        $fullPay = TRANSACTION_CODE['FULL_SALARY'];
        $pensionFund = TRANSACTION_CODE['PENSION_FUND'];
        $salaryBeforeTax = TRANSACTION_CODE['SALARY_BEFORE_TAX'];
        $salaryAfterTax = TRANSACTION_CODE['SALARY_AFTER_TAX'];
        $taxOnSalary = TRANSACTION_CODE['TAX_ON_SALARY'];

        $spouse = TRANSACTION_CODE['SPOUSE'];
        $overtime = TRANSACTION_CODE['OVERTIME'];
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
                    
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id in ($unpaidLeave,$maternityLeave,$salaryDeduction)) as total_deduction,
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id in ($overtime,$pchumAndNewYearBonue,$incentive,$locationAllowance, $foodAllowance, $thirdSalaryBonus, $degreeAllowance, $positionAllowance, $attendanceAllowance)) as total_allowance,
                    
                    (select transaction_object->>'$.amount' from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$fullPay limit 1) as full_salary,  
                    (select transaction_object->>'$.amount' from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$salaryBeforeTax limit 1) as salary_before_tax,
                    (select transaction_object->>'$.amount' from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$salaryAfterTax limit 1) as salary_after_tax,
                    (select transaction_object->>'$.amount' from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$taxOnSalary limit 1) as tax_on_salary,
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id in ($taxOnSalary, $taxOnFringeAllowance)) as total_tax_payable,            
                   
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$spouse) as spouse,
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$retroactiveSalary) as retroactive_salary,   
                    
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$nssf) as nssf,
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$pensionFund) as pension_fund,
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$staffLoanPaid) as staff_loan_paid,
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$insurancePay) as insurance_pay,
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$salaryDeduction) as salary_deduction,
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$maternityLeave) as maternity_leave,
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$unpaidLeave) as unpaid_leave,
                    
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$fringeAllowance) as fringe_allowance,
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$taxOnFringeAllowance) as tax_on_fringe_allowance,   
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$seniorityPay) as seniority_pay,
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$overtime) as overtime,
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$pchumAndNewYearBonue) as pchumben_and_newyear_bonus,
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$incentive) as incentive,
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$locationAllowance) as location_allowance,
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$foodAllowance) as food_allowance,
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$thirdSalaryBonus) as third_salary_bonus,
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$degreeAllowance) as degree_allowance,
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$positionAllowance) as position_allowance,
                    (select sum(transaction_object->>'$.amount') from temp_transactions t where t.deleted_at is null and t.contract_id=tt1.contract_id and t.transaction_code_id=$attendanceAllowance) as attendance_allowance,
                    
                    c.staff_id_card,                                                                         
                    c.contract_object->>'$.salary' as base_salary,
                    c.contract_object->>'$.position.name_kh' as position,
                    c.contract_object->>'$.company.name_kh' as company,                                                              
                    c.contract_object->>'$.branch_department.name_kh' as branch_department,                                                              
                    c.start_date,
                    
                    spi.first_name_en,
                    spi.last_name_en,
                    spi.staff_id
                    
                from temp_transactions tt1
                inner join contracts c
                    on c.id=tt1.contract_id
                inner join staff_personal_info spi
                    on spi.id=tt1.staff_personal_info_id
                where tt1.deleted_at is null";

        if (@$request->company_code) {
            $sql .= " and c.contract_object->>'$.company.code'=$request->company_code";
        }

        if (@$request->branch_department_code) {
            $sql .= " and c.contract_object->>'$.branch_department.code'=$request->branch_department_code";
        }

        if (@$request->staff_personal_info_id) {
            $sql .= " and c.staff_personal_info_id=$request->staff_personal_info_id";
        }

        $keyword = @$request->keyword;
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

        $sql .= " and tt1.transaction_code_id=$netSalary";


        if (@$request->is_download) {
            $sql .= " and (tt1.transaction_object->>'$.is_block' is null or tt1.transaction_object->>'$.is_block'='false' or tt1.transaction_object->>'$.is_block'='null')";
            $results = DB::select($sql);
            return Excel::download(
                new CheckingFullMonthPayrollExport(collect($results)),
                'checking_full_month_payroll.xlsx'
            );
        } else {
            $results = DB::select($sql);
            return $this->response([
                'exchange_rate' => getExchangeRate(),
                'items' => TempTransactionResource::collection(collect($results))
            ], HTTPStatus::HTTP_SUCCESS, 'Success');
        }
    }

    /**
     * Post final salary in monthly
     * @return \Illuminate\Http\JsonResponse
     */
    public function post(Request $request)
    {

        if (!$this->validateResponse->checkPermission(Permissions::POST_PAYROLL_FULL_MONTH)) {
            return $this->response(
                null,
                HTTPStatus::HTTP_AUTHORIZATION,
                'Sorry, you are not available to post payroll!'
            );
        }

        DB::beginTransaction();
        try {
            $temp_transactions = TempPayroll::with('contract')
                ->search(@$request->company_code, @$request->branch_department_code, @$request->staff_personal_info_id)
                ->select(['staff_personal_info_id', 'contract_id', 'transaction_code_id', 'transaction_object', 'created_at', 'transaction_date'])
                ->whereHas('contract', function ($query) {
                    $query->where('contract_object->salary', '!=', null);
                });

            foreach ($temp_transactions->get() as $key => $transaction) {

                //Check to save pension fund info into table pension fund
                if ($transaction->transaction_code_id == TRANSACTION_CODE['PENSION_FUND']) {
                    $deductPensionFund = $transaction->transaction_object->amount;
                    $acrBalanceStaff = $this->autoCalculatePf->calculateAcrBalanceStaff($transaction->staff_personal_info_id, $deductPensionFund);

                    $contractObject = @$transaction->contract->contract_object;
                    $data = [
                        "date_of_employment" => @$transaction->contract->start_date,
                        "effective_date" => @$transaction->contract->start_date,
                        "gross_base_salary" => @$contractObject['salary'],
                        "addition" => $deductPensionFund,
                        "acr_balance_staff" => $acrBalanceStaff,
                        "report_date" => date('d-m-Y', strtotime(@$transaction->transaction_date)), //Format this due to fit previous migrate data from excel
                    ];

                    $pensionFund = new PensionFunds();
                    $save = $pensionFund->createRecord([
                        'staff_personal_info_id' => @$transaction->staff_personal_info_id,
                        'contract_id' => @$transaction->contract_id,
                        'json_data' => $data,
                    ]);
                    if (!$save) {
                        throw new Exception('There are some errors occur while posting payroll with transaction code [Post Pension Fund], Please re-check and verify that all information is not missing and try again!, check contract_id=[' . $contract->id . ']');
                    }
                }

                $save = (new Payroll())->createRecord(
                    [
                        'staff_personal_info_id' => $transaction->staff_personal_info_id,
                        'contract_id' => $transaction->contract_id,
                        'transaction_code_id' => $transaction->transaction_code_id,
                        'transaction_date' => @$transaction->transaction_date,
                        'transaction_object' => $transaction->transaction_object
                    ]
                );

                if (!$save) {
                    DB::rollBack();
                    return response()->json(['status' => 0, 'message' => 'Post payroll full month unsuccessful!']);
                }

                //Must clear data from temp_transaction_uploads too, because this is a completed process to post payroll
                TempTransactionUpload::findCurrentTempTransactionUpload(
                    $transaction->staff_personal_info_id,
                    $transaction->contract_id,
                    $transaction->transaction_code_id
                )->delete();
            }

            // After save payroll success we need to clear data in temp_transaction.
            $temp_transactions->delete();
            DB::commit();

            return $this->response(
                null,
                HTTPStatus::HTTP_SUCCESS,
                'Successfully post full monthly payroll!'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response(
                null,
                HTTPStatus::HTTP_FAIL,
                $e->getMessage()
            );
        }
    }

    /**
     * Find gross salary from contract base on total working days in current month
     * Formula = (working_day * rate_perday)
     * rate_perday = (base_salary / total_days_of_month)
     * working_day = parse_to_number_of_days(date_one - date_two)
     */
    private function calculateGrossSalaryOfWorkingDays($contract, $dateOne, $dateTwo)
    {
        $totalWorkingDays = $dateOne->diffInDays($dateTwo) + 1; // pluse 1 because this function get only between value, ex: 20-30 = 9, but actual=10
        $salary = @$contract->contract_object['salary'] ?? '0';
        if (str_contains($salary, ',')) {
            $salary = floatval(str_replace(",", "", @$salary));
        }

        $grossSalary = (float)$salary;
        $salaryPerDay = $grossSalary / @$dateTwo->day;
        $amount = $salaryPerDay * $totalWorkingDays;

        if (@$contract->contract_object['currency'] == STORE_CURRENCY_KHR) {
            $amount = round($amount, -2);
        }
        return $amount;
    }

    /**
     * Half pay get from last payroll of half month
     */
    private function getHalfMonthTransaction($contract, $payrollDate)
    {
        $halfPayroll = Payroll::selectRaw("transaction_object->>'$.amount' as half_amount")
            ->where([
                'staff_personal_info_id' => @$contract->staff_personal_info_id,
                'contract_id' => @$contract->id,
                'transaction_code_id' => TRANSACTION_CODE['HALF_SALARY']
            ])
            ->whereMonth('transaction_date', '=', Carbon::parse($payrollDate)->month)
            ->whereYear('transaction_date', '=', Carbon::parse($payrollDate)->year)
            ->orderBy('id', 'DESC')
            ->first();
        return convertSalaryFromStrToFloatValue(@$halfPayroll->half_amount);
    }

    private function saveTransactionFromContract($transactionDate, $transactionCode, $contract, $amount, $currency, $halfPay = null, $spouse = null, $taxOnSalaryRate = null, $grossSalary = null): TempPayroll
    {
        $transactionObj = [
            "amount" => $amount,
            "exchange_rate" => getExchangeRate(),
            "currency" => $currency,
            "company" => $contract->contract_object['company'],
            "branch_department" => $contract->contract_object['branch_department']
        ];

        if ($transactionCode == TRANSACTION_CODE['NET_SALARY']) {
            $transactionObj['half_pay'] = $halfPay;
            $transactionObj['gross_base_salary'] = convertSalaryFromStrToFloatValue(@$contract['contract_object']['salary']);
            $transactionObj['gross_basic_salary'] = @$grossSalary;
        } else if (
            $transactionCode == TRANSACTION_CODE['SPOUSE']
            && @$spouse
        ) {
            $transactionObj['spouse'] = [
                'full_name' => @$spouse->full_name,
                'gender' => @$spouse->gender,
                'dob' => @$spouse->dob,
                'children_no' => @$spouse->children_no,
                'children_tax' => @$spouse->children_tax,
                'spouse_tax' => @$spouse->spouse_tax,
                'tax_on_exclude' => SPOUSE_CHILD_TAX_INCLUDE_AMOUNT
            ];
        } else if ($transactionCode == TRANSACTION_CODE['TAX_ON_SALARY']) {
            $transactionObj['tax_rate'] = @$taxOnSalaryRate;
        }

        $data = [
            'staff_personal_info_id' => $contract->staff_personal_info_id,
            'contract_id' => $contract->id,
            'transaction_code_id' => $transactionCode,
            'transaction_date' => $transactionDate,
            'transaction_object' => $transactionObj
        ];

        $existingTempTransaction = TempPayroll::findExistingTempTransaction(
            $contract->id,
            $contract->staff_personal_info_id,
            $transactionCode
        )
            ->first();
        if (@$existingTempTransaction) {
            return $existingTempTransaction->updateRecord($existingTempTransaction->id, $data);
        } else {
            $payRoll = new TempPayroll();
            return $payRoll->createRecord($data);
        }
    }

    /**
     * Calculate addition or deduction base on transaction code from salary after tax to find final salary (net salary)
     * return deduction amount
     */
    private function calculateAdditionOrDeductionAfterTax($contract): float
    {
        $additionalSalary = 0;
        foreach ($contract->tempTransactionUploads as $key => $transaction) {
            // Prevent all transactions code which will no longer to upload from excel, this should be used instance by other function
            if (
                TRANSACTION_CODE['SPOUSE'] == $transaction->transaction_code_id
                || TRANSACTION_CODE['NSSF'] == $transaction->transaction_code_id
                || TRANSACTION_CODE['SENIORITY_PAY'] == $transaction->transaction_code_id
                || TRANSACTION_CODE['FRINGE_ALLOWANCE'] == $transaction->transaction_code_id
            ) {
                continue;
            }

            $transactionObj = $transaction->transaction_object;
            $transactionCode = $transaction->transaction_code;
            if (@$transactionObj->before_or_after_tax == TRANSACTION_BEFORE_OR_AFTER_TAX['AFTER']) {
                if ($transactionCode->addition_or_deduction == TRANSACTION_CALCULATE_TYPE['ADDITION']) {
                    $additionalSalary += $transaction->transaction_object->amount;
                } else if ($transactionCode->addition_or_deduction == TRANSACTION_CALCULATE_TYPE['DEDUCTION']) {
                    $additionalSalary -= $transaction->transaction_object->amount;
                }
            }
        }
        return $additionalSalary;
    }

    /**
     * Check to delete all previous checking payroll by contract for re-calculate checking staff again
     */
    private function checkContractHasUploaded($contract)
    {
        $uploadedItems = $contract->tempTransactionUploads;
        if (@$uploadedItems && count($uploadedItems)) {
            TempPayroll::where('contract_id', @$contract->id)->delete();
        }
    }

    /**
     * Calculate all staffs have tax
     * @param $contract
     * @param $grossSalary
     * @param $halfPay => is amount of payroll half month paid to staff
     * @param $currency
     * @param retroactiveSalary is for new staff start working from last month
     * @throws Exception
     */
    private function calculateSalaryWithTax($contract, $grossSalary, $halfPay, $currency, $retroactiveSalary, $payrollDate)
    {
        $additionalSalary = 0;
        $this->checkContractHasUploaded($contract);
        //Iterate each temp_transaction table to calculate gross salary + (allowance, incentive, etc.) and other type of each transaction code and save one by one into real transaction table
        foreach ($contract->tempTransactionUploads as $key => $transaction) {
            $transactionObj = $transaction->transaction_object;
            $transactionCode = $transaction->transaction_code;

            // Prevent all transactions code which will no longer to upload from excel, this should be used instance by other function
            if (
                TRANSACTION_CODE['SPOUSE'] == $transaction->transaction_code_id
                || TRANSACTION_CODE['FRINGE_ALLOWANCE'] == $transaction->transaction_code_id
            ) {
                continue;
            }

            //For spouse deduct when find tax charge deduct from base salary
            if (
                TRANSACTION_CODE['SENIORITY_PAY'] != $transaction->transaction_code_id
                && TRANSACTION_CODE['NSSF'] != $transaction->transaction_code_id
                && @$transactionObj->before_or_after_tax == TRANSACTION_BEFORE_OR_AFTER_TAX['BEFORE']
            ) {
                //Calculate all transaction code include addition or deduction before tax
                if ($transactionCode->addition_or_deduction == TRANSACTION_CALCULATE_TYPE['ADDITION']) {
                    $additionalSalary += $transaction->transaction_object->amount;
                } else if ($transactionCode->addition_or_deduction == TRANSACTION_CALCULATE_TYPE['DEDUCTION']) {
                    $additionalSalary -= $transaction->transaction_object->amount;
                }
            }

            $data = [
                'contract_id' => $transaction->contract_id,
                'staff_personal_info_id' => $transaction->staff_personal_info_id,
                'transaction_code_id' => $transaction->transaction_code_id,
                'transaction_date' => $payrollDate,
                'transaction_object' => $transaction->transaction_object
            ];

            $tempPayroll = new TempPayroll();
            $tempPayroll->createRecord($data);
        }

        //Find seniority amount to separate add before or after tax
        $seniorityAmount = $this->calculateSeniority($contract, $currency);
        $additionalSalary = $additionalSalary + @$seniorityAmount['addition_before_tax'];
        $additionalSalary = checkToRoundValue($currency, $additionalSalary);

        //Save transaction (retroactive salary from previous month for new staff start day 20 to end of last month)
        if (@$retroactiveSalary) {
            $retroactiveSalary = checkToRoundValue($currency, $retroactiveSalary);
            $retroData = [
                'contract_id' => $contract->id,
                'staff_personal_info_id' => $contract->staff_personal_info_id,
                'transaction_code_id' => TRANSACTION_CODE['RETROACTIVE_SALARY'],
                'transaction_date' => $payrollDate,
                'transaction_object' => [
                    "amount" => $retroactiveSalary,
                    "currency" => $currency,
                    "company" => $contract->contract_object['company'],
                    "branch_department" => $contract->contract_object['branch_department'],
                    "remark" => 'Retroactive salary of new staff from previous month start from ' . @$contract->start_date
                ]
            ];

            $existingTempTransaction = TempPayroll::findExistingTempTransaction(
                $contract->id,
                $contract->staff_personal_info_id,
                TRANSACTION_CODE['RETROACTIVE_SALARY']
            )
                ->first();
            if (@$existingTempTransaction) {
                $isSaveRetroactive = $existingTempTransaction->updateRecord($existingTempTransaction->id, $retroData);
            } else {
                $tempPayrollTaxRetroactive = new TempPayroll();
                $isSaveRetroactive = $tempPayrollTaxRetroactive->createRecord($retroData);
            }

            if (!$isSaveRetroactive) {
                throw new Exception('There are some errors occur while posting payroll with transaction code [Retroactive Salary], Please re-check and verify that all information is not missing and try again!, check contract_id=[' . $contract->id . ']');
            }
        }

        //Save transaction (salary before tax)
        $salaryBeforeTax = $grossSalary + $additionalSalary + @$retroactiveSalary;
        $salaryBeforeTax = checkToRoundValue($currency, $salaryBeforeTax);
        $isSaveSalaryBeforeTax = $this->saveTransactionFromContract($payrollDate, TRANSACTION_CODE['SALARY_BEFORE_TAX'], $contract, $salaryBeforeTax, $currency);
        if (!$isSaveSalaryBeforeTax) {
            throw new Exception('There are some errors occur while posting payroll with transaction code [Salary Before Tax], Please re-check and verify that all information is not missing and try again!, check contract_id=[' . $contract->id . ']');
        }

        //Save transaction (spouse), to check spouse only staff has spouse
        $spouse = StaffSpouse::where('staff_personal_info_id', $contract->staff_personal_info_id)
            ->where(function ($query) {
                $query->where('spouse_tax', 0)
                    ->orWhere('children_tax', '>', 0);
            })
            ->first();
        $spouseAmount = 0;
        if (@$spouse) {
            $spouseAmount = calculateSpouseAmount($spouse);
            $isSaveSpouse = $this->saveTransactionFromContract($payrollDate, TRANSACTION_CODE['SPOUSE'], $contract, $spouseAmount, $currency, null, $spouse);

            if (!$isSaveSpouse) {
                throw new Exception('There are some errors occur while posting payroll with transaction code [Spouse], Please re-check and verify that all information is not missing and try again!, check contract_id=[' . $contract->id . ']');
            }
        }

        //Calculate tax charge from salary before tax and save transaction
        $calculateSalaryTaxCharge = new CalculateSalaryTax($contract, $currency);
        try {
            $taxArr = $calculateSalaryTaxCharge->calculateTax($salaryBeforeTax, @$spouseAmount);
            $taxCharge = $taxArr['tax_on_salary'];
            $taxRate = $taxArr['tax_rate'];
        } catch (Exception $e) {
            throw new Exception('There are some errors occur while calculate [Tax On Salary], Please re-check and verify that all information is not missing and try again!, check contract_id=[' . $contract->id . '] and ' . $e->getMessage());
        }

        $isSaveTaxCharge = $this->saveTransactionFromContract($payrollDate, TRANSACTION_CODE['TAX_ON_SALARY'], $contract, $taxCharge, $currency, null, null, $taxRate);
        if (!$isSaveTaxCharge) {
            throw new Exception('There are some errors occur while posting payroll with transaction code [Tax On Salary], Please re-check and verify that all information is not missing and try again!, check contract_id=[' . $contract->id . ']');
        }

        //Need to check and convert tax charge in USD back to find salary after tax
        $taxCharge = @$calculateSalaryTaxCharge->checkToCovertKhrSalaryToUsd(@$taxCharge);

        //Calculate fringe allowance with tax 20%
        $fringe = @$contract->tempTransactionUploads->filter(function ($transaction) {
            return TRANSACTION_CODE['FRINGE_ALLOWANCE'] == $transaction->transaction_code_id;
        })->first();

        $fring_allowance_tax_rate = PayrollSettings::where('type', PAYROLL_SETTING_TYPE['FRINGE_ALLOWANCE_TAX_RATE'])->first();

        if (@$fringe) {
            $fringeTax = @$fringe->transaction_object->amount * @$fring_allowance_tax_rate->json_data->rate;
            $data = [
                'contract_id' => $contract->id,
                'staff_personal_info_id' => $contract->staff_personal_info_id,
                'transaction_code_id' => TRANSACTION_CODE['FRINGE_ALLOWANCE'],
                'transaction_date' => $payrollDate,
                'transaction_object' => $fringe->transaction_object
            ];

            $existingTempTransactionFringAllowance = TempPayroll::findExistingTempTransaction(
                $contract->id,
                $contract->staff_personal_info_id,
                TRANSACTION_CODE['FRINGE_ALLOWANCE']
            )
                ->first();
            if (@$existingTempTransactionFringAllowance) {
                $isSaveFringAllowance = $existingTempTransactionFringAllowance->updateRecord($existingTempTransactionFringAllowance->id, $data);
            } else {
                $tempPayroll = new TempPayroll();
                $isSaveFringAllowance = $tempPayroll->createRecord($data);
            }
            if (!$isSaveFringAllowance) {
                throw new Exception('There are some errors occur while posting payroll with transaction code [Fringe Allowance], Please re-check and verify that all information is not missing and try again!, check contract_id=[' . $contract->id . ']');
            }

            //Tax on fringe allowance
            $dataFringAllowanceTax = [
                'contract_id' => $contract->id,
                'staff_personal_info_id' => $contract->staff_personal_info_id,
                'transaction_code_id' => TRANSACTION_CODE['TAX_ON_FRINGE_ALLOWANCE'],
                'transaction_date' => $payrollDate,
                'transaction_object' => [
                    "amount" => $fringeTax,
                    "rate" => @$fring_allowance_tax_rate->json_data->rate,
                    "exchange_rate" => getExchangeRate(),
                    "currency" => $currency,
                    "company" => $contract->contract_object['company'],
                    "branch_department" => $contract->contract_object['branch_department']
                ]
            ];
            $existingTempTransactionFringAllowanceTax = TempPayroll::findExistingTempTransaction(
                $contract->id,
                $contract->staff_personal_info_id,
                TRANSACTION_CODE['TAX_ON_FRINGE_ALLOWANCE']
            )
                ->first();
            if (@$existingTempTransactionFringAllowanceTax) {
                $isSaveTaxonFringeAllowance = $existingTempTransactionFringAllowanceTax->updateRecord($existingTempTransactionFringAllowanceTax->id, $dataFringAllowanceTax);
            } else {
                $tempPayrollTaxOnFringe = new TempPayroll();
                $isSaveTaxonFringeAllowance = $tempPayrollTaxOnFringe->createRecord($dataFringAllowanceTax);
            }
            if (!$isSaveTaxonFringeAllowance) {
                throw new Exception('There are some errors occur while posting payroll with transaction code [Tax on Fringe Allowance], Please re-check and verify that all information is not missing and try again!, check contract_id=[' . $contract->id . ']');
            }
        }

        //Calculate salary after tax and save transaction
        $salaryAfterTax = ($salaryBeforeTax + @$fringe->transaction_object->amount) - (@$taxCharge + @$fringeTax);
        $salaryAfterTax = checkToRoundValue($currency, $salaryAfterTax);
        $isSaveSalaryAfterTax = $this->saveTransactionFromContract($payrollDate, TRANSACTION_CODE['SALARY_AFTER_TAX'], $contract, $salaryAfterTax, $currency);
        if (!$isSaveSalaryAfterTax) {
            throw new Exception('There are some errors occur while posting payroll with transaction code [Salary After Tax], Please re-check and verify that all information is not missing and try again!, check contract_id=[' . $contract->id . ']');
        }
        $salaryAfterTax = $salaryAfterTax + @$seniorityAmount['addition_after_tax'];

        /**
         * Calculate pension fund and save transaction (PENSION_FUND)
         * Pension Fund only caluclate with SKP and Mekong company
         * This could has setting page later
         */
        $deductPensionFund = 0;
        $pension_fund = PayrollSettings::where('type', PAYROLL_SETTING_TYPE['PENSION_FOUND'])->first();
        $currentCompanyCode = @$contract->contract_object['company']['code'];
        if (in_array(@$currentCompanyCode, @$pension_fund->json_data->company_code)) {
            $grossBaseSalary = convertSalaryFromStrToFloatValue(@$contract->contract_object['salary']);
            $deductPensionFund = $this->autoCalculatePf->calculatePensionFundDeduction($contract->staff_id_card, @$currentCompanyCode, $grossBaseSalary, $payrollDate);
            if ($deductPensionFund > 0) {
                $deductPensionFund = checkToRoundValue($currency, $deductPensionFund);
                $isSavePensionFund = $this->saveTransactionFromContract($payrollDate, TRANSACTION_CODE['PENSION_FUND'], $contract, $deductPensionFund, $currency);
                if (!$isSavePensionFund) {
                    throw new Exception('There are some errors occur while posting payroll with transaction code [Pension Fund], Please re-check and verify that all information is not missing and try again!, check contract_id=[' . $contract->id . ']');
                }
            }
        }

        $additionOrDeductionAmount = $this->calculateAdditionOrDeductionAfterTax($contract);
        $additionOrDeductionAmount = checkToRoundValue($currency, $additionOrDeductionAmount);
        $fullSalary = ($salaryAfterTax + $additionOrDeductionAmount) - $deductPensionFund;
        $fullSalary = checkToRoundValue($currency, $fullSalary);
        //Check whenever current staff's contract is company paid, need to add tax charge back for net pay
        if ($calculateSalaryTaxCharge->checkIsCompanyPaidTax()) {
            $fullSalary += @$taxCharge;
        }

        //Deduct amount of half month paid back to get net pay of last month
        $isSaveFinalSalary = $this->saveTransactionFromContract($payrollDate, TRANSACTION_CODE['FULL_SALARY'], $contract, $fullSalary, $currency);
        if (!$isSaveFinalSalary) {
            throw new Exception('There are some errors occur while posting payroll with transaction code [Full Salary], Please re-check and verify that all information is not missing and try again!, check contract_id=[' . $contract->id . ']');
        }

        $netPay = $fullSalary - $halfPay;
        $netPay = checkToRoundValue($currency, $netPay);
        $isSaveFinalSalary = $this->saveTransactionFromContract($payrollDate, TRANSACTION_CODE['NET_SALARY'], $contract, $netPay, $currency, $halfPay, null, null, $grossSalary);
        if (!$isSaveFinalSalary) {
            throw new Exception('There are some errors occur while posting payroll with transaction code [NetPay Salary], Please re-check and verify that all information is not missing and try again!, check contract_id=[' . $contract->id . ']');
        }
        /**
         * End Calculate deduction and save final transaction (FULL_SALARY)
         */
    }

    /**
     * Special condition for seniority staff addition before or after text base on amount greater or lower than 1kUS (4MRiel)
     * @param $contract
     * @param $currency
     * @return array|int[]
     */
    private function calculateSeniority($contract, $currency)
    {
        $tempSeniority = @$contract->tempPayrolls->filter(function ($item) {
            if ($item->transaction_code_id == TRANSACTION_CODE['SENIORITY_PAY']) {
                return $item;
            }
        })->first();

        if (@$tempSeniority) {
            $amount = @$tempSeniority->transaction_object->amount;
            $seniority = PayrollSettings::where('type', PAYROLL_SETTING_TYPE['SENIORITY'])->first();
            if ($currency == STORE_CURRENCY_USD) {
                $compareAmount = @$seniority->json_data->usd_amount;
            } else {
                $compareAmount = @$seniority->json_data->khr_amount;
            }

            if ($amount > $compareAmount) {
                return [
                    'addition_before_tax' => $amount - $compareAmount,
                    'addition_after_tax' => $compareAmount
                ];
            } else {
                return [
                    'addition_before_tax' => 0,
                    'addition_after_tax' => $amount
                ];
            }
        }
        return [];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importIncentiveAndDeduction(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xls,xlsx',
        ]);

        DB::beginTransaction();
        try {

            $path1 = $request->file('excel_file')->store('temp');
            $path = storage_path('app') . '/' . $path1;

            $collection = Excel::toCollection(null, $path);
            $collectionFirstSheet = $collection[0]->forget(0)->filter(function ($item) {
                return !is_null(@$item[1]) && @$item[1] > 0;
            }); //Remove header and get first sheet and filter only records contain value

            /**
             * Check to delete all previous uploads by staff,contract for re-calculate checking staff again
             * Upload flow can upload new staff or existing staff for checking payroll,
             * this will never clear previous upload for case new upload
             */
            @$collectionFirstSheet->unique(1)->map(function ($item) {
                $staff_group_id = @$item[1];
                $staffPersonalInfo = StaffPersonalInfo::select('id')
                    ->with(['currentContract' => function ($q) {
                        return $q->select(['id', 'staff_personal_info_id']);
                    }])
                    ->where('staff_id', $staff_group_id)
                    ->whereHas('tempTransactionUploads')
                    ->first();
                if (@$staffPersonalInfo) {
                    $contractId = @$staffPersonalInfo->currentContract->id;
                    TempTransactionUpload::where('contract_id', $contractId)->delete();
                }
            });

            //Loop item to insert into temp_transaction_upload
            foreach ($collectionFirstSheet as $key => $row) {
                // Skip uncompleted data in each row.
                if (
                    is_null($row[1]) or
                    is_null($row[5]) or
                    is_null($row[6]) or
                    is_null($row[7]) or
                    is_null($row[8])
                ) {
                    continue;
                }

                $staff_group_id = (int)$row[1];
                $staffPersonalInfo = StaffPersonalInfo::with('currentContract')
                    ->where('staff_id', $staff_group_id)->first();

                $contract = @$staffPersonalInfo->currentContract;

                $transaction_arr = explode("-", @$row[5]);
                $transaction_id = @$transaction_arr[0];

                $deduction_status_arr = explode("-", @$row[6]);
                $deduction_status = (int)trim(@$deduction_status_arr[0]);

                $amount = trim(@$row[7]);
                $currency = trim(@$row[8]);
                $remark = trim(@$row[9]);

                if (@$currency == \STORE_CURRENCY_KHR && @$transaction_id != TRANSACTION_CODE['NSSF']) {
                    $amount = round(@$amount, -2);
                }

                $data = [
                    'contract_id' => @$contract->id,
                    'staff_personal_info_id' => @$contract->staff_personal_info_id,
                    'transaction_code_id' => @$transaction_id,
                    'transaction_object' => [
                        'amount' => @$amount,
                        'ccy' => @$currency,
                        'before_or_after_tax' => @$deduction_status,
                        'remark' => @$remark
                    ]
                ];
                $tempTran = new TempTransactionUpload();
                $tempTran->createRecord($data);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return response()->json(['status' => 'success']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getFullMonthPayrollReport(Request $request)
    {
        $transaction = new Payroll();
        $collection = $transaction->payrollReport(
            $request->company_code,
            $request->branch_department_code,
            $request->staff_personal_info_id,
            $request->year_month,
            true,
            @$request->keyword
        );
        // dd($collection);
        return PayrollFullMonthResource::collection(collect($collection));
    }

    public function exportFullMonthPayrollReport(Request $request)
    {
        try {
            return Excel::download(
                new ReportFullMonthPayrollExport(
                    $request->company_code,
                    $request->branch_department_code,
                    $request->transaction_date
                ),
                'ReportFullMonthPayrollExport.xlsx'
            );
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Preview file excel that was upload.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function previewDeductionFile(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xls,xlsx',
        ]);

        $path1 = $request->file('excel_file')->store('temp');
        $path = storage_path('app') . '/' . $path1;

        $collection = Excel::toCollection(null, $path);
        $collectionFirstSheet = $collection[0]->forget(0)->filter(function ($item) {
            return !is_null(@$item[1]) && @$item[1] > 0;
        }); //Remove header and get first sheet and filter only records contain value

        $data['total_row'] = $collectionFirstSheet->count();
        $data['total_staff'] = $collectionFirstSheet->unique(1)->count(); //unique(array_key)

        $data['total_amount'] = $collectionFirstSheet->reduce(function ($carry, $item) {
            if (@$item[1] > 0) {
                $carry = is_null(@$carry) ? 0 : @$carry;
                $amount = is_null(@$item[7]) ? 0 : @$item[7];

                $transaction_arr = explode("-", @$item[5]);
                $transaction_id = @$transaction_arr[0];
                $currency = trim(@$item[8]);
                if (@$currency == \STORE_CURRENCY_KHR && @$transaction_id != TRANSACTION_CODE['NSSF']) {
                    $amount = round(@$amount, -2);
                }

                return @$carry + @$amount;
            } else {
                return @$carry;
            }
        });

        return $this->response($data, $data && count($data) ? HTTPStatus::HTTP_SUCCESS : HTTPStatus::HTTP_FAIL);
    }

    /**
     * * Export report by company.
     * @param mix $request
     */
    public function exportFullMonthPayrollReportByStaff(Request $request)
    {
        try {
            // $payroll_object = Payroll::exportPayrollByCompany(400, '2021-06');
            return Excel::download(
                new ByStaffExport(
                    $request->transaction_date,
                    $request->company_codes,
                    $request->is_temp_payroll
                ),
                'Report_payroll_full_month_get_by_staff.xlsx'
            );
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function exportFullMonthPayrollReportByBranch(Request $request)
    {
        try {
            // $payroll_object = Payroll::exportPayrollByBranch(400, '2021-06');
            // dd($payroll_object);
            return Excel::download(
                new ByBranchExport(
                    $request->transaction_date,
                    $request->company_codes,
                    $request->is_temp_payroll
                ),
                'Report_payroll_full_month_get_by_branch.xlsx'
            );
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function exportReportToBank(Request $request)
    {
        try {
            return Excel::download(
                new ToBankExport($request->transaction_date,
                    $request->company_codes,
                    TRANSACTION_CODE['NET_SALARY']),
                'Report_payroll_full_month_to_bank.xlsx'
            );
        } catch (\Exception $e) {
            return $e;
        }
    }
}
