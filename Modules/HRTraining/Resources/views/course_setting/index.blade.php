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

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="breadcrumb"><a class="btn btn-sm btn-success" href="{{ route('hrtraining::course.setting.create') }}"> CREATE COURSE</a></div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><label for="">Course</label></div>
    <div class="panel-body">
        <table class="table table-striped">
            <tr>
                <th width="150px">Action</th>
                <th>Course Title</th>
                <th>Description</th>
                <th>Position</th>
                <th>Cost</th>
                <th>Frequency</th>
                <th>Grade</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Created Date</th>
            </tr>
            @foreach ($courses as $item)
            @php
                $jsonObj = @to_object($item->json_data);
            @endphp
            <tr>
                <td>
                    <form id="form_delete" action="{{ route('hrtraining::course.setting.delete', $item->id) }}" method="POST" role="form">
                        {{ Form::token() }}

                        <a class="" href="{{ route('hrtraining::course.setting.detail', $item->id) }}"><i class="fa fa-eye-slash"></i></a>

                        <a class="" style="margin-left: 5px" href="{{ route('hrtraining::course.setting.edit', $item->id) }}"><i class="fa fa-pencil"></i></a>

                        <a href="javascript:void(0);">
                            <button
                                    onclick="var x = confirm('Are you sure want to delete this course?'); if(x){this.form.submit();} else return false;"
                                    style="border: 0;background: none"
                                    class=""><i class="fa fa-trash"></i>
                            </button>
                        </a>
                    </form>
                </td>
                <td>{{ @$jsonObj->title }}</td>
                <td>{{ @$jsonObj->description }}</td>
                <td>{{ @$item->position->name_en .' ('. @$item->position->short_name .')' }}</td>
                <td>{{ @$jsonObj->cost ? number_format(@$jsonObj->cost, 2) . '$' : '' }}</td>
                <td>{{ @$jsonObj->frequency }}</td>
                <td>{{ @$jsonObj->grade }}</td>
                <td>{{ @$jsonObj->duration ? @$jsonObj->duration . 'hours': '' }}</td>
                <td>
                    <span class="badge badge-pill badge-info">{{ COURSE_STATUS[@$jsonObj->status] }}</span>
                </td>
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