@extends('adminlte::page')

@section('title', 'Branch And Department')

@section('content')

@include('partials.breadcrumb')

@can('add_branch_department')
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="breadcrumb">
            @can('add_setting')
            <a class="btn btn-sm btn-success " href="{{ url('/branch-and-department/create') }}"> CREATE BRANCH OR DEPARTMENT</a>
            @endcan
        </div>
    </div>
</div>
@endcan

<div class="panel panel-default">
    <div class="panel-heading"><i class="fa fa-filter"></i> <label for="">Filter</label></div>
    <div class="panel-body">
        <form action="{{ route('branch_department.index') }}" role="form" method="get" id="filter-form">
            {{ csrf_field() }}
            <input type="hidden" name="company" value="{{request('company')}}"/>
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
    <div class="panel-heading">
        <label for="">Branch Or Department</label>
    </div>
    <div class="panel-body" style="overflow-x:auto;">
        <table class="table table-bordered">
            <tr>
                <th width="150px">Action</th>
                <th>Code</th>
                <th>Short name</th>
                <th>Name EN</th>
                <th>Name KH</th>
                <th>Company</th>
            </tr>
            @foreach ($branchesDepartments as $item)
            <tr>
                <td>
                    <form id="form_delete" action="{{ url('/branch-and-department/'.$item->id.'/delete') }}" method="POST">
                        {{ Form::token() }}
                        @can('view_branch_department')
                        <a class="" href="{{ url('/branch-and-department/'.$item->id.'/show') }}"><i class="fa fa-eye-slash"></i></a>
                        @endcan
                        @can('edit_branch_department')
                        <a class="" style="margin-left: 5px" href="{{ url('/branch-and-department/'.$item->id.'/edit') }}"><i class="fa fa-pencil"></i></a>
                        @endcan
                        @can('delete_branch_department')
                        <a href="javascript:void(0);">
                            <button onclick="var x = confirm('Are you sure?');if(x){this.form.submit();} else return false;" style="border: 0;background: none" class=""><i class="fa fa-trash"></i></button>
                        </a>
                        @endcan
                    </form>
                </td>
                <td>{{ $item->code }}</td>
                <td>{{ $item->short_name }}</td>
                <td>{{ $item->name_en }}</td>
                <td>{{ $item->name_km }}</td>
                <td>
                    @if ($item->company)
                    <a href="{{ url('/company/'.$item->company->id.'/show') }}">{{ $item->company->name_kh }}({{ $item->company_code }})</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
        {!! $branchesDepartments->appends(request()->query())->links() !!}
    </div>
</div> <!-- .panel-default -->

@endsection

<div class="modal fade in" id="advanceSearch">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Advance Search</h4>
            </div>
            <div class="modal-body">
                <form action="{{ route('branch_department.index') }}" method="get">
                    <input type="hidden"
                           name="keyword"
                           class="form-control"
                           placeholder="Specific keyword"
                           value="{{ request()->get('keyword') }}"
                    >

                    {{ Form::token() }}
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