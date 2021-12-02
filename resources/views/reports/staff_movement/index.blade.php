@extends('adminlte::page')

@section('title', 'Report Staff End Contract')

@section('css')
<style>
    .select2 {
        width: 100% !important;
    }
</style>
@stop

@section('content')

@include('partials.breadcrumb')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-filter"></i> Filter</div>
            <div class="panel-body">

                <form action="{{ route('report.movement') }}" role="form" method="get" id="report_contract">
                    {{ csrf_field() }}
                    <input type="hidden" name="is_download" id="is_download">
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-6">
                            <input type="text" name="key_word" class="form-control"
                                   placeholder="Type keyword... Staff ID, Name, Phone"
                                   value="{{ request('key_word') }}">
                        </div>

                        <div class="form-group col-sm-6 col-md-6">
                            <button type="submit" class="btn btn-primary margin-r-5" id="searchBtn">
                                <i class="fa fa-search"></i> Search
                            </button>

                            <button type="button" data-toggle="collapse" class="btn btn-primary margin-r-5" data-target="#advanceFilter" aria-expanded="false" aria-controls="advanceFilter">
                                <i class="fa fa-search-plus"></i> Advance Filter
                            </button>

                            <button type="submit" class="btn btn-success margin-r-5" id="downloadBtn">
                                <i class="fa fa-download"></i> Download
                            </button>
                            <button type="reset" class="btn btn-danger" id="resetBtn"><i class="fa fa-remove"></i> Reset
                            </button>
                        </div>
                    </div> <!-- .row -->
                    <div class="collapse" id="advanceFilter">
                        <div class="panel panel-default">
                            <div class="panel-body">

                                <div class="row">
                                    <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                        <label for="contract_start_date">Contract start date</label>
                                        <input type="text" class="form-control pull-right contract_start_date"
                                               id="contract_start_date" name="contract_start_date"
                                               value="{{ request('contract_start_date') }}" readonly
                                               placeholder="{{ __('label.contract_start_date') }}">

                                    </div>
                                    <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                        <label for="contract_end_date">Contract end date <span
                                                    class="text-danger">*</span></label>
                                        <input type="text" class="form-control pull-right contract_end_date"
                                               id="contract_end_date" name="contract_end_date"
                                               value="{{ request('contract_end_date') }}" readonly
                                               placeholder="{{ __('label.contract_end_date') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-sm-12 col-md-6 col-lg-6">
                                        <label for="company_code">Company</label>
                                        <select id="company_code" name="company_code" class="form-control company_code select2">
                                            <option value="">>> {{ __('label.company') }} <<</option>
                                            @foreach($companies as $key => $value)
                                                <option value="{{ $value->company_code }}" {{request('company_code') == $value->company_code ? 'selected' : ''}}>{{ $value->name_kh }} ({{ $value->short_name }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-6 col-lg-6">
                                        <label for="branch_department_code">Department or Branch </label>
                                        <select id="branch_department_code" name="branch_department_code"
                                                class="form-control branch_department_code select2">
                                            <option value="">>> {{__('label.department_or_branch') }} <<</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-sm-12 col-md-6 col-lg-6">
                                        <label for="position_code">Position</label>
                                        <select id="position_code" name="position_code" class="form-control position_code select2">
                                            <option value="">>> {{ __('label.position') }} <<</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-6 col-lg-6">
                                        <label for="gender">Gender</label>
                                        <select id="gender" name="gender" class="form-control">
                                            <option value="" selected>>> {{ __('label.gender') }} <<</option>
                                            @foreach(GENDER as $key => $value)
                                            @if(request('gender') == $key.'')
                                            <option value="{{$key}}" selected>{{$value}}</option>
                                            @else
                                            <option value="{{$key}}">{{$value}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>

            </div> <!-- .panel-body -->
        </div> <!-- /.panel -->

        <div class="panel-body" style="overflow-x: auto;">
            <table class="table table-striped">
                <tr class="bg-gray-active">
                    <th>ID</th>
                    <th>Staff ID</th>
                    <th>Full Name KH</th>
                    <th>FUll Name EN</th>
                    <th>Gender</th>
                    <th>phone</th>
                    <th>Position</th>
                    <th>Branch/Department</th>
                    <th>Company</th>
                    <th>Contract Type</th>
                    <th>Contract Start Date</th>
                    <th>Contract End Date</th>
                    <th>Contract (Month)</th>
                    <th>កិច្ចសន្យាការងារ</th>
                    <th>លិខិតធានា</th>
                    <th>តាមដានប្រវត្ដិរូប(Home-Visit)</th>
                    <th>កិច្ចសន្យាបន្ថែម</th>
                    <th>បទពិពណ៌នាការងារ</th>

                </tr>
                @if(isset($contracts))
                @foreach($contracts as $key => $contract)
                @php

                $profile = @$contract->staffPersonalInfo;
                $position = @$contract->contract_object['position'];
                $company = @$contract->contract_object['company'];
                $branchDepartment = @$contract->contract_object['branch_department'];
                $array_flip = array_flip(CONTRACT_TYPE);

                $documentContract = @$profile->documents->where('staff_document_type_id', 21)->first();
                $ensure = @$profile->documents->where('staff_document_type_id', 7)->first();
                $home_visit = @$profile->documents->where('staff_document_type_id', 8)->first();
                $contract_more = @$profile->documents->where('staff_document_type_id', 6)->first();
                $descript_job = @$profile->documents->where('staff_document_type_id', 10)->first();

                @endphp
                <tr>
                    <td>
                        {{ $key+1 }}
                    </td>
                    <td>{{ @$profile->staff_id }}</td>
                    <td>{{ @$profile->last_name_kh." ".@$profile->first_name_kh }}</td>
                    <td>{{ @$profile->last_name_en." ".@$profile->first_name_en }}</td>
                    <td>{{ GENDER[$profile->gender] }}</td>
                    <td>{{ @$profile->phone}}</td>
                    <td>{{ @$position['name_kh'] ?? 'N/A' }}</td>
                    <td>{{ @$branchDepartment['name_kh'] ?? 'N/A' }}</td>
                    <td>{{ @$company['short_name'] ?? 'N/A' }}</td>
                    <td>
                        <label class="label label-info">{{ isset($contract->contract_type) ?
                            $array_flip[$contract->contract_type] : '' }}</label>
                    </td>
                    <td>
                        {{ date('d-M-Y', strtotime(@$contract->start_date)) }}
                    </td>
                    <td>
                        {{ date('d-M-Y', strtotime(@$contract->end_date)) }}
                    </td>
                    <td>
                        {{ find_duration_contract(@$contract->start_date, @$contract->end_date) }}
                    </td>
                    <td>
                        @if($documentContract)
                        @if($documentContract->check == 1)
                        Yes
                        @elseif($documentContract->not_have == 1)
                        No
                        @else
                        @endif
                        @else
                        N/A
                        @endif
                    </td>
                    <td>
                        @if($ensure)
                        @if($ensure->check == 1)
                        Yes
                        @elseif($ensure->not_have == 1)
                        No
                        @else
                        @endif
                        @else
                        N/A
                        @endif
                    </td>
                    <td>
                        @if($home_visit)
                        @if($home_visit->check == 1)
                        Yes
                        @elseif($home_visit->not_have == 1)
                        No
                        @else
                        @endif
                        @else
                        N/A
                        @endif
                    </td>
                    <td>
                        @if($contract_more)
                        @if($contract_more->check == 1)
                        Yes
                        @elseif($contract_more->not_have == 1)
                        No
                        @else
                        @endif
                        @else
                        N/A
                        @endif
                    </td>
                    <td>
                        @if($descript_job)
                        @if($descript_job->check == 1)
                        Yes
                        @elseif($descript_job->not_have == 1)
                        No
                        @else
                        @endif
                        @else
                        N/A
                        @endif
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="18">
                        <h4 class="text-center">Empty</h4>
                    </td>
                </tr>
                @endif
            </table>
            @if(isset($contracts))
            {{ $contracts->appends($page)->links() }}
            @endif
        </div>
    </div> <!-- .col-sm-12 -->
</div>
@stop

@section('js')
<script type="text/javascript" src="{{ asset('js/reports/staff_profile/index.js') }}"></script>
<script>
    $(document).ready(function (e) {
        $("#advance_filter").on('click', function (e) {
            $("input#is_download").val('');
            $("#advance_filter_modal").modal("show");
        });

        $("#btnSave").on('click', function (e) {
            e.preventDefault();
            $("input#is_download").val('');
            $("form#report_contract").submit();
        });

        $("#searchBtn").on('click', function (e) {
           e.preventDefault();
            $("input#is_download").val('');
            $("form#report_contract").submit();
        });

        $("#resetBtn").on('click', function (e) {
            e.preventDefault();
            $("form#report_contract").find(":input").val("");
            $("form#report_contract").submit();
        });

        $("#downloadBtn").on('click', function (e) {
            e.preventDefault();
            $("input#is_download").val(1);
            $("form#report_contract").submit();
        });

        $(".contract_start_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy',
            onSelect: function onSelect(selected) {
                var dt = new Date(selected);
                dt.setDate(dt.getDate());
                $(".contract_end_date").datepicker("option", "minDate", dt).datepicker("setDate", dt);
            }
        });

        $(".contract_end_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy',
            onSelect: function onSelect(selected) {
                var dt = new Date(selected);
                dt.setDate(dt.getDate());
                $(".contract_start_date").datepicker("option", "maxDate", dt);
            }
        });

        $('.select2').select2();
    });
</script>
@endsection
