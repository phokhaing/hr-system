<form action="{{route('hrtraining::staff_enrollment.request_join_training')}}" method="post"
      style="margin-bottom: 2px"
      id="form-request-training" class="form-request-training">
    {{ Form::token() }}
    <input type="hidden" name="enrollment_id" value="{{ @$enrollment_id }}" id="enrollment_id"/>

    <button id="request-join"
            type="submit"
            class="btn btn-sm btn-danger request-join"
            title="Request Join"
            {{ @$request_join_status == TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_APPROVED'] ? 'disabled' : 'enabled' }}>
        <i class="fa fa-send"></i>
    </button>
</form>