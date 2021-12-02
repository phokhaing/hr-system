@extends('adminlte::page')

@section('title', 'Enrollment')

@section('css')
    <style>
        .panel-body {
            max-width: 100%;
            overflow-x: auto;
        }

        #myUL {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        #myUL li a {
            border-bottom: 1px solid #ddd;
            margin-top: -1px; /* Prevent double borders */
            /*background-color: #f6f6f6;*/
            padding: 12px;
            text-decoration: none;
            /*font-size: 18px;*/
            color: black;
            display: block
        }

        .my-active {
            background-color: #eee;
        }

        #myUL li a:hover:not(.header) {
            background-color: #eee;
        }
    </style>

@endsection

@section('content')
    @include('partials.breadcrumb')

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="breadcrumb"><a class="btn btn-sm btn-primary"
                                       href="{{ route('hrtraining::enrollment.create', ['category_id' => Request::segment(3)]) }}"> NEW ENROLLMENT</a></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-title">
                <div class="panel-heading">
                    <label>
                        Branch/Department
                    </label>
                    <input type="search" onkeyup="myFunction()" class="form-control" id="myInput"
                           name="search"
                           placeholder="Search..."/>
                </div>

                <div class="panel-body">
                    <ul id="myUL">
                        @foreach($departments as $key => $value)
                            <li class="active">
                                <a href="{{ route('hrtraining::enrollment.index', ['id' => request('id'),'department-id' => encrypt(@$value->id)]) }}"
                                   class="{{ @$currentSelect == @$value->id ? 'my-active' : '' }}">
                                    {{ @$value->name_en}}
                                    <label> ({{@$value->company->short_name}})</label>
                                </a>

                            </li>
                        @endforeach
                    </ul>

                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="panel panel-title">
                <div class="panel-heading">
                    <label>{{@$category->title}} Course List</label>
                </div>

                <div class="panel-body">
                    <table class="table table-striped table-hover">
                        <tr>
                            <th width="150px">Action</th>
                            <th>Course Title</th>
                            <th>Progress</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Enrollment Date</th>
                            <th>Review Exam</th>
                        </tr>

                        <tbody>
                        @if($enrollments && count($enrollments))
                            @foreach($enrollments as $key => $value)
                                @php
                                    $jsonObj = @to_object(@$value->json_data);
                                    $courseObj = @to_object(@$value->course->json_data);
                                    $isDisableAction = @$value->status == TRAINING_CONSTANT_TYPE['ENROLLMENT_PROGRESS_COMPLETED']
                                @endphp

                                <tr>
                                    <td>
                                        <form id="form_delete"
                                              action="{{ route('hrtraining::enrollment.delete', $value->id) }}"
                                              method="POST" role="form">
                                            {{ Form::token() }}

                                            <a class="btn btn-sm btn-primary" title="View Detail"
                                               href="{{ route('hrtraining::enrollment.detail', $value->id) }}"><i
                                                        class="fa fa-eye-slash"></i></a>

                                            <a class="btn btn-sm btn-info {{ $isDisableAction ? 'disabled' : '' }}"
                                               title="Update Info"
                                               href="{{ route('hrtraining::enrollment.edit', ['id' => $value->id, 'category_id' => Request::segment(3)]) }}"><i
                                                        class="fa fa-pencil"></i></a>

                                            @if(@$value->status == TRAINING_CONSTANT_TYPE['ENROLLMENT_PROGRESS_PENDING'])
                                                <a href="javascript:void(0);" class="btn btn-sm btn-danger">
                                                    <button title="Delete"
                                                            onclick="var x = confirm('Are you sure want to delete this Training Event?'); if(x){this.form.submit();} else return false;"
                                                            style="border: 0;background: none"><i
                                                                class="fa fa-trash"></i>
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
                        @else
                            <tr>
                                <td colspan="7">Empty!</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>

                    {!! $enrollments->links() !!}
                </div>
            </div>
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

        function myFunction() {
            let input, filter, ul, li, a, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            ul = document.getElementById("myUL");
            li = ul.getElementsByTagName("li");
            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("a")[0];
                txtValue = a.textContent || a.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }
    </script>
@endsection