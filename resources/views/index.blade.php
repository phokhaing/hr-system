@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')

    <ul class="breadcrumb">
        <li><a href="{{ url('/') }}"> Dashboard</a></li>
    </ul>

    <div class="row">
        @can('view_staff')
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ $staffActive }}</h3>
                        <p>Staff Active</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-fw fa-user "></i>
                    </div>
                    <a href="{{ route('report.index') }}" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <!-- ./col -->
        @endcan

        @can('view_resign')
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{ $endContract }}</h3>
                        <p>Staff End Contract</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-fw fa-user-times"></i>
                    </div>
                    <a href="{{ route('report.index') }}" class="small-box-footer">More Info  <i class="fa fa-arrow-circle-right "></i></a>
                </div>
            </div>
            <!-- ./col -->
        @endcan

        @can('view_staff_movement')
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{ $movement }}</h3>
                        <p>Staff Movement</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-fw fa-exchange"></i>
                    </div>
                    <a href="{{ route('report.index') }}" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right "></i></a>
                </div>
            </div>
            <!-- ./col -->
        @endcan

    </div>

@stop

@section('css')
    <style>
        .small-box .icon {
            top: 10px;
            font-size: 60px
        }
        .small-box:hover .icon {
            /*top: 15px;*/
            font-size: 70px;
        }
    </style>
@endsection