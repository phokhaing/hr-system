@extends('adminlte::page')

@section('title', 'Show staff resign')

@section('css')
    <style>
        label {
            color: #0d6aad;
        }
    </style>
@endsection

@section('content')
    <ul class="breadcrumb">
        <li> <a href="/"> Dashboard</a> </li>
        <li> <a href="{{ route('resign.index') }}"> Staff-resign</a> </li>
        <li>  {{ @$resigns[0]->staffInfo->emp_id_card }}  </li>
    </ul>

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="breadcrumb">
                {{--                <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> IMPORT</a>--}}
                <a href="{{ route('resign.create') }}" class="btn btn-success btn-sm"><i class="fa fa-user-times"></i> RESIGN</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-bold">Resign information</div>
                <div class="panel-body">
                    @foreach($resigns as $key => $resign)
                        <div class="row margin-bottom">
                            <div class="col-sm-3 col-md-3">
                                <label for="">Full name KH: </label> {{ $resign->personalInfo->full_name_khmer ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">Full name EN: </label> {{ $resign->personalInfo->full_name_english ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">Gender: </label> {{ ($resign->personalInfo->gender == 1) ? 'Female' : 'Male' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">Is Fraud: </label> {{ ($resign->personalInfo->is_fraud == 1) ? 'Yes' : 'No' }}
                            </div>
                        </div>
                        <div class="row margin-bottom">
                            <div class="col-sm-3 col-md-3">
                                <label for="">Resign date: </label> {{ $resign->resign_date ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">Approved date: </label> {{ $resign->approved_date ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">Reject date: </label> {{ $resign->reject_date ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">Last day: </label> {{ $resign->last_date ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="row margin-bottom">
                            <div class="col-sm-3 col-md-3">
                                <label for="">Company: </label> {{ $resign->staffInfo->company->short_name ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">Branch: </label> {{ $resign->staffInfo->branch->name_kh ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">Department: </label> {{ $resign->staffInfo->department->name_ke ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">Position: </label> {{ $resign->staffInfo->position->name_kh ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="row margin-bottom">
                            <div class="col-sm-3 col-md-3">
                                <label for="">Staff ID replaced (1): </label> {{ $resign->staff_id_replaced_1 ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">Staff name replaced (1): </label> {{ $resign->staff_name_replaced_1 ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">Staff ID replaced (2): </label> {{ $resign->staff_id_replaced_2 ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">Staff name replaced (2): </label> {{ $resign->staff_name_replaced_2 ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="row margin-bottom">
                            <div class="col-sm-12 col-md-12">
                                <label for="">Reason: </label> {{ $resign->reason ?? 'N/A' }}
                            </div>
                        </div>
                        <hr style="border: 1px solid #3c8dbc;">
                    @endforeach
                </div> <!-- /.panel-body -->
            </div> <!-- /.panel panel-default -->
        </div> <!-- /.col-sm-12 col-md-12 -->
    </div> <!-- /.row -->
@stop
