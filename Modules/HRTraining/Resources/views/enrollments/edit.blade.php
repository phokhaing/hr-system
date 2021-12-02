@extends('adminlte::page')

@section('title', 'Update Enrollment Schedule')

@section('css')
    <style>
        .space-top {
            margin-top: 25px;
        }

        .table-container {
            max-width: 100%;
            overflow-x: auto;
        }
    </style>
@endsection

@section('content')
    @php
        $currentCourse = @$enrollment->course;
        $enrollmentObj = to_object(@$enrollment->json_data);
    @endphp

    @include('partials.breadcrumb')

    <form action="{{ route('hrtraining::enrollment.update') }}" method="POST" role="form"
          class="form-horizontal"
          id="form-submit">
        {{ Form::token() }}

        <div class="panel panel-default">
            <div class="panel-heading"><label for="">Update Enrollment Schedule for Training Event > {{@Request::segment(5)==1 ? 'Orientation' : 'Refreshment'}}</label></div>
            <div class="panel-body">
                {{ session()->get('message') }}

                <input type="hidden" name="tem_deleted_trainees" id="tem_deleted_trainees"/>
                <input type="hidden" name="enrollment_id" value="{{ @$enrollment->id }}"/>

                <div class="form-group @if($errors->has('status')) has-error @endif">
                    <label class="col-sm-2" for="status">Training Progress *</label>
                    <div class="col-sm-10">
                        <select class="form-control select2" name="status" id="status"
                                data-live-search="true"
                                required>
                            <option value=""><< Select Progress >></option>
                            @foreach(getEnrollmentProgressStatusList() as $enrollmentProgress)
                                <option value="{{ $enrollmentProgress }}" {{ @$enrollment->status==$enrollmentProgress ? 'selected' : ''  }}>{{ getEnrollmentProgressStatusKey($enrollmentProgress) }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="form-group @if($errors->has('course')) has-error @endif">
                    <label class="col-sm-2" for="course">Training Course *</label>
                    <div class="col-sm-10">
                        <select class="form-control select2" name="course" id="course"
                                data-live-search="true"
                                required>
                            <option value=""><< Select Course >></option>
                            @foreach($courses as $key => $value)

                                <option value="{{ $value->id }}" {{ $currentCourse->id==$value->id ? 'selected' : ''  }}>{{ @$value->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group @if($errors->has('purpose')) has-error @endif">
                    <label class="col-sm-2" for="purpose">Training Purpose</label>
                    <div class="col-sm-10">
                        <textarea id="purpose" name="purpose"
                                  class="form-control">{{ $enrollmentObj->training_purpose }}</textarea>
                    </div>
                </div>

                <div class="form-group @if($errors->has('duration')) has-error @endif">
                    <label class="col-sm-2" for="duration">Training Duration (Days) *</label>
                    <div class="col-sm-10">
                        <input type="number" id="duration" class="form-control" name="duration"
                               value="{{ @$enrollmentObj->duration }}"/>
                    </div>
                </div>

                <div class="form-group @if($errors->has('start_date')) has-error @endif">
                    <label class="col-sm-2" for="start_date">Training Duration (Days) *</label>
                    <div class="col-sm-10">
                        <input type="text" name="start_date" class="form-control" id="start_date"
                               value="{{ dateTimeToStr(@$enrollmentObj->start_date) }}" required/>
                    </div>
                </div>

                <div class="form-group @if($errors->has('end_date')) has-error @endif">
                    <label class="col-sm-2" for="end_date">Training Duration (Days) *</label>
                    <div class="col-sm-10">
                        <input type="text" name="end_date" class="form-control" id="end_date"
                               value="{{ dateTimeToStr(@$enrollmentObj->end_date) }}" required/>
                    </div>
                </div>

                <div class="form-group @if($errors->has('training_class')) has-error @endif">
                    <label class="col-sm-2" for="training_class">Training Class</label>
                    <div class="col-sm-1">
                        <div class="radio">
                            <label>
                                <input type="radio" id="training_class" name="training_class"
                                       {{ @$enrollment->class_type == CLASS_TYPE['ONLINE'] ? 'checked' : '' }}
                                       value="{{ CLASS_TYPE['ONLINE'] }}">
                                {{ getTrainingClassLabel(CLASS_TYPE['ONLINE']) }}
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="radio">
                            <label>
                                <input type="radio" id="training_class" name="training_class"
                                       {{ @$enrollment->class_type == CLASS_TYPE['ONCLASS'] ? 'checked' : '' }}
                                       value="{{ CLASS_TYPE['ONCLASS'] }}">
                                {{ getTrainingClassLabel(CLASS_TYPE['ONCLASS']) }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- .panel-default -->

        <div class="panel panel-default">
            <div class="panel-heading"><label for="">Update Trainees</label></div>

            <div class="panel-body">
                <div class="box-body">
                    <div class="form-group @if($errors->has('company')) has-error @endif">
                        <label class="col-sm-2" for="company">Select Company</label>
                        <div class="col-sm-10">
                            <select class="form-control select2" name="company" id="company"
                                    data-live-search="true">
                                <option value="0"><< All >></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group @if($errors->has('department')) has-error @endif">
                        <label class="col-sm-2" for="department">Select Department/Branch</label>
                        <div class="col-sm-10">
                            <select class="form-control select2" name="department" id="department"
                                    data-live-search="true">
                                <option value="0"><< All >></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group @if($errors->has('trainees')) has-error @endif">
                        <label class="col-sm-2" for="staff">Select Staff</label>
                        <div class="col-md-10">
                            <select class="form-control select2" name="staff" id="staff"
                                    data-live-search="true">
                                <option value="0"><< All >></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group pull-right">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-info" id="btn-add-staff">
                                <i class="fa fa-plus"></i>
                                Add Staff
                            </button>
                        </div>
                    </div>

                    <div class="form-group space-top @if($errors->has('trainees')) has-error @endif">
                        <div class="col-sm-12  table-container">
                            <label>Trainee List</label>
                            <table class="table table-striped table-hover" id="table_staff_list">
                                <tr>
                                    <th>Action</th>
                                    <th>Staff ID</th>
                                    <th>Full Name</th>
                                    <th>Gender</th>
                                    <th>position</th>
                                    <th>Department/Branch</th>
                                    <th>Company</th>
                                </tr>

                                <tbody id="staff_list_body">

                                @foreach($enrollment->trainees as $key => $value)
                                    @php
                                        $staff = @$value->staff;
                                        $contractObj = $value->contract->contract_object;

                                        $companyObj = @$contractObj['company'];
                                        $positionObj = @$contractObj['position'];
                                        $branchAndDepartmentObj = @$contractObj['branch_department'];
                                        $fullname = @$staff->last_name_kh . ' ' . @$staff->first_name_kh;
                                        $gender = GENDER[@$staff->gender] ?? 'N/A';

                                         $trainee = json_encode([
                                             'trainee_id' => @$value->id,
                                             'staff_id' => @$staff->staff_id,
                                             'contract_id' => @$value->contract_id
                                         ]);
                                    @endphp

                                    <tr id="tr-{{ @$staff->id }}">
                                        <td>
                                            <input type="hidden" id="staff_id" value="{{ @$staff->id }}"/>
                                            <input type="hidden" name="trainee_id" id="trainee_id"
                                                   value="{{ @$value->id }}">
                                            <a href="#" id="remove-staff-{{ @$staff->id }}"><i
                                                        class="fa fa-trash"></i></a>
                                            <input type="hidden" id="trainee-{{ @$staff->id }}" name="trainees[]"
                                                   value="{{ @$trainee }}"/>
                                        </td>
                                        <td>
                                            {{ @$staff->id }}
                                        </td>
                                        <td>
                                            {{ $fullname }}
                                        </td>
                                        <td>
                                            {{ $gender }}
                                        </td>
                                        <td>
                                            {{ @$positionObj['name_kh'] }}
                                        </td>
                                        <td>
                                            {{ @$branchAndDepartmentObj['name_kh'] }}
                                        </td>
                                        <td>
                                            {{ @$companyObj['name_kh'] }}
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>

                            </table>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <hr/>
                            <button type="submit" class="btn btn-primary" id="btn-submit">Update Enrollment</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
@stop

@section('js')
    <script>

        $(document).ready(function (e) {
            $('.select2').select2();

            $("#start_date").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-M-yy',
                minDate: '-10y'
            });

            $("#end_date").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-M-yy',
                minDate: '-10y'
            });

            //Select Change
            getCompany();
            $('#company').change(function (e) {
                const code = $(this).find(':selected').val();
                console.log('company_dode' + code);
                if (code == 0) {
                    $("#department").empty().append(
                        '<option value="0"> << All >> </option>'
                    )
                } else {
                    getDepartment(code);
                }
            });

            $('#department').change(function (e) {
                const code = $(this).find(':selected').val();
                console.log('depatment_code' + code);
                if (code == 0) {
                    $("#staff").empty().append(
                        '<option value="0"> << Select Staff >> </option>'
                    )
                } else {
                    getTrainees(code);
                }
            });
            //End Select Change

            const tableBody = $('#staff_list_body');
            const staffIds = [];
            $("#btn-add-staff").click(function () {
                const currentStaff = $("select#staff").find(':selected').val();
                console.log('currentStaff: ' + currentStaff);
                if (currentStaff) {
                    if (checkIsAlreadyAdded(currentStaff, staffIds)) {
                        // $("select#staff").prop('required', true);
                        return;
                    }

                    const fullname = $("select#staff").find(':selected').data('fullname');
                    const gender = $("select#staff").find(':selected').data('gender');
                    const position = $("select#staff").find(':selected').data('position');
                    const department = $("select#staff").find(':selected').data('department');
                    const company = $("select#staff").find(':selected').data('company');
                    const contractId = $("select#staff").find(':selected').data('contract_id');

                    const trainee = {
                        staff_id: currentStaff,
                        contract_id: contractId
                    };

                    const tr = '<tr id="tr-' + currentStaff + '">' +
                        '<td>' +
                        '<a href="#" id="remove-staff-' + currentStaff + '"><i class="fa fa-trash"></i></a> ' +
                        '<input type="hidden" id="trainee-' + currentStaff + '" name="trainees[]"/>' +
                        '</td>' +
                        '<td>' + currentStaff + '</td>' +
                        '<td>' + fullname + '</td>' +
                        '<td>' + gender + '</td>' +
                        '<td>' + position + '</td>' +
                        '<td>' + department + '</td>' +
                        '<td>' + company + '</td>' +
                        '</tr>';

                    tableBody.append(tr);
                    staffIds.push(currentStaff);

                    tableBody.find('input#trainee-' + currentStaff).val(JSON.stringify(trainee));
                    tableBody.find('a#remove-staff-' + currentStaff).click(function () {
                        removeItem(currentStaff, staffIds);
                        tableBody.find('tr#tr-' + currentStaff).remove();
                    });
                } else {
                    // $("select#staff").prop('required', true);
                }
            });

            //Loop child in tbody for delete
            const tempTraineeDeleted = [];
            $('tbody#staff_list_body').find('tr').each(function (index) {
                const staffId = $(this).find('#staff_id').val();
                console.log('index: ' + index + ', staffId: ' + staffId);
                staffIds.push(staffId);

                tableBody.find('a#remove-staff-' + staffId).click(function () {
                    removeItem(staffId, staffIds);

                    const rowItem = tableBody.find('tr#tr-' + staffId);
                    tempTraineeDeleted.push(rowItem.find('#trainee_id').val());
                    $("#tem_deleted_trainees").val(JSON.stringify(tempTraineeDeleted));
                    rowItem.remove();
                });
            });


            function checkIsAlreadyAdded(id, currentList) {
                return currentList.some(function (i) {
                    if (i === id) {
                        return true
                    }
                });
            }

            function removeItem(id, currentList) {
                for (let i = 0; i < currentList.length; i++) {
                    if (currentList[i] === id) {
                        currentList.splice(i, 1);
                        break;
                    }
                }
            }

            //Request Route
            function getCompany() {
                axios.get("/company/company-for-training")
                    .then(response => {
                        console.log('success api: ' + $.type(response.data));
                        response.data.data.forEach(function (company, index) {
                            $("#company").append(
                                '<option value="' + company.code + '">' + company.name_kh + ' (' + company.short_name + ')</option>'
                            );
                        });
                    }).catch(err => {
                    console.log('error api: ' + err);
                })

            }

            function getDepartment(code) {
                axios.get("/branch-and-department/" + code + "/all")
                    .then(response => {
                        console.log('success api: ' + $.type());
                        $("#department").empty().append(
                            '<option value="0"> << All >> </option>'
                        );
                        response.data.data.forEach(function (departmentBranch, index) {
                            $("#department").append(
                                '<option value="' + departmentBranch.code + '">' + departmentBranch.name_km + ' (' + departmentBranch.short_name + ')</option>'
                            );
                        });
                    }).catch(err => {
                    console.log('error api: ' + err);
                })
            }

            function getTrainees(code) {
                const companyCode = $("#company").find(':selected').val();
                axios.get("/hrtraining/enrollment/staff-current-contract-by-department-branch/" + companyCode + "/" + code + "/all")
                    .then(response => {
                        console.log('success api: staff ' + response.data.data.length);

                        // $("#staff").empty().append(response.data.data);

                        $("#staff").empty().append(
                            '<option value="0"> << All >> </option>'
                        );

                        response.data.data.forEach(function (contract, index) {
                            const staff = contract.staff_personal_info;
                            console.log('staff : ' + staff);

                            const fullname = staff.last_name_kh + ' ' + staff.first_name_kh;
                            const gender = getGender(staff.gender);

                            const position = contract.contract_object.position;
                            const company = contract.contract_object.company;
                            const department = contract.contract_object.branch_department;
                            const contractId = contract.id;

                            $("#staff").append(
                                '<option value="' + staff.id + '"'
                                + 'data-contract_id="' + contractId + '"'
                                + 'data-position="' + position.name_kh + '"'
                                + 'data-position_id="' + position.id + '"'
                                + 'data-department_branch_id="' + department.id + '"'
                                + 'data-department="' + department.name_kh + '"'
                                + 'data-company_id="' + company.id + '"'
                                + 'data-company="' + company.name_kh + '"'
                                + 'data-gender="' + gender + '"'
                                + 'data-fullname="' + fullname + '">'
                                + fullname
                                + '</option>'
                            );


                        });

                    }).catch(err => {
                    console.log('error api: ' + err);
                })
            }

            //End Request Route

            function getGender(code) {
                // const GENDER = [
                //     "0" => "ប្រុស",
                //     "1" => "ស្រី"
                // ];
                return (code == 0) ? 'ប្រុស' : 'ស្រី';
            }
        });
    </script>
@endsection
