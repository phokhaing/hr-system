@extends('adminlte::page')

@section('title', 'Staff resign')

@section('content')
    @include('partials.breadcrumb')

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="breadcrumb">
                <a href="#" class="btn btn-success btn-sm" id="btnSave"><i class="fa fa-save"></i> SAVE</a>
                <a href="#" class="btn btn-danger btn-sm" id="btnDiscard"><i class="fa fa-remove"></i> DISCARD</a>
            </div>
        </div>
    </div>

    {{ session()->get('message') }}
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading"><label for="">Form resign</label></div>
                <div class="panel-body">
                    @include('staff_resign._form.create')

                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/staff_resign/create.js') }}"></script>
@endsection
