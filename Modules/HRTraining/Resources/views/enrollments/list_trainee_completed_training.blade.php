@extends('adminlte::page')

@section('title', 'Review Training And Exam')

@section('content')

@section('css')
<style>
    .panel-body {
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
        <h4>
            <i class="fa fa-book"></i>
            {{ @$course->json_data->title }}
        </h4>
    </div>

    <div class="panel-body">

        <form action="{{ route('hrtraining::enrollment.review_trainee_exam') }}" role="form" method="get" id="filter-form">
            {{ csrf_field() }}
            <input type="hidden" name="enrollment_id" value="{{ @request('enrollment_id') }}"/>
            <div class="row">
                <div class="form-group col-sm-6 col-md-6">
                    <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Search..." value="{{ request('keyword') }}">
                </div>

                <div class="form-group col-sm-6 col-md-6">
                    <button type="submit" class="btn btn-primary margin-r-5"><i class="fa fa-search"></i> Search</button>
                    <button type="button" class="btn btn-danger margin-r-5" id="btn-clear"><i class="fa fa-remove"></i> Clear</button>
                </div>
            </div>
        </form>

        <table class="table table-striped table-hover">
            <tr>
                <th width="150px">Action</th>
                <th>Trainee Name</th>
                <th>Trainee Progress</th>
                <th>Total Score</th>
                <th>Average</th>
            </tr>

            <tbody>
                @foreach($trainees as $key => $trainee)
                @php
                $staff = @$trainee->staff;
                $isAllowReviewExam = @$trainee->training_status == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_FINISH_EXAM'] || @$trainee->training_status == TRAINING_CONSTANT_TYPE['TRAINEE_PROGRESS_COMPLETED_TRAINING'];
                @endphp

                <tr>
                    <td>
                        <a href="{{ route('hrtraining::enrollment.review_exam', ['enrollment_id' => encrypt(@$enrollment->id), 'trainee_id' => @$trainee->id, 'course_id' => @$course->id]) }}" class="btn btn-sm btn-info {{ $isAllowReviewExam ? '' : 'disabled' }}">
                            Review Exam
                        </a>
                    </td>
                    <td>
                        {{ $staff->last_name_kh . ' ' . $staff->first_name_kh }}
                    </td>
                    <td>
                        <a href="#" class="btn btn-success" data-toggle="modal" data-target="#modal-remark-progress-{{ $trainee->id }}">
                            <i class="fa fa-edit"></i>
                            @if(@$trainee->training_status)
                            {{ getTraineeProgressStatus(@$trainee->training_status) }}
                            @else
                            N/A
                            @endif
                        </a>
                    </td>
                    <td>
                        @if($trainee->traineeResult)
                        <label class="badge bg-red">
                            {{ number_format(@$trainee->traineeResult->json_data->total_point, 2) }}
                        </label>

                        @else
                        N/A
                        @endif
                    </td>
                    <td>
                        @if($trainee->traineeResult)
                        <label class="badge bg-red">
                            {{ number_format(@$trainee->traineeResult->json_data->average, 2) }}
                        </label>
                        @else
                        N/A
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {!! $trainees->appends(['enrollment_id' => @request('enrollment_id')])->links() !!}
    </div>
</div>

@include('hrtraining::enrollments.modals.remark_trainee_progress', ['trainees' => @$trainees])

@endsection

@section('js')
<script>

</script>
@endsection