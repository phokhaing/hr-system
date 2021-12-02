@extends('adminlte::page')

@section('title', 'Show department')

@section('content')

    @include('partials.breadcrumb')

    <div class="panel panel-default">
        <div class="panel-heading">
            <label for="">how department</label>
            <a class="btn btn-xs btn-primary pull-right" href="{{ url('/department') }}"><i class="fa fa-angle-left"></i> Back</a>
        </div>
        <div class="panel-body">
            <label for="">Name EN: </label> {{ $department->name_en }}
            <hr>
            <label for="">Name KH: </label> {{ $department->name_kh }}
            <hr>
            <label for="">Short name: </label> {{ $department->short_name }}
            <hr>
            <label for="">Company: </label> {{ $department->name_en }}
        </div>
    </div>

@endsection