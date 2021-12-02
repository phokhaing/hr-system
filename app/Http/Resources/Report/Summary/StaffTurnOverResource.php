<?php

namespace App\Http\Resources\Report\Summary;

use Illuminate\Http\Resources\Json\Resource;

class StaffTurnOverResource extends Resource
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
            "branch_name" => $this->branch_name,
            "branch_code" => $this->branch_code,
            "total_active" => $this->total_active,
            "total_resigned" => $this->total_resigned,
            "turn_orver_rate" => ($this->total_active !== 0) ? ($this->total_resigned / $this->total_active) : 0,
            "resign_male" => ($this->total_resigned - $this->resign_female),
            "resign_female" => $this->resign_female
        ];
    }
}
