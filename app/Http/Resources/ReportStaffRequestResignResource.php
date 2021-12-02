<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ReportStaffRequestResignResource extends Resource
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
        $personal_info = @$this->staffPersonalInfo;
        return [
            "id" => (int)@$this->id,
            "staff_id" => @$personal_info->staff_id,
            "full_name_kh" => @$personal_info->last_name_kh.' '.@$personal_info->first_name_kh,
            "full_name_en" => @$personal_info->first_name_en.' '.@$personal_info->last_name_en,
            "gender" => (GENDER[@$personal_info->gender]),
            "phone" => @$personal_info->phone,
            "company" => @$this->resign_object->company->name_kh,
            "branch_department" => @$this->resign_object->branch_department->name_kh,
            "position" => @$this->resign_object->position->name_kh,
            "request_date" => date('d-M-Y', strtotime(@$this->resign_object->request_date))
        ];
    }
}
