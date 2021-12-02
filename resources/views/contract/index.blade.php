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

    @include('contract.modals.contract_modal')

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="breadcrumb">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#contract_modal"><i
                            class="fa fa-plus"></i> CREATE
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-filter"></i> Filter</div>
                <div class="panel-body">
                    <form action="{{ route('contract.index') }}" role="form" method="get" id="form-filter">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group col-sm-6 col-md-6">
                                <input type="text" name="key_word" id="key_word" class="form-control"
                                       placeholder="ស្វែងរកតាមរយៈ៖ Staff ID, Name, Phone  "
                                       value="{{ request('key_word') }}">
                            </div>

                            <div class="form-group col-sm-6 col-md-6">
                                <button type="submit" class="btn btn-primary margin-r-5"><i class="fa fa-search"></i>
                                    Search
                                </button>
                                @can('view_contracts_advance_search')
                                    <button type="button" class="btn btn-primary margin-r-5" data-toggle="modal"
                                            data-target="#contract_advance_filter"><i class="fa fa-filter"></i> Advabce
                                        Search
                                    </button>
                                @endcan
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
                        <th>Full Name KH</th>
                        <th>Full Name EN</th>
                        <th>Company</th>
                        <th>Department <br>/ Branch</th>
                        <th>Position</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Probation <br>End Date</th>
                        <th>Contract <br> Type</th>
                        <th>Actions</th>
                    </tr>
                    <?php $i = 1; ?>
                    @foreach($contracts as $key => $value)
                        <?php
                        $contract_object = json_decode(@$value->contract_object);
                        $array_flip = array_flip(CONTRACT_TYPE); // Reverse array value to key
                        ?>
                        <tr id="tr-contract-{{@$value->id}}">
                            <td>{{ $i }}</td>
                            <td>{{ @$value->staff_id }}</td>
                            <td>{{ substr(@$value->staff_id_card, 3, (strlen(@$value->staff_id_card))) }}</td>
                            <td>{{ @$value->full_name_kh }}</td>
                            <td>{{ @$value->full_name_en }}</td>
                            <td style="cursor:help;" data-toggle="tooltip" data-placement="top"
                                title="{{ @$contract_object->company->name_kh }}">
                                {{ @$contract_object->company->short_name }}
                            </td>
                            <td style="cursor:help;" data-toggle="tooltip" data-placement="top"
                                title="{{ @$contract_object->branch_department->name_kh }}">
                                {{ @$contract_object->branch_department->short_name }}
                            </td>
                            <td style="cursor:help;" data-toggle="tooltip" data-placement="top"
                                title="{{ @$contract_object->position->name_kh }}">
                                {{ @$contract_object->position->short_name }}
                            </td>
                            <td>{{ date('d-M-Y', strtotime(@$value->start_date)) ?? 'N/A' }}</td>
                            <td>{{ date('d-M-Y', strtotime(@$value->end_date)) ?? 'N/A' }}</td>
                            <td>{{ @$contract_object->probation_end_date }}</td>
                            <td><label class="label label-info">{{ isset($value->contract_type) ?
                            @$array_flip[$value->contract_type] : '-' }}</label></td>
                            <td>
                                <a href="javascript:void(0)" class="btn btn-xs btn-danger margin-r-5"
                                   onclick="onDeleteContract({{@$value->id}})">
                                    <i class="fa fa-trash"></i>
                                </a>
                                <a href="{{ route('contract.edit',encrypt(@$value->id)) }}"
                                   class="btn btn-xs btn-primary margin-r-5" title="Edit contract information">
                                    <i class="fa fa-pencil"></i>
                                </a>

                                @if(isLastContract($value))

                                    @can('block_contract_for_final_pay')
                                        <a href="{{ route('block_salary.block_last_day', @$value->id) }}"
                                           class="btn btn-xs btn-primary margin-r-5"
                                           title="Last Day/Block Salary Info">
                                            <i class="fa fa-lock"></i>
                                        </a>
                                    @endcan

                                    @can('new_salary')
                                        <a href="{{ route('contract.new_salary', ['id' => @$value->id]) }}"
                                           class="btn btn-xs btn-primary margin-r-5" title="New salary">
                                            <i class="fa fa-usd" aria-hidden="true"></i>
                                        </a>
                                    @endcan

                                @endif
                            </td>
                        </tr>
                        <?php $i++ ?>
                    @endforeach
                </table>
                @if(! $contracts->isEmpty())
                    <div>
                        Total Record: {{ $contracts->total() }}
                    </div>
                    {{ $contracts->appends(request()->query())->links() }}
                @endif
            </div>
        </div>
    </div>

    @include('contract.modals.advance_filter')
