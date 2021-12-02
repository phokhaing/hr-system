<?php

namespace App\Http\Controllers;

use App\Contract;
use App\Exports\FinalPayExport;
use App\Exports\ReportFinalPayExport;
use App\FinalPay;
use App\Helper\HTTPStatus;
use App\Http\Resources\ReportFinalPayResource;
use App\StaffInfoModel\StaffPersonalInfo;
use App\StaffInfoModel\StaffSpouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Payroll\Entities\Payroll;
use Modules\Payroll\Helpers\CalculateSalaryTax;
use Modules\Payroll\Helpers\FindRetroSalary;
use Modules\PensionFund\Entities\AutoCalculatePensionFund;
use Modules\PensionFund\Entities\PensionFunds;

class FinalPayController extends BaseResponseController
{
    public function index(Request $request)
    {
        $finalPayList = collect();
        if ($request->input('_token') != null) {
            $finalPayList = FinalPay::with(['contract', 'staffPersonalInfo']);

            $is_admin = \auth()->user()->is_admin;
            if (!$is_admin) {
                $companyCode = auth()->user()->company_code;
                $finalPayList->whereHas('contract', function ($query) use ($companyCode) {
                    $query->where('contract_object->company->code', (int)$companyCode);
                });
            }

            $keyword = @$request->input('keyword');
            if (@$keyword) {
                $finalPayList->whereHas('staffPersonalInfo', function ($q) use ($keyword) {
                    $q->whereRaw('CONCAT(last_name_kh, " ", first_name_kh) LIKE ?', ["%$keyword%"]);
                    $q->orWhereRaw('LOWER(CONCAT(last_name_en, " ", first_name_en)) LIKE ?', ["%$keyword%"]);
                    $q->orWhereRaw('staff_id LIKE ?', ["%$keyword%"]);
                    $q->orWhereRaw('phone LIKE ?', ["%$keyword%"]);
                });
            }

            $finalPayList = $finalPayList->orderBy('created_at', 'DESC')
                ->paginate(PER_PAGE);
        }
        return view('final_pay.index', compact('finalPayList'));
    }

    public function create()
    {
        $contracts = Contract::with(['staffPersonalInfo' => function ($q) {
            $q->select('id', 'first_name_en', 'last_name_en', 'first_name_kh', 'last_name_kh', 'staff_id');
        }])
            ->getAllStaffActive()
            ->where('contract_object->block_salary->is_block', 1)//Only staff who has been done last day/block salary available to checking final pay
            ->doesntHave('finalPay')//Staff already checking final pay who did not posted yet, could not available to checking again, but can edit
            ->get();
        return view('final_pay.create', compact('contracts'));
    }

    public function edit($id)
    {
        if (is_null(@$id)) {
            return redirect()->back()->withErrors(['0' => 'Sorry ID is incorrect!']);
        }
        $finalPay = FinalPay::with(['contract', 'staffPersonalInfo'])->find($id);
        return view('final_pay.edit', compact('finalPay'));
    }

