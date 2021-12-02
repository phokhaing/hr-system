@extends('adminlte::page')

@section('title', 'company')

@section('content')

    @include('partials.breadcrumb')

    @can('add_company')
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="breadcrumb">
                <a href="{{ url('/company/create') }}" class="btn btn-success btn-sm">CREATE COMPANY</a>
            </div>
        </div>
    </div>
    @endcan

    <div class="panel panel-default">
        <div class="panel-heading"><label for="">Company</label></div>
        <div class="panel-body">
            <table class="table table-bordered">
                <tr>
                    <th width="100px">Action</th>
                    <th>Code</th>
                    <th>Short Name</th>
                    <th>Name EN</th>
                    <th>Name KH</th>
                    <th>Branch</th>
                </tr>
                @foreach ($company as $item)
                    <tr>
                        <td>
                            <form id="form_delete" action="{{ url('/company/'.$item->id.'/delete') }}" method="POST" role="form">
                                {{ Form::token() }}
                                @can('view_company')
                                <a class="" href="{{ url('company/'.$item->id.'/show') }}"><i class="fa fa-eye-slash"></i></a>
                                @endcan
                                @can('edit_company')
                                <a class="" style="margin-left: 5px" href="{{ url('/company/'. $item->id.'/edit') }}"><i class="fa fa-pencil"></i></a>
                                @endcan
                                @can('delete_company')
                                <a href="javascript:void(0);">
                                    <button
                                            onclick="var x = confirm('Are you sure?');if(x){this.form.submit(); } else return false;"
                                            style="border: 0;background: none"
                                            class=""><i class="fa fa-trash"></i>
                                    </button>
                                </a>
                                @endcan
                            </form>
                        </td>
                        <td>{{ $item->company_code }}</td>
                        <td>{{ $item->short_name }}</td>
                        <td>{{ $item->name_en }}</td>
                        <td>{{ $item->name_kh }}</td>
                        <td>{{ count($item->branchAndDepartments) }}</td>
                    </tr>
                @endforeach
            </table>
            {!! $company->links() !!}

        </div>
    </div> <!-- .panel-default -->


@endsection