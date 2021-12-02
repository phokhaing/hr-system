<?php

namespace Modules\PensionFund\Entities\Resources;

use Illuminate\Http\Resources\Json\Resource;

class StaffPersonalInfoFundResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $fullNameKh = @$this->last_name_kh . ' ' . @$this->first_name_kh;
        $fullNameEn = @$this->last_name_en . ' ' . @$this->first_name_en;
        return [
            "id" => @$this->id,
            "staff_id" => @$this->staff_id,
            "full_name_kh" => $fullNameKh,
            "full_name_en" => $fullNameEn,
            "display_staff_name" => @$this->staff_id . ' - ' . $fullNameKh . ' (' . $fullNameEn . ')'
        ];
    }
}
