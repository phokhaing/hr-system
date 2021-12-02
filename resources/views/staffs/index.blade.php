@extends('adminlte::page')

@section('title', 'List all staff')

@section('content')
    @include('partials.breadcrumb')

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="breadcrumb">
                <a href="{{ route('staff-personal-info.create') }}#personal_info" class="btn btn-success btn-sm"><i class="fa fa-user-plus"></i> CREATE</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-filter"></i> Filter</div>
                <div class="panel-body">
                    <form action="{{ route('staff.filter') }}" role="form" method="get" id="filter-form">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group col-sm-6 col-md-4">
                                <input type="text" name="key_word" class="form-control" placeholder="Keyword" value="{{ request('key_word') }}">
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
                                <button type="submit" class="btn btn-danger margin-r-5" id="btn-clear"><i class="fa fa-remove"></i> Clear Filter</button>
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
                        <th>Full Name KH</th>
                        <th>Full Name EN</th>
                        <th>D.O.E</th>
                        <th>Date Of Birth</th>
                        <th>Place Of Birth</th>
                        <th>Phone Number</th>
                        <th>Action</th>
                    </tr>
                    @foreach($staffs as $key => $staff)
                        @php $i++ @endphp
                        @php($enableDelete = !isset($staff->contract))
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ @$staff->staff_id ?? 'N/A' }}</td>
                            <td>{{ $staff->last_name_kh.' '.$staff->first_name_kh }}</td>
                            <td>{{ $staff->last_name_en.' '.$staff->first_name_en }}</td>
                            <td>
                                {{ @$staff->firstContract->start_date ? date('d-M-Y', strtotime(@$staff->firstContract->start_date)) : 'N/A' }}
                            </td>
                            <td>{{ isset($staff->dob) ? date_readable($staff->dob) : 'N/A' }}</td>
                            <td>{{ $staff->pob ?? 'N/A' }}</td>
                            <td>{{ $staff->phone }}</td>
                            <td>
                                <a title="Show" href="{{ route('staff-personal-info.show', encrypt($staff->id)) }}" class="btn btn-info btn-xs margin-r-5"><i class="fa fa-eye"></i></a>
                                <a title="Edit" href="{{ route('staff-personal-info.edit', encrypt($staff->id)) }}" class="btn btn-primary btn-xs margin-r-5"><i class="fa fa-edit"></i></a>
                                @if($enableDelete)
                                    <a title="Delete" href="javascript:void(0);" class="btn btn-danger btn-xs btn-delete margin-r-5" data-delete="{{ encrypt($staff->id) }}"><i class="fa fa-trash"></i></a>
                                @endif
                                {{--<a title="Resign" href="/staff-personal-info/{{ $staff->id }}/resign" class="btn btn-warning btn-xs"><i class="fa fa-sign-out"></i></a>--}}
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>

            {{ $staffs->appends($param)->links() }}
        </div>
    </div>
@stop

@section('js')
    <script type="text/javascript" src="{{ asset('js/staff/index.js') }}"></script>
    <script>
        $(document).ready(function (e) {
            $("#btn-clear").on('click', function (e) {
                e.preventDefault();
                $("form#filter-form").find(":input").val("");
                $("form#filter-form").submit();
            })
        });
    </script>
@stop