<?php


namespace Modules\HRTraining\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HRTraining\Entities\CourseContents;
use Modules\HRTraining\Entities\Courses;
use Modules\HRTraining\Entities\Enrollments;
use Modules\HRTraining\Entities\Exam;
use Modules\HRTraining\Entities\ExamHistory;
use Modules\HRTraining\Entities\Question;
use Modules\HRTraining\Entities\Trainees;
use Modules\HRTraining\Http\Requests\TakeExamSaveCountinueRequest;

class StaffOnTrainingController extends Controller
{
    public function startTraining(Request $request)
    {

        $enrollment = Enrollments::with(['course'])->find(decrypt($request->enrollment_id));
        if ($enrollment) {
            $currentTrainee = Trainees::getCurrentTraineeLoginBaseOnEnrollment($enrollment->id)
                ->first();
            $course = $enrollment->course;
            $courseContents = $course->contents;
            if(is_null($courseContents) || count($courseContents) <= 0){
                return back()->with('error', 'Sorry, There is no any content for this course yet!');
            }

            updateTrainingStatus([
                'status' => TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_IN_TRAINING'],
                'id' => @$currentTrainee->id
            ]);

            //Get current course content in training,
            //If current course content is null, first course content is in training
            $currentContentId = @$request->get('content_id');
            if (@$currentContentId) {
                $courseContent = findCourseContentFromCollection(@$courseContents, $currentContentId);
            } else {
                $courseContent = @$courseContents[0];
            }

            createTraineeHistory([
                'enrollment_id' => $enrollment->id,
                'trainee_id' => @$currentTrainee->id,
                'status' => TRAINING_CONSTANT_TYPE['TRAINEE_TAKE_TRAINING'],
                'json_data->course_id' => $course->id,
                'json_data->course_content_id' => $courseContent->id
            ]);

            $nextCourseContentId = $this->findNextOrPreviousCourseContent(true, $course->id, $courseContent->id);
            $previousCourseContentId = $this->findNextOrPreviousCourseContent(false, $course->id, $courseContent->id);

            return view('hrtraining::staff_on_training.start_training',
                compact(
                    'enrollment',
                    'course',
                    'courseContents',
                    'courseContent',
                    'nextCourseContentId',
                    'previousCourseContentId')
            );
        } else {
            //TODO: manage error here
            return redirect()->back()->with(['success' => 0]);
        }
    }

    public function completeMyTraining(Request $request)
    {
        $currentTrainee = Trainees::getCurrentTraineeLoginBaseOnEnrollment($request->enrollment_id)
            ->first();

        //Training is completed
        updateTrainingStatus([
            'status' => TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_FINISH_TRAINING'],
            'id' => @$currentTrainee->id
        ]);

        createTraineeHistory([
            'enrollment_id' => $request->enrollment_id,
            'trainee_id' => @$currentTrainee->id,
            'status' => TRAINING_CONSTANT_TYPE['TRAINEE_TAKE_FINISH_TRAINING'],
        ]);

        return redirect()->route('hrtraining::staff_on_training.take_exam', ['enrollment_id' => encrypt($request->enrollment_id)]);
    }

    public function takeExam(Request $request)
    {
        $enrollment = Enrollments::with('course')->find(decrypt(@$request->enrollment_id));
        if ($enrollment) {
            $course = $enrollment->course;
            $countExamInCurrentCourse = Exam::select('id')->where('course_id', $course->id)->count();
            $exam = $this->findNextExam(@$course->id, @$request->current_exam_id);

            if ($exam) {
                $isLastExam = $this->isLastExam($exam->id, $course->id);
                return view('hrtraining::staff_on_training.take_exam', compact('enrollment', 'course', 'exam', 'isLastExam', 'countExamInCurrentCourse'));
            }

            return redirect()->back()->with('error', 'Sorry, There is no any exam for this training yet!');

        } else {
            return redirect()->back()->with(['success' => 0]);
        }
    }

