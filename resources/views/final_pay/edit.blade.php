@extends('adminlte::page')
@section('title', 'Edit Checking Final Pay')

@section('css')
    <style>
        .table tr th,
        .table tr td {
            vertical-align: middle !important;
        }

        .date {
            background-color: white !important;
        }
    </style>
@endsection

@php
    $contract = @$finalPay->contract;
    $staffPersonalInfo = @$finalPay->staffPersonalInfo;
@endphp
@section('content')

    @include('partials.breadcrumb')
    @include('pensionfund::layouts.layout_flash_error', ['message' => @$message])

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><label for=""><i class="fa fa-filter"></i> FILTER</label></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-6">
                            <label for="staff"> Find Staff </label>
                            <select name="staff" id="staff" class="staff js-select2-single form-control" disabled>
                                <option value="{{ @$staffPersonalInfo->id }}" data-contract-id="{{ @$contract->id }}" selected>
                                    {{ @$staffPersonalInfo->staff_id }}
                                    - {{ @$staffPersonalInfo->last_name_kh }} {{ @$staffPersonalInfo->first_name_kh }}
                                    ( {{ @$staffPersonalInfo->last_name_en }} {{ @$staffPersonalInfo->first_name_en }}
                                    )
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6 col-md-3">
                            <label for="last_name_kh">Last Name KH <span class="text-danger">*</span></label>
                            <input type="text" id="last_name_kh" name="last_name_kh" class="form-control" readonly
                                   placeholder="{{ __('label.last_name_kh') }}"
                                   value="{{ @$staffPersonalInfo->last_name_kh }}">
                        </div>
                        <div class="form-group col-sm-6 col-md-3 @if($errors->has('first_name_kh')) has-error @endif">
                            <label for="first_name_kh">First Name KH <span class="text-danger">*</span></label>
                            <input type="text" id="first_name_kh" name="first_name_kh" class="form-control" readonly
                                   placeholder="{{ __('label.first_name_kh') }}"
                                   value="{{ @$staffPersonalInfo->first_name_kh }}">
                        </div>
                        <div class="form-group col-sm-6 col-md-3 @if($errors->has('last_name_en')) has-error @endif">
                            <label for="last_name_en">Last Name EN <span class="text-danger">*</span></label>
                            <input type="text" id="last_name_en" name="last_name_en" class="form-control" readonly
                                   placeholder="{{ __('label.last_name_en') }}"
                                   value="{{ @$staffPersonalInfo->last_name_en }}">
                        </div>
                        <div class="form-group col-sm-6 col-md-3 @if($errors->has('first_name_en')) has-error @endif">
                            <label for="first_name_en">First Name EN <span class="text-danger">*</span></label>
                            <input type="text" id="first_name_en" name="first_name_en" class="form-control" readonly
                                   placeholder="{{ __('label.first_name_en') }}"
                                   value="{{ @$staffPersonalInfo->first_name_en }}">
                        </div>
                    </div> <!-- /.row -->

                    <div class="row">
                        <div class="form-group col-sm-6 col-md-3 @if($errors->has('marital_status')) has-error @endif">
                            <label for="marital_status">Marital Status <span class="text-danger">*</span></label>
                            <input type="text" id="marital_status" name="marital_status"
                                   placeholder="{{ __('ស្ថានភាព') }}" class="form-control"
                                   value="{{ @MARITAL_STATUS[@$staffPersonalInfo->marital_status] }}" readonly>
                        </div>
                        <div class="form-group col-sm-6 col-md-3 @if($errors->has('gender')) has-error @endif">
                            <label for="gender">Gender <span class="text-danger">*</span></label>
                            <input type="text" id="gender" name="gender" placeholder="{{ __('ភេទ') }}"
                                   class="form-control" value="{{ @GENDER[@$staffPersonalInfo->gender] }}" readonly>
                        </div>

                        <div class="form-group col-sm-6 col-md-3 @if($errors->has('position')) has-error @endif">
                            <label for="position">Position <span class="text-danger">*</span></label>
                            <input type="text" id="position" name="position" placeholder="{{ __('តួនាទី') }}"
                                   class="form-control" value="{{ @$contract->contract_object['position']['name_en'] }}"
                                   readonly>
                        </div>

                        <div class="form-group col-sm-6 col-md-3 @if($errors->has('doe')) has-error @endif">
                            <label for="doe">Start Date <span class="text-danger">*</span></label>
                            <input type="text" id="doe" name="doe" placeholder="{{ __('ថ្ងៃចូលធ្វើការ') }}"
                                   class="form-control" value="{{ date('d-M-Y', strtotime(@$contract->start_date)) }}"
                                   readonly>
                        </div>
                    </div> <!-- /.row -->
                </div>
            </div> <!-- /.panel -->
        </div>
    </div>

    @include('final_pay.partials.claim', [
        'route' => route('final_pay.update'),
        'finalPay' => @$finalPay,
        'finalPayObj' => @$finalPay->json_data,
        'disabledEdit' => @$disableEdit
    ])

@stop

@section('js')

    @include('final_pay.partials.js')
    <script>
        $(document).ready(function () {
            //Call this to request staff info immediately for edit
            $("#staff").trigger('change');
        });
    </script>
@endsection