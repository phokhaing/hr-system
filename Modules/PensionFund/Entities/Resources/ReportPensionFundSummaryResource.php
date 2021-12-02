<?php

namespace Modules\PensionFund\Entities\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ReportPensionFundSummaryResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "no" => @$this->no,
            "company_code" => @$this->company_code,
            "company" => @$this->company_name,
            "department_/_branch" => @$this->department_branch,
            "total_pension_fund_5%_staff" => @number_format(@$this->total_pension_fund),
        ];
    }
}
