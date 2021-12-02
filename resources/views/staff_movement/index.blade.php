@extends('adminlte::page')

@section('title', 'Staff movement')

@section('content')
    @include('partials.breadcrumb')

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="breadcrumb">
                {{--<a href="#" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> IMPORT</a>--}}
                <a href="{{ route('movement.create') }}" class="btn btn-success btn-sm"><i class="fa fa-exchange "></i> MOVEMENT</a>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><i class="fa fa-filter"></i> Filter</div>
        <div class="panel-body">
            <form action="{{ route('movement.filter') }}" role="form" method="get" id="filter-movement">
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
                <th>Status</th>
                <th style="min-width: 100px;">Action</th>
                <th style="min-width: 120px;">Full name KH</th>
                <th>Gender</th>
                <th style="min-width: 120px;">Old branch</th>
                <th style="min-width: 120px;">New branch</th>
                <th style="min-width: 120px;">Old position</th>
                <th style="min-width: 120px;">New position</th>
                <th style="min-width: 120px;">Effective Date</th>
            </tr>
            @foreach($staffs as $key => $staff)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $staff->profile->emp_id_card ?? "N/A" }}</td>
                    <td><label class="label label-{{ $staff->status->class }}">{{ $staff->status->name_en }}</label></td>
                    <td>
                        <a title="View" href="{{ route('movement.show', encrypt($staff->staff_personal_info_id)) }}"
                           class="btn btn-xs btn-info margin-r-5"><i class="fa fa-eye"></i></a>
                        @if(! $staff->trashed())
                        <a title="Edit" href="{{ route('movement.edit', encrypt($staff->staff_personal_info_id)) }}"
                           class="btn btn-xs btn-primary btn-edit-movement margin-r-5"><i class="fa fa-edit"></i></a>

                        <a title="Delete" href="javascript:void(0)" move_id="{{ encrypt($staff->staff_personal_info_id) }}"
                           class="btn btn-xs btn-danger btn-delete-movement margin-r-5"><i class="fa fa-trash"></i></a>
                        @endif
                    </td>
                    <td>{{ $staff->personalInfo->last_name_kh." ".$staff->personalInfo->first_name_kh }}</td>
                    <td>
                        @if($staff->personalInfo->gender == 0)
                            ប្រុស
                        @elseif($staff->personalInfo->gender == 1)
                            ស្រី
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $staff->branch->name_kh ?? "N/A" }}</td>
                    <td>{{ $staff->new_branch->name_kh ?? "N/A" }}</td>
                    <td>{{ $staff->position->name_kh ?? "N/A" }}</td>
                    <td>{{ $staff->new_position->name_kh ?? "N/A" }}</td>
                    <td>{{ isset($staff->effective_date) ? date('d-M-Y', strtotime($staff->effective_date)) : 'N/A' }}</td>
                </tr>
                @php $i++ @endphp
            @endforeach
        </table>
        {{ $staffs->appends($param)->links() }}
    </div>

@endsection

@section('js')
    <script src="{{ asset('js/staff_movement/index.js') }}"></script>
@endsection