<?php

namespace Modules\HRTraining\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class ExaminationResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $course_object = to_object(@$this->courseContent);
        return [
            'id' => $this->id,
            'course_content_title' => @$course_object->json_data->title, // user ORM for call title from course_contents
            'grade'  => @$this->json_data->grade,
            'duration' => @$this->json_data->duration,
            'description' => @$this->json_data->description,
            // 'created_at' => date('d-M-Y', strtotime(@$this->created_at)),
            // 'deleted_at' => date('d-M-Y', strtotime(@$this->deleted_at)),
        ];
    }
}
