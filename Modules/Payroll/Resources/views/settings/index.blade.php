@extends('adminlte::page')

@section('title', 'Payroll Setting')

@section('css')
<style>
    .panel-body {
        max-width: 100%;
        overflow-x: auto;
    }

    .btn-circle {
        width: 30px;
        height: 30px;
        text-align: center;
        padding: 6px 0;
        font-size: 12px;
        line-height: 1.428571429;
        border-radius: 15px;
    }

    .btn-circle.btn-lg {
        width: 50px;
        height: 50px;
        padding: 10px 16px;
        font-size: 18px;
        line-height: 1.33;
        border-radius: 25px;
    }

    .btn-circle.btn-xl {
        width: 70px;
        height: 70px;
        padding: 10px 16px;
        font-size: 24px;
        line-height: 1.33;
        border-radius: 35px;
    }
</style>

@endsection

@section('content')

@include('partials.breadcrumb')

@can(Permissions::SET_EXCHANGE_RATE)
<div class="panel panel-default">
    <div class="panel-heading"><label for="">Exchange Rate</label></div>
    <div class="panel-body">

        <form method="post" action="{{ route('payroll.setting.updateExchangeRate') }}" class="form-horizontal">
            {{ Form::token() }}
            <input type="hidden" name="id" value="{{ @$exhangeRate->id }}" />

            <div class="form-group @if($errors->has('exchange_rate')) has-error @endif">
                <label class="col-sm-2 text-right" for="start_date">Currency ($ - ៛): </label>
                <div class="col-sm-8">
                    <input type="number" placeholder="Enter payroll exchange rate..." value="{{ @$exhangeRate->json_data->exchange_rate }}" name="exchange_rate" class="form-control" id="exchange_rate" required />
                </div>
                <div class="col-sm-2 text-right">
                    <button type="submit" class="btn btn-success btn-circle btn-sm"><i class="glyphicon glyphicon-ok" title="Update"></i></button>
                </div>
            </div>
        </form>

    </div>
</div>
@endcan

