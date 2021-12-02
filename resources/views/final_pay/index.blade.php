@extends('adminlte::page')
@section('title', 'Final Pay')
@section('content')

    @include('partials.breadcrumb')
    @include('pensionfund::layouts.layout_flash_error', ['message' => @$message])

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="breadcrumb">
                <a href="{{ route('final_pay.create') }}" class="btn btn-success"><i
                            class="fa fa-plus"></i> Checking Final Pay
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-filter"></i> Filter</div>
                <div class="panel-body">
                    <form action="{{ route('final_pay.index') }}" role="form" method="get" id="form-filter">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group col-sm-6 col-md-6">
                                <input type="text" name="keyword" id="keyword" class="form-control"
                                       placeholder="ស្វែងរកតាមរយៈ៖ Staff ID, Name, Phone  "
                                       value="{{ request('keyword') }}">
                            </div>

                            <div class="form-group col-sm-6 col-md-6">
                                <button type="submit" class="btn btn-primary margin-r-5"><i class="fa fa-search"></i>
                                    Search
                                </button>
                                <button type="reset" class="btn btn-danger margin-r-5" id="clearBtn"><i
                                            class="fa fa-remove"></i> Clear
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
                        <th>Staff ID</th>
                        <th>Company <br> ID Card</th>
                        <th>Full Name</th>
                        <th>Gender</th>
                        <th>Company</th>
                        <th>Department <br>/ Branch</th>
                        <th>Position</th>
                        <th>Net Pay</th>
                        <th>Status</th>
                        <th>Checking Date</th>
                        <th>Posted Date</th>
                        <th>Action</th>
                    </tr>
                    <?php $i = 1; ?>
                    @foreach($finalPayList as $key => $value)
                        <?php
                        $staffPersonalInfo = @$value->staffPersonalInfo;
                        $contract_object = @$value->contract->contract_object;
                        $isInChecking = !@$value->json_data->is_posted || @$value->json_data->is_posted == FINAL_PAY_STATUS['CHECKING'];
                        ?>
                        <tr id="tr-contract-{{@$value->id}}">
                            <td>{{ $i }}</td>
                            <td>{{ @$staffPersonalInfo->staff_id }}</td>
                            <td>{{ substr(@$value->contract->staff_id_card, 3, (strlen(@$value->contract->staff_id_card))) }}</td>
                            <td>{{ @$staffPersonalInfo->last_name_en . ' ' . @$staffPersonalInfo->first_name_en }}</td>
                            <td>{{ GENDER[@$staffPersonalInfo->gender] }}</td>
                            <td style="cursor:help;" data-toggle="tooltip" data-placement="top"
                                title="{{ @$contract_object['company']['name_en'] }}">
                                {{ @$contract_object['company']['short_name'] }}
                            </td>
                            <td style="cursor:help;" data-toggle="tooltip" data-placement="top"
                                title="{{ @$contract_object['branch_department']['name_en'] }}">
                                {{ @$contract_object['branch_department']['short_name'] }}
                            </td>
                            <td style="cursor:help;" data-toggle="tooltip" data-placement="top"
                                title="{{ @$contract_object['position']['name_en'] }}">
                                {{ @$contract_object['position']['short_name'] }}
                            </td>
                            <td>{{ number_format(@$value->json_data->net_pay) }}</td>
                            <td>
                                @if(!@$value->json_data->is_posted || @$value->json_data->is_posted == FINAL_PAY_STATUS['CHECKING'])
                                    <span class="badge bg-blue">CHECKING</span>
                                @else
                                    <span class="badge bg-red">POSTED</span>
                                @endif
                            </td>
                            <td>{{ date('d-M-Y', strtotime(@$value->created_at)) }}</td>

                            <td>{{ @$value->json_data->posted_date ? date('d-M-Y', strtotime(@$value->json_data->posted_date)) : 'N/A' }}</td>

                            <td>
                                @if($isInChecking)
                                    <a href="javascript:void(0)"
                                       onclick="onPostClick({{@$value->id}}, {{@$value->contract_id}})"
                                       class="btn btn-xs btn-primary margin-r-5" title="Confirm Post Now">
                                        <i class="fa fa-check"></i>
                                    </a>
                                @endif

                                <a href="{{ route('final_pay.show', ['id' => @$value->id]) }}"
                                   class="btn btn-xs btn-primary margin-r-5" title="View">
                                    <i class="fa fa-eye-slash"></i>
                                </a>

                                @if($isInChecking)
                                    <a href="{{ route('final_pay.edit', ['id' => @$value->id]) }}"
                                       class="btn btn-xs btn-primary margin-r-5" title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="btn btn-xs btn-danger margin-r-5"
                                       onclick="onDeleteFinalPay({{@$value->id}})"
                                       title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                @endif

                                <a href="{{ route('final_pay.export_excel', @$value->id) }}"
                                   class="btn btn-xs btn-primary margin-r-5" title="Export as Excel">
                                    <i class="fa fa-file-excel-o"></i>
                                </a>

                            </td>
                        </tr>
                        <?php $i++ ?>

                    @endforeach
                </table>
                @if(! $finalPayList->isEmpty())
                    <div>
                        Total Record: {{ $finalPayList->total() }}
                    </div>
                    {{ $finalPayList->appends(request()->query())->links() }}
                @endif
            </div>

        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function (e) {
            $("#clearBtn").on('click', function (e) {
                e.preventDefault();
                $("form#form-filter").find("input[type=text]").val("");
                $("form#form-filter").submit();
            });
        });

        function onPostClick(id, contractId) {
            console.log("onPostClick: " + id + ", " + contractId);
            Swal.fire({
                title: 'Are you sure want to post this Final Pay?',
                text: "Once posted, this staff's contract is going to be an inactive!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Post !'
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: APP_URL + "/final-pay/post",
                        dataType: "JSON",
                        method: "POST",
                        data: {
                            id: id,
                            contract_id: contractId
                        },
                        success: function success(data) {
                            console.log(data);
                            if (data.status) {
                                window.location.reload();
                            }
                        },
                        fail: function fail(err) {
                            console.log(err);
                        }
                    });
                }
            });
        }

        function onDeleteFinalPay(id) {
            console.log("onDeleteContract: " + id);
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to delete this Checking Final Pay?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete !'
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: APP_URL + "/final-pay/delete",
                        dataType: "JSON",
                        method: "POST",
                        data: {
                            id: id,
                        },
                        success: function success(data) {
                            console.log(data);
                            if (data.status) {
                                window.location.reload();
                            }
                        },
                        fail: function fail(err) {
                            console.log(err);
                        }
                    });
                }
            });
        }
    </script>
@stop