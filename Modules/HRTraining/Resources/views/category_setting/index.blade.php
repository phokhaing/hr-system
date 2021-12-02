@extends('adminlte::page')

@section('title', 'Category')

@section('css')
    <style>
        .panel-body {
            max-width: 100%;
            overflow-x: auto;
        }
    </style>

@endsection

@section('content')

    @include('partials.breadcrumb')

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="breadcrumb"><a class="btn btn-sm btn-success" href="{{ route('hrtraining::category.create') }}"> CREATE CATEGORY</a></div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><label for="">Category</label></div>
        <div class="panel-body">
            <table class="table table-striped">
                <tr>
                    <th width="150px">Action</th>
                    <th>Title KH</th>
                    <th>Title EN</th>
                    <th>Name EN</th>
                    <th>Name KH</th>
                </tr>
                @foreach ($categories as $item)
                    <tr>
                        <td>
                            <form id="form_delete" action="{{ route('hrtraining::category.destroy', $item->id) }}" method="POST" role="form">
                                {{ Form::token() }}
                                <a class="" style="margin-left: 5px" href="{{ route('hrtraining::category.edit', $item->id) }}"><i class="fa fa-pencil"></i></a>
                                <a class="" style="margin-left: 5px" href="{{ route('hrtraining::category.detail', $item->id) }}"><i class="fa fa-eye"></i></a>
                                <a href="javascript:void(0);">
                                    <button
                                        onclick="var x = confirm('Are you sure?'); if(x){this.form.submit();} else return false;"
                                        style="border: 0;background: none"
                                        class=""><i class="fa fa-trash"></i>
                                    </button>
                                </a>
                            </form>
                        </td>
                        <td>{{ @$item->json_data->title_kh }}</td>
                        <td>{{ @$item->json_data->title_en }}</td>
                        <td>{{ @$item->json_data->desc_kh }}</td>
                        <td>{{ @$item->json_data->desc_en }}</td>
                    </tr>
                @endforeach
            </table>

            {!! $categories->links() !!}
        </div>
    </div> <!-- .panel-default -->

@endsection