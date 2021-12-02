@extends('adminlte::page')

@section('title', 'Report staff profile')

@section('content')

    @include('partials.breadcrumb')

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-filter"></i> Filter</div>
                <div class="panel-body">
                    <form action="{{ route('report.action') }}" role="form" method="get" id="report_staff_profile">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group col-sm-6 col-md-3">
                                <input type="text" name="key_word" class="form-control" placeholder="Keyword" value="{{ request('key_word') }}">
                            </div>
                            {{--<div class="form-group col-sm-6 col-md-3">
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
                            </div>--}}
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
                                    <option value="0" @if(request('gender') === 0) selected @endif>Male</option>
                                    <option value="1" @if(request('gender') === 1) selected @endif>Female</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-6 col-md-3">
                                <input type="text" name="start_date" class="form-control start_date" id="start_date"
                                       placeholder="{{ __('label.start_date_filter') }}" value="{{ request('start_date') }}">
                            </div>
                            <div class="form-group col-sm-6 col-md-3">
                                <input type="text" name="end_date" class="form-control end_date" id="end_date"
                                       placeholder="{{ __('label.until') }}" value="{{ request('end_date') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12">
                                <div class="">
                                    <button type="submit" class="btn btn-default margin-r-5" name="action" value="view"><i class="fa fa-eye"></i> View</button>
                                    <button type="submit" class="btn btn-success margin-r-5" name="action" value="download"><i class="fa fa-download"></i> Download</button>
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
                                    <th>Probation (Month)</th>
                                    <th>Contract (Month)</th>
                                    <th>កិច្ចសន្យាការងារ</th>
                                    <th>លិខិតធានា</th>
                                    <th>តាមដានប្រវត្ដិរូប(Home-visit)</th>
                                    <th>កិច្ចសន្យាបន្ថែម</th>
                                    <th>លិខិតអះអាង</th>
                                    <th>បទពិពណ៌នាការងារ</th>
                                </tr>
                                @if(isset($profiles))
                                    @foreach($profiles as $key => $profile)
                                        @php
                                            $contract = $profile->documents->where('staff_document_type_id', 21)->first();
                                            $ensure = $profile->documents->where('staff_document_type_id', 7)->first();
                                            $home_visit = $profile->documents->where('staff_document_type_id', 8)->first();
                                            $contract_more = $profile->documents->where('staff_document_type_id', 6)->first();
                                            $descript_job = $profile->documents->where('staff_document_type_id', 10)->first();
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $key+1 }}
                                            </td>
                                            <td>{{ $profile->profile->emp_id_card }}</td>
                                            <td>{{ $profile->last_name_kh." ".$profile->first_name_kh }}</td>
                                            <td>{{ $profile->last_name_en." ".$profile->first_name_en }}</td>
                                            <td>{{ ($profile->gender === 0) ? 'Male' : 'Female' }}</td>
                                            <td>{{ $profile->profile->position->name_kh ?? 'N/A' }}</td>
                                            <td>{{ $profile->profile->department->name_kh ?? 'N/A' }}</td>
                                            <td>{{ $profile->profile->branch->name_kh ?? 'N/A' }}</td>
                                            <td>{{ $profile->profile->company->short_name ?? 'N/A' }}</td>
                                            <td>{{ date('d-M-Y', strtotime($profile->profile->employment_date)) ?? 'N/A' }}</td>
                                            <td>{{ $profile->profile->probation_duration ?? 'N/A' }}</td>
                                            <td>{{ $profile->profile->contract_duration ?? 'N/A' }}</td>
                                            <td>
                                                @if($contract)
                                                    @if($contract->check == 1)
                                                        Yes
                                                    @elseif($contract->not_have == 1)
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
                            @if(isset($profiles))
                            {{ $profiles->appends($page)->links() }}
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
