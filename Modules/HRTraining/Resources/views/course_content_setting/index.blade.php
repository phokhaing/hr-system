@extends('adminlte::page')

@section('title', 'Course')

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

<div class="panel panel-default">
    <div class="panel-heading"><label for="">Course Content</label></div>
    <div class="panel-body">
        <table class="table table-striped">
            <tr>
                <th>Action</th>
                <th>No.</th>
                <th>Course Title</th>
                <th>Total Course Content</th>
                <th>Created Date</th>
            </tr>
            @foreach ($courses as $key => $item)
            @php
            $jsonObj = @to_object(@$item->json_data);
            $courseObj = @to_object(@$item->course->json_data);
            @endphp
            <tr>
                <td>
                    <form id="form_delete" action="{{ route('hrtraining::course.setting.delete', $item->id) }}" method="POST" role="form">
                        {{ Form::token() }}
                        <a href="{{ route('hrtraining::course_content.setting.edit', $item->id) }}"><i class="fa fa-pencil"></i></a>
                    </form>
                </td>
                <td>{{ $key +=1 }}</td>
                <td>{{ @$jsonObj->title }}</td>
                <td><span class="badge bg-danger"> {{ count(@$item->contents) }} Contents</span></td>
                <td>
                    {{ date('d-M-Y', strtotime($item->created_at)) }}
                </td>
            </tr>
            @endforeach
        </table>

        {!! $courses->links() !!}
    </div>
</div> <!-- .panel-default -->

@endsection