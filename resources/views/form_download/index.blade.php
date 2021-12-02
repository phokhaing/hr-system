@extends('adminlte::page')

@section('title', 'List all form download')

@section('content')

    @include('partials.breadcrumb')

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="breadcrumb">
                <a href="{{ url('/form_download/create') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-download"></i> CREATE</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><label for="">List all form download</label></div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Action</th>
                            <th>Title</th>
                            <th>PDF Source</th>
                            <th>Other Document Source</th>
                        </tr>
                        @foreach ($data as $item)
                            <tr>
                                <td>
                                    <form id="form_delete" action="/form_download/{{$item->id}}/delete" method="POST">
                                        {{ Form::token() }}
                                        {{--<a href="/form_download/{{$item->id}}/show" ><i class="fa fa-eye-slash"></i></a>--}}
                                        <a href="#">
                                            <button type="submit"
                                                    style="border: 0;background: none"
                                                    class="delete_btn"><i class="fa fa-trash"></i></button>
                                        </a>
                                    </form>
                                </td>
                                <td>{{ $item->title }}</td>
                                <td>
                                    @if ($item->pdf_src)
                                        <a target="_blank" href="{{ asset($item->pdf_src) }}">{{ $item->pdf_name }}</a>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->doc_src)
                                        <a target="_blank" href="{{ asset($item->doc_src) }}">{{ $item->doc_name }}</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    {!! $data->links() !!}
                </div>
            </div>
        </div>
    </div>

@endsection
