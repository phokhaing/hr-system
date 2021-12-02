@extends('adminlte::page')

@section('title', 'HR-MIS')

@section('content')

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel panel-default">
        <div class="panel-heading">Create New User
            <div class="pull-right">
                <a class="btn btn-primary btn-xs" href="{{ route('users.index') }}"> Back</a>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::open(array('route' => 'users.store','method'=>'POST')) !!}
            <div class="row">
                <div class="form-group col-xs-12 col-sm-6 col-md-6">
                    <strong>Company: <span class="text-danger">*</span></strong>
                    <select name="company_code" id="company_code" class="form-control company_code js-select2-single" required>
                        <option value="">>> {{ __('label.company') }} <<</option>
                        @foreach($companies as $key => $value)
                        @if(old('company_code') == $value->company_code)
                        <option value="{{ $value->company_code }}" selected>{{ $value->name_kh . ' (' . $value->short_name .')' }}</option>
                        @else
                        <option value="{{ $value->company_code }}">{{ $value->name_kh . ' (' . $value->short_name .')' }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Full Name: <span class="text-danger">*</span></strong>
                        {!! Form::text('full_name', null, array('placeholder' => 'Full Name','class' => 'form-control', 'id' => 'fullName')) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Username: <span class="text-danger">*</span></strong>
                        {!! Form::text('username', null, array('placeholder' => 'Username','class' => 'form-control', 'id' => 'username')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Email: <span class="text-danger">*</span></strong>
                        {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control', 'id' => 'email')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Password: <span class="text-danger">*</span></strong>
                        {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Confirm Password: <span class="text-danger">*</span></strong>
                        {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Select Staff Name: <span class="text-danger">*</span></strong>
                        <select name="staff_name" class="form-control js-select2-single" id="staffName">
                            <option value=""> >> Select Staff Name << </option>
                            @foreach($staffs as $key => $value)

                                @if(old('staff_name') == $value->id )
                                    <option value="{{ $value->id }}" selected>{{ $value->last_name_en.' '.$value->first_name_en . ' (' . @$value->currentContract->contract_object["company"]["short_name"] . ')' }}</option>
                                @else
                                    <option value="{{ $value->id }}">{{ $value->last_name_en.' '.$value->first_name_en . ' (' . @$value->currentContract->contract_object["company"]["short_name"] . ')' }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Role: <span class="text-danger">*</span></strong>    
                        <select name="roles[]" class="form-control js-select2-single" id="roles" multiple>
                            @foreach($roles as $key => $value)
                                <option value="{{ $value->name }}">{{ $value->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group col-xs-12 col-sm-12 col-md-12">
                    <label for="is_admin">Is Admin </label>
                    <input type="checkbox" name="is_admin" id="is_admin" class="icheck">
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

@endsection

@section('js')
    <script !src="">
        $(document).ready(function () {
            $("#staffName").on('select2:select', function (e) {
                let data = e.params.data;
                axios.get('/staff-personal-info/'+data.id+'/detail-json')
                    .then((res) => {
                        console.log(res);
                        $("#fullName").val(res.data.data.last_name_en +' '+ res.data.data.first_name_en)
                        $("#username").val((res.data.data.last_name_en).toLowerCase() +'.'+ res.data.data.first_name_en.toLowerCase())
                        $("#email").val(res.data.data.email)
                    });
            });
        })
    </script>
@endsection