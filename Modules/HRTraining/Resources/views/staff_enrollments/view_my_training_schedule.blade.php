@extends('adminlte::page')

@section('title', 'My Training Schedule')

@section('content')

@section('css')
    <style>
        .button-request-join {
            padding: 10px 5px 10px 5px;
            background: #00a65a;
            border-radius: 2px;
        }

        table {
            max-width: 100%;
            overflow-x: auto;
        }
    </style>

@endsection

@include('partials.breadcrumb')
@include('hrtraining::layouts.layout_flash_error', ['message' => @$message])

<div class="panel panel-default">
    <div class="panel-heading"><label for="">My Training Schedule</label></div>
    <div class="panel-body">
        <table class="table table-hover">
            <tr>
                <th>Action</th>
                <th>Training Course</th>
                <th>Request Status</th>
                <th>Progress</th>
                <th>Training Start Date</th>
                <th>Training End Date</th>
                <th>Enrollment Date</th>
            </tr>

            <tbody>

            @foreach($enrollments as $key => $value)
                @php
                    $jsonObj = @to_object(@$value->json_data);
                    $courseObj = @$value->course->json_data;
                    $trainee = @$value->trainee;
                @endphp

                <tr id="row-{{ $value->id }}" class="row-item">
                    <td>
                        @php
                            $disableStartTraining = 'disabled';
                            if(!@$trainee->training_status || @$trainee->training_status == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_IN_TRAINING']){
                                if(@$value->status == TRAINING_CONSTANT_TYPE['ENROLLMENT_PROGRESS_ON_TRAINING']){
                                    $disableStartTraining = '';
                                }
                            }
                        @endphp

                        <a class="btn btn-sm btn-danger {{ $disableStartTraining }}"
                           href="{{ route('hrtraining::staff_on_training.start_training', ['enrollment_id' => encrypt(@$value->id)]) }}">
                            Start Training
                        </a>

                        @if(@$trainee->training_status == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_FINISH_TRAINING'])
                            <a class="btn btn-sm btn-primary"
                               href="{{ route('hrtraining::staff_on_training.take_exam', ['enrollment_id' => encrypt(@$value->id)]) }}">
                                Take Exam
                            </a>
                        @endif
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

                    <td id="col-request-status">
                        @include('hrtraining::enrollments.components.button_training_status', ['status' => @$value->status])
                    </td>

                    <td>{{ dateTimeToStr(@$jsonObj->start_date) }}</td>
                    <td>{{ dateTimeToStr(@$jsonObj->end_date) }}</td>
                    <td>{{ date('d-M-Y H:m:s', strtotime(@$value->created_at)) }}</td>
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