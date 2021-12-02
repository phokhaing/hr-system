<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title_prefix', config('adminlte.title_prefix', ''))
    @yield('title', config('adminlte.title', 'HR-MIS'))
    @yield('title_postfix', config('adminlte.title_postfix', ''))</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/font-awesome.css') }}">

    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('css/ionicons.css') }}">

    @if(config('adminlte.plugins.select2'))
        <!-- Select2 -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    @endif

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.css') }}">

    @if(config('adminlte.plugins.datatables'))
        <!-- DataTables with bootstrap 3 style -->
        <link rel="stylesheet" href="//cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.css">
    @endif

    <!-- Sweet alert 2 -->
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">

    <!-- Google Khmer font -->
    <link href="https://fonts.googleapis.com/css?family=Nokora:400,700&amp;subset=khmer" rel="stylesheet">

    @yield('adminlte_css')

    <style>
        body {
            font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif,'Nokora';
        }
        .swal2-popup {
            font-size: 1.6rem !important;
        }
    </style>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body class="hold-transition @yield('body_class')">
<div id="app1">
    @yield('body')
</div>

<script type="text/javascript">
    window.Laravel = {
        csrfToken: "{{ csrf_token() }}",
        jsPermissions: {!! auth()->check()?auth()->user()->jsPermissions():null !!}
    }
</script>

<script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.slimscroll.min.js') }}"></script>

@if(config('adminlte.plugins.select2'))
    <!-- Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
@endif
@if(config('adminlte.plugins.datatables'))
    <!-- DataTables with bootstrap 3 renderer -->
    <script src="{{ asset('js/datatables/datatables.min.js') }}"></script>
@endif
@if(config('adminlte.plugins.chartjs'))
    <!-- ChartJS -->
    <script src="{{ asset('js/Chart/Chart.bundle.min.js') }}"></script>
@endif
<script src="{{ asset('/sweet_alert2/sweetalert2.all.min.js') }}"></script>

@yield('adminlte_js')

@include('layouts.error')
@include('layouts.success')
</body>
</html>
