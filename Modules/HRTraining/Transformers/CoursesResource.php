<?php

namespace Modules\HRTraining\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class CoursesResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => (int)@$this->id,
            "cost" => (int)@$this->json_data->cost,
            "title" => @$this->json_data->title,
            "status" => @$this->json_data->status,
            "duration" => @$this->json_data->duration,
            "frequency" => @$this->json_data->frequency,
            "description" => @$this->json_data->description
        ];
    }
}
