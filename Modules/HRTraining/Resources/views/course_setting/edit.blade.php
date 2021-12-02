@extends('adminlte::page')

@section('title', 'Edit Course')

@section('content')

    @include('partials.breadcrumb')

    @php
        $courseObj = @$course->json_data;
    @endphp

    <div class="panel panel-default">
        <div class="panel-heading"><label for="">Edit Course</label></div>
        <div class="panel-body">
            {{ session()->get('message') }}
            <div class="row">
                <div class="col-sm-12">
                    <form action="{{ route('hrtraining::course.setting.update') }}" method="POST" role="form">
                        {{ Form::token() }}
                        <input type="hidden" id="course_id" name="course_id" value="{{$course->id}}"/>
                        <div class="row">

                            <div class="form-group col-sm-12 col-md-12 @if($errors->has('category')) has-error @endif">
                                <?php
                                form_label([
                                    'for' => 'category',
                                    'value' => __('hrtraining::label.category') . ' *',
                                ]);
                                ?>
                                <select class="form-control" name="category" id="category"
                                        data-live-search="true"
                                        required>
                                    <option value=""><< {{ __('hrtraining::label.select_category') }} >></option>
                                    @foreach($categories as $key => $value)

                                        @php
                                            $jsonObj = @to_object(@$value->json_data);
                                        @endphp

                                        <option value="{{ $value->id }}" {{ @$course->category_id == $value->id ? 'selected':'' }}>{{ @$jsonObj->title_en }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('course_title')) has-error @endif">
                                <?php
                                form_label([
                                    'for' => 'course_title',
                                    'value' => __('hrtraining::label.course_title') . ' *',
                                ]);
                                form_text([
                                    'name' => 'course_title',
                                    'class' => 'form-control',
                                    'id' => 'course_title',
                                    'value' => @$courseObj->title,
                                    'required' => true
                                ]);
                                ?>
                            </div>
                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('cost')) has-error @endif">
                                <?php
                                form_label([
                                    'for' => 'cost',
                                    'value' => __('hrtraining::label.cost'),
                                ]);
                                form_number([
                                    'name' => 'cost',
                                    'class' => 'form-control',
                                    'id' => 'cost',
                                    'value' => @$courseObj->cost
                                ]);
                                ?>
                            </div>

                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('frequency')) has-error @endif">
                                <?php
                                form_label([
                                    'for' => 'frequency',
                                    'value' => __('hrtraining::label.frequency'),
                                ]);
                                form_number([
                                    'name' => 'frequency',
                                    'class' => 'form-control',
                                    'id' => 'frequency',
                                    'value' => @$courseObj->frequency
                                ]);
                                ?>
                            </div>

                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('grade')) has-error @endif">
                                <?php
                                form_label([
                                    'for' => 'grade',
                                    'value' => __('hrtraining::label.grade'),
                                ]);
                                form_number([
                                    'name' => 'grade',
                                    'class' => 'form-control',
                                    'id' => 'grade',
                                    'value' => @$courseObj->grade
                                ]);
                                ?>
                            </div>

                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('duration')) has-error @endif">
                                <?php
                                form_label([
                                    'for' => 'duration',
                                    'value' => __('hrtraining::label.duration'),
                                ]);
                                form_number([
                                    'name' => 'duration',
                                    'class' => 'form-control',
                                    'id' => 'duration',
                                    'value' => @$courseObj->duration
                                ]);
                                ?>
                            </div>

                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('company')) has-error @endif">
                                <label for="company">Select Company *</label>
                                <select class="form-control select2" name="company" id="company"
                                        data-live-search="true" required>
                                    <option value="0"><< Select One >></option>
                                </select>
                            </div>

                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('department')) has-error @endif">
                                <label for="department">Select Department/Branch *</label>
                                <select class="form-control select2" name="department" id="department"
                                        data-live-search="true" required>
                                    <option value="0"><< Select One >></option>
                                </select>
                            </div>

                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('position')) has-error @endif">
                                <?php
                                form_label([
                                    'for' => 'position',
                                    'value' => __('hrtraining::label.link_to_position') . ' *',
                                ]);
                                ?>
                                <select class="form-control select2" name="position" id="position"
                                        required>
                                    <option value="0"><< {{ __('hrtraining::label.select_position') }} >></option>
                                </select>
                            </div>

                            <div class="form-group col-sm-12 col-md-12 @if($errors->has('desc')) has-error @endif">
                                <?php
                                form_label([
                                    'for' => 'desc',
                                    'value' => __('hrtraining::label.description'),
                                ]);
                                form_textarea([
                                    'name' => 'desc',
                                    'class' => 'form-control',
                                    'id' => 'desc',
                                    'value' => @$courseObj->description
                                ]);
                                ?>
                            </div>

                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('status')) has-error @endif">
                                <div class="row">
                                    <div class="col-md-3">
                                        <?php
                                        form_label([
                                            'for' => 'status',
                                            'value' => __('hrtraining::label.status') . ' *',
                                        ]);
                                        ?>
                                    </div>
                                    <div class="col-md-3">
                                        {{ Form::radio('status', '1', @$courseObj->status ? true : false, ['class' => 'field']) }}
                                        Internal
                                    </div>

                                    <div class="col-md-3">
                                        {{ Form::radio('status', '0', !@$courseObj->status ? true : false, ['class' => 'field']) }}
                                        External
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> <!-- .panel-default -->
@stop

