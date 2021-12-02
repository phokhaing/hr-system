<?php

namespace Modules\HRTraining\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HRTraining\Entities\Courses;
use Modules\HRTraining\Entities\Enrollments;
use Modules\HRTraining\Entities\Exam;
use Modules\HRTraining\Entities\ExamHistory;
use Modules\HRTraining\Entities\Trainees;
use Modules\HRTraining\Http\Requests\HRReviewExamRequest;

class HRReviewTrainingController extends Controller
{

    public function index()
    {

        $enrollments = Enrollments::with(['course'])
            ->where('status', TRAINING_CONSTANT_TYPE['ENROLLMENT_PROGRESS_COMPLETED'])
            ->orderBy('id', 'DESC')
            ->latest()
            ->paginate();

        return view('hrtraining::review_training_exam.index', compact('enrollments'));
    }

    /**
     * get all trainee from enrollment training course has been completed status (end date)
     * @param $enrollmentId
     */
    public function listTraineeInCurrentEnrollment(Request $request)
    {
        $enrollment = Enrollments::find(decrypt($request->enrollment_id));
        $trainees = Trainees::withTraineeExamResult($enrollment->id)
            ->where('enrollment_id', $enrollment->id)
            ->where('request_join_status', TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_APPROVED'])
            ->latest()
            ->paginate();
        return view('hrtraining::review_training_exam.list_trainee_completed_training',
            compact('trainees', 'enrollment'));
    }

    public function reviewTraineeExam(Request $request)
    {
        $enrollment = Enrollments::find(decrypt(@$request->enrollment_id));
        $course = Courses::find(@$request->course_id);
        $trainee = Trainees::find($request->trainee_id);

        $exams = Exam::with('questionAnswers')
            ->withExamHistoryByTrainee($enrollment->id, @$request->trainee_id)
            ->whereHas('examHistories', function ($query) use ($enrollment, $trainee) {
                $query->where('trainee_id', @$trainee->id)
                    ->where('enrollment_id', @$enrollment->id);
            })
            ->where('course_id', @$course->id)
            ->get();

        if (!$exams) {
            return back()->with('error', 'Sorry, Trainee doesn\'t take an exam yet!');
        }

        return view('hrtraining::review_training_exam.review_exams',
            compact('course', 'exams', 'trainee', 'enrollment'));
    }

    public function calculateExamResult(HRReviewExamRequest $request)
    {
        foreach (@$request->give_open_answers_point as $examHistoryId => $givePoint) {
            $examHistory = ExamHistory::find($examHistoryId);
            $examHistory->updateRecord($examHistoryId, [
                'json_data->answer_point' => $givePoint
            ]);
        }

        //Start Calculate total point of Trainee Exam Result
        $totalPoint = ExamHistory::where('enrollment_id', $request->enrollment_id)
            ->where('trainee_id', $request->trainee_id)
            ->sum('json_data->answer_point');

        updateTrainingStatus([
            'status' => TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_COMPLETED_TRAINING'],
            'id' => $request->trainee_id
        ]);

        createOrUpdateTraineeResult([
            'enrollment_id' => $request->enrollment_id,
            'trainee_id' => $request->trainee_id,
            'json_data->total_point' => $totalPoint
        ]);

        return redirect()->route('hrtraining::review_training.list_trainee_by_enrollment', ['enrollment_id' => encrypt($request->enrollment_id)])
            ->with(['success' => 'Exam Result is already created!']);
    }

    public function updateTraineeProgress(Request $request)
    {
        updateTrainingStatus([
            'status' => $request->trainee_progress,
            'id' => $request->trainee_id
        ]);
        return redirect()->back();
    }
}