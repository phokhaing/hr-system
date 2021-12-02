<?php

namespace Modules\Payroll\Http\Controllers;

use App\Contract;
use App\Exports\CheckingHalfPayrollExport;
use App\Exports\Report\half_month\ByBranchExport;
use App\Exports\Report\half_month\ByStaffExport;
use App\Exports\Report\half_month\ToBankExport;
use App\Exports\ReportHalfPayrollExport;
use App\Helper\HTTPStatus;
use App\Http\Controllers\BaseResponseController;
use App\Validations\ValidateResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Payroll\Entities\Payroll;
use Modules\Payroll\Entities\PayrollSettings;
use Modules\Payroll\Entities\TempPayroll;
use Modules\Payroll\Exports\TemplateDeductionExport;
use Modules\Payroll\Helpers\FindNewStaffForPayroll;
use Modules\Payroll\Traits\PayrollGenerator;
use Modules\Payroll\Transformers\PayrollResource;
use Modules\Payroll\Transformers\TempPayrollResource;
use Permissions;

class PostHalfMonthPayrollController extends BaseResponseController
{
    use PayrollGenerator;

    protected $objTempPayroll, $objPayroll;
    private $validateResponse;

    /**
     * PayrollController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objTempPayroll = new TempPayroll();
        $this->validateResponse = new ValidateResponse();
        $this->middleware('permission:checking_half_payroll', ['only' => ['checkingHalfMonth']]);
        $this->middleware('permission:post_half_payroll', ['only' => ['setHalfPayroll']]);
        $this->middleware('permission:download_half_payroll', ['only' => ['exportCheckingHalfPayroll']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        $payrollPeriodHalfMonth = getPayrollHalfMonthPeriod();
        if (@$payrollPeriodHalfMonth) {
            $startDate = @$payrollPeriodHalfMonth->start_date;
            $endDate = @$payrollPeriodHalfMonth->end_date;
            $currentDate = date('d');
            $isAvailable = $currentDate >= $startDate && $currentDate <= $endDate;

            if (!$isAvailable) {
                return redirect()->back()->withErrors(['0' => 'Sorry, Payroll half month available to check only in the day between ' . @$startDate . ' and ' . @$endDate . ' of month.']);
            }
        }

        return view('payroll::index');
    }

    /**
     * Store transaction of payroll. (Action confirmed post payroll)
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|string
     */
    public function postHalfPayroll(Request $request)
    {
        if (!$this->validateResponse->checkPermission(Permissions::POST_PAYROLL_HALF_MONTH)) {
            return $this->response(
                null,
                HTTPStatus::HTTP_AUTHORIZATION,
                'Sorry, you are not available to post payroll!'
            );
        }

        $temp_transactions = TempPayroll::search(@$request->company_code, @$request->branch_department_code, @$request->staff_personal_info_id)
            ->where('transaction_code_id', TRANSACTION_CODE['HALF_SALARY'])
            ->whereHas('contract', function ($query) {
                $query->where('contract_object->salary', '!=', null);
            });

        DB::beginTransaction();
        try {
            $tempTransactionItems = $temp_transactions->get();
            foreach ($tempTransactionItems as $key => $transaction) {
                $save = (new Payroll())->createRecord(
                    [
                        'staff_personal_info_id' => @$transaction->staff_personal_info_id,
                        'contract_id' => @$transaction->contract_id,
                        'transaction_code_id' => @$transaction->transaction_code_id,
                        'transaction_date' => @$transaction->transaction_date,
                        'transaction_object' => [
                            "ccy" => @$transaction->transaction_object->ccy,
                            "amount" => @$transaction->transaction_object->amount,
                            "exchange" => @$transaction->exchange,
                            "company" => @$transaction->transaction_object->company,
                            "branch_department" => @$transaction->transaction_object->branch_department,
                            "is_block" => @$transaction->transaction_object->is_block,
                            "gross_base_salary" => @$transaction->transaction_object->gross_base_salary
                        ]
                    ]
                );

                if (!$save) {
                    DB::rollBack();
                    return redirect()->back()->withErrors(['0' => 'Post half payroll unsuccessful.']);
                }
            }

            // After save payroll success we need to clear data in temp_transaction.
            $temp_transactions->delete();
            DB::commit();

            return $this->response(
                null,
                HTTPStatus::HTTP_SUCCESS,
                'Successfully post half monthly payroll!'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getHalfPayroll(Request $request)
    {
        $collection = Payroll::query()
            ->with(['staff_personal_info', 'contract'])
            ->search($request->company_code, $request->branch_department_code, $request->year_month)
            ->where('transaction_code_id', '=', TRANSACTION_CODE['HALF_SALARY'])
            ->whereRaw("(transaction_object->>'$.is_block' is null or transaction_object->>'$.is_block'='false' or transaction_object->>'$.is_block'='null')");

        $keyword = @$request->get('keyword');
        if (@$keyword) {
            $collection->byKeyword($keyword);
        }

        $collection = $collection->get();
        return PayrollResource::collection($collection);
    }

    /**
     * Save data into temp_payroll table.
     *
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function checkingHalfMonth(Request $request)
    {
        if (!$this->validateResponse->checkPermission(Permissions::CHECKING_PAYROLL_HALF_MONTH)) {
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
        $contractTempPayroll = Contract::getNoBlockForFinalyPay($payrollDate)
            ->getAllStaffToPostPayrollHalfMonth($payrollDate);

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
            ->checkingContractDidNotPostPayrollYet(date('m', strtotime($payrollDate)), date('Y', strtotime($payrollDate)), TRANSACTION_CODE['HALF_SALARY'])
            ->orderBy('start_date', 'asc')
            ->get();

        DB::beginTransaction();
        try {
            foreach ($contractTempPayroll as $contract) {

                $isNewStaff = new FindNewStaffForPayroll($contract, $payrollDate);
                //All new staffs match a condition are not available to check payroll half month
                if ($isNewStaff->isNotAvailableToCheckHalfMonth()) {
                    continue;
                } //Check whenever staff has promote,depromote,change location that contract start date (effective date) greather than current day must get previous contract base salary to calculate
                else if ($isNewStaff->checkIsOldStaffHasNewContract()) {
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
                } else if ($isNewStaff->checkIsContractIncoming()) {
                    continue;
                }

                //New salary has no use any more, can remove this condition
                //Check whenever staff has increase salary in current month, half_salary = old_salary/2
//                if (@$contract->newSalary) {
//                    $baseSalary = convertSalaryFromStrToFloatValue(@$contract->newSalary->object->old_salary);
//                } else {
//                    $baseSalary = convertSalaryFromStrToFloatValue(@$contract->contract_object['salary']);
//                }
                $baseSalary = convertSalaryFromStrToFloatValue(@$contract->contract_object['salary']);
                $halfAmount = $baseSalary / 2;
                $currency = @$contract->contract_object['currency'];
                if ($currency == STORE_CURRENCY_KHR) {
                    $halfAmount = round($halfAmount, -2);
                }

                $data = [
                    'contract_id' => @$contract->id,
                    'staff_personal_info_id' => @$contract->staff_personal_info_id,
                    'transaction_code_id' => TRANSACTION_CODE['HALF_SALARY'],
                    'transaction_date' => @$payrollDate,
                    'transaction_object' => [
                        'company' => @$contract->contract_object['company'],
                        'branch_department' => @$contract->contract_object['branch_department'],
                        'position' => @$contract->contract_object['position'],
                        'amount' => $halfAmount,
                        'ccy' => @$currency,
                        'gross_base_salary' => $baseSalary
                    ]
                ];

                $tempPayroll = TempPayroll::select('id')
                    ->findExistingTempTransaction(@$contract->id, $contract->staff_personal_info_id, TRANSACTION_CODE['HALF_SALARY'])
                    ->first();
                if (@$tempPayroll) {
                    $save = $tempPayroll->updateRecord($tempPayroll->id, $data);
                } else {
                    $tempPayroll = new TempPayroll();
                    $save = $tempPayroll->createRecord($data);
                }

                if (!$save) {
                    throw new Exception('There are some errors occur while posting payroll with transaction code [Half Month], Please re-check and verify that all information is not missing and try again!, check contract_id=[' . @$contract->id . ']');
                }
            }
            DB::commit();

            return $this->response(null, HTTPStatus::HTTP_SUCCESS, 'Checking payroll half monthly successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response(null, HTTPStatus::HTTP_FAIL, $e->getMessage());
        }
    }

    /**
     * Get temp half salary from temp_payroll table.
     *
     * @param Request $request
     */
    public function getCheckingHalfMonth(Request $request)
    {
        if (!$this->validateResponse->checkPermission(Permissions::VIEW_CHECKING_LIST_PAYROLL_HALF_MONTH)) {
            return $this->response(
                null,
                HTTPStatus::HTTP_AUTHORIZATION,
                'Sorry, you are not available to  view checking list payroll!'
            );
        }

        $collection = TempPayroll::query()
            ->with(['staff_personal_info', 'contract'])
            ->search($request->company_code, $request->branch_department_code, $request->staff_personal_info_id);

        $keyword = @$request->keyword;
        if (@$keyword) {
            $collection->whereHas('staff_personal_info', function ($query) use ($keyword) {
                $query->whereRaw("CONCAT(last_name_en, ' ' , first_name_en) LIKE ?", ["%$keyword%"])
                    ->orWhereRaw("CONCAT(last_name_kh, ' ' , first_name_kh) LIKE ?", ["%$keyword%"])
                    ->orWhereRaw("staff_id LIKE ?", ["%$keyword%"]);
            });
        }
        $collection = $collection->where('transaction_code_id', '=', TRANSACTION_CODE['HALF_SALARY'])
            ->get();

        $isDownload = $request->is_download;
        if ($isDownload) {
            return Excel::download(
                new CheckingHalfPayrollExport($collection),
                'CheckingHalfPayrollExport.xlsx'
            );
        } else {
            return $this->response(
                TempPayrollResource::collection($collection),
                HTTPStatus::HTTP_SUCCESS,
                'Success'
            );
        }
    }

    /**
     * Export report payroll half month.
     *
     * @param Request $request
     * @return \Exception|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportReportHalfPayroll(Request $request)
    {
        try {
            return Excel::download(
                new ReportHalfPayrollExport(
                    $request->company_code,
                    $request->branch_department_code,
                    $request->transaction_date
                ),
                'ReportHalfPayrollExport.xlsx'
            );
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Export deduction file template for upload into payroll deduction
     * in our system.
     *
     * @param Request $request
     * @return \Exception|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportDeductionTemplate(Request $request)
    {
        try {
            return Excel::download(
                new TemplateDeductionExport(
                    $request->company_code,
                    $request->branch_department_code,
                    TRANSACTION_CODE['BASE_SALARY']
                ),
                'deduction_template.xlsx'
            );
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function checkAvailableDate()
    {
        $payrollSetting = PayrollSettings::where('type', PAYROLL_SETTING_TYPE['PAYROLL_DATE_BETWEEN'])->first();
        if (@$payrollSetting) {
            $dateHalfMonth = @$payrollSetting->json_data->half_month;
            $startDate = Carbon::parse(@$dateHalfMonth->start_date);
            $endDate = Carbon::parse(@$dateHalfMonth->end_date);
            $currentDate = Carbon::now();
            $isAvailable = $currentDate->greaterThanOrEqualTo($startDate) && $currentDate->lessThanOrEqualTo($endDate);

            return $this->response(
                [
                    'is_available' => $isAvailable
                ],
                HTTPStatus::HTTP_FAIL,
                'Success'
            );
        }

        return $this->response(null, HTTPStatus::HTTP_FAIL, 'Something was wrong!');
    }

    /**
     * - Report_payroll_half_month_get_by_staff
     * @param mix $request
     */
    public function exportReportHalfPayrollByStaff(Request $request)
    {
        try {
            return Excel::download(
                new ByStaffExport($request->transaction_date, $request->company_codes, $request->is_temp_payroll),
                'Report_payroll_half_month_get_by_staff.xlsx'
            );
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * - Report_payroll_half_month_get_by_branch
     * @param mix $request
     */
    public function exportReportHalfPayrollByBranch(Request $request)
    {
        try {

            // $object = Payroll::exportByBranch(400, $request->transaction_date);
            // dd($object);

            return Excel::download(
                new ByBranchExport($request->transaction_date, $request->company_codes, $request->is_temp_payroll),
                'Report_payroll_half_month_get_by_branch.xlsx'
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
                    TRANSACTION_CODE['HALF_SALARY']),
                'Report_payroll_half_month_to_bank.xlsx'
            );
        } catch (\Exception $e) {
            return $e;
        }
    }

}
