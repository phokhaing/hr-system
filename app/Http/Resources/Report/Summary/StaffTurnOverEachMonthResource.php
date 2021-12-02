<?php

namespace App\Http\Resources\Report\Summary;

use Illuminate\Http\Resources\Json\Resource;

class StaffTurnOverEachMonthResource extends Resource
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
            "baranch_name"  => $this->branch_name,
            "branch_code"  => $this->branch_code,
            "january"  => $this->january,
            "february"  => $this->february,
            "march"  => $this->march,
            "april"  => $this->april,
            "may"  => $this->may,
            "june"  => $this->june,
            "july"  => $this->july,
            "auguest"  => $this->auguest,
            "september"  => $this->september,
            "october"  => $this->october,
            "november"  => $this->november,
            "december"  => $this->december,
            "male"      => ($this->total_staff - $this->total_female),
            "female"    => $this->total_female,
            "total_staff"  => $this->total_staff,
        ];
    }
}
