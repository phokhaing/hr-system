@extends('adminlte::page')
@section('title', 'Contract')
@section('css')
<style>
    .select2 {
        width: 100% !important;
    }

    .button-create,
    .select-contract-type {
        margin-left: 12px !important;
    }
</style>
@stop

@section("content")
@include('partials.breadcrumb')

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-filter"></i> Filter </div>
            <div class="panel-body">
                <form action="{{ route('block_salary.index') }}" role="form" method="get" id="form-filter">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-6">
                            <input type="text" name="keyword" id="keyword" class="form-control" placeholder="ស្វែងរកតាមរយៈ៖ Staff ID, Name, Phone  " value="{{ request('keyword') }}">
                        </div>

                        <div class="form-group col-sm-6 col-md-6">
                            <button type="submit" class="btn btn-primary margin-r-5"><i class="fa fa-search"></i> Search
                            </button>
                            <button type="reset" class="btn btn-danger margin-r-5" id="clearBtn"><i class="fa fa-remove"></i> Clear
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div> <!-- /.panel -->

        <div style="overflow: auto">
            <table class="table table-striped">
                <tr>
                    <th>#</th>
                    <th>Action</th>
                    <th>Staff ID</th>
                    <th>Company <br> ID Card</th>
                    <th>Full Name KH</th>
                    <th>Full Name EN</th>
                    <th>Company</th>
                    <th>Department <br>/ Branch</th>
                    <th>Position</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Probation <br>End Date</th>
                    <th>Contract <br> Type</th>
                </tr>

                @if(@$contracts && count($contracts))
                <?php $i = 1; ?>
                @foreach($contracts as $key => $value)

                <?php
                $contract_object = @$value->contract_object;
                $array_flip = array_flip(CONTRACT_TYPE); // Reverse array value to key
                $full_name_en = $value->staffPersonalInfo->last_name_en . ' ' . $value->staffPersonalInfo->first_name_en;
                $full_name_kh = $value->staffPersonalInfo->last_name_kh . ' ' . $value->staffPersonalInfo->first_name_kh;
                ?>
                <tr id="tr-contract-{{@$value->id}}">
                    <td>{{ $i }}</td>
                    <td>
                        <a href="{{ route('contract.edit',encrypt(@$value->id)) }}" class="btn btn-xs btn-primary margin-r-5"
                            title="{{ @$contract_object['is_block_salary'] ? 'Un-Block Now' : 'Block Now' }}">
                                @if(@$contract_object['is_block_salary'])
                                <i class="fa fa-unlock"></i>
                                @else
                                <i class="fa fa-lock"></i>
                                @endif
                        </a>
                    </td>
                    <td>{{ @$value->staffPersonalInfo->staff_id }}</td>
                    <td>{{ substr(@$value->staff_id_card, 3, (strlen(@$value->staff_id_card))) }}</td>
                    <td>{{ @$full_name_kh }}</td>
                    <td>{{ @$full_name_en }}</td>
                    <td style="cursor:help;" data-toggle="tooltip" data-placement="top" title="{{ @$contract_object['company']['name_kh'] }}">
                        {{ @$contract_object['company']['short_name'] }}
                    </td>
                    <td style="cursor:help;" data-toggle="tooltip" data-placement="top" title="{{ @$contract_object['branch_department']['name_kh'] }}">
                        {{ @$contract_object['branch_department']['short_name'] }}
                    </td>
                    <td style="cursor:help;" data-toggle="tooltip" data-placement="top" title="{{ @$contract_object['position']['name_kh'] }}">
                        {{ @$contract_object['position']['short_name'] }}
                    </td>
                    <td>{{ date('d-M-Y', strtotime(@$value->start_date)) ?? 'N/A' }}</td>
                    <td>{{ date('d-M-Y', strtotime(@$value->end_date)) ?? 'N/A' }}</td>
                    <td>{{ @$contract_object['probation_end_date'] }}</td>
                    <td><label class="label label-info">{{ isset($value->contract_type) ?
                            @$array_flip[$value->contract_type] : '-' }}</label></td>
                </tr>
                <?php $i++ ?>
                @endforeach
                @endif
            </table>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(document).ready(function(e) {
        $("#clearBtn").on('click', function(e) {
            e.preventDefault();
            $("form#form-filter").find("input[type=text]").val("");
            $("form#form-filter").submit();
        });

        $('.select2').select2();

    });
</script>
@stop