@can(Permissions::SET_PENSION_FUND)
<div class="panel panel-default">
    <div class="panel-heading"><label for="">Pension Fund</label></div>
    <div class="panel-body">

        <form class="form-horizontal" method="post" action="{{ route('payroll.setting.updatePensionFund', @$pension_fund->id) }}">
            {{ Form::token() }}
            <div class="form-group">
                <label for="pensionFund" class="col-sm-2 col-form-label text-right">Monthly Deduction Rate (%): </label>
                <div class="col-sm-8">
                    <input type="number" step="0.01" class="form-control" name="pension_fund" id="pensionFund" placeholder="0.5" value="{{ @$pension_fund->json_data->rate  }}" required />
                </div>
            </div>

            <div class="form-group">
                <label for="pensionFund" class="col-sm-2 col-form-label text-right">Available for Company: </label>
                <div class="col-sm-8">
                    <select name="company_pension_fund[]" class="form-control js-select2-single" id="companyPensionFound" multiple>
                        @foreach($companies as $key => $value)
                        @php(!$isSelected = false)
                        @foreach(@$pension_fund->json_data->company_code as $company_code => $value1)
                        @if($value1 == $value->company_code)
                        <?php
                        $isSelected = true;
                        break;
                        ?>
                        @endif
                        @endforeach
                        <option value="{{ $value->company_code }}" {{ @$isSelected ? 'selected' : '' }}>{{ $value->name_en }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12 text-right">
                    <button type="submit" class="btn btn-success btn-circle btn-sm"><i class="glyphicon glyphicon-ok" title="Update"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>
@endcan

@can(Permissions::SET_PENSION_FUND_RATE_FROM_COMPANY)
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <label for="" class="col-sm-10">Pension Fund Rate From Company</label>
            @can(Permissions::ADD_PENSION_FUND_RATE_FROM_COMPANY)
            <div class="col-sm-2 text-right">
                <button type="button" class="btn btn-primary btn-circle btn-sm" data-toggle="modal" data-target="#add_new_pension_fund_rate"><i class="glyphicon glyphicon-plus" title="Add"></i></button>
            </div>
            @endcan
        </div>
    </div>
    <div class="panel-body">

        @foreach(@$pensionFundRateFromCompany as $key => $pensionFund)

        <form method="POST" role="form" class="form-horizontal" action="{{ route('payroll.setting.updatePensionFundRate', ['id' => $pensionFund->id]) }}">
            {{ Form::token() }}

            <div class="form-group @if($errors->has('start_date')) has-error @endif">
                <label for="exchange_rate" class="col-sm-2 col-form-label text-right">Rate {{ $key+1 }}: </label>
                <div class="col-sm-2">
                    <input value="{{ @$pensionFund->json_data->rate }}" type="number" step="0.01" name="rate" class="form-control" id="rate" placeholder="Rate (%)..." required />
                </div>

                <div class="col-sm-3">
                    <input value="{{ @$pensionFund->json_data->year_start }}" type="number" step="0.01" name="year_start" class="form-control" id="year_start" placeholder="From year..." required />
                </div>
                <div class="col-sm-3">
                    <input value="{{ @$pensionFund->json_data->year_end }}" type="number" step="0.01" name="year_end" class="form-control" id="year_end" placeholder="To year..." required />
                </div>

                <div class="col-sm-2 text-right">
                    @can(Permissions::UPDATE_PENSION_FUND_RATE_FROM_COMPANY)
                    <button type="submit" class="btn btn-success btn-circle btn-sm"><i class="glyphicon glyphicon-ok" title="Update"></i></button>
                    @endcan
                    @can(Permissions::DELETE_PENSION_FUND_RATE_FROM_COMPANY)
                    <button type="button" class="btn btn-danger btn-circle btn-sm" onclick="removePensionFundRate({{@$pensionFund->id}})"><i class="glyphicon glyphicon-remove" title="Remove"></i></button>
                    @endcan
                </div>
            </div>
        </form>
        @endforeach
    </div>
</div>
@include('payroll::settings.modals.add_new_pension_fund_rate')
@endcan

@can(Permissions::SET_PAYROLL_HALF_MONTH)
<div class="panel panel-default">
    <div class="panel-heading"><label for="">Payroll Half Month</label></div>
    <div class="panel-body">

        <form class="form-horizontal" method="post" action="{{ route('payroll.setting.updateHalfMonth', @$payroll_half_month->id) }}">
            {{ Form::token() }}
            <div class="form-group">
                <label for="exchange_rate" class="col-sm-2 col-form-label tex-right">Available for company: </label>
                <div class="col-sm-8">
                    <select name="company_half_month[]" class="form-control js-select2-single" id="companyHalfMonth" multiple>
                        @foreach($companies as $key => $value)
                        @php(!$isSelected = false)
                        @foreach(@$payroll_half_month->json_data->company_code as $company_code => $value1)
                        @if($value1 == $value->company_code)
                        <?php
                        $isSelected = true;
                        break;
                        ?>
                        @endif
                        @endforeach
                        <option value="{{ $value->company_code }}" {{ @$isSelected ? 'selected' : '' }}>{{ $value->name_en }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2 text-right">
                    <button type="submit" class="btn btn-success btn-circle btn-sm"><i class="glyphicon glyphicon-ok" title="Update"></i></button>
                </div>
            </div>
        </form>

    </div>
</div>
@endcan

@can(Permissions::SET_PAYROLL_DATE_BETWEEN)
<div class="panel panel-default">

    <?php 
        $halfMonthObj = @$payroll_date_between->json_data->half_month;
        $fullMonthObj = @$payroll_date_between->json_data->full_month;
    ?>
    <div class="panel-heading"><label for="">Open Salary Between Day</label></div>
    <div class="panel-body">

        <form class="form-horizontal" method="post" action="{{ route('payroll.setting.updatePayrollPeriod', @$payroll_date_between->id) }}">
            {{ Form::token() }}
            <div class="form-group">
                <label for="exchange_rate" class="col-sm-2 col-form-label text-right">Half Month (1<sup>st</sup>) : </label>
                <div class="col-sm-4">
                    <select class="form-control" name="half_start_date" id="half_start_date">
                        @for($i = 1; $i <= 31; $i ++)
                        <option value="{{ $i }}" {{ (@$halfMonthObj->start_date == $i) ? 'selected' : '' }}>{{ $i < 10 ? '0'. $i : $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-sm-4">
                    <select class="form-control" name="half_end_date" id="half_end_date">
                        @for($i = 1; $i <= 31; $i ++)
                        <option value="{{ $i }}" {{ (@$halfMonthObj->end_date == $i) ? 'selected' : '' }}>{{ $i < 10 ? '0'. $i : $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="exchange_rate" class="col-sm-2 col-form-label text-right">Full Month (2<sup>nd</sup>) : </label>
                <div class="col-sm-4">
                    <select class="form-control" name="full_start_date" id="full_start_date">
                        @for($i = 1; $i <= 31; $i ++)
                        <option value="{{ $i }}" {{ (@$fullMonthObj->start_date == $i) ? 'selected' : '' }}>{{ $i < 10 ? '0'. $i : $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-sm-4">
                    <select class="form-control" name="full_end_date" id="full_end_date">
                        @for($i = 1; $i <= 31; $i ++)
                        <option value="{{ $i }}" {{ (@$fullMonthObj->end_date == $i) ? 'selected' : '' }}>{{ $i < 10 ? '0'. $i : $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12 text-right">
                    <button type="submit" class="btn btn-success btn-circle btn-sm"><i class="glyphicon glyphicon-ok" title="Update"></i></button>
                </div>
            </div>
        </form>

    </div>
</div>
@endcan

@can(Permissions::SET_RINGE_ALLOWANCE_TAX_RATE)
<div class="panel panel-default">
    <div class="panel-heading"><label for="">Tax on Fringe Allowance</label></div>
    <div class="panel-body">

        <form class="form-horizontal" method="post" action="{{ route('payroll.setting.updateFringAllowance', @$fring_allowance_tax_rate->id) }}">
            {{ Form::token() }}
            <div class="form-group">
                <label for="exchange_rate" class="col-sm-2 col-form-label text-right">Rate (%): </label>
                <div class="col-sm-8">
                    <input type="number" step="0.01" class="form-control" name="fring_allowance_tax_rate" id="fring_allwance_tax_rate" placeholder="0.2" value="{{ @$fring_allowance_tax_rate->json_data->rate}}" required />
                </div>
                <div class="col-sm-2 text-right">
                    <button type="SUBMIT" class="btn btn-success btn-circle btn-sm"><i class="glyphicon glyphicon-ok" title="Update"></i></button>
                </div>
            </div>
        </form>

    </div>
</div>
@endcan

@can(Permissions::SET_TAX_ON_SALARY)
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <label for="" class="col-sm-10">Tax on Salary</label>
            @can(Permissions::ADD_TAX_ON_SALARY)
            <div class="col-sm-2 text-right">
                <button type="button" class="btn btn-primary btn-circle btn-sm" data-toggle="modal" data-target="#add_new_tax_on_salary"><i class="glyphicon glyphicon-plus" title="Add"></i></button>
            </div>
            @endcan
        </div>
    </div>
    <div class="panel-body">

        @foreach(@$taxtOnSalaries as $key => $taxOnSalary)

        <form method="POST" role="form" class="form-horizontal" action="{{ route('payroll.setting.updateTaxOnSalary', ['id' => $taxOnSalary->id]) }}">
            {{ Form::token() }}

            <div class="form-group @if($errors->has('start_date')) has-error @endif">
                <label for="exchange_rate" class="col-sm-2 col-form-label text-right">Rate {{ $key+1 }}: </label>
                <div class="col-sm-2">
                    <input value="{{ @$taxOnSalary->tax_object->tax_rate }}" type="number" step="0.01" name="rate" class="form-control" id="rate" placeholder="Rate (%)..." required />
                </div>

                <div class="col-sm-3">
                    <input value="{{ @$taxOnSalary->tax_object->tax_range_from }}" type="number" step="0.01" name="salary_range_from" class="form-control" id="salary_range_from" placeholder="Salary range from..." required />
                </div>
                <div class="col-sm-3">
                    <input value="{{ @$taxOnSalary->tax_object->tax_range_to }}" type="number" step="0.01" name="salary_range_to" class="form-control" id="salary_range_to" placeholder="Salary range to..." required />
                </div>

                <div class="col-sm-2 text-right">
                    @can(Permissions::UPDATE_TAX_ON_SALARY)
                    <button type="submit" class="btn btn-success btn-circle btn-sm"><i class="glyphicon glyphicon-ok" title="Update"></i></button>
                    @endcan
                    @can(Permissions::DELETE_TAX_ON_SALARY)
                    <button type="button" class="btn btn-danger btn-circle btn-sm" onclick="removeTaxOnSalary({{@$taxOnSalary->id}})"><i class="glyphicon glyphicon-remove" title="Remove"></i></button>
                    @endcan
                </div>
            </div>
        </form>
        @endforeach
    </div>
</div>
@include('payroll::settings.modals.add_new_tax_on_salary')
@endcan

@can(Permissions::SET_SENIORITY)
<div class="panel panel-default">
    <div class="panel-heading"><label for="">Seniority</label></div>
    <div class="panel-body">
        <form class="form-horizontal" method="post" action="{{ route('payroll.setting.updateSeniority', @$seniority->id) }}">
            {{ Form::token() }}
            <div class="form-group">
                <label for="" class="col-sm-2 col-form-label text-right">Seniority Amount: </label>
                <div class="col-sm-4">
                    <div class="input-group">
                        <span class="input-group-addon"><i style="font-size: 20px;">៛</i></span>
                        <input type="number" class="form-control" name="khr_amount" id="khr_amount" placeholder="0.2" value="{{ @$seniority->json_data->khr_amount}}" required />
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                        <input type="number" class="form-control" name="usd_amount" id="usd_amount" placeholder="0.2" value="{{ @$seniority->json_data->usd_amount}}" required />
                    </div>
                </div>
                <div class="col-sm-2 text-right">
                    <button type="SUBMIT" class="btn btn-success btn-circle btn-sm"><i class="glyphicon glyphicon-ok" title="Update"></i></button>
                </div>
            </div>
        </form>

    </div>
</div>
@endcan

@can(Permissions::VIEW_TRANSACTION_CODE)
<div class="panel panel-default">
    <div class="panel-heading"><label for="">Transaction Code</label></div>
    <div class="panel-body">
        <div class="row">
            @foreach($transaction_code as $key => $transactioin)
            <div class="col-sm-4">
                <label>Code: {{ @$transactioin->id}}</label> 
                =>
                <span for="">{{ @$transactioin->name_kh}}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endcan


@endsection

@section("js")
<script>
    $(".datepicker").datepicker({
        dateFormat: 'dd-mm-yy'
    });

    function removePensionFundRate(id) {
        Swal.fire({
                title: 'Warining',
                text: 'តើអ្នកពិតជាចង់លុប PENSION FUND RATE នេះមែនឬ?',
                type: 'warning',
                showConfirmButton: true,
                showCancelButton: true,
                cancelButtonColor: '#d33',
                cancelButtonText: 'ទេ',
                confirmButtonText: 'បាទ/ចាស់'
            })
            .then(function(isConfirm) {
                if (isConfirm.value) {
                    $(".loading").fadeIn("fast");
                    console.log(id);
                    $.ajax({
                        type: 'post',
                        url: "{{ route('payroll.setting.deletePensionFundRate') }}",
                        data: {
                            'id': id,
                        },
                        success: function(data) {
                            if (data.status === 1) {
                                location.reload();
                            } else {
                                $(".loading").fadeOut("fast");
                                //Handle error message here
                                Swal.fire({
                                    title: 'Warning!',
                                    text: data.message,
                                    type: 'warning',
                                    showConfirmButton: false,
                                    showCancelButton: true,
                                    cancelButtonColor: '#d33',
                                    cancelButtonText: 'ពិនិត្យឡើងវិញ'
                                })
                            }
                        }
                    });
                }
            });
    }

    function removeTaxOnSalary(id) {
        Swal.fire({
                title: 'Warining',
                text: 'តើអ្នកពិតជាចង់លុប TAX ON SALARY RATE នេះមែនឬ?',
                type: 'warning',
                showConfirmButton: true,
                showCancelButton: true,
                cancelButtonColor: '#d33',
                cancelButtonText: 'ទេ',
                confirmButtonText: 'បាទ/ចាស់'
            })
            .then(function(isConfirm) {
                if (isConfirm.value) {
                    $(".loading").fadeIn("fast");
                    console.log(id);
                    $.ajax({
                        type: 'post',
                        url: "{{ route('payroll.setting.deleteTaxOnSalary') }}",
                        data: {
                            'id': id,
                        },
                        success: function(data) {
                            if (data.status === 1) {
                                location.reload();
                            } else {
                                $(".loading").fadeOut("fast");
                                //Handle error message here
                                Swal.fire({
                                    title: 'Warning!',
                                    text: data.message,
                                    type: 'warning',
                                    showConfirmButton: false,
                                    showCancelButton: true,
                                    cancelButtonColor: '#d33',
                                    cancelButtonText: 'ពិនិត្យឡើងវិញ'
                                })
                            }
                        }
                    });
                }
            });
    }
</script>
@endsection