@extends('adminlte::page')

@section('title', 'Department')

@section('content')

    @include('partials.breadcrumb')

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="breadcrumb">
                @can('add_setting')
                    <a class="btn btn-sm btn-success" href="{{ url('/department/create') }}"> CREATE</a>
                @endcan
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <label for="">Department</label>
        </div>
        <div class="panel-body">
            <table class="table table-bordered">
                <tr>
                    <th width="150px">Action</th>
                    <th>Short name</th>
                    <th>Name EN</th>
                    <th>Name KH</th>
                    <th>Company</th>
                </tr>
                @foreach ($department as $item)
                    <tr>
                        <td>
                            <form id="form_delete" action="{{ url('/department/'.$item->id.'/delete') }}" method="POST">
                                {{ Form::token() }}
                                <a class="" href="{{ url('/department/'.$item->id.'/show') }}"><i class="fa fa-eye-slash"></i></a>
                                {{--@can('company-edit')--}}
                                <a class="" style="margin-left: 5px" href="{{ url('/department/'.$item->id.'/edit') }}"><i class="fa fa-pencil"></i></a>
                                {{--@endcan--}}

                                {{--@can('product-delete')--}}
                                <a href="javascript:void(0);">
                                    <button
                                            onclick="var x = confirm('Are you sure?');if(x){this.form.submit();} else return false;"
                                            style="border: 0;background: none"
                                            class=""><i class="fa fa-trash"></i></button>
                                </a>
                                {{--@endcan--}}
                            </form>
                        </td>
                        <td>{{ $item->short_name }}</td>
                        <td>{{ $item->name_en }}</td>
                        <td>{{ $item->name_kh }}</td>
                        <td>
                            @if ($item->company)
                                <a href="{{ url('/company/'.$item->company->id.'/show') }}">{{ $item->company->name_kh }}</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>

            {!! $department->links() !!}
        </div>
    </div> <!-- .panel-default -->

@endsection