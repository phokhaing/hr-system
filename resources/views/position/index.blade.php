@extends('adminlte::page')

@section('title', 'Position')

@section('content')

@include('partials.breadcrumb')

@can('add_position')
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="breadcrumb"><a class="btn btn-sm btn-success" href="{{ url('/position/create') }}"> CREATE POSITION</a></div>
    </div>
</div>
@endcan

<div class="panel panel-default">
    <div class="panel-heading"><i class="fa fa-filter"></i> <label for="">Filter</label></div>
    <div class="panel-body">
        <form action="{{ route('position.index') }}" role="form" method="get" id="filter-form">
            {{ csrf_field() }}
            <input type="hidden" name="company" value="{{request()->get('company')}}"/>
            <div class="row">
                <div class="form-group col-sm-6 col-md-6">
                    <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Keyword" value="{{ request('keyword') }}">
                </div>

                <div class="form-group col-sm-6 col-md-6">
                    <button type="submit" class="btn btn-primary margin-r-5"><i class="fa fa-search"></i> Search</button>
                    <button type="button" class="btn btn-danger margin-r-5" id="btn-clear"><i class="fa fa-remove"></i> Clear</button>
                    <button type="button" class="btn btn-primary margin-r-5" data-toggle="modal" data-target="#advanceSearch"><i class="fa fa-filter"></i> Advance Search</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><label for="">Position</label></div>
    <div class="panel-body" style="overflow-x:auto;">
        <table class="table table-bordered">
            <tr>
                <th width="150px">Action</th>
                <th>Short Name</th>
                <th>Level</th>
                <th>Name EN</th>
                <th>Name KH</th>
                <th>Salary Range</th>
                <th>Company</th>
            </tr>
            @foreach ($position as $item)
            <tr>
                <td>
                    <form id="form_delete" action="{{ url('/position/'.$item->id.'/delete') }}" method="POST" role="form">
                        {{ Form::token() }}
                        @can('view_position')
                        <a class="" href="{{ url('/position/'.$item->id.'/show') }}"><i class="fa fa-eye-slash"></i></a>
                        @endcan

                        @can('edit_position')
                        <a class="" style="margin-left: 5px" href="{{ url('/position/'.$item->id.'/edit') }}"><i class="fa fa-pencil"></i></a>
                        @endcan

                        @can('delete_position')
                        <a href="javascript:void(0);">
                            <button onclick="var x = confirm('Are you sure?'); if(x){this.form.submit();} else return false;" style="border: 0;background: none" class=""><i class="fa fa-trash"></i>
                            </button>
                        </a>
                        @endcan
                    </form>
                </td>
                <td>{{ $item->short_name }}</td>
                <td>{{ $item->group_level }}</td>
                <td>{{ $item->name_en }}</td>
                <td>{{ $item->name_kh }}</td>
                <td>{{ $item->range }}</td>
                <td>{{ $item->company->name_kh ?? 'N/A' }}({{@$item->company_code}})</td>
            </tr>
            @endforeach
        </table>

        {!! $position->appends(request()->query())->links() !!}
    </div>
</div> <!-- .panel-default -->


<div class="modal fade in" id="advanceSearch">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Advance Search</h4>
            </div>
            <div class="modal-body">
                <form action="{{ route('position.index') }}" method="get">
                    {{ Form::token() }}
                    <input type="hidden" name="keyword" value="{{request()->get('keyword')}}"/>
                    <div class="form-group">
                        <select name="company" id="company" class="form-control">
                            <option value=""> << Select Company >> </option>
                            @foreach($companies as $key => $value)
                                <option value="{{ $value->company_code }}" {{ (request()->get('company') == $value->company_code) ? 'selected' : '' }}>
                                    {{ $value->company_code ." - ". $value->name_kh }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="level" id="level" class="form-control">
                            <option value=""> << Select Level >> </option>
                            @foreach($position_level as $key => $value)
                                <option value="{{ $value->group_level }}" {{ (request()->get('level') == $value->group_level) ? 'selected' : '' }}>
                                    {{ $value->group_level }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <div>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary pull-right">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection

@section('js')

<script type="text/javascript">
    $(document).ready(function() {
        $('#btn-clear').click(function(e) {
            e.preventDefault();
            $("#keyword").val(null);
            $("#filter-form").submit();
        });

    });
</script>
@endsection