@extends('adminlte::page')
@section('title', 'Contract')
@section('css')
<style>
    .select2 {
        width: 100% !important;
    }
    .button-create,.select-contract-type{
        margin-left: 12px!important;
    }
</style>
@stop

@section("content")
@include('partials.breadcrumb')

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="breadcrumb form-inline">

            <label for="select-contract-type">Select Contract Type: </label>
            <select class="form-control select-contract-type" name="contract_type" id="select-contract-type">
                @foreach(CONTRACT_TYPE as $key => $value)
                <option data-toggle="modal" data-target="{{$key}}" value="{{$key}}" {{$value == CONTRACT_TYPE['PROBATION'] ? 'selected' : ''}}>{{ $key }}</option>
                @endforeach
            </select>

            <button type="button" class="btn btn-success button-create" id="create-contract"><i
                        class="fa fa-plus"></i> CREATE
            </button>
        </div>
    </div>
</div>

@include('contract.modals.modal')
@include('contract.modals.regular_modal')
@include('contract.modals.death_modal')
@include('contract.modals.terminate_modal')
@include('contract.modals.resign_modal')
@include('contract.modals.demote_modal')
@include('contract.modals.promote_modal')
@include('contract.modals.layoff_modal')
@include('contract.modals.change_location')

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-filter"></i> Filter</div>
            <div class="panel-body">
                <form action="{{ route('contract.index') }}" role="form" method="get">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-4">
                            <label for="company_code">Company </label>
                            <select name="company_code" id="company_code" class="form-control js-select2-single">
                                <option value=""> << {{ __("label.company") }} >></option>
                                @foreach($companies as $key => $value)
                                @if(request('company_code') == $value->code)
                                <option value="{{ $value->code }}" selected>{{ $value->name_kh }}</option>
                                @else
                                <option value="{{ $value->code }}">{{ $value->name_kh }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6 col-md-4">
                            <label for="branch_department_code">Department or Branch </label>
                            <select name="branch_department_code" id="branch_department_code"
                                    class="form-control js-select2-single">
                                <option value=""> << {{__('label.department_or_branch') }} >></option>
                                @foreach($branchesDepartments as $key => $value)
                                @if($value->code == request('branch_department_code'))
                                <option value="{{ $value->code }}" selected>{{ $value->name_km }}</option>
                                @else
                                <option value="{{ $value->code }}">{{ $value->name_km }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6 col-md-4">
                            <label for="position_code">Position </label>
                            <select name="position_code" id="position_code" class="form-control js-select2-single">
                                <option value="">>> {{ __('label.position') }} <<</option>
                                @foreach($positions as $key => $value)
                                @if(request('position_code') == $value->code)
                                <option value="{{ $value->code }}" selected>{{ $value->name_kh }}</option>
                                @else
                                <option value="{{ $value->code }}">{{ $value->name_kh }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6 col-md-4">
                            <label for="contract_type">Contract Type </label>
                            <select name="contract_type" id="contract_type" class="form-control js-select2-single">
                                <option value=""> << {{ __('label.contract_type') }} >></option>
                                @foreach($contract_type as $key => $value)
                                <option value="{{ $value }}" {{ request(
                                'contract_type') == $value ? 'selected' : '' }}>{{ $key }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6 col-md-4">
                            <label for="start_date">Contract Start Date </label>
                            <input class="form-control"
                                   placeholder="Contract Start Date"
                                   type="text"
                                   id="start_date"
                                   name="start_date"
                                   value="{{ request('start_date') }}">
                        </div>
                        <div class="form-group col-sm-6 col-md-4">
                            <label for="end_date">Contract End Date </label>
                            <input type="text"
                                   class="form-control"
                                   id="end_date"
                                   name="end_date"
                                   value="{{ request('end_date') }}"
                                   placeholder="Contract End Date">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-4">
                            <label for="key_word">Keyword </label>
                            <input type="text" name="key_word" class="form-control" placeholder="Keyword"
                                   value="{{ request('key_word') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-4">
                            <button type="submit" class="btn btn-default margin-r-5"><i class="fa fa-search"></i> Search
                            </button>
                            <button type="reset" class="btn btn-danger margin-r-5"><i class="fa fa-remove"></i> Clear
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div> <!-- /.panel -->

        <div style="overflow: auto">
            <table class="table table-striped">
                <tr>
                    <th>#</th>
                    <th>Staff ID (On Card)</th>
                    <th>Full name KH</th>
                    <th>Full name EN</th>
                    <th>Company</th>
                    <th>Department / Branch</th>
                    <th>Position</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Probation End Date</th>
                    <th>Phone</th>
                    <th>Contract Type</th>
                    <th>Action</th>
                </tr>
                <?php $i = 1; ?>
                @foreach($contracts as $key => $value)
                <?php
                $contract_object = json_decode(@$value->contract_object);
                $array_flip = array_flip(CONTRACT_TYPE); // Reverse array value to key
                ?>
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ @$value->staff_code }}</td>
                    <td>{{ @$value->full_name_kh }}</td>
                    <td>{{ @$value->full_name_en }}</td>
                    <td>{{ @$contract_object->company->name_kh }}</td>
                    <td>{{ @$contract_object->branch_department->name_kh }}</td>
                    <td>{{ @$contract_object->position->name_kh }}</td>
                    <td>{{ @$value->start_date }}</td>
                    <td>{{ @$value->end_date }}</td>
                    <td>{{ @$contract_object->probation_end_date }}</td>
                    <td>{{ @$contract_object->phone_number }}</td>
                    <td><label class="label label-info">{{ isset($value->contract_type) ?
                            $array_flip[$value->contract_type] : '' }}</label></td>
                    <td>
                        <a href="#" class="btn btn-sm btn-info margin-r-5" title="Edit contract information"><i
                                    class="fa fa-edit"></i> Edit</a>
                    </td>
                </tr>
                <?php $i++ ?>
                @endforeach
            </table>
        </div>

    </div>
</div>
@stop

@section('js')
<script>

    $(document).ready(function (e) {


        $("#create-contract").on('click', function (e) {
            const selectedOption = $("#select-contract-type").find(":selected").data("target");
            console.log("selectedOption: "+ selectedOption);
            $("#"+selectedOption).modal("show");
        });

        /**
         * Start implement Resign Form
         */
        $("#contract_resign_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });

        $("#contract_resign_approve_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });

        $("#contract_resign_last_day").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });
        /**
         * End implement Resign Form
         */

        /**
         * Start implement Probation Form
         */
        $("#contract_probation_end_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });

        $("#contract_start_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });

        $("#contract_end_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });
        /**
         * End implement Probation Form
         */

        /**
         * Start Implement Change Location From
         */
        $("#contract_change_location_start_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });

        $("#contract_change_location_end_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });

        $("#change_location_effective_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });
        /**
         * End Implement Change Location From
         */

        /**
         * Start Implement Regular From
         */
        $("#contract_regular_start_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });
        $("#contract_regular_end_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });
        /**
         * End Implement Regular From
         */

        /**
         * Start Implement Death From
         */
        $("#death_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });
        /**
         * End Implement Death From
         */

        /**
         * Start Implement Promote From
         */
        $("#contract_promote_start_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });
        $("#contract_promote_end_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });
        $("#promote_effective_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });
        /**
         * End Implement Promote From
         */

        /**
         * Start Implement Demote From
         */
        $("#contract_demote_start_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });
        $("#contract_demote_end_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });
        $("#demote_effective_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });
        /**
         * End Implement demote From
         */

        /**
         * Start Implement Terminate From
         */
        $("#terminate_effective_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });
        /**
         * End Implement Terminate From
         */

        /**
         * Start Implement Terminate From
         */
        $("#layoff_effective_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy'
        });
        /**
         * End Implement Terminate From
         */

        $('.select2').select2();
    });

</script>
@stop
