@if(@$status == TRAINING_CONSTANT_TYPE['ENROLLMENT_PROGRESS_PENDING'])
    <span class="badge bg-blue">
        {{ getEnrollmentProgressStatusKey(@$status) }}
    </span>
@elseif(@$status == TRAINING_CONSTANT_TYPE['ENROLLMENT_PROGRESS_ON_TRAINING'])
    <span class="badge bg-yellow">
        {{ getEnrollmentProgressStatusKey(@$status) }}
    </span>
@else
    <span class="badge bg-danger">
        {{ getEnrollmentProgressStatusKey(@$status) }}
    </span>
@endif