    public function show($id)
    {
        if (is_null(@$id)) {
            return redirect()->back()->withErrors(['0' => 'Sorry ID is incorrect!']);
        }
        $finalPay = FinalPay::with(['contract', 'staffPersonalInfo'])->find($id);
        $disableEdit = true;
        return view('final_pay.edit', compact('finalPay', 'disableEdit'));
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'final_pay_id' => 'required',
            'contract_id' => 'required',
            'staff_personal_id' => 'required',
            'net_pay' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }
        $data = $this->getFinalPayData($request);
        $finalPay = new FinalPay();
        $saved = $finalPay->updateRecord(@$request->final_pay_id, $data);
        if ($saved) {
            return redirect()->route('final_pay.index')->with(['success' => 1]);
        } else {
            return back()->with('error', 'Sorry, Something was wrong!');
        }
    }

    public function delete(Request $request)
    {
        $finalPay = FinalPay::find($request->get('id'));
        $finalPay->deleted_at = date('Y-m-d H:i:s');
        $finalPay->deleted_by = \auth()->id();
        $deleted = $finalPay->save();
        return response()->json([
            'status' => isset($deleted),
            'data' => $deleted
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function getStaffInfo(Request $request)
    {
        $staff = StaffPersonalInfo::where('id', $request->id)
            ->select([
                'id',
                'first_name_en',
                'last_name_en',
                'first_name_kh',
                'last_name_kh',
                'marital_status',
                'gender'
            ])->first();

        $marital = @$staff['marital_status'];
        if ($marital) {
            $marital_status = MARITAL_STATUS[$marital];
        }

        $gender = @$staff['gender'];
        if ($gender) {
            $sex = GENDER[$gender];
        }

        $contract = Contract::find(@$request->contract_id);
        $contractObj = @$contract->contract_object;
        $currentPosition = @$contractObj["position"]["name_en"];
        $start_date = Carbon::parse(@$contract->start_date)->format('d-M-Y');
        $baseSalary = convertSalaryFromStrToFloatValue(@$contractObj['salary']);
        $currency = @$contractObj['currency'];

        $blockDateRange = collect(@$contractObj['block_salary']['date_range'])->map(function ($item) use ($baseSalary, $currency) {
            $earnedSalary = ($baseSalary / $item['days_in_month']) * $item['total_days'];
            if ($currency == STORE_CURRENCY_KHR) {
                $earnedSalary = round($earnedSalary, -2);
            }
            $item['earned_salary'] = $earnedSalary;
            return $item;
        });

        //Find Retro Salary in case new staff join during 20th-EndOfMonth of the previous month, and has last day/block salary in current month or next month
        //Equal (last_day_or_block_salary_date_from is next to contract_start_date one month), check cash staff_id=3563
        $findRetro = new FindRetroSalary($contract, @$contractObj['block_salary']['from_date']);
        $retroSalaryAmount = $findRetro->getRetroSalaryOfNewStaff();
        if ($retroSalaryAmount) {
            $contractStartDate = Carbon::parse($contract->start_date);
            $retroSalaryDesc = 'Retro Salary from '
                . $contractStartDate->day . ' - ' . $contractStartDate->endOfMonth()->day
                . ', ' . $contractStartDate->format('M')
                . ' ' . $contractStartDate->year;
            $retroSalaryTotalDays = $findRetro->getTotalDays();
        }

        //Find half month in case staff already opened half month salary base on first blocking date
        $year = date('Y', strtotime(@$contractObj['block_salary']['from_date']));
        $month = date('m', strtotime(@$contractObj['block_salary']['from_date']));
        $halfMonth = Payroll::where('transaction_code_id', TRANSACTION_CODE['HALF_SALARY'])
            ->where('contract_id', @$contract->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->first();
        if (@$halfMonth) {
            $halfMontDesc = 'Opened first salary in ' . date('d-M-Y', strtotime($halfMonth->transaction_date));
            $halfMonthAmount = @$halfMonth->transaction_object->amount;
        }

        $fullMonth = Payroll::where('transaction_code_id', TRANSACTION_CODE['NET_SALARY'])
            ->where('contract_id', @$contract->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->first();
        if (@$fullMonth) {
            $fullMontDesc = 'Opened full month salary in ' . date('d-M-Y', strtotime($fullMonth->transaction_date));
            $fullMonthAmount = @$fullMonth->transaction_object->amount;
        }

        //Find spouse from staff personnel
        $spouse = StaffSpouse::spouseTax(@$staff->id)->first();
        if (@$spouse) {
            $countSpouse = countSpouseAmount($spouse);
            $spouseAmount = calculateSpouseAmount($spouse);
        }
        $isCompanyPaidTax = @$contractObj['contract_object']['pay_tax_status'];

        //Get final pay in case from edit final pay page
        $finalPay = FinalPay::where('staff_personal_info_id', $staff->id)
            ->where('contract_id', @$contract->id)
            ->first();

        //Calculate Pension Fund
        $pensionFund = PensionFunds::findLastPensionFundByStaff(@$staff->id)->first();
        $calculatePf = new AutoCalculatePensionFund(@$staff->id);
        $acrBalanceCompany = $calculatePf->calculatePFFromCompany(@$contract->staff_id_card, $contractObj['company']['code']);
        $pensionFundFromCompany = checkToRoundValue($currency, @$acrBalanceCompany['total_acr_company']);
        //End calculate Pension Fund

        return $this->response(
            json_encode(
                [
                    'contract_id' => @$contract->id,
                    'staff_personal_info_id' => @$staff->id,
                    "staff" => @$staff,
                    "currentPosition" => @$currentPosition,
                    "start_date" => @$start_date,
                    "marital_status" => @$marital_status,
                    "sex" => @$sex,
                    "pensionfund" => @$pensionFund,
                    "acr_balance_company" => @$pensionFundFromCompany,
                    "balance_to_paid" => @$acrBalanceCompany['balance_to_paid'],
                    "interest_rate" => @$acrBalanceCompany['rate'],
                    "block_from_date" => @$contractObj['block_salary']['from_date'],
                    "block_until_date" => @$contractObj['block_salary']['until_date'],
                    "block_date_range" => $blockDateRange,
                    "base_salary" => $baseSalary,
                    "spouse_amount" => @$spouseAmount,
                    "spouse_count" => @$countSpouse,
                    "half_month_desc" => @$halfMontDesc,
                    "half_month_amount" => @$halfMonthAmount,
                    "full_month_desc" => @$fullMontDesc,
                    "full_month_amount" => @$fullMonthAmount,
                    "is_company_paid_tax" => @$isCompanyPaidTax,
                    "retro_salary_desc" => @$retroSalaryDesc,
                    "retro_salary_total_days" => @$retroSalaryTotalDays,
                    "retro_salary_amount" => @$retroSalaryAmount,
                    "currency" => @$currency,
                    "final_pay" => @$finalPay
                ]
            ),
            HTTPStatus::HTTP_SUCCESS,
            'Success!'
        );
    }

    public function calculateBlockSalaryDateRange($originalStart, $originalEnd)
    {
        $firstDate = date('M-Y', strtotime($originalStart));
        $secondDate = date('M-Y', strtotime($originalEnd));

        $fromDate = Carbon::parse($firstDate);
        $untilDate = Carbon::parse($secondDate);
        $months = [];
        do {
            $start = $fromDate->format('Y-m-d');
            $endDate = Carbon::parse($start);

            if ($endDate == $untilDate) {
                $end = Carbon::parse($originalEnd)->format('Y-m-d');
            } else {
                $end = $endDate->endOfMonth()->format('Y-m-d');
            }
            $totalDays = $fromDate->diffInDays(Carbon::parse($end)) + 1;

            $months[] = [
                'first_date_of_month' => $start,
                'last_date_of_month' => $end,
                'days_in_month' => $endDate->daysInMonth,
                'total_days' => $totalDays
            ];
        } while ($fromDate->addMonth() <= $untilDate);
        return $months;
    }

    public function checkingTax(Request $request)
    {
        //Calculate tax charge from salary before tax and save transaction
        $contract = Contract::find($request->contract_id);
        if (@$contract) {
            $calculateSalaryTaxCharge = new CalculateSalaryTax($contract, @$contract->contract_object['currency']);
            $taxCharge = $calculateSalaryTaxCharge->calculateTax($request->salary_before_tax, $request->spouse_amount);
            return $this->response(
                json_encode($taxCharge),
                HTTPStatus::HTTP_SUCCESS,
                'sucess'
            );
        }
        return $this->response(
            null,
            HTTPStatus::HTTP_FAIL,
            'Something went wrong when checking tax!'
        );
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'final_pay_id' => 'required',
            'contract_id' => 'required',
            'staff_personal_id' => 'required',
            'net_pay' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        $isAlreadyCleared = FinalPay::where('staff_personal_info_id', $request->staff_personal_id)
            ->where('contract_id', @$request->contract_id)
            ->first();
        if ($isAlreadyCleared) {
            return redirect()->back()->with('error', 'Sorry, This staff has been cleared final pay!');
        }
        $data = $this->getFinalPayData($request);
        $finalPay = new FinalPay();
        $saved = $finalPay->createRecord($data);
        if ($saved) {
            return redirect()->route('final_pay.index')->with(['success' => 1]);
        } else {
            return back()->with('error', 'Sorry, Something was wrong!');
        }
    }

    private function getFinalPayData(Request $request)
    {
        $contract = Contract::find($request->contract_id);
        $block_date = @$contract->contract_object['block_salary'];
        $baseSalary = convertSalaryFromStrToFloatValue(@$contract->contract_object['salary']);
        $currency = @$contract->contract_object['currency'];

        $dateRangeFrom = $this->calculateEarnFromSalary(
            @$block_date['date_range'],
            @$baseSalary,
            @$currency
        );
        $block_date['date_range'] = $dateRangeFrom['date_range_from'];
        $totalBaseSalary = $dateRangeFrom['total_base_salary'];

        $retroSalary = [
            'amount' => @$request->total_retro_salary,
            'total_days' => @$request->retro_salary_total_days,
            'desc' => @$request->retro_salary_desc,
        ];

        $leave_without_pay = [
            [
                "start_date" => $request->start_leave_1,
                "end_date" => $request->end_leave_1,
                "days" => $request->num_leave_1,
                "amount" => $request->total_leave_1,
            ],
            [
                "start_date" => $request->start_leave_2,
                "end_date" => $request->end_leave_2,
                "days" => $request->num_leave_2,
                "amount" => $request->total_leave_2,
            ],
            [
                "start_date" => $request->start_leave_3,
                "end_date" => $request->end_leave_3,
                "days" => $request->num_leave_3,
                "amount" => $request->total_leave_3,
            ],
            [
                "start_date" => $request->start_leave_4,
                "end_date" => $request->end_leave_4,
                "days" => $request->num_leave_4,
                "amount" => $request->total_leave_4,
            ],
            [
                "start_date" => $request->start_leave_5,
                "end_date" => $request->end_leave_5,
                "days" => $request->num_leave_5,
                "amount" => $request->total_leave_5,
            ]
        ];

        $figer_print = [
            "description" => $request->finger_print,
            "amount" => $request->total_finger_print,
        ];

        $specail_branch_alloance = [
            "description" => $request->alloance,
            "amount" => $request->total_alloance,
        ];

        $incentive = [
            "description" => $request->incentive,
            "amount" => $request->total_incentive,
        ];

        $bonus_kny = [
            "description" => $request->bonus_kny,
            "amount" => $request->total_bonus_kny,
        ];

        $bonus_pcb = [
            "description" => $request->num_bonus_pcb,
            "amount" => $request->total_bonus_pcb,
        ];

        $salaryBeforeTax = $request->total_total;

        $less_dependents = [
            "description" => $request->dependent,
            "amount" => $request->total_dependent,
            "number_of_spouse" => @$request->num_dependent
        ];

        $amount_taxable = $request->total_amount_taxable;

        $withholding_tax_on_salary = $request->total_tax;
        $withholding_tax_on_salary_rate = @$request->tax_on_salary_rate;

        $total_benefit_after_tax = $request->total_beneft_after;
        $wht_on_motorcycle_rental = @$request->total_wht_moto;

        $motorcycle_rental = [
            "start_date" => $request->start_moto,
            "end_date" => $request->end_moto,
            "amount" => $request->num_amount_moto,
            "days" => $request->total_day_moto,
            "total" => $request->total_moto,
        ];

        $gasoline = [
            "start_date" => $request->start_gasoline,
            "end_date" => $request->end_gasoline,
            "amount" => $request->num_amount_gasoline,
            "days" => $request->total_day_gasoline,
            "total" => $request->total_gasoline,
        ];

        $settlement = [
            "description" => $request->settlement,
            "amount" => $request->total_settlement,
        ];

        $compensation = [
            "description" => $request->compensation,
            "amount" => $request->total_compensation,
        ];

        if (@$request->pf_id) {
            $pension_fund = [
                "id" => @$request->pf_id,
                "pf_staff_description" => @$request->pf_staff,
                "pf_company_description" => @$request->pf_company,
                "acr_balance_staff" => @$request->total_pf_staff,
                "acr_balance_company" => @$request->total_pf_company,
                "interest_rate" => @$request->pf_company_interest_rate
            ];
        }

        $seniority = [
            "description" => $request->seniority,
            "amount" => $request->total_seniority,
        ];

        $telephone = [
            "description" => $request->telephone,
            "amount" => $request->total_telephone,
        ];

        $halfPay = [
            'amount' => @$request->half_month_amount,
            'description' => @$request->half_month
        ];

        $fullPay = [
            'amount' => @$request->full_month_amount,
            'description' => @$request->full_month
        ];

        $net_pay = $request->net_pay;
        $payTaxStatus = @$contract->contract_object['pay_tax_status']; //1 => company_paid, null or 0 => own paid

        $pfObj = [
            "block_date" => $block_date,
            "retro_salary" => $retroSalary,
            "leave_without_pay" => $leave_without_pay,
            "figer_print" => $figer_print,
            "specail_branch_alloance" => $specail_branch_alloance,
            "incentive" => $incentive,
            "bonus_kny" => $bonus_kny,
            "bonus_pcb" => $bonus_pcb,
            "less_dependents" => $less_dependents,
            "total_base_salary" => @$totalBaseSalary,
            "salary_before_tax" => $salaryBeforeTax,
            "salary_for_taxable" => $amount_taxable,
            "tax_on_salary" => $withholding_tax_on_salary,
            "tax_on_salary_rate" => @$withholding_tax_on_salary_rate,
            "salary_after_tax" => $total_benefit_after_tax,
            "half_pay" => $halfPay,
            "full_pay" => $fullPay,
            "wht_on_motorcycle_rental" => $wht_on_motorcycle_rental,
            "motorcycle_rental" => $motorcycle_rental,
            "gasoline" => $gasoline,
            "settlement" => $settlement,
            "compensation" => $compensation,
            "pension_fund" => @$pension_fund,
            "seniority" => $seniority,
            "telephone" => $telephone,
            "net_pay" => $net_pay,
            "pay_tax_status" => $payTaxStatus,
            "is_posted" => FINAL_PAY_STATUS['CHECKING']
        ];

        return [
            'staff_personal_info_id' => $request->staff_personal_id,
            'contract_id' => $request->contract_id,
            'json_data' => $pfObj,
        ];
    }

    public function report(Request $request)
    {
        try {
            $isDownload = $request->input('download');
            $companyCode = $request->input('company_code');
            $branchCode = $request->input('branch_department_code');
            $startDate = $request->input('request_date_from');
            $endDate = $request->input('request_date_to');
            $keyword = $request->input('keyword');

            $items = FinalPay::orderBy('id', ' desc')
                ->where('json_data->is_posted', FINAL_PAY_STATUS['POSTED'])
                ->with(['contract']);

            if ($keyword) {
                $items->whereHas('staffPersonalInfo', function ($q) use ($keyword) {
                    $q->where('staff_id', 'Like', '%' . $keyword . '%')
                        ->orWhere(DB::raw("CONCAT(last_name_kh, ' ', first_name_kh)"), 'LIKE', '%' . $keyword . '%')
                        ->orWhere(DB::raw("CONCAT(last_name_en, ' ', first_name_en)"), 'LIKE', '%' . $keyword . '%');
                })->orWhereHas('contract', function ($q) use ($keyword) {
                    $q->where("staff_id_card", 'LIKE', '%' . $keyword . '%');
                });
            }

            if ($startDate && $endDate) {
                $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
                $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
                $items->whereBetween('created_at', [$startDate, $endDate]);
            }

            if ($companyCode) {
                $items->whereHas('contract', function ($q) use ($companyCode) {
                    $q->where(DB::raw("contract_object->>'$.company.code'"), $companyCode);
                });
            }

            if ($branchCode) {
                $items->whereHas('contract', function ($q) use ($branchCode) {
                    $q->where(DB::raw("contract_object->>'$.branch_department.code'"), $branchCode);
                });
            }
            $items = $items->get();

            if ($isDownload) {
                return Excel::download(new ReportFinalPayExport($items), 'report_final_pay.xlsx');
            } else {
                return ReportFinalPayResource::collection($items);
            }
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function post(Request $request)
    {
        $updated = FinalPay::where('id', @$request->id)
            ->update([
                'json_data->is_posted' => FINAL_PAY_STATUS['POSTED'],
                'json_data->posted_date' => date('Y-m-d H:i:s'),
                'json_data->posted_by' => Auth::id()
            ]);

        //Auto book resign base on user book last day/block salary from current contract
        if ($updated) {
            $currentContract = Contract::find(@$request->contract_id);
            $currentContractObj = @$currentContract->contract_object;
            $newContractObj = [
                "company" => @$currentContractObj['company'],
                "branch_department" => @$currentContractObj['branch_department'],
                "position" => @$currentContractObj['position'],
                "salary" => @$currentContractObj['salary'],
                "currency" => @$currentContractObj['currency'],
                "contract_last_date" => date('Y-m-d H:i:s'),
                "from_final_id" => @$request->id
            ];

            //Bind value from last day/block salary info
            $blockInfo = @$currentContractObj['block_salary'];
            $contractDate = date('Y-m-d H:i:s', strtotime(@$blockInfo['until_date']));
            $newContractObj['reason'] = @$blockInfo['notice'];
            $newContractObj['transfer_work_to_staff'] = @$blockInfo['transfer_to_staff'];
            $newContractObj['file_reference'] = @$blockInfo['file_reference'];

            $newContractData = [
                "staff_id_card" => @$currentContract->staff_id_card,
                "staff_personal_info_id" => @$currentContract->staff_personal_info_id,
                "company_profile" => @$currentContract->company_profile,
                "contract_object" => $newContractObj,
                "created_by" => Auth::id(),
                "created_at" => date('Y-m-d H:i:s'),
                "updated_by" => Auth::id(),
                "updated_at" => date('Y-m-d H:i:s'),
                "start_date" => $contractDate,
                "end_date" => $contractDate,
                "contract_type" => @$blockInfo['contract_type']
            ];
            $newContract = new Contract();
            $isSaveNewContract = $newContract->createRecord($newContractData);

            //Update contract last date for remove previous contract from current contract
            if ($isSaveNewContract) {
                $updateRecord = $currentContract->contract_object;
                $updateRecord['contract_last_date'] = date('Y-m-d H:i:s', strtotime('-1 days'));
                $currentContract->contract_object = $updateRecord;
                $currentContract->save();
            }
        }
        return response()->json([
            'status' => isset($updated),
            'data' => $updated
        ]);
    }

    public function exportExcel($id)
    {
        $finalPay = FinalPay::with(['staffPersonalInfo', 'contract'])->find($id);
        if (@$finalPay) {
            $contract = @$finalPay->contract;
            $staffPersonalInfo = @$finalPay->staffPersonalInfo;
            $newDateRange = $this->calculateEarnFromSalary(
                @$contract->contract_object['block_salary']['date_range'],
                @$contract->contract_object['salary'],
                @$contract->contract_object['currency']
            )['date_range_from'];
            @$contract->contract_object->block_salary->date_range = $newDateRange;
            $sheetName = @$staffPersonalInfo->last_name_en . ' ' . @$staffPersonalInfo->first_name_en . '.xlsx';
            return Excel::download(new FinalPayExport(
                $finalPay,
                $contract,
                $newDateRange,
                $staffPersonalInfo
            ), $sheetName);
        } else {
            return back();
        }
    }

    private function calculateEarnFromSalary($dateRageFrom, $baseSalary, $currency)
    {
        $dataRange = [];
        $totalBaseSalary = 0;
        if (!is_null($dateRageFrom) && count($dateRageFrom)) {
            $baseSalary = convertSalaryFromStrToFloatValue($baseSalary);
            foreach (@$dateRageFrom as $key => $item) {
                $earnedSalary = (@$baseSalary / @$item['days_in_month']) * @$item['total_days'];
                if (@$currency == STORE_CURRENCY_KHR) {
                    $earnedSalary = round(@$earnedSalary, -2);
                }
                $totalBaseSalary += @$earnedSalary;
                $item['earned_salary'] = @$earnedSalary;
                $dataRange[] = @$item;
            }
        }
        return [
            'date_range_from' => $dataRange,
            'total_base_salary' => @$totalBaseSalary
        ];
    }
}
