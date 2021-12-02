<?php


namespace Modules\HRTraining\Entities\Resources;


use Illuminate\Http\Resources\Json\Resource;

class TrainingCourseByCategoryResource extends Resource
{
    public function toArray($request)
    {
        return [
            'staff_id' => @$this->staff_id,
            'staff_name' => @$this->last_name_en . ' ' . @$this->first_name_en,
            'course' => @$this->course_title,
            'category' => @$this->category_title,
            'branch_department' => @$this->branch_department_title . '(' . @$this->company_short_name . ')',
            'training_start_date' => date('d/m/Y', strtotime(@$this->start_date)),
            'training_end_date' => date('d/m/Y', strtotime(@$this->end_date)),
            'is_trained' => @$this->is_trained ? 'YES' : 'NO'
        ];
    }

}