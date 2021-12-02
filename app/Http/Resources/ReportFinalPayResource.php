<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ReportFinalPayResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // call from ORM in model
        $contract = $this->contract;
        $personal_info = @$this->staffPersonalInfo;
        $pensionFundInfo = @$this->json_data->pension_fund;
        $contractObj = @$contract->contract_object;

        return [ 
            "staff_id" => @$this->staffPersonalInfo->staff_id,
            "staff_full_name_kh" => @$personal_info->last_name_kh . ' ' . @$personal_info->first_name_kh,
            "gender" => @GENDER[@$personal_info->gender],
            "position" => @$contractObj['position']['name_kh'] ?? "N/A",
            "department_/_branch" => @$contractObj['branch_department']['name_kh'] ?? "N/A",
            "company" => @$contractObj['company']['name_kh'] ?? "N/A",
            "total_base_salary" => @number_format(@$this->json_data->total_base_salary),
            "pension_fund_5%_staff" => @number_format(@$pensionFundInfo->acr_balance_staff),
            "pension_fund_company " => @number_format(@$pensionFundInfo->acr_balance_company),
            "interest_rate " => @$pensionFundInfo->interest_rate * 100 . "%",
            "salary_before_tax" => @number_format(@$this->json_data->salary_before_tax),
            "tax_on_salary" => @number_format(@$this->json_data->tax_on_salary),
            "salary_after_tax" => @number_format(@$this->json_data->salary_after_tax),
            "half_pay" => @number_format(@$this->json_data->half_pay->amount),
            "net_pay " => @number_format(@$this->json_data->net_pay),
            "posted_date" => date('d-M-Y', strtotime(@$this->created_at))
        ];
    }
}
