<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ReportLastDayBlockSalaryResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $personal_info = @$this->staffPersonalInfo;
        $contractObj = @$this->contract_object;

        return [
            "staff_id" => @$personal_info->staff_id,
            "name_in_english" => @$personal_info->last_name_en . ' ' . @$personal_info->first_name_en,
            "Sex" => @GENDER_EN[@$personal_info->gender ?? '0'],
            "position" => @$contractObj['position']['name_en'],
            "location" => @$contractObj['branch_department']['name_en'],
            "D.O.E" => date('d-M-Y', strtotime(@$this->start_date)),
            "date_block" => date('d-M-Y', strtotime(@$contractObj['block_salary']['from_date'])),
            "remark" => @$contractObj['block_salary']['notice'] ?? 'N/A',
        ];
    }
}
