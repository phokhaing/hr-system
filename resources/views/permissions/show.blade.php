@extends('adminlte::page')

@section('title', 'HR-MIS')

@section('content')

    <div class="panel panel-default">
        <div class="panel-heading">Show Permission
            <div class="pull-right">
                <a class="btn btn-primary btn-xs" href="{{ route('permissions.index') }}"> Back</a>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Permission name :</strong>
                        <label class="text-aqua">{{ $permission->name }}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection