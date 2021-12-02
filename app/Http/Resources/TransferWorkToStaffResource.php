<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TransferWorkToStaffResource extends Resource
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
            "id" => @$this->id,
            "staff_code" => @$this->staff_code,
            "first_name_kh" => @$this->staffPersonalInfo->first_name_kh,
            "first_name_en" => @$this->staffPersonalInfo->first_name_en,
            "last_name_en" => @$this->staffPersonalInfo->last_name_en,
            "last_name_kh" => @$this->staffPersonalInfo->last_name_kh,
        ];
    }
}
