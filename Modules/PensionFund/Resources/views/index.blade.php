
@extends('adminlte::page')

@section('title', 'PensionFund')

@section('content')
	@include('partials.breadcrumb')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('pensionfund.name') !!}
    </p>
@endsection
