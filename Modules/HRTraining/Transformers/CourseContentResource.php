<?php

namespace Modules\HRTraining\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class CourseContentResource extends Resource
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
            "title" => @$this->json_data->title,
            "description" => @$this->json_data->description
        ];
    }
}
