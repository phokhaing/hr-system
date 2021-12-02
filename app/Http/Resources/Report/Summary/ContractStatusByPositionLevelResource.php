<?php

namespace App\Http\Resources\Report\Summary;

use Illuminate\Http\Resources\Json\Resource;

class ContractStatusByPositionLevelResource extends Resource
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
            // "company_name"  => $this->company_name,
            "position_level"   => $this->position_level,
            "probation" => $this->probation,
            "one_year"  => $this->one_year,
            "two_year"  => $this->two_year,
            "regular"   => $this->regular,
            "female"    => $this->total_female,
            "male"      => ($this->total_staff - $this->total_female),
            "total"     => $this->total_staff
        ];
    }
}
