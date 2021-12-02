@extends('adminlte::page')

@section('title', 'Detail Result Examination')

@section('content')

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-newspaper-o"></i> <b>Detail Result Examination</b>
                </div>
                <div class="panel-body">
                    <div class="row" style="overflow-x: auto;">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-xs-4 col-sm-3 col-md-2 col-lg-2 control-label">Training Course:</label>
                                        <div class="col-xs-8 col-sm-9 col-md-10 col-lg-10">
                                             <div class="form-control no-border">{{ @$course->json_data->title ?: 'N/A' }} </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-xs-4 col-sm-3 col-md-2 col-lg-2 control-label">Total Point:</label>
                                        <div class="col-xs-8 col-sm-9 col-md-10 col-lg-10">
                                            <div class="form-control no-border">
                                                <label for="" class="badge bg-red">
                                                    {{ number_format(@$trainee->traineeResult->json_data->total_point, 2) ?: 'N/A' }}</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-xs-4 col-sm-3 col-md-2 col-lg-2 control-label">Average:</label>
                                        <div class="col-xs-8 col-sm-9 col-md-10 col-lg-10">
                                            <div class="form-control no-border">
                                                <div class="badge bg-red">
                                                    {{ number_format(@$trainee->traineeResult->json_data->average, 2) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-xs-4 col-sm-3 col-md-2 col-lg-2 control-label">Training Start From:</label>
                                        <div class="col-xs-8 col-sm-9 col-md-10 col-lg-10">
                                            <div class="form-control no-border">
                                                </label> {{ date_readable(@$enrollment->json_data->start_date) }} <label>
                                                    <i class="fa fa-long-arrow-right"></i>
                                                </label> {{ date_readable(@$enrollment->json_data->end_date) }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-xs-4 col-sm-3 col-md-2 col-lg-2 control-label">Enrollment Date:</label>
                                        <div class="col-xs-8 col-sm-9 col-md-10 col-lg-10">
                                            <div class="form-control no-border">
                                                {{ date('d-M-Y', strtotime(@$enrollment->created_at)) }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-xs-4 col-sm-3 col-md-2 col-lg-2 control-label">Training Purpose($):</label>
                                        <div class="col-xs-8 col-sm-9 col-md-10 col-lg-10">
                                            <div class="form-control no-border">
                                                {{ @$enrollment->json_data->training_purpose ?: 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection

