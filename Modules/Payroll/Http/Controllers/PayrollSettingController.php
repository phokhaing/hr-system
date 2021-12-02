<?php

namespace Modules\Payroll\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Payroll\Entities\SysTaxParams;
use Modules\Payroll\Entities\PayrollSettings;
use App\Http\Controllers\BaseResponseController;
use Modules\PensionFund\Entities\PensionFundRate;

class PayrollSettingController extends BaseResponseController
{
    public function index()
    {
        $payrollSetting = PayrollSettings::get();
        $exhangeRate = $payrollSetting->where('type', PAYROLL_SETTING_TYPE['EXCHANGE_RATE'])->first();
        $pension_fund = $payrollSetting->where('type', PAYROLL_SETTING_TYPE['PENSION_FOUND'])->first();
        $payroll_half_month = $payrollSetting->where('type', PAYROLL_SETTING_TYPE['PAYROLL_HALF_MONTH'])->first();
        $payroll_date_between = $payrollSetting->where('type', PAYROLL_SETTING_TYPE['PAYROLL_DATE_BETWEEN'])->first();
        $fring_allowance_tax_rate = $payrollSetting->where('type', PAYROLL_SETTING_TYPE['FRINGE_ALLOWANCE_TAX_RATE'])->first();
        $seniority = $payrollSetting->where('type', PAYROLL_SETTING_TYPE['SENIORITY'])->first();
        $companies = Company::all();
        $taxtOnSalaries = SysTaxParams::orderBy('tax_object->tax_rate', 'ASC')->get();
        $pensionFundRateFromCompany = PensionFundRate::orderBy('json_data->rate', 'ASC')->get();
        $transaction_code = DB::select('select * from transaction_code');

        return view('payroll::settings.index', compact(
            'exhangeRate',
            'companies',
            'pension_fund',
            'payroll_half_month',
            'payroll_date_between',
            'fring_allowance_tax_rate',
            'taxtOnSalaries',
            'pensionFundRateFromCompany',
            'seniority',
            'transaction_code'
        ));
    }

    public function updateExchangeRate(Request $request)
    {
        $request->validate([
            'exchange_rate' => 'required|digits_between:1,5'
        ]);
        $setting = PayrollSettings::find($request->id);
        $setting->updateRecord($setting->id, [
            "json_data" => [
                'exchange_rate' => @$request->exchange_rate
            ]
        ]);

        return redirect()->back()->with(['success' => 1]);
    }

    public function updatePensionFund($id, Request $request)
    {
        $request->validate([
            'pension_fund' => 'required',
            'company_pension_fund' => 'required|array',
        ]);
        $setting = PayrollSettings::find($request->id);
        $setting->updateRecord($setting->id, [
            "json_data" => [
                'rate' => @$request->pension_fund,
                'company_code' => @$request->company_pension_fund,
            ]
        ]);

        return redirect()->back()->with(['success' => 1]);
    }

    public function updateHalfMonth($id, Request $request)
    {
        $request->validate([
            'company_half_month' => 'required|array',
        ]);
        $setting = PayrollSettings::find($id);
        $setting->updateRecord($setting->id, [
            "json_data" => [
                'company_code' => @$request->company_half_month,
            ]
        ]);

        return redirect()->back()->with(['success' => 1]);
    }

    public function updatePayrollPeriod($id, Request $request)
    {
        $request->validate([
            'half_start_date' => 'required|numeric',
            'half_end_date' => 'required|numeric',
            'full_start_date' => 'required|numeric',
            'full_end_date' => 'required|numeric',
        ]);
        $data["half_month"] = [
            'start_date' => $request->half_start_date,
            'end_date' => $request->half_end_date,
        ];
        $data["full_month"] = [
            'start_date' => $request->full_start_date,
            'end_date' => $request->full_end_date,
        ];
        $setting = PayrollSettings::find($id);
        $setting->updateRecord($setting->id, [
            "json_data" => $data
        ]);

        return redirect()->back()->with(['success' => 1]);
    }

    public function updateFringAllowance($id, Request $request)
    {
        $request->validate([
            'fring_allowance_tax_rate' => 'required',
        ]);
        $setting = PayrollSettings::find($id);
        $setting->updateRecord($setting->id, [
            "json_data" => [
                'rate' => $request->fring_allowance_tax_rate,
            ]
        ]);

        return redirect()->back()->with(['success' => 1]);
    }

    public function updateSeniority($id, Request $request) {
        $request->validate([
            'khr_amount' => 'required',
            'usd_amount' => 'required'
        ]);
        $setting = PayrollSettings::find($request->id);
        $setting->updateRecord($setting->id, [
            "json_data" => [
                'khr_amount' => @$request->khr_amount,
                'usd_amount' => @$request->usd_amount
            ]
        ]);

        return redirect()->back()->with(['success' => 1]);
    }
}
