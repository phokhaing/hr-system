<?php

if (!function_exists('getTraineeRequestJoinStatusKey')) {
    function getTraineeRequestJoinStatusKey($value)
    {
        switch ($value) {
            case $value == TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_PENDING']:
                return 'Pending';
                break;
            case $value == TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_REJECTED']:
                return 'Rejected';
                break;
            case $value == TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_APPROVED']:
                return 'Approved';
                break;
            case $value == TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_CANCEL']:
                return 'Cancel';
                break;
            default:
                return null;
                break;
        }

    }
}

if (!function_exists('getEnrollmentProgressStatusKey')) {
    function getEnrollmentProgressStatusKey($value)
    {
        switch ($value) {
            case $value == TRAINING_CONSTANT_TYPE['ENROLLMENT_PROGRESS_PENDING']:
                return 'Pending';
                break;
            case $value == TRAINING_CONSTANT_TYPE['ENROLLMENT_PROGRESS_ON_TRAINING']:
                return 'On Training';
                break;
            case $value == TRAINING_CONSTANT_TYPE['ENROLLMENT_PROGRESS_COMPLETED']:
                return 'Completed';
                break;
            default:
                return null;
                break;
        }
    }
}

if (!function_exists('getEnrollmentProgressStatusList')) {
    function getEnrollmentProgressStatusList()
    {
        return [
            TRAINING_CONSTANT_TYPE['ENROLLMENT_PROGRESS_PENDING'],
            TRAINING_CONSTANT_TYPE['ENROLLMENT_PROGRESS_ON_TRAINING'],
            TRAINING_CONSTANT_TYPE['ENROLLMENT_PROGRESS_COMPLETED']
        ];
    }
}

if (!function_exists('getTraineeProgressStatus')) {
    function getTraineeProgressStatus($value)
    {
        if ($value) {
            switch ($value) {
                case $value == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_IN_TRAINING']:
                    return 'In Training';
                    break;
                case $value == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_FINISH_TRAINING']:
                    return 'Finish Training';
                    break;
                case $value == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_FINISH_EXAM']:
                    return 'Finish Exam';
                    break;

                case $value == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_IN_COMPLETE_TRAINING']:
                    return 'In Complete Training';
                    break;

                case $value == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_COMPLETED_TRAINING']:
                    return 'Complete Training';
                    break;

                case $value == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_OVERDUE']:
                    return 'Overdue';
                    break;

                case $value == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_RE_TAKEN']:
                    return 'Re-Taken';
                    break;
                default:
                    return null;
                    break;
            }
        } else {
            return "N/A";
        }
    }
}

if (!function_exists('getTrainingClassLabel')) {
    function getTrainingClassLabel($value)
    {
        switch ($value) {
            case $value == CLASS_TYPE['ONLINE']:
                return 'ONLINE';
                break;
            case $value == CLASS_TYPE['ONCLASS']:
                return 'ON CLASS';
                break;
            default:
                return null;
                break;
        }
    }
}