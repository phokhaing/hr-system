@extends('adminlte::page')

@section('title', 'Staff resign')

@section('content')
    @include('partials.breadcrumb')
    @include('staff_resign._form.modal')

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="breadcrumb">
                {{--<a href="#" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> IMPORT</a>--}}
                <a href="{{ route('resign.create') }}" class="btn btn-success btn-sm"><i class="fa fa-user-times"></i> RESIGN</a>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><i class="fa fa-filter"></i> Filter</div>
        <div class="panel-body">
            <form action="{{ route('resign.filter') }}" role="form" method="get" id="filter-resign">
                {{ csrf_field() }}
                <div class="row">
                    <div class="form-group col-sm-6 col-md-3">
                        <input type="text" name="key_word" class="form-control" placeholder="Keyword" value="{{ request('key_word') }}">
                    </div>
                    <div class="form-group col-sm-6 col-md-3">
                        <select name="company" id="company" class="form-control js-select2-single">
                            <option value=""><< {{ __("label.company") }} >></option>
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
                            <option value=""><< {{ __("label.branch")  }} >></option>
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
                            <option value=""><< {{__('label.department') }} >></option>
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
                            <option value=""><< {{ __('label.position') }} >></option>
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
                        <button type="submit" class="btn btn-default margin-r-5"><i class="fa fa-search"></i> Search</button>
                        <button type="reset" class="btn btn-danger "><i class="fa fa-remove"></i> Clear</button>
                    </div>
                </div>
            </form>
        </div>
    </div> <!-- /.panel -->
    <div style="overflow-x: auto;">
        <table class="table table-striped">
            <tr>
                <th>#</th>
                <th>Staff ID</th>
                <th>Full name KH</th>
                <th>Full name EN</th>
                <th>Sex</th>
                <th>Company</th>
                <th>Branch</th>
                <th>Position</th>
                <th>Request Date</th>
                <th>Approved Date</th>
                <th>Last day Date</th>
                <th>Is Fraud</th>
                <th>Status</th>
                <th width="150px">Action</th>
            </tr>
            @foreach($staffResigns as $key => $resign)
                @php $i++ @endphp
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $resign->personalInfo->profile->emp_id_card ?? "N/A" }}</td>
                    <td>{{ $resign->personalInfo->last_name_kh." ".$resign->personalInfo->first_name_kh }}</td>
                    <td>{{ $resign->personalInfo->last_name_en." ".$resign->personalInfo->first_name_en }}</td>
                    <td>
                        @if($resign->personalInfo->gender == 0)
                            ប្រុស
                        @elseif($resign->personalInfo->gender == 1)
                            ស្រី
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $resign->personalInfo->profile->company->short_name ?? "N/A" }}</td>
                    <td>{{ $resign->personalInfo->profile->branch->name_kh ?? "N/A" }}</td>
                    <td>{{ $resign->personalInfo->profile->position->name_kh ?? "N/A" }}</td>
                    <td>{{ empty($resign->resign_date) ? "N/A" :date('d-M-Y', strtotime($resign->resign_date)) }}</td>
                    <td>
                        {{ empty($resign->approved_date) ? "N/A" : date('d-M-Y', strtotime($resign->approved_date)) }}
                    </td>
                    <td>
                        {{ empty($resign->last_day) ? "N/A" :date('d-M-Y', strtotime($resign->last_day)) }}
                    </td>
                    <td>
                        @if($resign->is_fraud == 1)
                            <label class="text-danger">Yes</label>
                        @endif
                    </td>
                    <td>
                        <span class="label label-{{$resign->personalInfo->flagTitle->class}}">{{ $resign->personalInfo->flagTitle->name_en }}</span>
                    </td>
                    <td>
                        @if($resign->personalInfo->flag != 3)
                            <a href="{{ route('resign.show', encrypt($resign->personalInfo->id))  }}" class="btn btn-info btn-xs margin-r-5" title="View">
                                <i class="fa fa-eye"></i>
                            </a>
                            <button type="button" class="btn btn-primary btn-xs margin-r-5" data-toggle="modal" data-target="#EditResign"
                                    title="Edit" data-staff_id="{{ encrypt($resign->personalInfo->id) }}"
                                    data-approved-date="{{ empty($resign->approved_date) ? "" : date('d-M-Y', strtotime($resign->approved_date)) }}">
                                <i class="fa fa-edit"></i>
                            </button>
                            <a title="Reject" href="javascript:void(0)" data-id="{{ encrypt($resign->personalInfo->id) }}"
                               class="btn btn-danger btn-xs btn-reject-resign margin-r-5"><i class="fa fa-ban"></i></a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    {!! $staffResigns->appends($param)->links() !!}

@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/staff_resign/index.js') }}"></script>
@endsection