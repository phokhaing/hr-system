@extends('adminlte::page')

@section('title', 'Show position')

@section('content')

    @include('partials.breadcrumb')

    <div class="panel panel-default">
        <div class="panel-heading">
            <label for="">Show position</label>
            <a class="btn btn-xs btn-primary pull-right" href="{{ url('/position') }}"> Back</a>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
                    <label for="">Short Name:</label> {{ $position->short_name }}
                    <hr>
                    <label for="">Name EN:</label> {{ $position->name_en }}
                    <hr>
                    <label for="">Name KH:</label> {{ $position->name_kh }}
                    <hr>
                    <label for="">Salary Range:</label> {{ $position->range }}
                    <hr>
                    <label for="">Description EN: </label>
                    {!! $position->desc_en !!}
                    <hr>
                    <label for="">Description KH:</label>
                    {!! $position->desc_kh !!}
                    <hr/>
                    <label for="">Company:</label>
                    {!! @$position->company->name_kh ?: 'N/A' !!}
                </div>
            </div>
        </div>
    </div> <!-- .panel-heading -->

@endsection