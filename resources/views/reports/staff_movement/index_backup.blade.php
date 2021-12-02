@extends('adminlte::page')

@section('title', 'Report staff movement')

@section('content')

    @include('partials.breadcrumb')

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-filter"></i> Filter</div>
                <div class="panel-body">
                    <form action="{{ route('report.action_movement') }}" role="form" method="get" id="report_staff_movement">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group col-sm-6 col-md-3">
                                <input type="text" name="key_word" class="form-control" placeholder="Keyword" value="{{ request('key_word') }}">
                            </div>
                            <div class="form-group col-sm-6 col-md-3">
                                <select name="company" id="company" class="form-control js-select2-single">
                                    <option value="">>> {{ __("label.company") }} <<</option>
                                    @foreach($companies as $key => $company)
                                        @if($company->id == request('company'))
                                            <option value="{{ $company->id }}" selected>{{ $company->short_name }}</option>
                                        @else
                                            <option value="{{ $company->id }}">{{ $company->short_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-6 col-md-3">
                                <select name="branch" id="branch" class="form-control js-select2-single">
                                    <option value="">>> {{ __("label.branch")  }} <<</option>
                                    @foreach($branches as $key => $branch)
                                        @if($branch->id == request('branch'))
                                            <option value="{{ $branch->id }}" selected>{{ $branch->name_kh }}</option>
                                        @else
                                            <option value="{{ $branch->id }}">{{ $branch->name_kh }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-6 col-md-3">
                                <select name="department" id="department" class="form-control js-select2-single">
                                    <option value="">>> {{__('label.department') }} <<</option>
                                    @foreach($departments as $key => $department)
                                        @if($department->id == request('department'))
                                            <option value="{{ $department->id }}" selected>{{ $department->name_kh }}</option>
                                        @else
                                            <option value="{{ $department->id }}">{{ $department->name_kh }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6 col-md-3">
                                <select name="position" id="position" class="form-control js-select2-single">
                                    <option value="">>> {{ __('label.position') }} <<</option>
                                    @foreach($positions as $key => $position)
                                        @if($position->id == request('position'))
                                            <option value="{{ $position->id }}" selected>{{ $position->name_kh }}</option>
                                        @else
                                            <option value="{{ $position->id }}">{{ $position->name_kh }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-6 col-md-3">
                                <select name="gender" id="gender" class="form-control">
                                    <option value="" selected>>> {{ __('label.gender') }} <<</option>
                                    <option value="0" @if(request('gender') == '0') selected @endif>Male</option>
                                    <option value="1" @if(request('gender') == '1') selected @endif>Female</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-6 col-md-3">
                                <input type="text" name="start_date" class="form-control start_date" id="start_date"
                                       placeholder="{{ __('label.effective_date') }}" value="{{ request('start_date') }}">
                            </div>
                            <div class="form-group col-sm-6 col-md-3">
                                <input type="text" name="end_date" class="form-control end_date" id="end_date"
                                       placeholder="{{ __('label.until') }}" value="{{ request('end_date') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12">
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-default" name="action" value="view"><i class="fa fa-eye"></i> View</button>
                                    <button type="submit" class="btn btn-success" name="action" value="download"><i class="fa fa-download"></i> Download</button>
                                    <button type="reset" class="btn btn-danger "><i class="fa fa-remove"></i> Clear</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div style="overflow-x: auto;">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <table class="table table-striped">
                                    <tr class="bg-gray-active">
                                        <th>ID</th>
                                        <th>Staff ID</th>
                                        <th>Full name KH</th>
                                        <th>FUll name EN</th>
                                        <th>Gender</th>
                                        <th>Old company</th>
                                        <th>New company</th>
                                        <th>Old Branch</th>
                                        <th>New Branch</th>
                                        <th>Old department</th>
                                        <th>New department</th>
                                        <th>Old position</th>
                                        <th>New position</th>
                                        <th>Effective date</th>
                                        <th>Transfer work to</th>
                                        <th>Get work from</th>
                                    </tr>
                                    @if(isset($movements))
                                        @foreach($movements as $key => $movement)
                                            <tr>
                                                <td>
                                                    {{ $key+1 }}
                                                </td>
                                                <td>{{ $movement->profile->emp_id_card }}</td>
                                                <td>{{ $movement->personalInfo->last_name_kh." ".$movement->personalInfo->first_name_kh }}</td>
                                                <td>{{ $movement->personalInfo->last_name_en." ".$movement->personalInfo->first_name_en }}</td>
                                                <td>{{ ($movement->personalInfo->gender === 0) ? 'Male' : 'Female' }}</td>
                                                <td>{{ $movement->company->short_name ?? 'N/A' }}</td>
                                                <td>{{ $movement->to_company->short_name ?? 'N/A' }}</td>
                                                <td>{{ $movement->branch->name_kh ?? 'N/A' }}</td>
                                                <td>{{ $movement->to_branch->name_kh ?? 'N/A' }}</td>
                                                <td>{{ $movement->department->name_kh ?? 'N/A' }}</td>
                                                <td>{{ $movement->to_department->name_kh ?? 'N/A' }}</td>
                                                <td>{{ $movement->position->name_kh ?? 'N/A' }}</td>
                                                <td>{{ $movement->to_position->name_kh ?? 'N/A' }}</td>
                                                <td>{{ date('d-M-Y', strtotime($movement->effective_date)) ?? 'N/A' }}</td>
                                                <td>{{ $movement->transfer_to_name ?? 'N/A' }}</td>
                                                <td>{{ $movement->get_work_form_name ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="16">
                                                <h4 class="text-center">Empty</h4>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                                @if(isset($movements))
                                    {{ $movements->appends($page)->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div> <!-- .panel-body -->
            </div> <!-- /.panel -->
        </div> <!-- .col-sm-12 -->
    </div> <!-- .row -->
@stop

@section('js')
    <script>
        $(document).ready(function () {
            $("#start_date").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-M-yy',
                yearRange: "-130:+1",
                // defaultDate: '-1yr',
                // maxDate: '+0d',
                onSelect: function (selected) {
                    var dt = new Date(selected);
                    dt.setDate(dt.getDate());
                    $("#end_date").datepicker("option", "minDate", dt);
                }
            });

            $("#end_date").each(function () {
                $(this).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd-M-yy',
                    yearRange: "-70:+70",

                });
            });
        });
    </script>
@endsection
