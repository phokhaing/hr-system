@extends('adminlte::page')

@section('title', 'show company')

@section('content')

    @include('partials.breadcrumb')

    <div class="panel panel-default">
        <div class="panel-heading">
            <label for="">Show Company</label>
            <a class="btn btn-xs btn-primary pull-right" href="{{ url('/company') }}"><i class="fa fa-angle-left"></i> Back</a>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <label for="">Code:</label> {{ $company->code }} <hr>
                    <label for="">Short Name:</label> {{ $company->short_name }} <hr>
                    <label for="">Name EN:</label> {{ $company->name_en }} <hr>
                    <label for="">Name KH:</label> {{ $company->name_kh }}<hr>
                </div>
            </div>
        </div>
    </div> <!-- .panel-default -->

@endsection