@section('js')
    <script>
        $(document).ready(function (e) {
            $('.select2').select2();

            getCompany();
            $('#company').change(function (e) {
                const code = $(this).find(':selected').val();
                console.log('company_dode' + code);
                if (code == 0) {
                    $("#department").empty().append(
                        '<option value="0"> << Select One >> </option>'
                    );
                    $("#position").empty().append(
                        '<option value="0"> << Select One >> </option>'
                    );
                } else {
                    getDepartment(code);
                    getPosition(code);
                }
            });

            //Request Route
            function getCompany() {
                let companyCode = "<?= @$course->branchDepartment->company_code ?: 0; ?>";
                console.log(companyCode + ' adlkasd');
                axios.get("/company/company-for-training")
                    .then(response => {
                        console.log('success api: ' + $.type(response.data));
                        response.data.data.forEach(function (company, index) {
                            console.log('companycode: ' + company.code)
                            if (companyCode == company.code) {
                                $("#company").append(
                                    '<option value="' + company.code + '" selected>' + company.name_kh + ' (' + company.short_name + ')</option>'
                                );
                            } else {
                                $("#company").append(
                                    '<option value="' + company.code + '">' + company.name_kh + ' (' + company.short_name + ')</option>'
                                );
                            }
                            $("#company option[value=" + companyCode + "]").change();

                        });

                    }).catch(err => {
                    console.log('error api: ' + err);
                })

            }

            function getDepartment(code) {
                let branchDepartmentCode = "<?= @$course->branchDepartment->code ?: 0; ?>";
                axios.get("/branch-and-department/" + code + "/all")
                    .then(response => {
                        console.log('success api: ' + $.type(response.data));
                        $("#department").empty().append(
                            '<option value="0"> << Select One >> </option>'
                        );
                        response.data.data.forEach(function (departmentBranch, index) {
                            if (departmentBranch.code == branchDepartmentCode) {
                                $("#department").append(
                                    '<option value="' + departmentBranch.id + '" selected>' + departmentBranch.name_km + ' (' + departmentBranch.short_name + ')</option>'
                                );
                            } else {
                                $("#department").append(
                                    '<option value="' + departmentBranch.id + '">' + departmentBranch.name_km + ' (' + departmentBranch.short_name + ')</option>'
                                );
                            }
                            $("#department option[value=" + branchDepartmentCode + "]").change();
                        });
                    }).catch(err => {
                    console.log('error api: ' + err);
                })
            }

            function getPosition(code) {
                let positionId = "<?=@$courseObj->position ?: 0 ?>";
                axios.get("/position/" + code + "/all")
                    .then(response => {
                        console.log('success api: ' + $.type());
                        $("#position").empty().append(
                            '<option value="0"> << Select One >> </option>'
                        );
                        response.data.data.forEach(function (position, index) {
                            if (positionId == position.id) {
                                $("#position").append(
                                    '<option value="' + position.id + '" selected>' + position.name_en + ' (' + position.short_name + ')</option>'
                                );
                            } else {
                                $("#position").append(
                                    '<option value="' + position.id + '">' + position.name_en + ' (' + position.short_name + ')</option>'
                                );
                            }
                            $("#department option[value=" + positionId + "]").change();
                        });
                    }).catch(err => {
                    console.log('error api: ' + err);
                })
            }
        });
    </script>
@endsection
