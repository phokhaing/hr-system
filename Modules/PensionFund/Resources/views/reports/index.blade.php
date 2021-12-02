@extends('adminlte::page')

@section('title', 'Reports')

@section('content')

    @include('partials.breadcrumb')

    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-filter"></i>
            Filter Report
        </div>

        <div class="panel-body">
            {{ session()->get('message') }}

            <form action="{{ route('pensionfund::report.index') }}" method="GET"
                  class="form-horizontal"
                  role="form">
                {{ Form::token() }}

                <div class="form-group">
                    <label for="report_type" class="col-sm-2">
                        Report Type *
                    </label>
                    <div class="col-sm-10">
                        <select class="form-control js-select2-single" id="report_type" name="report_type" required>
                            <option value="">>> Please Select Report Type <<</option>
                            <option value="{{ PF_REPORT_TYPE['CURRENT'] }}" {{ @request('report_type') == PF_REPORT_TYPE['CURRENT'] ? 'selected' : '' }}>Current Staff Pension Fund</option>
                            <option value="{{ PF_REPORT_TYPE['SUMMARY'] }}" {{ @request('report_type') == PF_REPORT_TYPE['SUMMARY'] ? 'selected' : '' }}>Summary Pension Fund</option>
                            <option value="{{ PF_REPORT_TYPE['CLAIM'] }}" {{ @request('report_type') == PF_REPORT_TYPE['CLAIM'] ? 'selected' : '' }}>Claim Pension Fund</option>
                        </select>
                    </div>
                </div>

            </form>
        </div>
    </div> <!-- .panel-default -->

    <div class="panel-container">
        <div class="panel panel-default panel-filter-data">
            <div class="panel-heading">
                <i class="fa fa-filter"></i>
                Filter Date
            </div>

            <div class="panel-body">

                <div class="row">
                    <form action="{{ route('pensionfund::report.index') }}" method="GET"
                          role="form">
                        {{ Form::token() }}

                        <div class="form-group col-sm-12">
                            <label for="staff">Search Staff</label>
                            <input type="text" name="search" id="search" placeholder=""/>
                        </div>

                        <div class="form-group col-sm-12">
                            <button class="btn btn-primary">
                                <i class="fa fa-search"></i>
                                Search
                            </button>

                            <button class="btn btn-success">
                                <i class="fa fa-download"></i>
                                Download
                            </button>
                        </div>

                    </form>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        @if(@$reportType == PF_REPORT_TYPE['CLAIM'])
                            @include('pensionfund::reports.partials.claim_report_layout', ['items' => $items])
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

@stop

@section('js')
    <script>

        const CURRENT_STAFF_PF = "{{ PF_REPORT_TYPE['CURRENT'] }}";
        const SUMMARY_PF = "{{ PF_REPORT_TYPE['SUMMARY'] }}";
        const CLAIM_PF = "{{ PF_REPORT_TYPE['CLAIM'] }}";

        $(document).ready(function () {
            const panelFilterData = $(".panel-filter-data").clone();
            $(".panel-filter-data").remove();

            $("#report_type").change(function () {
                const type = $(this).find(':selected').val();
                const panelFilterDataClone = panelFilterData.clone();

                if (type > 0) {
                    $('.panel-container').empty().append(panelFilterDataClone);
                    $("#form-filter-data").find('#type').val(type);
                }else{
                    $(".panel-filter-data").remove();
                }
            });
        });
    </script>
@endsection
