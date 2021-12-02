<?php

namespace Modules\PensionFund\Entities\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ReportPensionFundCurrentStaffResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "no" => @$this->no,
            "staff_id" => @$this->staff_id,
            "staff_full_name_kh" => @$this->last_name_kh . ' ' . @$this->first_name_kh,
            "gender" => @GENDER[@$this->gender],
            "position" => @$this->position_name ?? "N/A",
            "department_/_branch" => @$this->department_branch ?? "N/A",
            "company" => @$this->company ?? "N/A",
            "effective_date" => date('d-M-Y', strtotime(@$this->contract_start_date)),
            "total_pension_fund_5%_staff" => @number_format(@$this->total_pension_fund_staff),
            "total_pension_fund_company" => @number_format(@$this->total_acr_company),
            "balance_to_be_paid" => @number_format(@$this->balance_to_paid)
        ];
    }
}
