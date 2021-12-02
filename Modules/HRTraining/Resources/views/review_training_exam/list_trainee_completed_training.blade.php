@extends('adminlte::page')

@section('title', 'Review Training And Exam')

@section('content')

@section('css')
    <style>
        .modal-body {
            max-width: 100%;
            overflow-x: auto;
        }
    </style>

@endsection

@include('partials.breadcrumb')
@include('hrtraining::layouts.layout_flash_error', ['message' => @$message])

@php
    $course = @$enrollment->course;
@endphp

<div class="panel panel-default">
    <div class="panel-heading">
        <label for="">Review Training and Exam​ > {{ @$course->json_data->title }}</label>
    </div>

    <div class="panel-body">
        <table class="table table-hover">
            <tr>
                <th width="150px">Action</th>
                <th>Trainee Name</th>
                <th>Trainee Progress</th>
                <th>Total Score</th>
                <th>Range</th>
            </tr>

            <tbody>
            @foreach($trainees as $key => $trainee)
                @php
                    $staff = @$trainee->staff;
                    $isAllowReviewExam = @$trainee->training_status == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_FINISH_EXAM'] || @$trainee->training_status == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_COMPLETED_TRAINING'];
                @endphp

                <tr>
                    <td>
                        <a href="{{ route('hrtraining::review_training.review_exam', ['enrollment_id' => encrypt(@$enrollment->id), 'trainee_id' => @$trainee->id, 'course_id' => @$course->id]) }}"
                           class="btn btn-sm btn-info {{ $isAllowReviewExam ? '' : 'disabled' }}">
                            Review Exam
                        </a>
                    </td>
                    <td>
                        {{ $staff->last_name_kh . ' ' . $staff->first_name_kh }}
                    </td>
                    <td>
                        <a href="#" data-toggle="modal" data-target="#modal-remark-progress-{{ $trainee->id }}">
                            @include('hrtraining::review_training_exam.components.layout_staff_training_status', ['training_status' => @$trainee->training_status])
                        </a>
                    </td>
                    <td>
                        @if($trainee->traineeResult)
                            <label class="badge bg-red">
                                {{ @$trainee->traineeResult->json_data->total_point }}
                            </label>
                        @endif
                    </td>
                    <td>N/A</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {!! $trainees->links() !!}
    </div>
</div>

{{--@include('hrtraining::review_training_exam.modals.remark_enrollment_progress', ['trainees' => @$trainees])--}}

@endsection

@section('js')
    <script>

    </script>
@endsection