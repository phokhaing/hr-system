@extends('adminlte::page')

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

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading"><label for="">Create</label></div>
                <div class="panel-body">
                    @include('staff_movement._form.form-create')

                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/staff_movement/create.js') }}"></script>
    <script type="text/javascript">

    </script>
@endsection