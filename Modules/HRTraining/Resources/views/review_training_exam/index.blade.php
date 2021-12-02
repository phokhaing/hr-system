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

<div class="panel panel-default">
    <div class="panel-heading"><label for="">Review Training and Exam</label></div>

    <div class="panel-body">
        <table class="table table-hover">
            <tr>
                <th width="150px">Action</th>
                <th>Training Course</th>
                <th>Progress</th>
                <th>Training Start Date</th>
                <th>Training End Date</th>
                <th>Enrollment Date</th>
            </tr>

            <tbody>
            @foreach($enrollments as $key => $value)
                @php
                    $jsonObj = @to_object(@$value->json_data);
                    $courseObj = @to_object(@$value->course->json_data);
                @endphp

                <tr>
                    <td>
                        <a class="btn btn-sm btn-primary" title="View Detail"
                           href="{{ route('hrtraining::enrollment.detail', $value->id) }}"><i
                                    class="fa fa-eye-slash"></i></a>
                    </td>
                    <td>
                        <a href="{{ route('hrtraining::review_training.list_trainee_by_enrollment',
                                ['enrollment_id' => encrypt(@$value->id)]) }}">
                            {{ @$courseObj->title }}
                        </a>
                    </td>
                    <td>
                        <a href="#" data-toggle="modal" data-target="#modal-remark-progress-{{ @$value->id }}">
                            @include('hrtraining::enrollments.components.button_training_status', ['status' => @$value->status])
                        </a>
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
</div>

@include('hrtraining::enrollments.modals.remark_enrollment_progress', ['enrollments' => @$enrollments])
@endsection

@section('js')
    <script>

    </script>
@endsection