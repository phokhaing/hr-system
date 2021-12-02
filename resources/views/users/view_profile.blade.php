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
        <div class="panel-heading">View and Update</div>
        <div class="panel-body">
            {!! Form::model($user, ['method' => 'PATCH','route' => ['user.updateProfile', $user->id]], array('autocomplete' => 'off')) !!}
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Current Password:</strong>
                        {!! Form::password('current_password', array('placeholder' => 'Current Password','class' => 'form-control', 'id' => 'currentPassword')) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>New Password:</strong>
                        {!! Form::password('new_password', array('placeholder' => 'New Password','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Confirm Password:</strong>
                        {!! Form::password('confirm_password', array('placeholder' => 'Confirm New Password','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-primary pull-right">Change Password</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(document).ready(function () {
           $("#currentPassword").val("");
        });
    </script>
@endsection