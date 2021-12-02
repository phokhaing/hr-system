<?php


namespace Modules\PensionFund\Http\Controllers;


use App\Contract;
use App\Http\Controllers\Controller;
use App\StaffInfoModel\StaffPersonalInfo;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\PensionFund\Entities\AutoCalculatePensionFund;
use Modules\PensionFund\Entities\ClaimPensionFundHistories;
use Modules\PensionFund\Entities\PensionFunds;

class ClaimPensionFundController extends Controller
{
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function claim(Request $request)
    {
        $staff = StaffPersonalInfo::select('id', 'first_name_en', 'last_name_en', 'first_name_kh', 'last_name_kh', 'staff_id')->get();
        return view('pensionfund::claim_pension_fund.index', compact('staff'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function getInfo(Request $request)
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

        $currentContract = Contract::currentContract(@$staff->id)->first();
        $currentPosition = @$currentContract->contract_object["position"]["name_en"];

        $firstContract = Contract::firstContract(@$staff->id)->first()->start_date;
        $start_date = Carbon::parse(@$firstContract)->format('d-M-Y');

        $pensionFund = PensionFunds::findLastPensionFundByStaff(@$staff->id)->first();
        $calculatePf = new AutoCalculatePensionFund(@$staff->id);
        $acrBalanceCompany = $calculatePf->calculatePFFromCompany($currentContract->staff_id_card, $currentContract->contract_object['company']['code']);

        return json_encode([
            "staff" => @$staff,
            "currentPosition" => @$currentPosition,
            "start_date" => @$start_date,
            "marital_status" => @$marital_status,
            "sex" => @$sex,
            "pensionfund" => @$pensionFund,
            "acr_balance_company" => @$acrBalanceCompany['total_acr_company'],
            "balance_to_paid" => @$acrBalanceCompany['balance_to_paid'],
            "interest_rate" => @$acrBalanceCompany['rate'],
        ]);

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     */
    public function storeClaim(Request $request)
    {
        $isAlreadyClaim = ClaimPensionFundHistories::where('staff_personal_info_id', $request->staff_personal_id)->first();
        if ($isAlreadyClaim) {
            return back()->with('error', 'Sorry, This staff is already claimed!');
        }

        $block_date = [[
            "start_date" => $request->start_block_1,
            "end_date" => $request->end_block_1,
            "days" => $request->num_earn_1,
            "amount" => $request->total_earn_1,
        ],
            [
                "start_date" => $request->start_block_2,
                "end_date" => $request->end_block_2,
                "days" => $request->num_earn_2,
                "amount" => $request->total_earn_2,
            ],
            [
                "start_date" => $request->start_block_3,
                "end_date" => $request->end_block_3,
                "days" => $request->num_earn_3,
                "amount" => $request->total_earn_3,
            ],
            [
                "start_date" => $request->start_block_4,
                "end_date" => $request->end_block_4,
                "days" => $request->num_earn_4,
                "amount" => $request->total_earn_4,
            ],
            [
                "start_date" => $request->start_block_5,
                "end_date" => $request->end_block_5,
                "days" => $request->num_earn_5,
                "amount" => $request->total_earn_5,
            ]];

        $leave_without_pay = [[
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
            ]];

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

        $total = $request->total_total;

        $less_dependents = [
            "description" => $request->dependent,
            "amount" => $request->total_dependent,
        ];

        $amount_taxable = $request->total_amount_taxable;

        $withholding_tax_on_salary = $request->total_tax;

        $total_benefit_after_tax = $request->total_beneft_after;

        $motorcycle_rental = [
            "start_date" => $request->start_moto,
            "end_date" => $request->end_moto,
            "amount" => $request->num_amount_moto,
            "days" => $request->total_day_moto,
            "total" => $request->total_moto,
        ];

        $wht_motorcycle_rental = $request->num_wht_moto;

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

        $pension_fund = [
            "id" => $request->pf_id,
            "acr_balance_staff" => $request->total_pf_staff,
            "acr_balance_company" => $request->total_pf_company,
            "interest_rate" => $request->pf_company_interest_rate
        ];

        $seniority = [
            "description" => $request->seniority,
            "amount" => $request->total_seniority,
        ];

        $telephone = [
            "description" => $request->telephone,
            "amount" => $request->total_telephone,
        ];

        $net_pay = $request->net_pay;

        $pfObj = [
            "block_date" => $block_date,
            "leave_without_pay" => $leave_without_pay,
            "figer_print" => $figer_print,
            "specail_branch_alloance" => $specail_branch_alloance,
            "incentive" => $incentive,
            "bonus_kny" => $bonus_kny,
            "bonus_pcb" => $bonus_pcb,
            "total" => $total,
            "less_dependents" => $less_dependents,
            "amount_taxable" => $amount_taxable,
            "withholding_tax_on_salary" => $withholding_tax_on_salary,
            "total_benefit_after_tax" => $total_benefit_after_tax,
            "motorcycle_rental" => $motorcycle_rental,
            "gasoline" => $gasoline,
            "settlement" => $settlement,
            "compensation" => $compensation,
            "pension_fund" => $pension_fund,
            "seniority" => $seniority,
            "telephone" => $telephone,
            "net_pay" => $net_pay,
        ];

        ClaimPensionFundHistories::create([
            'staff_personal_info_id' => $request->staff_personal_id,
            'contract_id' => $request->contract_id,
            'json_data' => $pfObj,
            'created_by' => Auth::id(),
        ]);
        return back()->with(['success' => 1]);
    }
}