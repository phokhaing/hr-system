<form action="{{route('hrtraining::staff_enrollment.request_cancel_training')}}" method="post"
      style="margin-bottom: 2px"
      id="form-cancel-request-training" class="form-cancel-request-training">
    {{ Form::token() }}
    <input type="hidden" name="enrollment_id" value="{{ @$enrollment_id }}" id="enrollment_id"/>

    <button id="request-join"
            type="submit"
            title="Cancel Request Join"
            class="btn btn-sm btn-info request-join">
        <i class="fa fa-refresh"></i>
    </button>
</form>