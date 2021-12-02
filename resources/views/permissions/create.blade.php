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
        <div class="panel-heading">
            Create New Permission
            <div class="pull-right">
                <a class="btn btn-primary btn-xs" href="{{ route('permissions.index') }}"> Back</a>
            </div>
        </div>
        <div class="panel-body">

            {!! Form::open(array('route' => 'permissions.store','method'=>'POST')) !!}
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Permission name :</strong>
                        {!! Form::text('name', null, array('placeholder' => 'Permission name','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top: 20px">
                    <div class="pull-right">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

@endsection
