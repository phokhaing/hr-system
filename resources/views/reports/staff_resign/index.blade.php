@extends('adminlte::page')

@section('title', 'Report staff resign')

@section('content')

    @include('partials.breadcrumb')

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-filter"></i> Filter</div>
                <div class="panel-body">
                    <form action="{{ route('report.action_resign') }}" role="form" method="get" id="report_staff_resign">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group col-sm-6 col-md-3">
                                <input type="text" name="key_word" class="form-control" placeholder="Keyword" value="{{ request('key_word') }}">
                            </div>
                        </div>
                        <div class="row">
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

                    <div class="row" style="overflow-x: auto;">
                        <div class="col-sm-12 col-md-12">
                            <table class="table table-striped">
                                <tr class="bg-gray-active">
                                    <th>ID</th>
                                    <th>Staff ID</th>
                                    <th>Full name KH</th>
                                    <th>FUll name EN</th>
                                    <th>Gender</th>
                                    <th>Position</th>
                                    <th>Department</th>
                                    <th>Branch</th>
                                    <th>Company</th>
                                    <th>Employment Date</th>
                                    <th>Resign Date</th>
                                    <th>CEO Approved</th>
                                    <th>Reason</th>
                                    <th>លិខិតលាឈប់</th>
                                </tr>
                                @if(isset($resigns))
                                    @foreach($resigns as $key => $resign)
                                        <tr>
                                            <td>
                                                {{ $key+1 }}
                                            </td>
                                            <td>{{ $resign->personalInfo->profile->emp_id_card }}</td>
                                            <td>{{ $resign->personalInfo->last_name_kh." ".$resign->personalInfo->first_name_kh }}</td>
                                            <td>{{ $resign->personalInfo->last_name_en." ".$resign->personalInfo->first_name_en }}</td>
                                            <td>{{ ($resign->personalInfo->gender == 0) ? 'Male' : 'Female' }}</td>
                                            <td>{{ $resign->personalInfo->profile->position->name_kh ?? 'N/A' }}</td>
                                            <td>{{ $resign->personalInfo->profile->department->name_kh ?? 'N/A' }}</td>
                                            <td>{{ $resign->personalInfo->profile->branch->name_kh ?? 'N/A' }}</td>
                                            <td>{{ $resign->personalInfo->profile->company->short_name ?? 'N/A' }}</td>
                                            <td>
                                                {{ ($resign->personalInfo->profile->employment_date != "") ?
                                                 date('d-M-Y', strtotime($resign->personalInfo->profile->employment_date)) : 'N/A' }}
                                            </td>
                                            <td>
                                                {{ ($resign->resign_date != "") ?
                                                date('d-M-Y', strtotime($resign->resign_date)) : 'N/A' }}
                                            </td>
                                            <td>
                                                {{ ($resign->approved_date != "") ?
                                                date('d-M-Y', strtotime($resign->approved_date)) : 'N/A' }}
                                            </td>
                                            <td>{{ ($resign->reason) ?? 'N/A' }}</td>
                                            <td>{{ isset($resign->file_reference) ? 'Yes' : 'No' }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                   <tr>
                                       <td colspan="14">
                                           <h4 class="text-center">Empty</h4>
                                       </td>
                                   </tr>
                                @endif
                            </table>
                            @if(isset($resigns))
                            {{ $resigns->appends($page)->links() }}
                            @endif
                        </div>
                    </div>

                </div> <!-- .panel-body -->
            </div> <!-- /.panel -->
        </div> <!-- .col-sm-12 -->
    </div> <!-- .row -->
@stop

@section('js')
    <script type="text/javascript" src="{{ asset('js/reports/staff_profile/index.js') }}"></script>
@endsection
