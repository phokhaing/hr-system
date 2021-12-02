<?php

namespace Modules\HRTraining\Transformers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CourseContentResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection;
    }
}
