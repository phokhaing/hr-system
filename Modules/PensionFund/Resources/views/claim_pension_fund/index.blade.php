@extends('adminlte::page')

@section('title', 'Upload Pension Fund')

@section('css')

<style type="text/css">
    .table tr  th, .table tr td {
        vertical-align: middle !important;
    }
    .date {
        background-color: white !important;
    }
</style>

@endsection

@section('content')

@include('partials.breadcrumb')
@include('pensionfund::layouts.layout_flash_error', ['message' => @$message])

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><label for=""><i class="fa fa-filter"></i> FILTER</label></div>
            <div class="panel-body">
                <div class="row">
                    <div class="form-group col-sm-6 col-md-6">
                        <label for="staff"> Find Staff </label>
                        <select name="staff" id="staff"
                                class="staff js-select2-single form-control">
                            <option value="">>> Finding <<</option>
                            @foreach($staff as $key => $value)
                                <option value="{{ $value->id }}">
                                    {{ $value->staff_id }} - {{ $value->last_name_kh }} {{ $value->first_name_kh }}
                                    ( {{ $value->last_name_en }} {{ $value->first_name_en }} )
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div> <!-- /.panel -->
    </div>
</div>

@include('pensionfund::claim_pension_fund.partials.info')

@include('pensionfund::claim_pension_fund.partials.claim')


@stop

@section('js')

    @include('pensionfund::claim_pension_fund.partials.js')

@endsection