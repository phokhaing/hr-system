@extends('adminlte::page')

@section('title', 'Create New Course')

@section('content')

    @include('partials.breadcrumb')

    <div class="panel panel-default">
        <div class="panel-heading"><label for="">Create New Course</label></div>
        <div class="panel-body">
            {{ session()->get('message') }}
            <div class="row">
                <div class="col-sm-12">
                    <form action="{{ route('hrtraining::course.setting.store') }}" method="POST" role="form">
                        {{ Form::token() }}
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

                                        <option value="{{ $value->id }}" {{ old('category') == $value->id ? 'selected':'' }}>{{ @$jsonObj->title_en }}</option>
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
                                    'value' => old('course_title'),
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
                                    'value' => old('cost')
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
                                    'value' => old('frequency')
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
                                    'value' => old('grade')
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
                                    'value' => old('duration')
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
                                    'value' => old('desc')
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
                                        {{ Form::radio('status', '1', true, ['class' => 'field']) }} Internal
                                    </div>

                                    <div class="col-md-3">
                                        {{ Form::radio('status', '0', false, ['class' => 'field']) }} External
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
                        console.log('success api: ' + $.type(response.data));
                        $("#department").empty().append(
                            '<option value="0"> << Select One >> </option>'
                        );
                        response.data.data.forEach(function (departmentBranch, index) {
                            $("#department").append(
                                '<option value="' + departmentBranch.id + '">' + departmentBranch.name_km + ' (' + departmentBranch.short_name + ')</option>'
                            );
                        });
                    }).catch(err => {
                    console.log('error api: ' + err);
                })
            }

            function getPosition(code) {
                axios.get("/position/" + code + "/all")
                    .then(response => {
                        console.log('success api: ' + $.type());
                        $("#position").empty().append(
                            '<option value="0"> << Select One >> </option>'
                        );
                        response.data.data.forEach(function (position, index) {
                            $("#position").append(
                                '<option value="' + position.id + '">' + position.name_en + ' (' + position.short_name + ')</option>'
                            );
                        });
                    }).catch(err => {
                    console.log('error api: ' + err);
                })
            }
        });
    </script>
@endsection
