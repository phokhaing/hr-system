@extends('adminlte::page')
@section('title', 'Last Day/Block Salary Info')

@section('css')
@stop

@php
    $contract_object = @$contract->contract_object;
    $isBlockedSalary = @$contract_object['block_salary']['is_block'] ?: 0;
    $notice = @$contract_object['block_salary']['notice'];
    $staffPersonalInfoId = @$contract_object['block_salary']['staff_personal_info_id'];
    $transferWorkToStaffId = @$contract_object['block_salary']['transfer_to_staff'];
    $transferWorkToName = @$contract_object['block_salary']['transfer_to_staff_name'];
    $fileReference = @$contract_object['block_salary']['file_reference'];
    $contractType = @$contract_object['block_salary']['contract_type'];

    $fromDate = @$contract_object['block_salary']['from_date'];
    if($fromDate){
        $fromDate = date('d-M-Y', strtotime($fromDate));
    }else{
        $fromDate = date('d-M-Y');
    }

    $untilDate = @$contract_object['block_salary']['until_date'];
    if($untilDate){
        $untilDate = date('d-M-Y', strtotime($untilDate));
    }else{
        $untilDate = date('d-M-Y');
    }
@endphp

@section("content")
    @include('partials.breadcrumb')

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-lock"></i> Last Day/Block Salary Info</div>
                <div class="panel-body">
                    <form class="form-verticle" method="post" action="{{ route('block_salary.block_or_unBlock') }}"
                          enctype="multipart/form-data"
                          id="form_block_salary">
                        {{ csrf_field() }}

                        <input type="hidden" name="contract_id" id="contract_id"
                               value="{{ @$contract->id }}">
                        <input type="hidden" id="staff_personal_info_id" name="staff_personal_info_id"
                               value="{{ @$staffPersonalInfoId }}">

                        <div class="row">
                            <div class="form-group col-md-12 col-lg-12">
                                <label class="control-label">Choose Block Or Un-Block:</label>
                                <select class="form-control" name="block_or_unblock" id="block_or_unblock">
                                    <option value="0" {{ $isBlockedSalary == 0 ? 'selected' : '' }}>Un-Block</option>
                                    <option value="1" {{ $isBlockedSalary == 1 ? 'selected' : '' }}>Block</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                <label for="contract_type">Select Contract Type <span
                                            class="text-danger">*</span></label>
                                <select class="form-control contract_type" name="contract_type"
                                        id="block_contract_type">
                                    @foreach(CONTRACT_END_TYPE as $key => $value)
                                        <option data-key="{{$key}}"
                                                value="{{$value}}" {{ $value==$contractType ? 'selected' : '' }}>{{ $key }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 col-lg-6">
                                <label class="control-label">Start Date:</label>
                                <input type="text" class="form-control" id="block_from_date" readonly required
                                       value="{{ $fromDate }}"
                                       name="block_from_date" placeholder="dd-mm-yyyy">
                            </div>
                            <div class="form-group col-md-6 co-lg-6">
                                <label class="control-label">End Date:</label>
                                <input type="text" class="form-control" id="block_until_date" readonly required
                                       value="{{ $untilDate }}"
                                       name="block_until_date" placeholder="dd-mm-yyyy">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 col-lg-6">
                                <label for="transfer_to_staff">Transfer Work To</label>
                                <input type="text" class="form-control" name="transfer_to_staff"
                                       id="block_transfer_to_staff"
                                       placeholder="{{ __('label.transfer_work_to').' ('.__('label.employee_id').')' }}"
                                       value="{{ @$transferWorkToStaffId }}">
                            </div>
                            <div class="form-group col-md-6 col-lg-6">
                                <label for="transfer_to_staff_name">Transfer Work To Name</label>
                                <input type="text" class="form-control" name="transfer_to_staff_name"
                                       id="block_transfer_to_staff_name"
                                       placeholder="{{ __('label.transfer_work_to') }}"
                                       value="{{ @$transferWorkToName }}"
                                       readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="file_reference">Reference File</label>
                            <input type="file" class="form-control" name="file_reference" id="block_file_reference">
                            @if(@$fileReference)
                                <p>File: {{@$fileReference}}</p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="control-label">Notice:</label>
                            <textarea rows="3" class="form-control" name="block_salary_notice" id="block_salary_notice">{{ @$notice }}</textarea>
                        </div>

                        <div class="form-group margin-bottom">
                            <button type="submit" class="btn btn-success btn-sm margin-r-5" id="btnSave"><i
                                        class="fa fa-save"></i> SAVE
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" id="btnDiscard" data-dismiss="modal">
                                <i class="fa fa-remove"></i> DISCARD
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function () {

            checkIsBlockOrUnBlockSelect(`<?=@$isBlockedSalary?>`);

            $("#block_or_unblock").change(function () {
                let blockOrUnBlock = $(this).find(":selected").val();
                console.log('blockOrUnBlock change' + blockOrUnBlock);
                checkIsBlockOrUnBlockSelect(blockOrUnBlock);
            });

            function checkIsBlockOrUnBlockSelect(blockOrUnBlock) {
                if (blockOrUnBlock == 1) {
                    $("#block_from_date").attr('disabled', false);
                    $("#block_until_date").attr('disabled', false);
                    $("#block_salary_notice").attr('disabled', false);
                    $("#block_transfer_to_staff").attr('disabled', false);
                    $("#block_transfer_to_staff_name").attr('disabled', false);
                    $("#block_file_reference").attr('disabled', false);
                    $("#block_contract_type").attr('disabled', false);
                } else {
                    $("#block_from_date").attr('disabled', true);
                    $("#block_until_date").attr('disabled', true);
                    $("#block_salary_notice").attr('disabled', true);
                    $("#block_transfer_to_staff").attr('disabled', true);
                    $("#block_transfer_to_staff_name").attr('disabled', true);
                    $("#block_file_reference").attr('disabled', true);
                    $("#block_contract_type").attr('disabled', true);
                }
            }

            $("#block_from_date").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-M-yy'
            });

            $("#block_until_date").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-M-yy'
            });

            /** Transfer work to **/
            $("#block_transfer_to_staff").on('keyup', function (e) {
                const staffId = $(this).val();
                if (!staffId) {
                    $("#block_transfer_to_staff_name").val("");
                    return;
                }
                const contractType = $("#block_or_unblock").find(":selected").val();
                findTransferWorkToByStaffId(staffId,
                    contractType,
                    function (data) {
                        if (data.status) {
                            const obj = data.staff;
                            const fullName = obj.first_name_en + " " + obj.last_name_en;
                            $("#block_transfer_to_staff_name").val(fullName);
                            $("#block_transfer_work_to_id").val(obj.id)
                        } else {
                            $("#block_transfer_to_staff_name").val("");
                            $("#block_transfer_work_to_id").val("")
                        }
                    },
                    function (err) {
                        $("#block_transfer_work_to_id").val("");
                        $("#block_transfer_to_staff_name").val("");
                    });
            });

            //Call API find transfer work to
            function findTransferWorkToByStaffId(staffId, contractType, onSuccess, onFail) {
                $.ajax({
                    url: APP_URL + "/contract/find",
                    dataType: "JSON",
                    method: "POST",
                    data: {
                        staff_id_card: staffId,
                        contract_type: contractType
                    },
                    success: function success(data) {
                        console.log(data);
                        onSuccess(data);
                    },
                    fail: function fail(err) {
                        console.log(err.message);
                        onFail(err)
                    }
                });
            }

            /** End transfer work to **/
        });
    </script>
@stop