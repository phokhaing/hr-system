@extends('adminlte::page')

@section('title', 'Start Training')

@section('content')

@section('css')
    <style>
        .max-line {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 1; /* number of lines to show */
            -webkit-box-orient: vertical;
        }

    </style>
@endsection

@php
    $courseObj = to_object(@$course->json_data);
    $enrollmentObj = to_object($enrollment->json_data);
@endphp

@include('partials.breadcrumb')
@include('hrtraining::layouts.layout_flash_error', ['message' => @$message])

<div class="panel panel-default">
    <div class="panel-heading">
        <a target="_blank"
           href="{{ route('hrtraining::course.setting.detail', ['id' => @$course->id]) }}">
            <h4>
                <i class="fa fa-book"></i>
                {{ @$courseObj->title }}
            </h4>
        </a>


    </div>

    <div class="panel-body">

        <dl class="dl-horizontal">

            <dt>Duration</dt>
            <dd>
                {{ (@$enrollmentObj->duration) ? @$enrollmentObj->duration .' (Days)' : 'N/A' }}
            </dd>

            <dt>Start From Date</dt>
            <dd class="">
                {{ dateTimeToStr(@$enrollmentObj->start_date) }} - {{ dateTimeToStr(@$enrollmentObj->end_date) }}
            </dd>

            <dt>Training Purpose</dt>
            <dd>
                {{ @$enrollmentObj->training_purpose ?: 'N/A' }}
            </dd>
        </dl>

    </div>
</div>

@include('hrtraining::staff_on_training.components.template_training_course_content',
        [
            'course_contents' => @$courseContents,
            'current_content' => @$courseContent,
            'enrollment_id' => @$enrollment->id
        ]
    )

@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $("#btn-complete-training").click(function (e) {
                Swal.fire({
                    title: 'Complete My Training',
                    text: "Are you sure to complete this training course ?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes and Continue to Exam'
                }).then(function (result) {
                    if (result.value) {
                        $("#form-complete-my-training").submit();
                    }
                });
            });
        });
    </script>
@endsection