    public function takeExamSaveContinue(TakeExamSaveCountinueRequest $request)
    {
        $currentTrainee = Trainees::getCurrentTraineeLoginBaseOnEnrollment($request->enrollment_id)
            ->first();

        //Store exam history from open question
        if (@$request->open_answers) {
            foreach (@$request->open_answers as $questionId => $answer) {

                $question = Question::find($questionId);
                $questionObj = @$question->json_data;

                $examHistoryData = [
                    'enrollment_id' => $request->enrollment_id,
                    'course_id' => $request->course_id,
                    'exam_id' => $request->current_exam_id,
                    'trainee_id' => @$currentTrainee->id
                ];
                $examHistoryData['question_id'] = $questionId;
                $examHistoryData['json_data->type'] = QUESTION_TYPE['OPEN']['value'];
                $examHistoryData['json_data->point'] = @$questionObj->point;
                $examHistoryData['json_data->answer'] = $answer[0];//Open question have only on answer index

                $newExamHistory = new ExamHistory();
                $newExamHistory->createRecord($examHistoryData);
            }
        }

        //Store exam history from close question
        if (@$request->close_answers && count(@$request->close_answers)) {
            foreach (@$request->close_answers as $questionId => $answerId) {

                $question = Question::find($questionId);
                $questionObj = @$question->json_data;

                $examHistoryData = [
                    'enrollment_id' => $request->enrollment_id,
                    'course_id' => $request->course_id,
                    'exam_id' => $request->current_exam_id,
                    'trainee_id' => @$currentTrainee->id
                ];
                $examHistoryData['question_id'] = $questionId;
                $examHistoryData['json_data->type'] = QUESTION_TYPE['CLOSE']['value'];
                $examHistoryData['json_data->point'] = @$questionObj->point;

                $answer = findMultipleChoiceAnswerById(@$questionObj->answer, $answerId[0]);//Close question have only on answer index

                //Calculate Trainee gets answer point, close question is get full point in correct answer otherwise 0
                $answerPoint = 0;
                if ($answer->status == ANSWER_STATUS['CORRECT']) {
                    $answerPoint = @$questionObj->point;
                }
                $examHistoryData['json_data->answer_point'] = $answerPoint;

                $examHistoryData['json_data->answer_point'] = @$questionObj->point;
                $examHistoryData['json_data->answer'] = @$answer;

                $newExamHistory = new ExamHistory();
                $newExamHistory->createRecord($examHistoryData);
            }
        }

        //Store exam history from multiple-choice question
        if (@$request->multiple_answers && count(@$request->multiple_answers)) {
            foreach (@$request->multiple_answers as $questionId => $answerIds) {

                $question = Question::find($questionId);
                $questionObj = @$question->json_data;

                $answers = [];
                $answerPoint = 0;
                $pointEachCorrectAnswer = $questionObj->point / count(@$questionObj->answer);

                foreach (@$answerIds as $answerId) {
                    $answer = findMultipleChoiceAnswerById(@$questionObj->answer, $answerId);
                    if ($answer->status == ANSWER_STATUS['CORRECT']) {
                        $answerPoint += $pointEachCorrectAnswer;
                    }
                    $answers[] = $answer;
                }

                $examHistoryData = [
                    'enrollment_id' => $request->enrollment_id,
                    'course_id' => $request->course_id,
                    'exam_id' => $request->current_exam_id,
                    'trainee_id' => @$currentTrainee->id
                ];
                $examHistoryData['question_id'] = $questionId;
                $examHistoryData['json_data->type'] = QUESTION_TYPE['MULTIPLE-CHOICE']['value'];
                $examHistoryData['json_data->point'] = @$questionObj->point;
                $examHistoryData['json_data->answer'] = $answers;
                $examHistoryData['json_data->answer_point'] = @$answerPoint;

                $newExamHistory = new ExamHistory();
                $newExamHistory->createRecord($examHistoryData);
            }
        }

        createTraineeHistory([
            'enrollment_id' => $request->enrollment_id,
            'trainee_id' => @$currentTrainee->id,
            'status' => TRAINING_CONSTANT_TYPE['TRAINEE_TAKE_EXAM'],
            'json_data->course_id' => $request->course_id,
            'json_data->exam_id' => $request->current_exam_id
        ]);

        if ($request->is_last_exam) {
            //Exam is completed
            updateTrainingStatus([
                'status' => TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_FINISH_EXAM'],
                'id' => @$currentTrainee->id
            ]);

            $category = Courses::select('category_id')->find($request->course_id);
            return redirect()->route('hrtraining::staff_training.orientation', ['id' => @$category->category_id])
                ->with(['success' => 'You have been completed your training, Please waiting to see your result!']);

        } else {
            //Continue to next exam
            return redirect()->route('hrtraining::staff_on_training.take_exam', [
                'enrollment_id' => encrypt($request->enrollment_id),
                'current_exam_id' => $request->current_exam_id
            ]);
        }
    }

    /**
     * Find course content has next or previous item
     * @param $isNext
     * @param $courseId
     * @param $currentContentId
     * @return mixed
     */
    private function findNextOrPreviousCourseContent($isNext, $courseId, $currentContentId = 0)
    {

        $courseContent = CourseContents::select('id')
            ->where('course_id', $courseId);

        if ($isNext) {
            $courseContent->where('id', '>', $currentContentId)
                ->orderBy('id', 'ASC');
        } else {
            $courseContent->where('id', '<', $currentContentId)
                ->orderBy('id', 'DESC');
        }

        $courseContent = $courseContent->first();
        if ($courseContent) {
            return $courseContent->id;
        }

        return 0;
    }

    /**
     * Algorithms to get next an exam for staff training by course base on current exam
     * Find (next exam > current exam )
     * Case @param $currentExamId == null find first
     * @param $courseId
     * @param $currentExamId
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|Exam|object|null
     */
    private function findNextExam($courseId, $currentExamId)
    {
        $exam = Exam::with('questionAnswers');
        if ($currentExamId) {
            $exam
                ->where('course_id', $courseId)
                ->where('id', '>', $currentExamId);
        } else {
            $exam
                ->where('course_id', $courseId);
        }
        return $exam->first();
    }

    /**
     * @param $examId
     * @return bool
     */
    private function isLastExam($examId, $courseId)
    {
        $exam = Exam::where('course_id', $courseId)
            ->latest()
            ->first();
        return @$examId == $exam->id;
    }
}