<?php

namespace Modules\PensionFund\Entities\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ReportStaffClaimRequestPensionFundResource extends Resource
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
        $blockDateObj = @$this->json_data->block_date[0];
        $pensionFundInfo = @$this->json_data->pension_fund;
        $contractObj = @$contract->contract_object;

        return [
            "company_id_card" => substr(@$this->contract->staff_id_card, 3, (strlen(@$this->contract->staff_id_card))),
            "staff_full_name_kh" => @$personal_info->last_name_kh.' '.@$personal_info->first_name_kh,
            "gender" => @GENDER[@$personal_info->gender],
            "position" => @$contractObj['position']['name_kh'] ?? "N/A",
            "department_/_branch" => @$contractObj['branch_department']['name_kh'] ?? "N/A",
            "company" => @$contractObj['company']['name_kh'] ?? "N/A",
            "pension_fund_5%_staff" => @number_format(@$pensionFundInfo->acr_balance_staff),
            "pension_fund_company " => @number_format(@$pensionFundInfo->acr_balance_company),
            "interest_rate " => @$pensionFundInfo->interest_rate . "%",
            "total_benefit_after_tax" => @number_format(@$this->json_data->total_benefit_after_tax),
            "net_pay " => @number_format(@$this->json_data->net_pay),
            "claim_request_Date" => date('d-M-Y', strtotime(@$this->created_at))
        ];
    }
}
