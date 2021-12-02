@extends('adminlte::page')
@section('title', 'Edit contract')
@section('css')

@stop

@section("content")
@include('partials.breadcrumb')

{{--@include('contract.modals.contract_modal')--}}

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-pencil"></i> Edit contract</div>
            <div class="panel-body">
                <form action="{{ route('contract.update') }}" role="form" method="post" id="create_contract" enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">

                    {{ csrf_field() }}

                    <input type="hidden" name="contract_id" value="{{ $contract->id }}">
                    <input type="hidden" id="staff_personal_info_id" name="staff_personal_info_id" value="{{ $contract->staff_personal_info_id }}">
                    <input type="hidden" id="transfer_work_to_staff_id" name="transfer_work_to_staff_id">
                    <input type="hidden" id="get_work_form_staff_id" name="get_work_form_staff_id">

                    <div class="row">
                        <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('staff_id_card')) has-error @endif">
                            <label for="staff_id_card">Search Staff By ID Card <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="staff_id_card" name="staff_id_card" placeholder="{{ __('label.employee_id') }}" value="{{ $contract->staffPersonalInfo->staff_id }}" readonly>
                        </div>

                        <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('staff_name')) has-error @endif">
                            <label for="staff_name">Staff Full Name </label>
                            <input type="text" class="form-control" id="staff_name" name="staff_name" placeholder="{{ __('Staff full name') }}" value="{{ $contract->staffPersonalInfo->last_name_en.' '. $contract->staffPersonalInfo->first_name_en}}" readonly required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('contract_type')) has-error @endif">
                            <label for="contract_type">Select Contract Type <span class="text-danger">*</span></label>
                            <select class="form-control contract_type" name="contract_type" id="contract_type" disabled>
                                @foreach(CONTRACT_TYPE as $key => $value)
                                <option data-key="{{$key}}" value="{{$value}}" {{ ($value == $contract->contract_type) ? 'selected' : '' }}>
                                    {{ $key }}
                                </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="contract_type" value="{{$contract->contract_type}}" />
                        </div>
                    </div> <!-- /.row -->

                    <div class="row" id="block-fix-contract">
                        <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('contract_start_date')) has-error @endif">
                            <label for="contract_start_date">Contract Start Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control pull-right contract_start_date" id="contract_start_date" value="{{ date('d-M-Y', strtotime($contract->start_date)) }}" name="contract_start_date" placeholder="{{ __('label.contract_start_date') }}">
                        </div>
                        <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('contract_end_date')) has-error @endif">
                            <label for="contract_end_date">Contract End Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control pull-right contract_end_date" id="contract_end_date" value="{{ date('d-M-Y', strtotime($contract->end_date)) }}" required name="contract_end_date" placeholder="{{ __('label.contract_end_date') }}">
                        </div>
                    </div> <!-- /.row -->

                    <div class="row" id="block-company-profile">
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('company_code')) has-error @endif">
                            <label for="company_code">Company</label>
                            <select name="company_code" id="company_code" class="form-control company_code select2" required>
                                <option value=""> >>{{ __('label.company') }}<<</option>
                                        @foreach($companies as $key => $value)
                                        @if($contract->contract_object['company']['code'] == $value->company_code)
                                <option value="{{ $value->company_code }}" selected>{{ @$value->company_code.'-'.@$value->name_kh }}({{@$value->short_name}})</option>
                                @else
                                <option value="{{ $value->company_code }}">{{ @$value->company_code.'-'.@$value->name_kh }}({{@$value->short_name}})</option>
                                @endif
                                @endforeach
                            </select>
                            {{-- <company-selection></company-selection>--}}
                        </div>

                        <div class="form-group col-sm-12 col-md-6 col-lg-6">
                            <label for="branch_department_code">Department or Branch </label>
                            <select name="branch_department_code" id="branch_department_code" class="form-control branch_department_code select2" required>
                                <option value="">
                                    << {{__('label.department_or_branch') }}>>
                                </option>
                                @foreach($branchesDepartments as $key => $value)
                                @if($value->code == $contract->contract_object['branch_department']['code'])
                                <option value="{{ $value->code }}" selected>{{ $value->name_km }}</option>
                                @else
                                <option value="{{ $value->code }}">{{ $value->name_km }}</option>
                                @endif
                                @endforeach
                            </select>
                            {{-- <branch-department-selection></branch-department-selection>--}}
                        </div>

                        <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('position_code')) has-error @endif">
                            <label for="position_code">Position</label>
                            <select name="position_code" id="position_code" class="form-control position_code select2" required>
                                <option value="">>> {{ __('label.position') }}
                                    <<< /option>
                                        @foreach($positions as $key => $value)
                                        @if($contract->contract_object['position']['code'] == $value->code)
                                <option value="{{ $value->code }}" selected>{{ $value->name_kh }}</option>
                                @else
                                <option value="{{ $value->code }}">{{ $value->name_kh }}</option>
                                @endif
                                @endforeach
                            </select>
                            {{-- <position-selection></position-selection>--}}
                        </div>
                    </div>

                    <div class="row" id="block-probation">
                        <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('salary')) has-error @endif">
                            <label for="salary">Salary <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="salary" name="salary" placeholder="{{ __('label.base_salary') }}" value="{{ @$contract->contract_object['salary'] }}">
                        </div>

                        <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('currency')) has-error @endif" id="container-currency">
                            <label for="currency">Currency <span class="text-danger">*</span></label>
                            <select name="currency" id="currency" class="form-control">
                                <option value="">>> {{ __('label.currency') }}
                                    <<< /option>
                                        @foreach($currency as $key => $value)
                                        @if(@$contract->contract_object['currency'] == $key)
                                <option value="{{ $key }}" selected>{{ $value }}</option>
                                @else
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('probation_end_date')) has-error @endif" id="container_probation_end_date">
                            <label for="probation_end_date">Probation End Date</label>
                            <input type="text" class="form-control pull-right probation_end_date" id="probation_end_date" readonly value="{{ (@$contract->contract_object['probation_end_date'] !== NULL) ?
                                    date('d-M-yy', strtotime(@$contract->contract_object['probation_end_date'])) : '' }}" name="probation_end_date" placeholder="{{ __('label.probation_end_date') }}">
                        </div>

                        <div class="form-group col-sm-12 col-md-6 col-lg-6" id="container_tax_responsiblity">
                            <label for="pay_tax_status">Tax responsibility </label>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="pay_tax_status" class="form-check-input" id="pay_tax_status" @if(@$contract->contract_object['pay_tax_status'] == true) checked @endif
                                    > Pay Tax By Company
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="container_transfer_to_staff">
                        <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('transfer_to_staff')) has-error @endif">
                            <label for="transfer_to_staff">Transfer Work To</label>
                            <input type="text" class="form-control" name="transfer_to_staff" id="transfer_to_staff" placeholder="{{ __('label.transfer_work_to').' ('.__('label.employee_id').')' }}" value="{{ old('transfer_to_staff') }}">
                        </div>

                        <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('transfer_to_staff')) has-error @endif">
                            <label for="transfer_to_staff_name">Transfer Work To Name</label>
                            <input type="text" class="form-control" name="transfer_to_staff_name" id="transfer_to_staff_name" placeholder="{{ __('label.transfer_work_to') }}" value="{{ old('transfer_to_staff') }}" readonly>
                        </div>
                    </div>

                    <div class="row" id="container_get_work_form_staff">
                        <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('get_work_form_staff')) has-error @endif">
                            <label for="get_work_form_staff">Get Work From</label>
                            <input type="text" class="form-control" name="get_work_form_staff" id="get_work_form_staff" placeholder="{{ __('label.get_work_from').' ('.__('label.employee_id').')' }}" value="{{ old('get_work_form_staff') }}">
                        </div>
                        <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('get_work_form_staff_full_name')) has-error @endif">
                            <label for="get_work_form_staff_full_name">Get Work From Name</label>
                            <input type="text" class="form-control" placeholder="{{ __('label.get_work_from') }}" name="get_work_form_staff_full_name" id="get_work_form_staff_full_name" value="{{ old('get_work_form_staff_full_name') }}" readonly>
                        </div>
                    </div>

                    <div class="row" id="container_file_reference">
                        <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('file_reference')) has-error @endif">
                            <label for="file_reference">Reference File</label>
                            <input type="file" class="form-control" name="file_reference" id="file_reference">
                        </div>
                    </div>

                    <div class="row" id="container_reason">
                        <div class="form-group col-sm-12 col-md-12 @if($errors->has('reason')) has-error @endif">
                            <label for="reason">Reason</label>
                            <textarea name="reason" id="reason" cols="30" rows="6" class="form-control" placeholder="{{ __('label.reason') }}">{{ @$contract->contract_object['reason'] }}</textarea>
                        </div>
                    </div>

                    <!-- /.row -->

                    <div class="row margin-bottom">
                        <div class="col-sm-12 col-md-12">
                            <button type="submit" class="btn btn-success btn-sm margin-r-5" id="btnSave"><i class="fa fa-save"></i> UPDATE
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" id="btnDiscard" data-dismiss="modal"><i class="fa fa-remove"></i> DISCARD
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        function show_dynamic_form() {
            const selectedType = $("#contract_type").find(":selected").data("key");
            console.log("contract_type: " + selectedType);

            if (selectedType === 'PROBATION') {
                showProbation();
            } else if (selectedType === 'FDC' || selectedType === 'UDC') {
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
                $("#container_transfer_to_staff").show();
                $("#container_get_work_form_staff").show();

                $("#container_file_reference").show();
                $("#container_reason").show();

                $("#block-probation").show();
                $("#container-salary").show();
                $("#container-currency").show();
                
            } else if (selectedType === 'PROMOTE' || selectedType === 'DEMOTE') {
                showProbation();
                $("#container_probation_end_date").hide();

                $("#container_transfer_to_staff").show();
            }
        }

        show_dynamic_form();

        $("#contract_type").on("change", function(e) {
            e.preventDefault();

            show_dynamic_form();
        });

        function showProbation() {
            $("#block-probation").show();
            $("#container_probation_end_date").show();

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

        $("#staff_id_card").on('change', function(e) {
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
                            $("#company_code").val(companyCode).change();
                            $("#branch_department_code").val(branchDepartmentCode).change();
                            $("#position_code").val(positionCode).change();
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

        $("#transfer_to_staff").on('keyup', function(e) {
            const idCard = $(this).val();
            if (!idCard) {
                $("#transfer_to_staff_name").val("");
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
                        $("#transfer_to_staff_name").val(fullName);
                        $("#transfer_work_to_staff_id").val(obj.id)
                    } else {
                        $("#transfer_to_staff_name").val("");
                        $("#transfer_work_to_staff_id").val("")
                    }
                },
                fail: function fail(err) {
                    console.log(err.message);
                    $("#transfer_work_to_staff_id").val("");
                    $("#transfer_to_staff_name").val("");
                }
            });
        });

        $("#get_work_form_staff").on('keyup', function(e) {
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
            beforeShow: function(input, inst) {
                $(document).off('focusin.bs.modal');
            },
            onClose: function() {
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
            beforeShow: function(input, inst) {
                $(document).off('focusin.bs.modal');
            },
            onClose: function() {
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

        $(".select2, #company_code, #branch_department_code, #position_code").select2();

    });
</script>
@stop