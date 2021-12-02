<?php

namespace App\Http\Resources\Report\Summary;

use Illuminate\Http\Resources\Json\Resource;

class SeniorityByPositionLevelResource extends Resource
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
            "position_level"   => $this->position_level,
            "less_than_3m"  => $this->less_than_3m,
            "month_3_to_6"  => $this->month_3_to_6,
            "month_6_to_12" => $this->month_6_to_12,
            "year_1_to_2"   => $this->year_1_to_2,
            "year_2_to_5"   => $this->year_2_to_5,
            "year_5_to_10"  => $this->year_5_to_10,
            "greater_than_10y"  => $this->greater_than_10y,
            "female"    => $this->total_female,
            "male"      => ($this->total_staff - $this->total_female),
            "total_staff"  => $this->total_staff,
        ];
    }
}
