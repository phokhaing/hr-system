@extends('adminlte::page')

@section('title', 'Show staff movement')

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
        <li> <a href="{{ route('movement.index') }}"> Staff-movement</a> </li>
        <li>  {{ $movements[0]->profile->emp_id_card }}  </li>
    </ul>

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="breadcrumb">
                {{--                <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> IMPORT</a>--}}
                <a href="{{ route('movement.create') }}" class="btn btn-success btn-sm"><i class="fa fa-exchange"></i> MOVEMENT</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-bold">Movement information</div>
                <div class="panel-body">
                    @foreach($movements as $key => $movement)
                        <div class="row margin-bottom">
                            <div class="col-sm-3 col-md-3">
                                <label for="">Staff ID: </label> {{ ($movement->staff_personal_info_id) ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">Full name KH: </label> {{ $movement->personalInfo->full_name_khmer ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">Full name EN: </label> {{ $movement->personalInfo->full_name_english ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">Gender: </label> {{ ($movement->personalInfo->gender == 1) ? 'Female' : 'Male' }}
                            </div>
                        </div>
                        <div class="row margin-bottom">
                            <div class="col-sm-3 col-md-3">
                                <label for="">Old company: </label> {{ $movement->company->short_name ?? 'N/A'  }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">New company: </label> {{ $movement->new_company->short_name ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">Old branch: </label> {{ $movement->branch->name_kh ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">New branch: </label> {{ $movement->new_branch->name_kh ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="row margin-bottom">
                            <div class="col-sm-3 col-md-3">
                                <label for="">Old department: </label> {{ $movement->department->name_kh ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">New department: </label> {{ $movement->new_department->name_kh ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">Old position: </label> {{ $movement->position->name_kh ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">New position: </label> {{ $movement->new_position->name_kh ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="row margin-bottom">
                            <div class="col-sm-3 col-md-3">
                                <label for="">Old salary: </label> {{ number_format($movement->old_salary, 2) ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">New salary: </label> {{ number_format($movement->new_salary, 2) ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">Effective date: </label> {{ $movement->effective_date ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">Reject date: </label> {{ $movement->reject_date ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="row margin-bottom">
                            <div class="col-sm-3 col-md-3">
                                <label for="">Transfer work to: </label> {{ $movement->transfer_to_name ?? 'N/A' }}
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <label for="">Get work from: </label> {{ $movement->get_work_form_name ?? 'N/A' }}
                            </div>
                        </div>

                        <hr style="border: 1px solid #3c8dbc;">
                    @endforeach
                </div> <!-- /.panel-body -->
            </div> <!-- /.panel panel-default -->
        </div> <!-- /.col-sm-12 col-md-12 -->
    </div> <!-- /.row -->
@stop
