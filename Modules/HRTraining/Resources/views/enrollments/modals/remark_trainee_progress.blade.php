@foreach(@$trainees as $key => $trainee)

    <div class="modal fade in modal-remark-progress" id="modal-remark-progress-{{ $trainee->id }}" tabindex="-1"
         role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Update Trainee Progress</h4>
                </div>

                <form action="{{ route('hrtraining::review_training.update_trainee_progress') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="trainee_id" value="{{ @$trainee->id }}"/>

                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12">

                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="trainee_progress" value="{{ TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_IN_COMPLETE_TRAINING']}}"
                                                    {{ @$trainee->training_status == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_IN_COMPLETE_TRAINING'] ? 'checked' : '' }}/>
                                            {{ getTraineeProgressStatus(TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_IN_COMPLETE_TRAINING']) }}
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="trainee_progress" value="{{ TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_COMPLETED_TRAINING'] }}"
                                                    {{ @$trainee->training_status == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_COMPLETED_TRAINING'] ? 'checked' : '' }}/>
                                            {{ getTraineeProgressStatus(TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_COMPLETED_TRAINING']) }}
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="trainee_progress" value="{{ TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_OVERDUE'] }}"
                                                    {{ @$trainee->training_status == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_OVERDUE'] ? 'checked' : '' }}/>
                                            {{ getTraineeProgressStatus(TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_OVERDUE']) }}
                                        </label>
                                    </div>
                                </div>
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