@stop

@section('js')
    <script>

        function onDeleteContract(id) {
            console.log("onDeleteContract: " + id);
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to delete this contract ?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete !'
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: APP_URL + "/contract/delete",
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

        $(document).ready(function (e) {
            const isNewContract = '<?php echo @$isNewContract; ?>';
            const newStaffPersonalInfo = '<?php echo @$newStaffPersonalInfo; ?>';
            console.log("isNewContract:" + isNewContract + "newStaffPersonalInfo: " + newStaffPersonalInfo);
            if (isNewContract === '1' && newStaffPersonalInfo) {
                $("#contract_modal").modal("show").on('change', function () {
                    $(this).blur()
                });
                const newStaffObj = jQuery.parseJSON(newStaffPersonalInfo);
                const fullName = newStaffObj.first_name_en + " " + newStaffObj.last_name_en;

                $("#staff_id_card").val(newStaffObj.staff_id);
                $("#staff_name").val(fullName);
                $("#staff_personal_info_id").val(newStaffObj.id);
            }

            showProbation();

            $("#contract_type").on('change', function (e) {
                const selectedType = $(this).find(":selected").data("key");
                console.log("contract_type: " + selectedType);

                if (selectedType === 'FDC') {
                    showProbation();
                } else if (selectedType === 'UDC') {
                    showRegular();
                } else if (selectedType === 'RESIGN' ||
                    selectedType === 'DEATH' ||
                    selectedType === 'TERMINATE' ||
                    selectedType === 'LAY_OFF') {
                    $("#block-probation").hide();
                    $("#container_get_work_form_staff").hide();

                    $("#container_file_reference").show();
                    $("#container_reason").show();
                    $("#container_transfer_to_staff").show();

                } else if (selectedType === 'CHANGE_LOCATION') {
                    $("#container_probation_end_date").hide();

                    $("#container_tax_responsiblity").show();
                    $("#container_file_reference").show();
                    $("#container_reason").show();

                    $("#block-probation").show();
                    $("#container-salary").show();
                    $("#container-currency").show();

                    $("#container_transfer_to_staff").show();
                    $("#container_get_work_form_staff").show();

                } else if (selectedType === 'PROMOTE' || selectedType === 'DEMOTE') {
                    showProbation();
                    $("#container_probation_end_date").hide();

                    $("#container_transfer_to_staff").show();
                }

                if (selectedType === 'RESIGN' ||
                    selectedType === 'DEATH' ||
                    selectedType === 'TERMINATE' ||
                    selectedType === 'LAY_OFF'
                ) {
                    $("#contract_start_date").datepicker().datepicker("setDate", new Date());
                    $("#contract_end_date").datepicker().datepicker("setDate", new Date());
                } else {
                    $("#contract_start_date").val("");
                    $("#contract_end_date").val("");
                }
            });

            function showProbation() {
                $("#block-probation").show();
                $("#container_probation_end_date").show();
                $("#container_tax_responsiblity").show();

                $("#container_transfer_to_staff").hide();
                $("#container_get_work_form_staff").hide();

                $("#container_file_reference").hide();
                $("#container_reason").hide();
            }

            function showRegular() {
                showProbation();
                $("#container_probation_end_date").hide();
            }

            /**
             * Set up token
             * ===============================
             */
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#staff_id_card").on('change', function (e) {
                const idCard = $(this).val();
                if (!idCard) {
                    $("#staff_name").val("");
                    return;
                }
                const contractType = $("#contract_type").find(":selected").val();
                $.ajax({
                    url: APP_URL + "/contract/find",
                    dataType: "JSON",
                    method: "POST",
                    data: {
                        staff_id_card: idCard,
                        contract_type: contractType
                    },
                    success: function success(data) {
                        console.log(data);
                        if (data.status) {
                            const obj = data.staff;
                            const fullName = obj.first_name_en + " " + obj.last_name_en;
                            $("#staff_name").val(fullName);
                            $("#staff_personal_info_id").val(obj.id);

                            const contract = obj.contract;
                            console.log("contract: " + contract);
                            if (contract) {
                                const companyCode = contract.company_profile.substring(0, 3);
                                const branchDepartmentCode = contract.company_profile.substring(3, 6);
                                const positionCode = contract.company_profile.substring(6, 9);
                                console.log("companyCodeSubString: " + companyCode + ", branch_department_code: " + branchDepartmentCode + ", positionCode: " + positionCode);

                                // Fire actiion on vuex
                                let x = app.$store.dispatch("setBranchDepartment", companyCode);
                                let z = app.$store.dispatch("setPosition", companyCode);

                                setTimeout(function () {
                                    $("#company_code").val(companyCode);
                                    $("#branch_department_code").val(branchDepartmentCode);
                                    $("#position_code").val(positionCode);
                                    $('#branch_department_code').trigger('change'); // show select2
                                    $('#position_code').trigger('change'); // show select2
                                }, 3000);

                            } else {
                                $("#company_code").prop("selectedIndex", 0).change();
                                $("#branch_department_code").prop("selectedIndex", 0).change();
                                $("#position_code").prop("selectedIndex", 0).change();
                            }
                        } else {
                            $("#staff_name").val("");
                            $("#staff_personal_info_id").val("");
                            $("#company_code").prop("selectedIndex", 0).change();
                            $("#branch_department_code").prop("selectedIndex", 0).change();
                            $("#position_code").prop("selectedIndex", 0).change();
                        }
                        if (data.staff == null) {
                            Swal.fire({
                                title: "រកមិនឃើញមាននៅក្នុងប្រព័ន្ធ",
                                text: "សូមធ្វើការត្រួតពិនិត្យលេខឡើងសម្គាល់ឡើងវិញ!",
                                type: 'info',
                                showCancelButton: false,
                            })
                        }
                    },
                    fail: function fail(err) {
                        console.log(err.message);
                        $("#staff_name").val("");
                        $("#staff_personal_info_id").val("");
                        $("#company_code").prop("selectedIndex", 0).change();
                        $("#branch_department_code").prop("selectedIndex", 0).change();
                        $("#position_code").prop("selectedIndex", 0).change();
                    }
                });
            });

            /** Transfer work to **/
            $("#transfer_to_staff").on('keyup', function (e) {
                const idCard = $(this).val();
                if (!idCard) {
                    $("#transfer_to_staff_name").val("");
                    return;
                }
                const contractType = $("#contract_type").find(":selected").val();
                findTransferWorkToByStaffId(idCard,
                    contractType,
                    function (data) {
                        if (data.status) {
                            const obj = data.staff;
                            const fullName = obj.first_name_en + " " + obj.last_name_en;
                            $("#transfer_to_staff_name").val(fullName);
                            $("#transfer_work_to_staff_id").val(obj.id)
                        } else {
                            $("#transfer_to_staff_name").val("");
                            $("#transfer_work_to_staff_id").val("")
                        }
                    },
                    function (err) {
                        $("#transfer_work_to_staff_id").val("");
                        $("#transfer_to_staff_name").val("");
                    });
            });

            //Call API find transfwer work to
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

            $("#get_work_form_staff").on('keyup', function (e) {
                const idCard = $(this).val();
                if (!idCard) {
                    $("#get_work_form_staff_full_name").val("");
                    return;
                }
                const contractType = $("#contract_type").find(":selected").val();
                $.ajax({
                    url: APP_URL + "/contract/find",
                    dataType: "JSON",
                    method: "POST",
                    data: {
                        staff_id_card: idCard,
                        contract_type: contractType
                    },
                    success: function success(data) {
                        console.log(data);
                        if (data.status) {
                            const obj = data.staff;
                            const fullName = obj.first_name_en + " " + obj.last_name_en;
                            $("#get_work_form_staff_full_name").val(fullName);
                            $("#get_work_form_staff_id").val(obj.id)
                        } else {
                            $("#get_work_form_staff_full_name").val("");
                            $("#get_work_form_staff_id").val("")
                        }
                    },
                    fail: function fail(err) {
                        console.log(err.message);
                        $("#get_work_form_staff_id").val("");
                        $("#get_work_form_staff_full_name").val("");
                    }
                });
            });

            $("#contract_start_date").datepicker({
                beforeShow: function (input, inst) {
                    $(document).off('focusin.bs.modal');
                },
                onClose: function () {
                    $(document).on('focusin.bs.modal');
                },
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-M-yy',
                minDate: '-10y',
                onSelect: function onSelect(selected) {
                    var dt = new Date(selected);
                    dt.setDate(dt.getDate());
                    $(".contract_end_date").datepicker("option", "minDate", dt).datepicker("setDate", dt);
                }
            });

            $("#contract_end_date").datepicker({
                beforeShow: function (input, inst) {
                    $(document).off('focusin.bs.modal');
                },
                onClose: function () {
                    $(document).on('focusin.bs.modal');
                },
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-M-yy',
                minDate: '-10y'
            });

            $("#probation_end_date").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-M-yy'
            });

            $("#clearBtn").on('click', function (e) {
                e.preventDefault();
                $("form#form-filter").find("input[type=text]").val("");
                $("form#form-filter").submit();
            });

            $('.select2').select2();
        });
    </script>
@stop