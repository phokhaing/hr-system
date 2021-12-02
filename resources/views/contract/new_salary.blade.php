@extends('adminlte::page')
@section('title', 'Contract')
@section('css')

@stop

@section("content")
    @include('partials.breadcrumb')

    @include('contract.modals.contract_modal')

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="breadcrumb">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal_new_salary"><i
                            class="fa fa-plus"></i> New Salary
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Salary List</h4>
                </div>

                <div class="panel-body">
                    <div style="overflow: auto">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Action</th>
                                <th>Old Salary</th>
                                <th>New Salary</th>
                                <th>Notice</th>
                                <th>Effective Date</th>
                                <th>Created Date</th>

                            </tr>
                            </thead>
                            <tbody>
                            @if(@$newSalaries && count($newSalaries))
                                @php($no = 0)
                                @foreach($newSalaries as $key => $value)
                                    <?php
                                    $no++;
                                    $effectiveDate = date('d-M-Y', strtotime(@$value->effective_date));
                                    ?>
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>
                                            @if($no == 1)
                                                <a href="javascript:void(0)"
                                                   onclick="onEditClick('{{@$value->id}}', '{{@$value->object->new_salary}}', '{{@$effectiveDate}}', '{{@$value->object->notice}}')"
                                                   class="btn btn-xs btn-primary margin-r-5" title="Edit">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a href="javascript:void(0)" class="btn btn-xs btn-danger margin-r-5"
                                                   title="Delete"
                                                   onclick="onDeleteContract({{@$value->id}})">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endif
                                        </td>
                                        <td>{{ number_format(convertSalaryFromStrToFloatValue(@$value->object->old_salary), 2)}}</td>
                                        <td>{{ number_format(@$value->object->new_salary)}}</td>
                                        <td>{{ @$value->object->notice}}</td>
                                        <td>{{ date('d-m-Y', strtotime(@$value->effective_date)) }}</td>
                                        <td>{{ date('d-m-Y', strtotime(@$value->created_at)) }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7">Empty!</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('contract.modals.new_salary')
    @include('contract.modals.update_new_salary')
@stop

@section('js')
    <script>

        function onEditClick(id, amount, effectiveDate, notice) {
            console.log(amount);
            $("#update_new_salary_id").val(id);
            $("#update_amount").val(amount);
            $("#update_notice").val(notice);
            $("#update_effective_date").datepicker('setDate', new Date(effectiveDate));
            $("#modal_update_new_salary").modal('show');
        }

        function onDeleteContract(id) {
            console.log("onDeleteContract: " + id);
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to delete this salary ?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete !'
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: APP_URL + "/contract/new-salary/delete",
                        dataType: "JSON",
                        method: "POST",
                        data: {
                            id: id,
                        },
                        success: function success(data) {
                            console.log(data);
                            if(data.status){
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

        $(document).ready(function () {
            $(".effective_date").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-M-yy'
            });
        });
    </script>
@stop