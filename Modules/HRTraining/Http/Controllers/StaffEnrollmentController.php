<?php


namespace Modules\HRTraining\Http\Controllers;

use App\Contract;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HRTraining\Entities\Enrollments;
use Modules\HRTraining\Entities\TraineeResult;
use Modules\HRTraining\Entities\Trainees;


class StaffEnrollmentController extends Controller
{
    public function viewAllTrainingEvent()
    {
        $enrollments = Enrollments::with(['course'])
            ->withCurrentTraineeFromLoginStaff()
            ->orderBy('id', 'DESC')
            ->latest()
            ->paginate();
        return view('hrtraining::staff_enrollments.view_all_training_event')->with(compact('enrollments'));
    }

    public function viewMyTrainingSchedule()
    {
        $enrollments = Enrollments::with(['course'])
            ->withCurrentTraineeFromLoginStaff()
            ->whereIn('status', [
                TRAINING_CONSTANT_TYPE['ENROLLMENT_PROGRESS_PENDING'],
                TRAINING_CONSTANT_TYPE['ENROLLMENT_PROGRESS_ON_TRAINING'],
            ])
            ->whereCurrentTraineeApprove()
            ->orderBy('id', 'DESC')
            ->latest()
            ->paginate();
        return view('hrtraining::staff_enrollments.view_my_training_schedule')->with(compact('enrollments'));
    }

    public function detail($id)
    {
        $enrollment = Enrollments::with(['course', 'trainee' => function ($q) {
            $q->where('staff_personal_id', getStaffIdFromCurrentAuth());
        }])->find($id);
        return view('hrtraining::staff_enrollments.show')->with(compact('enrollment'));
    }

    public function requestJoinTraining(Request $request)
    {
        if (is_null(getStaffIdFromCurrentAuth())) {
            return back()->with('error', 'Sorry, You are not available to join on this training!');
        }

        //User request join must be with current contract
        $contract = Contract::currentContract(getStaffIdFromCurrentAuth())->first();
        $enrollment = Enrollments::withCurrentTraineeFromLoginStaff()
            ->find($request->enrollment_id);

        if ($enrollment->status == TRAINING_CONSTANT_TYPE['ENROLLMENT_PROGRESS_COMPLETED']) {
            return back()->with('error', 'Sorry, This Training event does not available right now!');
        }

        $traineeData = [
            'enrollment_id' => $request->enrollment_id,
            'staff_personal_id' => $contract->staff_personal_info_id,
            'contract_id' => @$contract->id,
            'status_from' => TRAINING_CONSTANT_TYPE['TRAINEE_STATUS_FROM_REQUEST_JOIN'],
            'request_join_status' => TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_PENDING']
        ];

        //Check if this staff have been request join enrollment event before
        // (case cancel will be can request join again)
        if (@$enrollment->trainee) {
            $trainee = new Trainees();
            $trainee->updateRecord(@$enrollment->trainee->id, $traineeData);
            $traineeId = @$enrollment->trainee->id;
        } else {
            $trainee = new Trainees();
            $trainee->createRecord($traineeData);
            $traineeId = @$trainee->id;
        }

        createTraineeHistory([
            'enrollment_id' => $enrollment->id,
            'trainee_id' => $traineeId,
            'status' => TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_PENDING']
        ]);

        return redirect()->back()->with(['success' => 1]);
    }

    public function requestCancelTraining(Request $request)
    {
        if (is_null(getStaffIdFromCurrentAuth())) {
            //TODO: handle error here
            return redirect()->back()->with(['success' => 0]);
        }

        $enrollment = Enrollments::with(['trainee' => function ($query) {
            return $query->where('staff_personal_id', getStaffIdFromCurrentAuth());
        }])
            ->whereHas('trainee', function ($q) {
                $q->where('staff_personal_id', getStaffIdFromCurrentAuth());
            })
            ->find($request->enrollment_id);

        if ($enrollment) {
            $trainee = new Trainees();
            $updateDate = [
                'request_join_status' => TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_CANCEL']
            ];
            $trainee->updateRecord(@$enrollment->trainee->id, $updateDate);

            createTraineeHistory([
                'enrollment_id' => $enrollment->id,
                'trainee_id' => @$enrollment->trainee->id,
                'status' => TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_CANCEL']
            ]);

            return redirect()->back()->with(['success' => 1]);
        }

        //TODO: handle error here
        return redirect()->back()->with(['success' => 0]);
    }

    public function myExamResult(Request $request)
    {
        $enrollment = Enrollments::with(['course'])
            ->withCurrentTraineeFromLoginStaff()
            ->find(decrypt($request->enrollment_id));

        $course = $enrollment->course;
        $trainee = $enrollment->trainee;

        if (!$trainee->training_status
            || $trainee->training_status == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_IN_TRAINING']) {
            return back()->with('error', 'Sorry, You did not finish your training yet!');

        } elseif ($trainee->training_status == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_FINISH_TRAINING']) {
            return back()->with('error', 'Sorry, You did not finish an exam yet!');

        } elseif ($trainee->training_status == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_FINISH_EXAM']) {
            return back()->with('error', 'Sorry, Your result did not review from HR yet!');

        }

        //Get Trainee Result base on enrollment training event
        $traineeResult = TraineeResult::where('trainee_id', $trainee->id)
            ->where('enrollment_id', $enrollment->id)
            ->latest()
            ->first();

        return view('hrtraining::staff_enrollments.my_exam_result')->with(compact('enrollment', 'course', 'trainee', 'traineeResult'));
    }
}