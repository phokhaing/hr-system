<?php


namespace Modules\HRTraining\Http\Controllers;

use App\BranchesAndDepartments;
use App\Position;
use Illuminate\Routing\Controller;
use Modules\HRTraining\Entities\Categories;
use Modules\HRTraining\Entities\Courses;
use Modules\HRTraining\Entities\Filters\CourseFilter;
use Modules\HRTraining\Http\Requests\StoreCourseSettingRequest;
use Modules\HRTraining\Transformers\CourseContentResource;
use Modules\HRTraining\Transformers\CoursesResource;

class CourseSettingController extends Controller
{

    public function index()
    {
        $courses = Courses::withPosition()
            ->getCourseByUser()
            ->orderBy('id', 'DESC')
            ->latest()
            ->paginate();
        return view('hrtraining::course_setting.index')->with(compact('courses'));
    }

    public function create()
    {
        $categories = Categories::orderBy('id', 'DESC')->get();
        $positions = Position::orderBy('id', 'DESC')->get();
        $branchDepartments = BranchesAndDepartments::orderBy('id', 'DESC')->get();
        return view('hrtraining::course_setting.create')->with(
            compact('categories', 'positions', 'branchDepartments')
        );
    }

    public function store(StoreCourseSettingRequest $request)
    {
        $data = [
            'category_id' => $request->category,
            'branch_department_id' => $request->department,
            'json_data->title' => $request->course_title,
            'json_data->description' => $request->desc,
            'json_data->cost' => $request->cost,
            'json_data->status' => $request->status,
            'json_data->frequency' => $request->frequency,
            'json_data->position' => $request->position,
            'json_data->grade' => $request->grade,
            'json_data->duration' => $request->duration,
        ];

        $course = new Courses();
        $course->createRecord($data);

        return redirect()
            ->route('hrtraining::course.setting')
            ->with(['success' => 1]);
    }

    public function detail($id)
    {
        $course = Courses::with('branchDepartment')
            ->filter(new CourseFilter([
                'courseId' => $id
            ]))
            ->withDetail()
            ->first();
        return view('hrtraining::course_setting.show')->with(compact('course'));
    }

    public function delete($id)
    {
        $course = Courses::find($id);
        if ($course) {
            $course->delete();
            return redirect()
                ->back()
                ->with(['success' => 1]);
        }

        return redirect()
            ->back()
            ->with(['success' => 0]);
    }

    public function edit($id)
    {
        $course = Courses::with('branchDepartment')->filter(new CourseFilter([
            'courseId' => $id
        ]))->first();
        $categories = Categories::orderBy('id', 'DESC')->get();
        $positions = Position::orderBy('id', 'DESC')->get();
        return view('hrtraining::course_setting.edit')->with(compact('course', 'categories', 'positions'));
    }

    public function update(StoreCourseSettingRequest $request)
    {
        $data = [
            'category_id' => $request->category,
            'branch_department_id' => $request->department,
            'json_data->title' => $request->course_title,
            'json_data->description' => $request->desc,
            'json_data->cost' => $request->cost,
            'json_data->status' => $request->status,
            'json_data->frequency' => $request->frequency,
            'json_data->position' => $request->position,
            'json_data->grade' => $request->grade,
            'json_data->duration' => $request->duration,
        ];

        $course = new Courses();
        $updated = $course->updateRecord($request->course_id, $data);

        if ($updated) {
            return redirect()
                ->route('hrtraining::course.setting')
                ->with(['success' => 1]);
        }

        return redirect()
            ->back()
            ->with(['success' => 0]);
    }

    /**
     * Return resource API
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function lists()
    {
        $courses = Courses::getCourseByUser()
            ->orderBy('id', 'DESC')
            ->get();
        return CoursesResource::collection($courses);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function detailApi($id)
    {
        $contents = Courses::with('contents')->find($id);
//        dd($contents->contents);
        return CourseContentResource::collection($contents->contents);
    }

}