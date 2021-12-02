@extends('adminlte::page')

@section('title', 'Course')

@section('content')

    @include('partials.breadcrumb')

    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('hrtraining.name') !!}
    </p>
@endsection
