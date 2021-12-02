@extends('adminlte::page')

@section('title', 'Enrollment Detail')

@section('content')

    @include('partials.breadcrumb')

    @php
        $enrollmentObj = @$enrollment->json_data;
        $courseObj = @$enrollment->course->json_data;
        $categoryObj = @$enrollment->course->category->json_data;
        $trainee = @$enrollment->trainee;
    @endphp

    <div class="panel panel-default">
        <div class="panel-heading">
            <label for="">Training Information</label>
            <a class="btn btn-xs btn-primary pull-right" href="{{ route('hrtraining::staff_enrollment.view_all_training_event') }}"> Back</a>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
                    <label for="">Category:</label> {{ @$categoryObj->title_en ?: 'N/A' }}
                    <hr/>
                    
                    <label for="">Training Course:</label> {{ @$courseObj->title ?: 'N/A' }}
                    <hr/>

                    <label for="">Course Price($):</label> {{ @$categoryObj->cost ?: 'N/A' }}
                    <hr/>

                    <label for="">Course Status:</label>
                    @if($courseObj->status)
                        <span class="badge bg-yellow">{{ COURSE_STATUS[@$courseObj->status] }}</span>
                    @else
                        N/A
                    @endif
                    <hr/>

                    <label for="">Training Purpose:</label> {{ @$enrollmentObj->description ?: 'N/A' }}
                    <hr/>

                    <label for="">Training Duration (Days):</label> {{ @$enrollmentObj->duration ?: 'N/A' }}
                    <hr/>

                    <label for="">Training Start From:</label> {{ dateTimeToStr(@$enrollmentObj->start_date) }} <label>
                        Until </label> {{ dateTimeToStr(@$enrollmentObj->end_date) }}
                    <hr/>

                    <label for="">Training Progress:</label>
                    @include('hrtraining::enrollments.components.button_training_status', ['status' => @$enrollment->status])
                    <hr/>

                    <label for="">Training Class:</label>
                    <span class="badge bg-info">{{ getTrainingClassLabel($enrollment->class_type) }}</span>
                    <hr/>

                    <label for="">Enrollment
                        Date:</label> {{ date('d-M-Y H:m:s', strtotime(@$enrollment->created_at)) }}

                    @if(@$trainee)
                        <hr/>

                        @if(@$trainee->status_from == TRAINING_CONSTANT_TYPE['TRAINEE_STATUS_FROM_ADMIN'])
                            <i class="fa fa-info" aria-hidden="true"></i>
                            <label class="text-red">You were added to join this training event!</label>
                        @elseif(@$trainee->request_join_status != TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_CANCEL'])
                            <i class="fa fa-info" aria-hidden="true"></i>
                            <label class="text-red">You have been requested to join this training event!</label>

                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Request Status</th>
                                    <th>Request Date</th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr>
                                    <td>
                                             <span class="badge bg-blue-active">
                                                 {{ getTraineeRequestJoinStatusKey($trainee->request_join_status) }}
                                            </span>
                                    </td>
                                    <td>
                                        {{ date('d-M-Y H:m:s', strtotime(@$trainee->created_at)) }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        @endif

                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection