@extends('adminlte::page')

@section('title', 'List all staff')

@section('content')
    @include('partials.breadcrumb')

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-filter"></i> Filter</div>
                <div class="panel-body">
                    <form action="{{ route('request_resign.list') }}" role="form" method="get">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group col-sm-6 col-md-4">
                                <input type="text" name="keyword" class="form-control" placeholder="Staff ID, Name" value="{{ request('keyword') }}">
                            </div>
                            <div class="form-group col-sm-6 col-md-4">
                                <select name="gender" id="gender" class="form-control js-select2-single">
                                    <option value=""> << {{ __("label.gender") }} >></option>
                                    @foreach(GENDER as $key => $value)
                                        <option value="{{ $key }}" {{ $key.'' == request('gender') ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-sm-6 col-md-4">
                                <button type="submit" class="btn btn-default margin-r-5"><i class="fa fa-search"></i> Search</button>
                                <button type="reset" class="btn btn-danger margin-r-5" id="btn-clear"><i class="fa fa-remove"></i> Clear</button>
                                <button type="submit" name="download" value="download" class="btn btn-success margin-r-5"><i class="fa fa-download"></i> Download</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div> <!-- /.panel -->
            <div style="overflow-x: auto;">
                <table class="table table-striped">
                    <tr>
                        <th>No</th>
                        <th>Staff ID</th>
                        <th>Last Name KH</th>
                        <th>First Name KH</th>
                        <th>Gender</th>
                        <th>Request Date</th>
                        <th>Reason</th>
                        <th>Company</th>
                        <th>Branch / Department</th>
                        <th>Position</th>
                        <th>Action</th>
                    </tr>
                    <tbody>
                    @foreach($request_resigns as $key => $value)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $value->staffPersonalInfo->staff_id }}</td>
                            <td>{{ $value->staffPersonalInfo->last_name_kh }}</td>
                            <td>{{ $value->staffPersonalInfo->first_name_kh }}</td>
                            <td>{{ GENDER[$value->staffPersonalInfo->gender] }}</td>
                            <td>{{ date('d-M-Y', strtotime($value->resign_object->request_date)) }}</td>
                            <td>{{ $value->resign_object->reason }}</td>
                            <td>{{ $value->resign_object->company->name_kh }} {{ '('.$value->resign_object->company->short_name.')' }}</td>
                            <td>{{ $value->resign_object->branch_department->name_kh }} {{ '('.$value->resign_object->branch_department->short_name.')' }}</td>
                            <td>{{ $value->resign_object->position->name_kh }} {{ '('.$value->resign_object->position->short_name.')' }}</td>
                            <td><a href="{{ route('request_resign.edit', encrypt($value->staffPersonalInfo->id)) }}" class="btn btn-xs btn-default"><i class="fa fa-edit"></i></a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{ $request_resigns->appends($param)->links() }}
        </div>
    </div>
@stop
