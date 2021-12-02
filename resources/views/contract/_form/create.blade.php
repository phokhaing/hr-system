<div class="row">
    <div class="col-sm-12 col-md-12">
        <form action="{{ route('contract.store') }}" role="form" method="post" id="create_contract">

            {{ csrf_field() }}
            <input type="hidden" name="contract_type" value="{{CONTRACT_TYPE['PROBATION']}}">

            <div class="row">
                <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('staff_personal_info_id')) has-error @endif">
                    <label for="staff_personal_info_id">Select staff profile <span class="text-danger">*</span></label>
                    <select name="staff_personal_info_id" id="staff_personal_info_id" class="form-control select2" >
                        <option value="">>> {{ __('label.choose_staff_name') }} <<</option>
                        @foreach($staff_profiles as $key => $value)
                            @if(old('staff_personal_info_id') == $value->id)
                                <option value="{{ $value->id }}" selected>{{ $value->full_name_kh }}</option>
                            @else
                                <option value="{{ $value->id }}">{{ $value->full_name_kh }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('emp_id_card')) has-error @endif">
                    <label for="p_emp_id_card">Employee ID <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="emp_id_card" name="emp_id_card"
                           placeholder="{{ __('label.employee_id') }}" value="{{ old('emp_id_card') }}" required>
                </div>
            </div> <!-- /.row -->

            <div class="row">
                <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('company_code')) has-error @endif">
                    <label for="company_code">Company <span class="text-danger">*</span></label>
                    <select name="company_code" id="company_code" class="form-control select2" required>
                        <option value="">>> {{ __('label.company') }} <<</option>
                        @foreach($companies as $key => $value)
                            @if(old('company_code') == $value->code)
                                <option value="{{ $value->company_code }}" selected>{{ $value->name_kh }}</option>
                            @else
                                <option value="{{ $value->company_code }}">{{ $value->name_kh }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="branch_department_code">Department or Branch <span class="text-danger">*</span></label>
                    <select name="branch_department_code" id="branch_department_code" class="form-control select2" required>
                        <option value=""> << {{__('label.department_or_branch') }} >></option>
                        @foreach($branchesDepartments as $key => $value)
                            @if($value->code == request('branch_department_code'))
                                <option value="{{ $value->company_code }}" selected>{{ $value->name_km }}</option>
                            @else
                                <option value="{{ $value->company_code }}">{{ $value->name_km }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('position_id')) has-error @endif">
                    <label for="position_code">Position <span class="text-danger">*</span></label>
                    <select name="position_code" id="position_code" class="form-control select2" required>
                        <option value="">>> {{ __('label.position') }} <<</option>
                        @foreach($positions as $key => $value)
                            @if(old('position_code') == $value->code)
                                <option value="{{ $value->code }}" selected>{{ $value->name_kh }}</option>
                            @else
                                <option value="{{ $value->code }}">{{ $value->name_kh }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('base_salary')) has-error @endif">
                    <label for="p_base_salary">Base salary <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="p_base_salary" name="base_salary"
                           pattern="^\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" required
                           placeholder="{{ __('label.base_salary') }}" value="{{ old('base_salary') }}">
                </div>
            </div> <!-- /.row -->

            <div class="row">
                <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('currency')) has-error @endif">
                    <label for="currency">Currency <span class="text-danger">*</span></label>
                    <select name="currency" id="currency" class="form-control" required>
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
                <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('contract_probation_end_date')) has-error @endif">
                    <label for="probation_end_date">Probation end date <span class="text-danger">*</span></label>
                    <input type="text" class="form-control pull-right contract_probation_end_date" id="contract_probation_end_date" readonly
                           value="{{ old('contract_probation_end_date') }}" required
                           name="contract_probation_end_date" placeholder="{{ __('label.probation_end_date') }}">
                </div>
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('contract_start_date')) has-error @endif">
                    <label for="contract_start_date">Contract start date <span class="text-danger">*</span></label>
                    <input type="text" class="form-control pull-right contract_start_date" id="contract_start_date" readonly
                           value="{{ old('contract_start_date') }}" required
                           name="contract_start_date" placeholder="{{ __('label.contract_start_date') }}">
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('contract_end_date')) has-error @endif">
                    <label for="contract_end_date">Contract end date <span class="text-danger">*</span></label>
                    <input type="text" class="form-control pull-right contract_end_date" id="contract_end_date" readonly
                           value="{{ old('contract_end_date') }}" required
                           name="contract_end_date" placeholder="{{ __('label.contract_end_date') }}">
                </div>
            </div> <!-- /.row -->

            <div class="row">
                <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('mobile')) has-error @endif">
                    <label for="p_mobile">Phone number</label>
                    <input type="text" class="form-control" name="mobile" id="p_mobile"
                           placeholder="{{ __('label.mobile_manager') }}" value="{{ old('mobile') }}">
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('email')) has-error @endif">
                    <label for="p_email">Email</label>
                    <input type="email" class="form-control" name="email" id="p_email"
                           placeholder="{{ __('label.email') }}" value="{{ old('email') }}">
                </div>
            </div> <!-- /.row -->

            <div class="row">
                <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('m_mobile')) has-error @endif">
                    <label for="m_mobile">Phone number (Manager)</label>
                    <input type="text" class="form-control" name="m_mobile" id="m_mobile"
                           placeholder="{{ __('label.mobile_manager') }}" value="{{ old('m_mobile') }}">
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('m_email')) has-error @endif">
                    <label for="m_email">Email (Manager)</label>
                    <input type="email" class="form-control" name="m_email" id="m_email"
                           placeholder="{{ __('label.email') }}" value="{{ old('email') }}">
                </div>
            </div> <!-- /.row -->

            <div class="row">
                <div class="form-group col-sm-12 col-md-12 col-lg-12 @if($errors->has('manager')) has-error @endif">
                    <label for="manager">Manager <span class="text-danger">*</span></label>
                    <select name="manager[]" id="manager" class="form-control select2" multiple="multiple" >
                        <option value=""> << {{__('label.manager') }} >></option>
                        @foreach($managers as $key => $manager)
                            @if($manager->id == request('manager'))
                                <option value="{{ @$manager->id }}" selected>{{ @$manager->full_name_kh }}</option>
                            @else
                                <option value="{{ @$manager->id }}">{{ @$manager->full_name_kh }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div> <!-- /.row -->

            <div class="row margin-bottom">
                <div class="col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-success btn-sm margin-r-5" id="btnSave"><i class="fa fa-save"></i> SAVE</button>
                    <button type="button" class="btn btn-danger btn-sm" id="btnDiscard" data-dismiss="modal"><i class="fa fa-remove"></i> DISCARD</button>
                </div>
            </div>
        </form>
    </div>
</div>