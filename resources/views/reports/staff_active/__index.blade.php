@extends('adminlte::page')

@section('title', 'Report staff active')

@section('css')
<style>
    .select2 {
        width: 100% !important;
    }
</style>
@stop

@section('content')

@include('partials.breadcrumb')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-filter"></i> Filter</div>
            <div class="panel-body">

                <div class="form-group col-sm-6 col-md-6">
                    <label for="frm">Please Select Report</label>
                    <select name="select_form_report" id="selectFormReport" class="form-control">
                        @foreach(FORM_REPORTS as $key => $value)
                            <option value="{{$key}}"​>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Input form here -->
                <div id="form_active" class="sho ">@include('reports.staff_active.form_filter')</div>
                <div id="form_end_contract" class="sho hidden">@include('reports.staff_end_contract.form_filter')</div>
                <div id="form_movement" class="sho hidden">@include('reports.staff_movement.form_filter')</div>

            </div> <!-- .panel-body -->
        </div> <!-- /.panel -->

        <!-- Input list here -->
        @if (request('is_report_active'))
            <div class="list" data-list="form_active">@include('reports.staff_active.list_view')</div>
        @elseif (request('is_report_end_contract'))
            <div class="list" data-list="form_active">@include('reports.staff_end_contract.list_view')</div>
        @elseif (request('is_report_movement'))
            <div class="list" data-list="form_active">@include('reports.staff_movement.list_view')</div>
        @else

        @endif

    </div> <!-- .col-sm-12 -->
</div>
@stop

@section('js')
<script type="text/javascript" src="{{ asset('js/reports/staff_profile/index.js') }}"></script>
<script>
    $(document).ready(function (e) {

        $("#selectFormReport").on('change', function () {
            let a = $(this).val();
            $('.sho').each(function () {
                let frm = $(this).attr('id');
                console.log(a, frm);
                if (a == frm) {
                    $(this).fadeIn(500).removeClass("hidden");
                } else {
                    $(this).fadeIn(500).addClass("hidden");
                }
            });
        });

        /*$("#advance_filter").on('click', function (e) {
            $("input#is_download").val('');
            $("#advance_filter_modal").modal("show");
        });*/

        /*$("#btnSave").on('click', function (e) {
            e.preventDefault();
            $("input#is_download").val('');
            $("form#report_contract").submit();
        });*/

        /*$("#searchBtn").on('click', function (e) {
           e.preventDefault();
            $("input#is_download").val('');
            $("form#report_contract").submit();
        });*/

        /*$("#advanceFilterBtn").on('click', function (e) {
            e.preventDefault();
            $("input#is_download").val('');

            const filterContractType = $("select#filter_contract_type").find(':selected').val();
            const filterContractStartDate = $("input#filter_contract_start_date").val();
            const filterContractEndDate = $("input#filter_contract_end_date").val();
            const filterContractCompany = $("select#filter_company_code").find(':selected').val();
            const filterContractBrandDepartment = $("select#filter_branch_department_code").find(':selected').val();
            const filterContractPosition = $("select#filter_position_code").find(':selected').val();
            const filterContractGender = $("select#filter_gender").find(':selected').val();

            $("#contract_type").val(filterContractType);
            $("#contract_start_date").val(filterContractStartDate);
            $("#contract_end_date").val(filterContractEndDate);
            $("#company_code").val(filterContractCompany);
            $("#branch_department_code").val(filterContractBrandDepartment);
            $("#position_code").val(filterContractPosition);
            $("#gender").val(filterContractGender);

            $("form#report_contract").submit();
        });*/

       /* $("#resetBtn").on('click', function (e) {
            e.preventDefault();
            $("form#report_contract").find(":input").val("");
            $("form#report_contract").submit();
        });*/

        $("#downloadBtn").on('click', function (e) {
            e.preventDefault();
            $("input#is_download").val(1);
            $("form#report_contract").submit();
        });

        $(".contract_start_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy',
            onSelect: function onSelect(selected) {
                var dt = new Date(selected);
                dt.setDate(dt.getDate());
                $(".contract_end_date").datepicker("option", "minDate", dt).datepicker("setDate", dt);
            }
        });

        $(".contract_end_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy',
            onSelect: function onSelect(selected) {
                var dt = new Date(selected);
                dt.setDate(dt.getDate());
                $(".contract_start_date").datepicker("option", "maxDate", dt);
            }
        });

        $('.select2').select2();
    });
</script>
@endsection
