<?php

use Carbon\Carbon;
use Modules\Payroll\Entities\PayrollSettings;

if (!function_exists('updateExchangeRate')) {
    function updateExchangeRate($exchange)
    {
        if (@$exchange) {
            $setting = PayrollSettings::where('type', PAYROLL_SETTING_TYPE['EXCHANGE_RATE'])->first();
            $setting->updateRecord($setting->id, [
                "json_data" => [
                    'exchange_rate' => @$exchange
                ]
            ]);
        }
    }
}

if (!function_exists('getExchangeRate')) {
    function getExchangeRate()
    {
        $setting = PayrollSettings::selectRaw('json_data->>"$.exchange_rate" as exchange_rate')
            ->where('type', PAYROLL_SETTING_TYPE['EXCHANGE_RATE'])
            ->first();
        return @$setting->exchange_rate ?? DEFAULT_EXCHANGE;
    }
}

if (!function_exists('convertSalaryFromStrToFloatValue')) {
    function convertSalaryFromStrToFloatValue($amount)
    {
        try {
            $salary = $amount ?? 0;
            if (str_contains($salary, ',')) {
                $salary = floatval(str_replace(",", "", @$salary));
            }
            return $salary;
        } catch (Exception $e) {
            return $amount;
        }
    }
}

if (!function_exists('isLastContract')) {
    /**
     * Staff that available to block salary (contract) must in active and current contract
     */
    function isLastContract($contract)
    {
        $contractObj = to_object(@$contract->contract_object);
        $contractLastDate = @$contractObj->contract_last_date ? Carbon::parse(@@$contractObj->contract_last_date) : Carbon::now();
        $now = Carbon::now();
        return @$contract->contract_type != CONTRACT_END_TYPE['RESIGN']
            && @$contract->contract_type != CONTRACT_END_TYPE['DEATH']
            && @$contract->contract_type != CONTRACT_END_TYPE['TERMINATE']
            && @$contract->contract_type != CONTRACT_END_TYPE['LAY_OFF']
            && (@$contractLastDate->greaterThan($now) || @$contractLastDate->equalTo($now));
    }
}

if (!function_exists('calculateSpouseAmount')) {
    function calculateSpouseAmount($spouse)
    {
        return SPOUSE_CHILD_TAX_INCLUDE_AMOUNT * countSpouseAmount($spouse);
    }
}

if (!function_exists('countSpouseAmount')) {
    function countSpouseAmount($spouse)
    {
        $countAmount = 0;
        //Spouse is include tax, spouse_tax=0 (include), 1(exclude)
        if (!@$spouse->spouse_tax) {
            $countAmount += 1;
        }

        if (@$spouse->children_tax) {
            $countAmount += @$spouse->children_tax;
        }

        return $countAmount;
    }
}

if (!function_exists('checkToRoundValue')) {
    /**
     * Check whenever to Round up amount in case: currency in KHR. 
     * Note: trasaction not tax on salary
     */
    function checkToRoundValue($currency, $amount)
    {
        if (
            $currency == STORE_CURRENCY_KHR
        ) {
            //EX: 150 to 200, 149 to 100
            $amount = round($amount, -2);
        }
        return $amount;
    }
}

if (!function_exists('getPayrollHalfMonthPeriod')) {
    function getPayrollHalfMonthPeriod()
    {
        $payrollSetting = PayrollSettings::where('type', PAYROLL_SETTING_TYPE['PAYROLL_DATE_BETWEEN'])->first();
        return @$payrollSetting->json_data->half_month;
    }
}

if (!function_exists('getPayrollFullMonthPeriod')) {
    function getPayrollFullMonthPeriod()
    {
        $payrollSetting = PayrollSettings::where('type', PAYROLL_SETTING_TYPE['PAYROLL_DATE_BETWEEN'])->first();
        return @$payrollSetting->json_data->full_month;
    }
}
