<?php


namespace Modules\HRTraining\Http\Controllers;


use App\Contract;
use App\Http\Controllers\Controller;
use Modules\HRTraining\Entities\Categories;
use Modules\HRTraining\Entities\Enrollments;

class StaffTrainingController extends Controller
{
    public function getAllOrientationTrainingCourse($categoryId)
    {
        $contractFromCurrentLogin = Contract::selectRaw('contract_object->>"$.branch_department.id" as id')
            ->where('staff_personal_info_id', getStaffIdFromCurrentAuth())
            ->where("contract_object->contract_last_date", '>=', date("Y-m-d"))
            ->first();

        $enrollments = Enrollments::with(['course'])
            ->withCurrentTraineeFromLoginStaff()
            ->whereHas('course', function ($query) use ($categoryId, $contractFromCurrentLogin) {
                $query->where('category_id', @$categoryId)
                    ->where('branch_department_id', @$contractFromCurrentLogin->id);
            })
            ->orderBy('id', 'DESC')
            ->latest()
            ->paginate();
        $category = Categories::find(@$categoryId);

        return view('hrtraining::staff_training.view_all_course_training_event_by_category')
            ->with(compact('enrollments', 'category'));
    }
}