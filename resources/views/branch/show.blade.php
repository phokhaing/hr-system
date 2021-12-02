@extends('adminlte::page')

@section('title', 'Show branch')

@section('content')

    @include('partials.breadcrumb')

    <div class="panel panel-default">
        <div class="panel-heading">
            <label for="">Show branch</label>
            <div class="pull-right">
                <a class="btn btn-xs btn-primary" href="{{ url('/company') }}"><i class="fa fa-angle-left"></i> Back</a>
            </div>
        </div>
        <div class="panel-body">
            <label>Code: </label> {{ $branch->code }} <hr>
            <label>Short name: </label> {{ $branch->short_name }} <hr>
            <label>Name EN: </label> {{ $branch->name_en }} <hr>
            <label>Name KH: </label> {{ $branch->name_kh }} <hr>
            <label>Company: </label> {{ $branch->name_en }} <hr>
        </div>
    </div> <!-- .panel-default -->

@endsection