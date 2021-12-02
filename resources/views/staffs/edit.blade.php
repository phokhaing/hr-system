@extends('adminlte::page')

@section('title', 'Edit Staff')

@section('css')
    <style>
        .breadcrumb {
            background-color: #ffffff;
        }
        .nav-tabs-custom>.nav-tabs>li.active>a,
        .nav-tabs-custom>.nav-tabs>li.active:hover>a {
            border-bottom: 3px solid #3c8dbc;
            background: rgba(222, 224, 224, 0.5);
        }
    </style>
@endsection

@section('content')

    <ul class="breadcrumb">
        <li>
            <a href="{{ url('/') }}"> Dashboard</a>
        </li>
        <?php $link = url('/'); ?>
        <li>
            <?php $link .= "/" . Request::segment(1); ?>
            <a href="<?= $link ?>">{{Request::segment(1)}}</a>
                 /
            <a class="text-black">{{ $staff->last_name_kh.' '.$staff->first_name_kh }}</a>
        </li>
    </ul>

{{--    <div class="row">--}}
{{--        <div class="col-sm-12 col-md-12">--}}
{{--            <div class="breadcrumb">--}}
{{--                <a href="#" class="btn btn-success btn-sm" id="btnSave"><i class="fa fa-save"></i> SAVE</a>--}}
{{--                <a href="#" class="btn btn-danger btn-sm" id="btnDiscard"><i class="fa fa-remove"></i> DISCARD</a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs" id="staff-tabs">
                    <li class="@if(\Request::segment(1) == 'staff-personal-info') active @endif">
                        <a href="{{ route('staff-personal-info.edit', encrypt($staff->id)) }}" >Personal Info</a>
                    </li>
                    <li class="@if(\Request::segment(2) == 'education') active @endif">
                        <a href="{{ route('education.edit', encrypt($staff->id)) }}" >Education</a>
                    </li>
{{--                    <li class="@if(\Request::segment(2) == 'training') active @endif">--}}
{{--                        <a href="{{ route('training.edit', encrypt($staff->id)) }}">Training Course</a>--}}
{{--                    </li>--}}
{{--                    <li class="@if(\Request::segment(2) == 'experience') active @endif">--}}
{{--                        <a href="{{ route('experience.edit', encrypt($staff->id)) }}">Work Experience</a>--}}
{{--                    </li>--}}
                    <li class="@if(\Request::segment(2) == 'spouse') active @endif">
                        <a href="{{ route('spouse.edit', encrypt($staff->id)) }}">Spouse</a>
                    </li>
                    <li class="@if(\Request::segment(2) == 'guarantor') active @endif">
                        <a href="{{ route('guarantor.edit', encrypt($staff->id)) }}">Guarantor</a>
                    </li>
{{--                    <li class="@if(\Request::segment(2) == 'profile') active @endif">--}}
{{--                        <a href="{{ route('profile.edit', encrypt($staff->id)) }}">Profile</a>--}}
{{--                    </li>--}}
                    <li class="@if(\Request::segment(2) == 'document') active @endif">
                        <a href="{{ route('document.edit', encrypt($staff->id)) }}" >Document</a>
                    </li>
                    @if(@$staff->contract->contract_object["company"]["code"] == @\auth()->user()->company_code)
                    <li class="@if(\Request::segment(2) == 'request-resign') active @endif">
                        <a href="{{ route('request_resign.edit', encrypt($staff->id)) }}" >Request Resign</a>
                    </li>
                    @endif
                    <li class="@if(\Request::segment(2) == 'pension-fund') active @endif">
                        <a href="{{ route('pension_fund.view', encrypt($staff->id)) }}" >Pension-fund</a>
                    </li>
                    <li class="@if(\Request::segment(2) == 'training') active @endif">
                        <a href="{{ route('training.view', encrypt($staff->id)) }}" >Training</a>
                    </li>
                </ul>

                <div class="tab-content">

                    @yield('tab')
{{--                    <div class="tab-pane active" id="personal_info">--}}
{{--                        @include('staffs._form._edit-personal-info')--}}
{{--                    </div>--}}
{{--                    <!-- /.tab-pane -->--}}
{{--                    <div class="tab-pane" id="education">--}}
{{--                        @if(count($staff->educations) > 0)--}}
{{--                            @include('staffs._form._edit-education')--}}
{{--                        @else--}}
{{--                            @include('staffs._form.education')--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                    <!-- /.tab-pane -->--}}
{{--                    <div class="tab-pane" id="training">--}}
{{--                        @if(count($staff->trainings) > 0)--}}
{{--                            @include('staffs._form._edit-training-course')--}}
{{--                        @else--}}
{{--                            @include('staffs._form.training-course')--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                    <!-- /.tab-pane -->--}}
{{--                    <div class="tab-pane" id="experience">--}}
{{--                        @if(count($staff->experiences) > 0)--}}
{{--                            @include('staffs._form._edit-work-experience')--}}
{{--                        @else--}}
{{--                            @include('staffs._form.work-experience')--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                    <!-- /.tab-pane -->--}}
{{--                    <div class="tab-pane" id="spouse">--}}
{{--                        @if(count($staff->spouse) > 0)--}}
{{--                            @include('staffs._form._edit-spouse')--}}
{{--                        @else--}}
{{--                            @include('staffs._form.spouse')--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                    <!-- /.tab-pane -->--}}
{{--                    <div class="tab-pane" id="guarantor">--}}
{{--                        @if(count($staff->guarantors) > 0)--}}
{{--                            @include('staffs._form._edit-guarantor')--}}
{{--                        @else--}}
{{--                            @include('staffs._form.guarantor')--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                    <!-- /.tab-pane -->--}}
{{--                    <div class="tab-pane" id="profile">--}}
{{--                        @if(!empty($staff->profile))--}}
{{--                            @include('staffs._form._edit-staff-profile')--}}
{{--                        @else--}}
{{--                            @include('staffs._form.staff-profile')--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                    <!-- /.tab-pane -->--}}
{{--                    <div class="tab-pane" id="document">--}}
{{--                        --}}{{--@if(count($staff->documents) > 0)--}}
{{--                            --}}{{--@include('staffs._form._edit-document')--}}
{{--                        --}}{{--@else--}}
{{--                            @include('staffs._form.document')--}}
{{--                        --}}{{--@endif--}}
{{--                    </div>--}}
{{--                    <!-- /.tab-pane -->--}}
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- nav-tabs-custom -->
        </div>
    </div>
@stop

@section('js')
    <script type="text/javascript" src="{{ asset('js/staff/index.js') }}"></script>
    @include('branch.ajax_get_branch_by_company', ['selector' => '#p_company_id'])
    @include('branch.ajax_get_manager_by_company', ['selector' => '#p_company_id'])
@stop
