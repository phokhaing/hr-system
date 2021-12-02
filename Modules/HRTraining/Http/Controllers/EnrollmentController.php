<?php

namespace Modules\HRTraining\Http\Controllers;

use App\BranchesAndDepartments;
use App\Contract;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HRTraining\Entities\Categories;
use Modules\HRTraining\Entities\Courses;
use Modules\HRTraining\Entities\Enrollments;
use Modules\HRTraining\Entities\Exam;
use Modules\HRTraining\Entities\ExamHistory;
use Modules\HRTraining\Entities\Trainees;
use Modules\HRTraining\Http\Requests\EnrollmentApproveRequest;
use Modules\HRTraining\Http\Requests\HRReviewExamRequest;
use Modules\HRTraining\Http\Requests\StoreEnrollmentRequest;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = @$request->id ?? CATEGORY_ORIENTATION;
        $currentUser = \auth()->user();
        $departments = BranchesAndDepartments::with(['courses', 'company']);
        if (!@$currentUser->is_admin && !@$currentUser->can('manage_all_training_company')) {
            $departments->where('company_code', @$currentUser->company_code);
        }
        $departments = $departments->get();

        if ($request->get('department-id')) {
            $currentSelect = decrypt($request->get('department-id'));
        } else {
            $currentSelect = $departments[0]->id;
        }

        $enrollments = Enrollments::with(['course', 'traineesRequested' => function ($q) {
            return $q->whereIn('request_join_status', [
                TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_PENDING'],
                TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_APPROVED'],
                TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_REJECTED'],
            ]);
        }])
            ->getEnrollmentByUser()
            ->whereHas('course', function ($query) use ($currentSelect, $categoryId) {
                $query->where('branch_department_id', $currentSelect)
                    ->where('category_id', $categoryId);
            })
            ->orderBy('id', 'DESC')
            ->latest()
            ->paginate();

        $category = Categories::selectRaw('json_data->>"$.title_en" as title')->find($categoryId);
        return view('hrtraining::enrollments.course_by_department')->with(compact('departments', 'currentSelect', 'enrollments', 'category'));
    }

    public function detail($id)
    {
        $enrollment = Enrollments::with([
            'course',
            'traineesAddedFromAdmin',
            'traineesRequested' => function ($q) {
                return $q->where('request_join_status', '<>', TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_CANCEL']);
            }
        ])->find($id);

        return view('hrtraining::enrollments.show')->with(compact('enrollment'));
    }

    public function reviewExam(Request $request)
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

        return view(
            'hrtraining::enrollments.review_exams',
            compact('course', 'exams', 'trainee', 'enrollment')
        );
    }

    public function calculateExamResult(Request $request)
    {
        if (@$request->give_open_answers_point && count(@$request->give_open_answers_point)) {
            foreach (@$request->give_open_answers_point as $examHistoryId => $givePoint) {
                $examHistory = ExamHistory::find($examHistoryId);
                $examHistory->updateRecord($examHistoryId, [
                    'json_data->answer_point' => $givePoint
                ]);
            }
        }


        //Start Calculate total point of Trainee Exam Result
        $totalPoint = ExamHistory::where('enrollment_id', $request->enrollment_id)
            ->where('trainee_id', $request->trainee_id)
            ->sum('json_data->answer_point');

        //Start calculate average of Trainee Exam Result
        $countQuestionInTraining = ExamHistory::select('id')->where('enrollment_id', $request->enrollment_id)
            ->where('trainee_id', $request->trainee_id)
            ->count();
        $average = $totalPoint / $countQuestionInTraining;

        updateTrainingStatus([
            'status' => TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_COMPLETED_TRAINING'],
            'id' => $request->trainee_id
        ]);

        createOrUpdateTraineeResult([
            'enrollment_id' => $request->enrollment_id,
            'trainee_id' => $request->trainee_id,
            'json_data->total_point' => $totalPoint,
            'json_data->average' => $average
        ]);

        return redirect()->route('hrtraining::enrollment.review_trainee_exam', ['enrollment_id' => encrypt($request->enrollment_id)])
            ->with(['success' => 'Exam Result has been saved successfully']);
    }

    /**
     * get all trainee from enrollment training course has been completed status (end date)
     * @param $enrollmentId
     */
    public function reviewTraineeExam(Request $request)
    {

        if (!@$request->enrollment_id) {
            return redirect()->back();
        }

        $enrollment = Enrollments::find(decrypt($request->enrollment_id));
        $trainees = Trainees::withTraineeExamResult($enrollment->id)
            ->where('enrollment_id', $enrollment->id)
            ->where('request_join_status', TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_APPROVED']);

        $keyword = @$request->get('keyword');
        if (@$keyword) {
            $trainees->whereHas('staff', function ($q) use ($keyword) {
                $q->whereRaw('LOWER(CONCAT(last_name_kh, " ", first_name_kh)) LIKE ?', ["%$keyword%"]);
                $q->orWhereRaw('LOWER(CONCAT(last_name_en, " ", first_name_en)) LIKE ?', ["%$keyword%"]);
                $q->orWhereRaw('staff_id LIKE ?', ["%$keyword%"]);
            });
        }

        $trainees = $trainees->orderBy('id', 'DESC')->paginate();
        return view(
            'hrtraining::enrollments.list_trainee_completed_training',
            compact('trainees', 'enrollment')
        );
    }

    public function create(Request $request)
    {
        $courses = Courses::withCourseInfo()->getCourseByUser()->where('category_id', @$request->category_id)->get();
        return view('hrtraining::enrollments.create')->with(compact('courses'));
    }

    public function store(StoreEnrollmentRequest $request)
    {
        $data = [
            'course_id' => $request->course,
            'class_type' => $request->training_class,
            'status' => TRAINING_CONSTANT_TYPE['ENROLLMENT_PROGRESS_PENDING'],

            'json_data->training_purpose' => $request->purpose,
            'json_data->start_date' => strToTimestamp($request->start_date),
            'json_data->end_date' => strToTimestamp($request->end_date),
            'json_data->duration' => $request->duration,
        ];

        $enrollment = new Enrollments();
        $enrollment->createRecord($data);
        $enrollmentId = $enrollment->id;

        //Store Trainees
        foreach ($request->trainees as $key => $value) {
            $traineeObj = to_object($value);
            $trainee = new Trainees();
            $traineeData = [
                'enrollment_id' => $enrollmentId,
                'staff_personal_id' => $traineeObj->staff_id,
                'contract_id' => $traineeObj->contract_id,
                'status_from' => TRAINING_CONSTANT_TYPE['TRAINEE_STATUS_FROM_ADMIN'],
                'request_join_status' => TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_APPROVED']
            ];
            $trainee->createRecord($traineeData);
        }

        return redirect()
            ->route('hrtraining::enrollment.index', ['id' => CATEGORY_ORIENTATION])
            ->with(['success' => 1]);
    }

    public function delete($id)
    {
        $enrollment = Enrollments::find($id);
        if ($enrollment) {
            $enrollment->trainees()->delete();
            $enrollment->delete();
            return redirect()
                ->back()
                ->with(['success' => 1]);
        }

        return redirect()
            ->back()
            ->with(['success' => 0]);
    }

    public function edit($id, $category_id)
    {
        $enrollment = Enrollments::with(['course', 'trainees' => function ($q) {
            return $q->where('status_from', TRAINING_CONSTANT_TYPE['TRAINEE_STATUS_FROM_ADMIN']);
        }])
            ->find($id);

        $courses = Courses::withCourseInfo()->getCourseByUser()->where('category_id', @$category_id)->get();
        $staffs = Contract::currentStaffForEnrollment()->get();
        return view('hrtraining::enrollments.edit')->with(compact('enrollment', 'courses', 'staffs'));
    }

    public function update(StoreEnrollmentRequest $request​)
    {
        $data = [
            'course_id' => $request​->course,
            'status' => $request​->status,
            'class_type' => $request​->training_class,
            'json_data->training_purpose' => $request​->purpose,
            'json_data->start_date' => strToTimestamp($request​->start_date),
            'json_data->end_date' => strToTimestamp($request​->end_date),
            'json_data->duration' => $request​->duration,
        ];

        $enrollment = Enrollments::find($request​->enrollment_id);
        $enrollment->updateRecord($request​->enrollment_id, $data);

        $temTraineeDeleted = json_decode($request​->tem_deleted_trainees);
        if ($temTraineeDeleted && count($temTraineeDeleted)) {
            Trainees::whereIn('id', $temTraineeDeleted)
                ->where('enrollment_id', $request​->enrollment_id)
                ->delete();
        }

        //Store new Trainees added
        if (@$request​->trainees && count(@$request​->trainees)) {
            foreach (@$request​->trainees as $key => $value) {
                $traineeObj = to_object($value);
                if (@$traineeObj->trainee_id) {
                    continue;
                }

                $trainee = new Trainees();
                $traineeData = [
                    'enrollment_id' => $request​->enrollment_id,
                    'staff_personal_id' => $traineeObj->staff_id,
                    'contract_id' => $traineeObj->contract_id,
                    'status_from' => TRAINING_CONSTANT_TYPE['TRAINEE_STATUS_FROM_ADMIN'],
                    'request_join_status' => TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_APPROVED'],
                ];
                $trainee->createRecord($traineeData);
            }
        }

        return redirect()
            ->route('hrtraining::enrollment.index', ['id' => CATEGORY_ORIENTATION])
            ->with(['success' => 1]);
    }

    public function approve(EnrollmentApproveRequest $request)
    {
        foreach ($request->check as $value) {
            $trainee = Trainees::find($value);
            $traineeData = [
                'request_join_status' => $request->approve_type
            ];
            $trainee->updateRecord($value, $traineeData);
        }

        return redirect()
            ->route('hrtraining::enrollment.index')
            ->with(['success' => 1]);
    }

    public function updateProgress(Request $request)
    {
        $enrollment = new Enrollments();
        $enrollment->updateRecord($request->enrollment_id, [
            'status' => $request->enrollment_progress
        ]);

        return redirect()->back();
    }

    /**
     * Ajax Request
     * @param $companyCode
     * @param $departmentCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTraineeWithCurrentContract($companyCode, $departmentCode)
    {
        $contracts = Contract::getAllStaffActiveByDepartmentBranch($companyCode, $departmentCode)->get();
        return response()->json(['data' => $contracts]);
    }
}
