<div class="row">
    <div class="col-sm-12 col-md-12">
        <form action="{{ route('contract.storeContractByType') }}" role="form" method="post" id="create_contract"
              enctype="multipart/form-data" onkeydown="return event.key != 'Enter';">

            {{ csrf_field() }}

            <input type="hidden" id="staff_personal_info_id" name="staff_personal_info_id">
            <input type="hidden" id="transfer_work_to_staff_id" name="transfer_work_to_staff_id">
            <input type="hidden" id="get_work_form_staff_id" name="get_work_form_staff_id">

            <div class="row">
                <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('staff_id_card')) has-error @endif">
                    <label for="staff_id_card">Search Staff By ID Card <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="staff_id_card" name="staff_id_card"
                           placeholder="{{ __('label.employee_id') }}" value="{{ old('staff_id_card') }}" required>
                </div>

                <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('staff_name')) has-error @endif">
                    <label for="staff_name">Staff Full Name </label>
                    <input type="text" class="form-control" id="staff_name" name="staff_name"
                           placeholder="{{ __('Staff full name') }}" value="{{ old('staff_name') }}" readonly required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('contract_type')) has-error @endif">
                    <label for="contract_type">Select Contract Type <span class="text-danger">*</span></label>
                    <select class="form-control contract_type" name="contract_type" id="contract_type">
                        <option value=""> << Select Contract Type >> </option>
                        @foreach(CONTRACT_TYPE as $key => $value)
                        <option data-key="{{$key}}" value="{{$value}}">{{ $key }}</option>
                        @endforeach
                    </select>
                </div>

            </div> <!-- /.row -->

            <div class="row" id="block-fix-contract">
                <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('contract_start_date')) has-error @endif">
                    <label for="contract_start_date">Contract Start Date <span class="text-danger">*</span></label>
                    <input type="text" class="form-control pull-right contract_start_date" id="contract_start_date"
                           readonly
                           value="{{ old('contract_start_date') }}" required
                           name="contract_start_date" placeholder="{{ __('label.contract_start_date') }}">
                </div>
                <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('contract_end_date')) has-error @endif">
                    <label for="contract_end_date">Contract End Date <span class="text-danger">*</span></label>
                    <input type="text" class="form-control pull-right contract_end_date" id="contract_end_date" readonly
                           value="{{ old('contract_end_date') }}" required
                           name="contract_end_date" placeholder="{{ __('label.contract_end_date') }}">
                </div>

            </div> <!-- /.row -->

            <div class="row" id="block-company-profile">
                <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('company_code')) has-error @endif">
                    <label for="company_code">Company</label>
                    {{--<select name="company_code" id="company_code" class="form-control company_code select2" required>
                        <option value="">>> {{ __('label.company') }} <<</option>
                        @foreach($companies as $key => $value)
                        @if(old('company_code') == $value->company_code)
                        <option value="{{ $value->company_code }}" selected>{{ $value->name_kh }}</option>
                        @else
                        <option value="{{ $value->company_code }}">{{ $value->name_kh }}</option>
                        @endif
                        @endforeach
                    </select>--}}
                    <company-selection></company-selection>
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="branch_department_code">Department or Branch </label>
                    {{--<select name="branch_department_code" id="branch_department_code" class="form-control branch_department_code select2" required>
                        <option value=""> << {{__('label.department_or_branch') }} >></option>
                        @foreach($branchesDepartments as $key => $value)
                        @if($value->code == request('branch_department_code'))
                        <option value="{{ $value->code }}" selected>{{ $value->name_km }}</option>
                        @else
                        <option value="{{ $value->code }}">{{ $value->name_km }}</option>
                        @endif
                        @endforeach
                    </select>--}}
                    <branch-department-selection></branch-department-selection>
                </div>

                <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('position_code')) has-error @endif">
                    <label for="position_code">Position</label>
                    {{--<select name="position_code" id="position_code" class="form-control position_code select2" required>
                        <option value="">>> {{ __('label.position') }} <<</option>
                        @foreach($positions as $key => $value)
                           @if(old('position_code') == $value->code)
                           <option value="{{ $value->code }}" selected>{{ $value->name_kh }}</option>
                           @else
                           <option value="{{ $value->code }}">{{ $value->name_kh }}</option>
                           @endif
                       @endforeach
                   </select>--}}
                    <position-selection></position-selection>
               </div>
           </div>

           <div class="row" id="block-probation">
               <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('salary')) has-error @endif" id="container-salary">
                   <label for="salary">Salary <span class="text-danger">*</span></label>
                   <input type="text" class="form-control" id="salary" name="salary"
                          pattern="^\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"
                          placeholder="{{ __('label.base_salary') }}" value="{{ old('salary') }}">
               </div>

               <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('currency')) has-error @endif" id="container-currency">
                   <label for="currency">Currency <span class="text-danger">*</span></label>
                   <select name="currency" id="currency" class="form-control">
                       <option value="">>> {{ __('label.currency') }} <<</option>
                       @foreach($currency as $key => $value)
                       @if(old('currency') == $key)
                       <option value="{{ $key }}" selected>{{ $value }}</option>
                       @else
                       <option value="{{ $key }}">{{ $value }}</option>
                       @endif
                       @endforeach
                   </select>
               </div>

               <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('probation_end_date')) has-error @endif" id="container_probation_end_date">
                   <label for="probation_end_date">Probation End Date</label>
                   <input type="text" class="form-control pull-right probation_end_date" id="probation_end_date"
                          readonly
                          value="{{ old('probation_end_date') }}"
                          name="probation_end_date" placeholder="{{ __('label.probation_end_date') }}">
               </div>

                <div class="form-group col-sm-12 col-md-6 col-lg-6" id="container_tax_responsiblity">
                    <label for="pay_tax_status">Tax responsibility </label>
                    <div class="checkbox">
                            <label>
                                <input type="checkbox" 
                                name="pay_tax_status" 
                                class="form-check-input" 
                                id="pay_tax_status"> Pay Tax By Company
                            </label>
                    </div>
                </div>
           </div>

           <div class="row" id="container_transfer_to_staff">
               <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('transfer_to_staff')) has-error @endif">
                   <label for="transfer_to_staff">Transfer Work To</label>
                   <input type="text" class="form-control" name="transfer_to_staff" id="transfer_to_staff"
                          placeholder="{{ __('label.transfer_work_to').' ('.__('label.employee_id').')' }}"
                          value="{{ old('transfer_to_staff') }}">
               </div>

               <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('transfer_to_staff')) has-error @endif">
                   <label for="transfer_to_staff_name">Transfer Work To Name</label>
                   <input type="text" class="form-control" name="transfer_to_staff_name" id="transfer_to_staff_name"
                          placeholder="{{ __('label.transfer_work_to') }}" value="{{ old('transfer_to_staff') }}"
                          readonly>
               </div>
           </div>

           <div class="row" id="container_get_work_form_staff">
               <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('get_work_form_staff')) has-error @endif">
                   <label for="get_work_form_staff">Get Work From</label>
                   <input type="text" class="form-control" name="get_work_form_staff" id="get_work_form_staff"
                          placeholder="{{ __('label.get_work_from').' ('.__('label.employee_id').')' }}"
                          value="{{ old('get_work_form_staff') }}">
               </div>
               <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('get_work_form_staff_full_name')) has-error @endif">
                   <label for="get_work_form_staff_full_name">Get Work From Name</label>
                   <input type="text" class="form-control" placeholder="{{ __('label.get_work_from') }}"
                          name="get_work_form_staff_full_name" id="get_work_form_staff_full_name"
                          value="{{ old('get_work_form_staff_full_name') }}" readonly>
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
                   <textarea name="reason" id="reason" cols="30" rows="6" class="form-control"
                             placeholder="{{ __('label.reason') }}">{{ old('reason') }}</textarea>
               </div>
           </div>

           <!-- /.row -->

            <div class="row margin-bottom">
                <div class="col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-success btn-sm margin-r-5" id="btnSave"><i
                                class="fa fa-save"></i> SAVE
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" id="btnDiscard" data-dismiss="modal">
                        <i class="fa fa-remove"></i> DISCARD
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>