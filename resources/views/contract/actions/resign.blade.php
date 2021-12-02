@extends('adminlte::page')

@section('title', 'Staff resign')

@section('content')
@include('partials.breadcrumb')
{{ session()->get('message') }}


@if (\Session::has('error'))
    <div class="row alert alert-error">
        <ul>
            <li>{!! \Session::get('error') !!}</li>
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading"><label for="">Form resign</label></div>
            <div class="panel-body">
                @include('contract._form.resign')

            </div>
        </div>
    </div>
</div>
@endsection