@extends('adminlte::page')

@section('title', 'Upload Pension Fund')

@section('content')

@include('partials.breadcrumb')

<div class="panel panel-default">
    <div class="panel-heading"><label for="">Migration Pension Fund Info</label></div>
    <div class="panel-body">
        {{ session()->get('message') }}
        <div class="row">
            <div class="col-sm-12">

                <form action="{{ route('pensionfund::migrate.import') }}" enctype="multipart/form-data" method="POST" role="form">
                    {{ Form::token() }}
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-6 @if($errors->has('excel_file')) has-error @endif">
                            <?php
                                form_label([
                                    'for' => 'excel_file',
                                    'value' => 'File Excel *',
                                ]);
                                form_file([
                                    'name' => 'excel_file',
                                    'class' => 'form-control',
                                    'id' => 'excel_file', 
                                    'value' => old('excel_file')
                                ]);
                            ?>
                        </div>
                        
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-upload"></i>
                                Upload
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div> <!-- .panel-default -->

<div class="panel panel-default">
    <div class="panel-heading"><label for="">Migration Pension Fund Info Check</label></div>
    <div class="panel-body">
        {{ session()->get('message') }}
        <div class="row">
            <div class="col-sm-12">

                <form action="{{ route('pensionfund::migrate.import_check') }}" enctype="multipart/form-data" method="POST" role="form">
                    {{ Form::token() }}
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-6 @if($errors->has('excel_file')) has-error @endif">
                            <?php
                            form_label([
                                'for' => 'excel_file',
                                'value' => 'File Excel *',
                            ]);
                            form_file([
                                'name' => 'excel_file',
                                'class' => 'form-control',
                                'id' => 'excel_file',
                                'value' => old('excel_file')
                            ]);
                            ?>
                        </div>

                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-upload"></i>
                                Upload
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div> <!-- .panel-default -->

@stop