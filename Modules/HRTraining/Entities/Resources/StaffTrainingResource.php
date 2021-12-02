<?php


namespace Modules\HRTraining\Entities\Resources;


use Illuminate\Http\Resources\Json\Resource;

class StaffTrainingResource extends Resource
{
    public function toArray($request)
    {
        $staff = @$this->staff;
        $contract = @$this->contract;
        $company = @$contract->contract_object['company'];
        $department = @$contract->contract_object['branch_department'];
        $position = @$contract->contract_object['position'];
        $enrollment = @$this->enrollment;
        $course = @$enrollment->course;

        return [
            'company_id_card' => substr(@$contract->staff_id_card, 3, (strlen(@$contract->staff_id_card))),
            'staff_full_name' => @$staff->last_name_kh.' '.@$staff->first_name_kh,
            'gender' => @GENDER[$staff->gender],
            'company' => @$company['name_kh'],
            'department_/_branch' => @$department['name_kh'],
            'position' => @$position['name_kh'],
            'training_course' => @$course->json_data->title,
            'training_class' => @CLASS_TYPE_KEY[@$enrollment->class_type],
            'training_duration_(days)' => @$enrollment->json_data->duration,
            'training_start_date' => date('d/m/Y', strtotime(@$enrollment->json_data->start_date)),
            'training_end_date' => date('d/m/Y', strtotime(@$enrollment->json_data->end_date)),
            'trainee_progress' => getTraineeProgressStatus(@$this->training_status),
            'enrollment_date' => date('d/m/Y', strtotime(@$enrollment->created_at)),
        ];
    }

}