<div class="row">
    <div class="col-sm-12 col-md-12">
        <form action="{{ route('profile.update') }}" role="form" method="post" id="edit_form_profile">

            <div class="row margin-bottom">
                <div class="col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-success btn-sm margin-r-5" id="btnSave"><i class="fa fa-save"></i> UPDATE</button>
                    <button type="reset" class="btn btn-danger btn-sm" id="btnDiscard"><i class="fa fa-remove"></i> DISCARD</button>
                </div>
            </div>

            {{ csrf_field() }}
            <input type="hidden" name="staff_token" value="{{ encrypt($staff->id) }}">

            <div class="row">
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('profile.emp_id_card')) has-error @endif">
                    <label for="p_emp_id_card">Employee ID <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="p_emp_id_card" name="profile[emp_id_card]"
                           placeholder="{{ __('label.employee_id') }}" value="{{ $staff->profile->emp_id_card }}">
                </div>
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('profile.company_id')) has-error @endif">
                    <label for="p_company_id">Company <span class="text-danger">*</span></label>
                    <select name="profile[company_id]" id="p_company_id" class="form-control js-select2-single">
                        <option value="">>> {{ __('label.company') }} <<</option>
                        @foreach($companies as $key => $value)
                            @if($staff->profile->company_id == $value->id)
                                <option value="{{ $value->id }}" selected>{{ $value->name_kh }}</option>
                            @else
                                <option value="{{ $value->id }}">{{ $value->name_kh }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('profile.branch_id')) has-error @endif">
                    <label for="p_branch_id">Branch <span class="text-danger">*</span></label>
                    <select name="profile[branch_id]" id="p_branch_id" class="form-control js-select2-single">
                        <option value="">>> {{ __('label.branch') }} <<</option>
                        @foreach($branches as $key => $value)
                            @if($staff->profile->branch_id == $value->id)
                                <option value="{{ $value->id }}" selected>{{ $value->name_kh }}</option>
                            @else
                                <option value="{{ $value->id }}">{{ $value->name_kh }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('profile.dpt_id')) has-error @endif">
                    <label for="p_dpt_id">Department <span class="text-danger">*</span></label>
                    <select name="profile[dpt_id]" id="p_dpt_id" class="form-control js-select2-single">
                        <option value="">>> {{ __('label.department') }} <<</option>
                        @foreach($departments as $key => $value)
                            @if($staff->profile->dpt_id == $value->id)
                                <option value="{{ $value->id }}" selected>{{ $value->name_kh }}</option>
                            @else
                                <option value="{{ $value->id }}">{{ $value->name_kh }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div> <!-- /.row -->

            <div class="row">
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('profile.position_id')) has-error @endif">
                    <label for="p_position_id">Position <span class="text-danger">*</span></label>
                    <select name="profile[position_id]" id="p_position_id" class="form-control js-select2-single">
                        <option value="">>> {{ __('label.position') }} <<</option>
                        @foreach($positions as $key => $value)
                            @if($staff->profile->position_id == $value->id)
                                <option value="{{ $value->id }}" selected>{{ $value->name_kh }}</option>
                            @else
                                <option value="{{ $value->id }}">{{ $value->name_kh }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('profile.manager')) has-error @endif">
                    <label for="p_manager">Manager <span class="text-danger">*</span></label>
                    <input type="text" class="form-control pull-right p_manager" id="p_manager" name="profile[manager]"
                           placeholder="{{ __('label.manager') }}" value="{{ $staff->profile->manager }}">
                </div>
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('profile.base_salary')) has-error @endif">
                    <label for="p_base_salary">Base salary <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="p_base_salary" name="profile[base_salary]"
                           pattern="^\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"
                           placeholder="{{ __('label.base_salary') }}" value="{{ number_format($staff->profile->base_salary, 2) }}">
                </div>
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('profile.currency')) has-error @endif">
                    <label for="p_currency">Currency <span class="text-danger">*</span></label>
                    <select name="profile[currency]" id="p_currency" class="form-control">
                        <option value="">>> {{ __('label.currency') }} <<</option>
                        @foreach($currency as $key => $value)
                            @if($staff->profile->currency == $key)
                                <option value="{{ $key }}" selected>{{ $value }}</option>
                            @else
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div> <!-- /.row -->

            <div class="row">
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('profile.employment_date')) has-error @endif">
                    <label for="p_employment_date">Employment date <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right end_date" id="p_employment_date" readonly
                               name="profile[employment_date]" placeholder="{{ __('label.employee_date') }}"
                               value="{{ date('d-M-Y', strtotime($staff->profile->employment_date)) }}">
                    </div>
                </div>
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('profile.probation_duration')) has-error @endif">
                    <label for="probation_duration">Duration of probation (Month)<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="probation_duration" name="profile[probation_duration]"
                           placeholder="{{ __('label.duration_probation') }} (គិតជាខែ)" value="{{ $staff->profile->probation_duration }}">
                </div>
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('profile.probation_end_date')) has-error @endif">
                    <label for="p_probation_date">Probation end date <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right start_date" id="p_probation_date" readonly
                               name="profile[probation_end_date]" placeholder="{{ __('label.probation_date') }}"
                               value="{{ date('d-M-Y', strtotime($staff->profile->probation_end_date)) }}">
                    </div>
                </div>
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('profile.contract_end_date')) has-error @endif">
                    <label for="p_contract_end_date">Contract date <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right end_date" id="p_contract_end_date" readonly
                               name="profile[contract_end_date]" placeholder="{{ __('label.contract_date') }}"
                               value="{{ date('d-M-Y', strtotime($staff->profile->contract_end_date)) }}">
                    </div>
                </div>
                {{--<div class="form-group col-sm-6 col-md-3 @if($errors->has('profile.home_visit')) has-error @endif">
                    <label for="p_home_visit">Home Visit <span class="text-danger">*</span></label>
                    <select name="profile[home_visit]" id="p_home_visit" class="form-control">
                        <option value="">>> {{ __('label.home_visit') }} <<</option>
                        @foreach($homeVisit as $key => $value)
                            @if($staff->profile->home_visit == $key)
                                <option value="{{ $key }}" selected>{{ $value }}</option>
                            @else
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>--}}
            </div> <!-- /.row -->

            <div class="row">
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('profile.contract_duration')) has-error @endif">
                    <label for="contract_duration">Duration Of Contract (Month)<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="contract_duration" name="profile[contract_duration]"
                           placeholder="{{ __('label.contract_duration') }} (គិតជាខែ)" value="{{ $staff->profile->contract_duration }}">
                </div>
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('profile.mobile')) has-error @endif">
                    <label for="p_mobile">Manager Phone Number</label>
                    <input type="text" class="form-control" name="profile[mobile]" id="p_mobile"
                           placeholder="{{ __('label.mobile_manager') }}" value="{{ $staff->profile->mobile }}">
                </div>
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('profile.phone')) has-error @endif">
                    <label for="p_phone">Institutions​Phone Number</label>
                    <input type="text" class="form-control" name="profile[phone]" id="p_phone"
                           placeholder="{{ __('label.mobile_institution') }}" value="{{ $staff->profile->phone }}">
                </div>
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('profile.email')) has-error @endif">
                    <label for="p_email">Email </label>
                    <input type="text" class="form-control" name="profile[email]" id="p_email"
                           placeholder="{{ __('label.email') }}" value="{{ $staff->profile->email }}">
                </div>
            </div> <!-- /.row -->

        </form>
    </div>
</div>