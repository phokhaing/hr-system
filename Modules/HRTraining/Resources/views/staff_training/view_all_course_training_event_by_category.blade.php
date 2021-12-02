@extends('adminlte::page')

@section('title', @$category->json_data->title_en.' Course Training Event')

@section('content')

@include('partials.breadcrumb')
@include('hrtraining::layouts.layout_flash_error', ['message' => @$message])

<div class="panel panel-default">
    <div class="panel-heading"><label for="">{{@$category->json_data->title_en}} Course Tradining</label></div>
    <div class="panel-body">
        <table class="table table-striped table-hover">
            <tr>
                <th>Action</th>
                <th>Training Course</th>
                <th>Request Status</th>
                <th>Training Progress</th>
                <th>Training Start Date</th>
                <th>Training End Date</th>
                <th>Enrollment Date</th>
                <th>Training Result</th>
            </tr>

            <tbody>
            @foreach($enrollments as $key => $value)
                @php
                    $jsonObj = @to_object(@$value->json_data);
                    $courseObj = @to_object(@$value->course->json_data);
                    $trainee = @$value->trainee;
                    $isStaffRequestJoinPending = @$trainee && @$trainee->request_join_status == TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_PENDING'];
                @endphp

                <tr id="row-{{ $value->id }}" class="row-item">
                    <td>

                        <form action="{{$isStaffRequestJoinPending ? route('hrtraining::staff_enrollment.request_cancel_training') : route('hrtraining::staff_enrollment.request_join_training') }}" method="post"
                              style="margin-bottom: 2px"
                              id="form-request-training" class="form-request-training">
                            {{ Form::token() }}
                            <input type="hidden" name="enrollment_id" value="{{ @$value->id }}" id="enrollment_id"/>

                            @if($isStaffRequestJoinPending)
                                <button id="request-join"
                                        type="submit"
                                        title="Cancel Request Join"
                                        class="btn btn-sm btn-info request-join">
                                    <i class="fa fa-refresh"></i>
                                </button>
                            @else

                                <button id="request-join"
                                        type="submit"
                                        class="btn btn-sm btn-danger request-join"
                                        title="Request Join"
                                        {{ @$trainee->request_join_status == TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_APPROVED'] ? 'disabled' : 'enabled' }}>
                                    <i class="fa fa-send"></i>
                                </button>

                            @endif

                            @if(@$trainee->request_join_status == TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_APPROVED'])
                                @if(@$value->status == TRAINING_CONSTANT_TYPE['ENROLLMENT_PROGRESS_ON_TRAINING']
                                    || @$value->status == TRAINING_CONSTANT_TYPE['ENROLLMENT_PROGRESS_COMPLETED'])

                                    @php
                                        $disableTrainingOrExam = (@$trainee->training_status == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_FINISH_EXAM'])
                                                        || (@$trainee->training_status == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_COMPLETED_TRAINING']);
                                    @endphp

                                    <a class="btn btn-sm btn-danger {{ $disableTrainingOrExam ? 'disabled' : '' }}"
                                       title="Start Training"
                                       href="{{ route('hrtraining::staff_on_training.start_training', ['enrollment_id' => encrypt(@$value->id)]) }}">
                                        <i class="fa fa-play-circle"></i>
                                    </a>

                                    @if(@$trainee->training_status == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_FINISH_TRAINING']
                                        || @$trainee->training_status == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_FINISH_EXAM']
                                        || @$trainee->training_status == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_COMPLETED_TRAINING'])
                                        <a class="btn btn-sm btn-primary {{ $disableTrainingOrExam ? 'disabled' : '' }}"
                                           title="Take an Exam"
                                           href="{{ route('hrtraining::staff_on_training.take_exam', ['enrollment_id' => encrypt(@$value->id)]) }}">
                                            <i class="fa fa-file-text-o"></i>
                                        </a>
                                    @endif
                                @endif
                            @endif

                        </form>
                    </td>

                    <td>
                        <a href="{{ route('hrtraining::staff_enrollment.detail', $value->id) }}">
                            {{ @$courseObj->title }}
                        </a>
                    </td>

                    <td id="col-request-status">
                        @if(is_null($trainee) || @$value->trainee->request_join_status == TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_CANCEL'])
                            N/A
                        @else
                            <span class="badge bg-blue">
                                {{ getTraineeRequestJoinStatusKey(@$value->trainee->request_join_status) }}
                            </span>

                        @endif
                    </td>

                    <td>
                        @include('hrtraining::enrollments.components.button_training_status', ['status' => @$value->status])
                    </td>

                    <td>{{ dateTimeToStr(@$jsonObj->start_date) }}</td>
                    <td>{{ dateTimeToStr(@$jsonObj->end_date) }}</td>
                    <td>{{ date('d-M-Y', strtotime(@$value->created_at)) }}</td>
                    <td>
                        @if(@$trainee->request_join_status == TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_APPROVED'])
                            <a class="btn btn-sm btn-info"
                               href="{{ route('hrtraining::staff_enrollment.my_exam_result', ['enrollment_id' => encrypt(@$value->id)]) }}">
                                Show Result
                            </a>

                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {!! $enrollments->links() !!}
    </div>
</div> <!-- .panel-default -->
@endsection

@section('js')
    <script>
    </script>
@endsection