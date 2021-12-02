@extends('adminlte::page')

@section('title', 'HR-MIS')

@section('content')

    <div class="panel panel-default">
        <div class="panel-heading">Show Role
            <div class="pull-right">
                <a class="btn btn-primary btn-xs" href="{{ route('roles.index') }}"> Back</a>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Role name :</strong>
                        {{ $role->name }}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <strong>Permissions:</strong>
                    <div class="form-group">
                        @if(!empty($rolePermissions))
                            @foreach($rolePermissions as $v)
                                <label class="col-xs-6 col-sm-4 col-md-3 text-primary">{{ $v->name }}</label>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection