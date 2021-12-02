@extends('adminlte::page')

@section('title', 'Create New Staff')

@section('css')
    <style>
        .nav-tabs-custom>.nav-tabs>li.active>a,
        .nav-tabs-custom>.nav-tabs>li.active:hover>a {
            border-bottom: 3px solid #3c8dbc;
            background: rgba(222, 224, 224, 0.5);
        }
    </style>
@endsection

@section('content')
    @include('partials.breadcrumb')

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#personal_info" data-toggle="tab" aria-expanded="true">Personal Info</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="personal_info">
                        @include('staffs._form.personal-info')
                    </div>
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- nav-tabs-custom -->
        </div>
    </div>
@stop

@section('js')
    @yield('personal_js')
@stop