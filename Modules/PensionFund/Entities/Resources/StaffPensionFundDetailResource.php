<?php

namespace Modules\PensionFund\Entities\Resources;

use Illuminate\Http\Resources\Json\Resource;

class StaffPensionFundDetailResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $contract = $this->contract;
        $staffPersonalInfo = $this->staffPersonalInfo;
        $contractObj = @$contract->contract_object;
        return [
            "no" => @$this->no,
            "staff_id" => @$staffPersonalInfo->staff_id,
            "staff_full_name_kh" => @$staffPersonalInfo->last_name_kh . ' ' . @$staffPersonalInfo->first_name_kh,
            "gender" => @GENDER[@$staffPersonalInfo->gender],
            "position" => @$contractObj['position']['name_kh'] ?? "N/A",
            "department_/_branch" => @$contractObj['branch_department']['name_kh'] ?? "N/A",
            "company" => @$contractObj['company']['name_kh'] ?? "N/A",
            "date_of_employment" => date('d-M-Y', strtotime(@$this->json_data->date_of_employment)),
            "effective_date" => date('d-M-Y', strtotime(@$this->json_data->effective_date)),
            "base_salary" => @number_format(convertSalaryFromStrToFloatValue(@$this->json_data->gross_base_salary)),
            "addition" => @number_format(@$this->json_data->addition),
            "pension_fund_5%_staff" => @number_format(@$this->json_data->acr_balance_staff),
            "date" => date('d-M-Y', strtotime(@$this->json_data->report_date)),
        ];
    }
}
