<?php


namespace Modules\HRTraining\Entities\Filters;


use Modules\HRTraining\Filters\AbstractListFilter;

class CourseFilter extends AbstractListFilter
{
    protected $filters = [
      'courseId'
    ];

    public function courseId($courseId){
        return $this->builder->where('id', $courseId);
    }

}