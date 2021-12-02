@extends('adminlte::page')

@section('content')
    @include('partials.breadcrumb')

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="breadcrumb">
                {{--<a href="#" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> IMPORT</a>--}}
                <a href="{{ route('movement.update') }}" class="btn btn-success btn-sm" id="btnUpdate"><i class="fa fa-save"></i> UPDATE</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><label for="">Edit </label></div>
                <div class="panel-body">
                    @include('staff_movement._form.form-edit')
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ asset('js/staff_movement/create.js') }}"></script>
@endsection