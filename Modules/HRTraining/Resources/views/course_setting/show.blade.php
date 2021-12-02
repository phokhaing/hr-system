@extends('adminlte::page')

@section('title', 'Course Detail')

@section('content')

    @include('partials.breadcrumb')

    @php
        $courseObj = @$course->json_data;
        $categoryObj = @$course->category->json_data;
        $positionObj = @$course->position;
    @endphp

    <div class="panel panel-default">
        <div class="panel-heading">
            <label for="">Course Detail</label>
            <a class="btn btn-xs btn-primary pull-right" href="{{ route('hrtraining::course.setting') }}"> Back</a>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
                    <label for="">{{ __('hrtraining::label.course_category') }}:</label> {{ @$categoryObj->title_en ?: 'N/A' }}
                    <hr>
                    <label for="">{{ __('hrtraining::label.course_title') }}:</label> {{ @$courseObj->title ?: 'N/A' }}
                    <hr>
                    <label for="">{{ __('hrtraining::label.description') }}:</label> {{ @$courseObj->description ?: 'N/A' }}
                    <hr>
                    <label for="">{{ 'Branch/Department' }}:</label> {{ @$course->branchDepartment->name_en . ' ('. @$course->branchDepartment->short_name .')' }}
                    <hr>
                    <label for="">{{ __('hrtraining::label.course_for_position') }}:</label> {{ @$positionObj->name_en . ' ('. @$positionObj->short_name .')' }}
                    <hr>
                    <label for="">{{ __('hrtraining::label.cost') }}: </label> {{ $courseObj->cost ? number_format($courseObj->cost) : 'N/A' }}
                    <hr>
                    <label for="">{{ __('hrtraining::label.frequency') }}:</label> {{ $courseObj->frequency ?: 'N/A' }}
                    <hr/>
                    <label for="">{{ __('hrtraining::label.grade') }}:</label> {{ $courseObj->grade ?: 'N/A' }}
                    <hr/>

                    <label for="">{{ __('hrtraining::label.duration') }}:</label> {{ $courseObj->duration ?: 'N/A' }}
                    <hr/>

                    <label for="">{{ __('hrtraining::label.status') }}:</label>
                    @if($courseObj->status)
                    <span class="badge badge-pill badge-info">{{ COURSE_STATUS[@$courseObj->status] }}</span>
                    @else
                    N/A
                    @endif
                    <hr/>

                    <label for="">{{ __('hrtraining::label.created_by') }}:</label> {{ @$course->createdBy->full_name ?: 'N/A' }}
                    <hr/>

                    <label for="">{{ __('hrtraining::label.created_date') }}:</label> {{ @$course->created_at ? date('d-M-Y', strtotime($course->created_at)) : 'N/A' }}
                </div>
            </div>
        </div>
    </div> <!-- .panel-heading -->

@endsection