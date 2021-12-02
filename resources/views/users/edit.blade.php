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
        <div class="panel-heading">Edit New User
            <div class="pull-right">
                <a class="btn btn-primary btn-xs" href="{{ route('users.index') }}"> Back</a>
            </div>
        </div>
        <div class="panel-body">
            {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}
            <div class="row">
                <div class="form-group col-xs-12 col-sm-6 col-md-6">
                    <strong>Company:</strong>
                    <select name="company_code" id="company_code" class="form-control company_code js-select2-single" required>
                        <option value="">>> {{ __('label.company') }} <<</option>
                        @foreach($companies as $key => $value)
                            <option value="{{ $value->company_code }}" {{ ($user->company_code == $value->company_code) ? 'selected' : '' }} >{{ $value->name_kh . ' (' . $value->short_name .')' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Full Name:</strong>
                        {!! Form::text('full_name', null, array('placeholder' => 'Full Name','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Username:</strong>
                        {!! Form::text('username', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Email:</strong>
                        {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Password:</strong>
                        {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Confirm Password:</strong>
                        {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Role:</strong>
                        <select name="roles[]" class="form-control js-select2-single" id="roles" multiple>
                            @foreach($roles as $key => $value)
                                @php(!$isSelected = false)
                                @foreach($userRole as $key1 => $value1)
                                    @if($value1->name == $value->name)
                                        <?php
                                        $isSelected = true;
                                        break;
                                        ?>
                                        
                                    @endif
                                @endforeach   
                                <option value="{{ $value->name }}" {{ @$isSelected ? 'selected' : '' }}>{{ $value->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group col-xs-12 col-sm-12 col-md-12">
                    <label for="is_admin">Is Admin </label>
                    <input type="checkbox" name="is_admin" id="is_admin" class="icheck" {{ ($user->is_admin != '') ? 'checked' : '' }}>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

@endsection