<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class DepartmentBranchResource extends Resource
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
            "id" => (int)@$this->id,
            "code" => (int)@$this->code,
            "short_name" => @$this->short_name,
            "name_en" => @$this->name_en,
            "name_kh" => @$this->name_km
        ];
    }
}
