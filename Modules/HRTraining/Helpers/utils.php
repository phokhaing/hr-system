<?php


use Modules\HRTraining\Entities\TraineeHistory;
use Modules\HRTraining\Entities\TraineeResult;
use Modules\HRTraining\Entities\Trainees;

if (!function_exists('getOAuthCredentialsFile')) {
    function getOAuthCredentialsFile()
    {
        // oauth2 creds
        $oauth_creds = __DIR__ . '/../credentials.json';

        if (file_exists($oauth_creds)) {
            return $oauth_creds;
        }

        return false;
    }
}

if (!function_exists('getStaffIdFromCurrentAuth')) {

    function getStaffIdFromCurrentAuth()
    {
        return Auth::user()->staff_personal_info_id;
    }
}

if (!function_exists('compareCurrentSection')) {

    function compareCurrentSection($currentSection, $compareSection)
    {
        return $currentSection->id == $compareSection->id;
    }
}

if (!function_exists('findMultipleChoiceAnswerById')) {

    function findMultipleChoiceAnswerById(array $answers, $currentAnswerId)
    {
        return collect($answers)->filter(function ($answer) use ($currentAnswerId) {
            return $answer->id == $currentAnswerId;
        })->first();
    }
}

if (!function_exists('findExamHistoryBaseOnQuestionId')) {

    function findExamHistoryBaseOnQuestionId($examHistories, $currentQuestionId)
    {
        return $examHistories->filter(function ($examHistory) use ($currentQuestionId) {
            return $examHistory->question_id == $currentQuestionId;
        })->first();
    }
}

if (!function_exists('updateTrainingStatus')) {
    function updateTrainingStatus($data)
    {
        $trainee = new Trainees();
        $updateDate = [
            'training_status' => @$data['status']
        ];
        $trainee->updateRecord(@$data['id'], $updateDate);
    }
}

if (!function_exists('createTraineeHistory')) {
    function createTraineeHistory(array $data)
    {
        $traineeHistory = new TraineeHistory();
        $traineeHistory->createRecord($data);
    }
}

if (!function_exists('createOrUpdateTraineeResult')) {
    /**
     * One staff join training event (enrollment) -> one result
     * @param $data = [
     * 'enrollment_id' => $request->enrollment_id,
     * 'trainee_id' => $request->trainee_id,
     * 'json_data->total_point' => $totalPoint
     * ];
     */
    function createOrUpdateTraineeResult(array $data)
    {
        $traineeResult = TraineeResult::select('id')
            ->where('enrollment_id', @$data['enrollment_id'])
            ->where('trainee_id', @$data['trainee_id'])
            ->first();

        if ($traineeResult) {
            $traineeResult->updateRecord($traineeResult->id, $data);
        } else {
            $traineeResult = new TraineeResult();
            $traineeResult->createRecord($data);
        }

    }
}

if (!function_exists('findCourseContentFromCollection')) {

    function findCourseContentFromCollection($courseContents, $currentCourseContentId)
    {
        return $courseContents->filter(function ($courseContent) use ($currentCourseContentId) {
            return $courseContent->id == $currentCourseContentId;
        })->first();
    }
}
