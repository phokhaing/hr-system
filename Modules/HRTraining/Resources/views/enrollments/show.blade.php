@extends('adminlte::page')

@section('title', 'Enrollment Detail')

@section('css')
    <style>
        .panel-body {
            max-width: 100%;
            overflow-x: auto;
        }
    </style>
@endsection

@section('content')

    @include('partials.breadcrumb')

    @php
        $enrollmentObj = @$enrollment->json_data;
        $courseObj = @$enrollment->course->json_data;
        $categoryObj = @$enrollment->course->category->json_data;
    @endphp

    <div class="panel panel-default">
        <div class="panel-heading">
            <label for="">Enrollment Information</label>
            <a class="btn btn-xs btn-primary pull-right" href="{{ route('hrtraining::enrollment.index', ['id' => CATEGORY_ORIENTATION]) }}"> Back</a>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
                    <label for="">Category:</label> {{ @$categoryObj->title_en ?: 'N/A' }}
                    <hr/>

                    <label for="">Training Course:</label> {{ @$courseObj->title ?: 'N/A' }}
                    <hr/>

                    <label for="">Course Price($):</label> {{ @$courseObj->cost ?: 'N/A' }}
                    <hr/>

                    <label for="">Course Status:</label>
                    @if($courseObj->status)
                        <span class="badge badge-pill badge-info">{{ COURSE_STATUS[@$courseObj->status] }}</span>
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

                </div>
            </div>
        </div>
    </div> <!-- .panel-heading -->

    <div class="panel panel-default">
        <div class="panel-heading">
            <label for="">Trainee List</label>
        </div>
        <div class="panel-body">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Staff ID</th>
                    <th>Full Name</th>
                    <th>Gender</th>
                    <th>position</th>
                    <th>Department/Branch</th>
                    <th>Company</th>
                </tr>
                </thead>

                <tbody>
                @foreach(@$enrollment->traineesAddedFromAdmin as $key => $value)
                    @php
                        $trainee = @$value->staff;
                        $company = @$trainee->contract->contract_object['company'];
                        $position = @$value->contract->contract_object['position'];
                        $branchDepartment = @$value->contract->contract_object['branch_department'];
                    @endphp

                    <tr>
                        <td>{{ @$trainee->staff_id }}</td>
                        <td>{{ @$trainee->last_name_kh . ' ' .@$trainee->first_name_kh  }}</td>
                        <td>{{ GENDER[@$trainee->gender] ?? 'N/A' }}</td>
                        <td>{{ @$position['name_kh'] ?: @$position['name_en'] }}</td>
                        <td>{{ @$branchDepartment['name_kh'] ?: @$branchDepartment['name_en'] }}</td>
                        <td>{{ @$company['name_kh'] ?: @$company['name_en'] }}</td>
                    </tr>
                @endforeach
                </tbody>

            </table>
            <label>Total Record: {{ count(@$enrollment->traineesAddedFromAdmin) }}</label>

        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <label for="">Trainee Requested List</label>
        </div>
        <div class="panel-body">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Staff ID</th>
                    <th>Full Name</th>
                    <th>Gender</th>
                    <th>position</th>
                    <th>Department/Branch</th>
                    <th>Company</th>
                    <th>Status</th>
                </tr>
                </thead>

                <tbody>
                @foreach(@$enrollment->traineesRequested as $key => $value)
                    @php
                        $trainee = @$value->staff;
                        $company = @$trainee->contract->contract_object['company'];
                        $position = @$value->contract->contract_object['position'];
                        $branchDepartment = @$value->contract->contract_object['branch_department'];
                    @endphp

                    <tr>
                        <td>{{ @$trainee->staff_id }}</td>
                        <td>{{ @$trainee->last_name_kh . ' ' .@$trainee->first_name_kh  }}</td>
                        <td>{{ GENDER[@$trainee->gender] ?? 'N/A' }}</td>
                        <td>{{ @$position['name_kh'] ?: @$position['name_en'] }}</td>
                        <td>{{ @$branchDepartment['name_kh'] ?: @$branchDepartment['name_en'] }}</td>
                        <td>{{ @$company['name_kh'] ?: @$company['name_en'] }}</td>
                        <td>
                            <span class="badge bg-blue-active">
                                {{ getTraineeRequestJoinStatusKey($value->request_join_status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
            <label>Total Record: {{ count(@$enrollment->traineesRequested) }}</label>

        </div>
    </div>

@endsection