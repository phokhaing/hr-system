<?php

namespace Modules\HRTraining\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HRTraining\Entities\Exam;
use Modules\HRTraining\Entities\TraineeResult;
use Modules\HRTraining\Entities\Trainees;

class ExaminationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('hrtraining::examination_setting.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('hrtraining::examination_setting.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->input('data');
        $courseId = @$input['selectCourse'];
        $courseContentId = @$input['selectCourseContent'];

        //An exam with the same course and course content not allow duplicate
        $isExistExam = Exam::where('course_id', $courseId)
            ->where('course_contents_id', $courseContentId)
            ->first();
        if ($isExistExam) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Sorry, this course content is already has an exam. Please select other course content!',
                'data' => null
            ]);
        }

        $data = [
            'course_id' => $courseId,
            'course_contents_id' => $courseContentId,
            'json_data->description' => @ $input['description'],
            'json_data->duration' => @$input['duration'],
            'json_data->grade' => @$input['grade'],
        ];
        $exam = new Exam();
        $record = $exam->createRecord($data);

        return response()->json([
            'status' => 'success',
            'data' => $record
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('hrtraining::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('hrtraining::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function update(Request $request)
    {
        $input = $request->input('data');
        $data = [
            'course_id' => @$input['selectCourse'],
            'course_contents_id' => @$input['selectCourseContent'],
            'json_data->description' => @ $input['description'],
            'json_data->duration' => @$input['duration'],
            'json_data->grade' => @$input['grade'],
        ];
        $exam = new Exam();
        $update = $exam->updateRecord($input['exam_id'], $data);

        return response()->json([
            'status' => 'success',
            'data' => $update
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        Exam::destroy($id);
        return response()->json("deleted");
    }

    public function list()
    {
        $data = Exam::with('courseContent')
            ->getExamByUser()
            ->orderBy('id', 'desc')
            ->paginate(50);
        return response()->json([
            'data' => $data,
            'status' => 'success'
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function results()
    {
        $staff_personal_info_id = getStaffIdFromCurrentAuth();
        $trainees = Trainees::whereHas('traineeResult')->where('staff_personal_id', $staff_personal_info_id)->get();

        return view('hrtraining::staff_exam.my_exam', compact('trainees'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function detailResult($id)
    {
        $result_id = decrypt($id);
        $result = TraineeResult::with([
            'trainee',
            'trainee.staff',
            'trainee.company',
            'trainee.position',
            'trainee.enrollment',
            'trainee.enrollment.course'
        ])->find($result_id);

        $course = $result->trainee->enrollment->course;
        $enrollment = $result->trainee->enrollment;
        $staff = $result->trainee->staff;
        $trainee = $result->trainee;

        return view('hrtraining::staff_exam.detail', compact('course', 'enrollment', 'staff', 'trainee'));
    }
}
