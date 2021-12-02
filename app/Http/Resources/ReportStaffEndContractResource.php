<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ReportStaffEndContractResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $contract_object = (object) @$this->contract_object;
        $personal_info = @$this->staffPersonalInfo;
        $flip_contract = array_flip(CONTRACT_TYPE);

        return [
            "id" => (int)@$this->id,
            "staff_id" => @$personal_info->staff_id,
            "full_name_kh" => @$personal_info->last_name_kh.' '.@$personal_info->first_name_kh,
            "full_name_en" => @$personal_info->first_name_en.' '.@$personal_info->last_name_en,
            "gender" => (GENDER[@$personal_info->gender]),
            "phone" => @$personal_info->phone,
            "company" => @$contract_object->company['name_kh'],
            "branch_department" => @$contract_object->branch_department['name_kh'],
            "position" => @$contract_object->position['name_kh'],
            "contract_type" => @$flip_contract[@$this->contract_type],
            "contract start date" => date('d-M-Y', strtotime(@$this->start_date)),
            "contract end date" => date('d-M-Y', strtotime(@$this->end_date)),
        ];
    }
}
