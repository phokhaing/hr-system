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
        <filter-form-report></filter-form-report>
    </div> <!-- .col-sm-12 -->
</div>
@stop

@section('js')
<script>
    $(document).ready(function (e) {

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
