@extends('adminlte::page')

@section('title', 'Show Branch Or Department')

@section('content')

    @include('partials.breadcrumb')

    <div class="panel panel-default">
        <div class="panel-heading">
            <label for="">Show Branch Or Department</label>
            <div class="pull-right">
                <a class="btn btn-xs btn-primary" href="{{ url('/branch-and-department') }}"><i class="fa fa-angle-left"></i> Back</a>
            </div>
        </div>
        <div class="panel-body">
            <label>Code: </label> {{ $branchOrDepartment->code }} <hr>
            <label>Short name: </label> {{ $branchOrDepartment->short_name }} <hr>
            <label>Name EN: </label> {{ $branchOrDepartment->name_en }} <hr>
            <label>Name KH: </label> {{ $branchOrDepartment->name_kh }} <hr>
            <label>Company: </label> {{ $branchOrDepartment->name_en }} <hr>
            <label>Detail: </label> {{ $branchOrDepartment->detail }} <hr>
        </div>
    </div> <!-- .panel-default -->

@endsection