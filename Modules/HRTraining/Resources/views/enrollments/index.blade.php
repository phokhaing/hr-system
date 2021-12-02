@extends('adminlte::page')

@section('title', 'Enrollment')

@section('css')
    <style>
        .modal-body {
            max-width: 100%;
            overflow-x: auto;
        }

        .panel-body {
            max-width: 100%;
            overflow-x: auto;
        }
    </style>

@endsection

@section('content')
@include('partials.breadcrumb')

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="breadcrumb"><a class="btn btn-sm btn-success"
                                   href="{{ route('hrtraining::enrollment.create') }}"> NEW ENROLLMENT</a></div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><label for="">Enrollment List</label></div>
    <div class="panel-body">
        <table class="table table-striped table-hover">
            <tr>
                <th width="150px">Action</th>
                <th>Training Course</th>
                <th>Training Progress</th>
                <th>Training Start Date</th>
                <th>Training End Date</th>
                <th>Enrollment Date</th>
                <th>Review Trainee Exam</th>
            </tr>

            <tbody>
            @foreach($enrollments as $key => $value)
                @php
                    $jsonObj = @to_object(@$value->json_data);
                    $courseObj = @to_object(@$value->course->json_data);
                    $isDisableAction = @$value->status == TRAINING_CONSTANT_TYPE['ENROLLMENT_PROGRESS_COMPLETED']
                @endphp

                <tr>
                    <td>
                        <form id="form_delete" action="{{ route('hrtraining::enrollment.delete', $value->id) }}"
                              method="POST" role="form">
                            {{ Form::token() }}

                            <a class="btn btn-sm btn-primary" title="View Detail"
                               href="{{ route('hrtraining::enrollment.detail', $value->id) }}"><i
                                        class="fa fa-eye-slash"></i></a>

                            <a class="btn btn-sm btn-info {{ $isDisableAction ? 'disabled' : '' }}" title="Update Info"
                               href="{{ route('hrtraining::enrollment.edit', $value->id) }}"><i
                                        class="fa fa-pencil"></i></a>

                            @if(@$value->status == TRAINING_CONSTANT_TYPE['ENROLLMENT_PROGRESS_PENDING'])
                                <a href="javascript:void(0);" class="btn btn-sm btn-danger">
                                    <button title="Delete"
                                            onclick="var x = confirm('Are you sure want to delete this Training Event?'); if(x){this.form.submit();} else return false;"
                                            style="border: 0;background: none"><i class="fa fa-trash"></i>
                                    </button>
                                </a>
                            @endif

                            <a href="#" title="Approve Staff Request join this Training Event!"
                               class="btn btn-sm btn-danger {{ $isDisableAction ? 'disabled' : '' }}"
                               data-toggle="modal" data-target="#form-modal-{{ @$value->id }}">
                                <i class="fa fa-users"></i>
                                {{ count(@$value->traineesRequested) }}
                            </a>
                        </form>
                    </td>

                    <td>{{ @$courseObj->title }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-info" data-toggle="modal"
                           data-target="#modal-remark-progress-{{ @$value->id }}">
                            <i class="fa fa-edit"></i>
                            {{ getEnrollmentProgressStatusKey(@$value->status) }}
                        </a>
                    </td>
                    <td>{{ dateTimeToStr(@$jsonObj->start_date) }}</td>
                    <td>{{ dateTimeToStr(@$jsonObj->end_date) }}</td>
                    <td>{{ date('d-M-Y', strtotime(@$value->created_at)) }}</td>
                    <td>
                        <a class="btn btn-sm btn-info"
                           href="{{ route('hrtraining::enrollment.review_trainee_exam', ['enrollment_id' => encrypt($value->id)]) }}">
                            Show Trainees
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {!! $enrollments->links() !!}
    </div>
</div>

@include('hrtraining::enrollments.modals.remark_enrollment_progress', ['enrollments' => @$enrollments])
@include('hrtraining::enrollments.modals.form_approve_staff_join_training', ['key' => @$key, 'enrollments' => @$enrollments])

@endsection

@section('js')
    <script>

        const APPROVED = "<?=TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_APPROVED']?>";
        const REJECTED = "<?=TRAINING_CONSTANT_TYPE['TRAINEE_REQUEST_JOIN_STATUS_REJECTED']?>";

        $(document).ready(function (e) {
            $(".modal-approve").each(function (index) {
                console.log('approve: ' + index);
                const formApprove = $(this);
                formApprove.find(".check_all").click(function () {
                    console.log('click');
                    if ($(this).prop('checked')) {
                        formApprove.find(".checkbox").prop('checked', true);
                    } else {
                        formApprove.find(".checkbox").prop('checked', false);
                    }
                });

                formApprove.find(".btn-approve").click(function () {
                    formApprove.find(".approve_type").val(APPROVED);
                    formApprove.find(".form-approve").submit();
                });

                formApprove.find(".btn-reject").click(function () {
                    formApprove.find(".approve_type").val(REJECTED);
                    formApprove.find(".form-approve").submit();
                });
            });
        });
    </script>
@endsection