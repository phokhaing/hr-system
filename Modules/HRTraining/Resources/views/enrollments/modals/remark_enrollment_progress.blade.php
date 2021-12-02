@foreach(@$enrollments as $key => $enrollment)

    <div class="modal fade in modal-remark-progress" id="modal-remark-progress-{{ $enrollment->id }}" tabindex="-1"
         role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Update Enrollment Progress</h4>
                </div>

                <form action="{{ route('hrtraining::enrollment.update_progress') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="enrollment_id" value="{{ @$enrollment->id }}"/>

                    <div class="modal-body">
                        <div class="form-group">
                            <div class="col-md-12">

                                @foreach(getEnrollmentProgressStatusList() as $enrollmentProgress)
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="enrollment_progress"
                                                   value="{{ @$enrollmentProgress }}"
                                                    {{ @$enrollment->status == $enrollmentProgress ? 'checked' : '' }}/>
                                            {{ getEnrollmentProgressStatusKey($enrollmentProgress) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button id="btn-reject" type="submit" class="btn btn-danger btn-reject">Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach