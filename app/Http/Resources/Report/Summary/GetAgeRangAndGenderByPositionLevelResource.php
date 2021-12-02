<?php

namespace App\Http\Resources\Report\Summary;

use Illuminate\Http\Resources\Json\Resource;

class GetAgeRangAndGenderByPositionLevelResource extends Resource
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
            "position_level" => $this->position_level,
            "male" => ($this->total_staff - $this->total_female),
            "female" => $this->total_female,
            "<= 20" => $this->below_or_equal_20_y,
            "> 20-30" => $this->between_21_and_30_y,
            "> 30-40" => $this->between_31_and_40_y,
            "> 40-50" => $this->between_41_and_50_y,
            "> 50-55" => $this->between_51_and_55_y,
            "> 55" => $this->over_55_y,
            "total_staff" => $this->total_staff
        ];
    }
}
