<div class="row">
    <div class="col-sm-12 col-md-12">
        <form action="{{ route('contract.storeDemote') }}" role="form" method="post" id="create_contract" enctype="multipart/form-data">

            {{ csrf_field() }}
            <input type="hidden" name="contract_type" value="{{CONTRACT_TYPE['PROMOTE']}}">

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

                <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('company_code')) has-error @endif">
                    <label for="company_code">New company</label>
                    <select name="company_code" id="company_code" class="form-control select2">
                        <option value="">>> {{ __('label.company') }} <<</option>
                        @foreach($companies as $key => $value)
                        @if(old('company_code') == $value->company_code)
                        <option value="{{ $value->company_code }}" selected>{{ $value->name_kh }}</option>
                        @else
                        <option value="{{ $value->company_code }}">{{ $value->name_kh }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="branch_department_code">New Department or Branch</label>
                    <select name="branch_department_code" id="branch_department_code" class="form-control select2">
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

                <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('position_code')) has-error @endif">
                    <label for="position_code">New position <span class="text-danger">*</span></label>
                    <select name="position_code" id="position_code" class="form-control select2" required>
                        <option value="">>> {{ __('label.position') }} <<</option>
                        @foreach($positions as $key => $value)
                        @if(old('position_code') == $value->code)
                        <option value="{{ $value->id }}" selected>{{ $value->name_kh }}</option>
                        @else
                        <option value="{{ $value->id }}">{{ $value->name_kh }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>

            </div> <!-- /.row -->

            <div class="row">
                <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('old_salary')) has-error @endif">
                    <label for="old_salary">Old salary </label>
                    <input type="number" class="form-control" name="old_salary" id="old_salary" value="{{ old('old_salary') }}"
                           placeholder="{{ __('label.old_salary') }}" step="any">
                </div>
                <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('new_salary')) has-error @endif">
                    <label for="new_salary">New salary <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="new_salary" id="new_salary" value="{{ old('new_salary') }}"
                           placeholder="{{ __('label.new_salary') }}" step="any" required>
                </div>

                <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('contract_demote_start_date')) has-error @endif">
                    <label for="contract_demote_start_date">Contract start date <span class="text-danger">*</span></label>
                    <input type="text" class="form-control pull-right contract_demote_start_date" id="contract_demote_start_date" readonly
                           value="{{ old('contract_demote_start_date') }}" required
                           name="contract_demote_start_date" placeholder="{{ __('label.contract_start_date') }}">
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('contract_demote_end_date')) has-error @endif">
                    <label for="contract_demote_end_date">Contract end date <span class="text-danger">*</span></label>
                    <input type="text" class="form-control pull-right contract_demote_end_date" id="contract_demote_end_date" readonly
                           value="{{ old('contract_demote_end_date') }}" required
                           name="contract_demote_end_date" placeholder="{{ __('label.contract_end_date') }}">
                </div>

                <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('demote_effective_date')) has-error @endif">
                    <label for="demote_effective_date">Effective date <span class="text-danger">*</span></label>
                    <input type="text" class="pull-right form-control demote_effective_date" id="demote_effective_date" name="demote_effective_date" readonly
                           placeholder="ថ្ងៃ-ខែ-ឆ្នាំ {{ __('label.effective_date') }}" autocomplete="off" value="{{ old('demote_effective_date') }}" required>
                </div>
                <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('file_reference')) has-error @endif">
                    <label for="file_reference">Reference file <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" name="file_reference" id="file_reference" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('contract_demote_transfer_to_id')) has-error @endif">
                    <label for="contract_demote_transfer_to_id">Transfer work to (Staff ID) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="contract_demote_transfer_to_id" id="contract_demote_transfer_to_id"
                           placeholder="{{ __('label.transfer_work_to').' ('.__('label.employee_id').')' }}" value="{{ old('contract_demote_transfer_to_id') }}" required>
                </div>
                <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('transfer_to_name')) has-error @endif">
                    <label for="transfer_to_name">Transfer work to (Full name KH) </label>
                    <input type="text" class="form-control" name="transfer_to_name" id="transfer_to_name"
                           placeholder="{{ __('label.transfer_work_to').' ('.__('label.full_name_kh').')' }}" value="{{ old('transfer_to_name') }}">
                </div>

                <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('contract_demote_get_work_form_id')) has-error @endif">
                    <label for="contract_demote_get_work_form_id">Get work from (Staff ID) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="contract_demote_get_work_form_id" id="contract_demote_get_work_form_id"
                           placeholder="{{ __('label.get_work_from').' ('.__('label.employee_id').')' }}" value="{{ old('contract_demote_get_work_form_id') }}" required>
                </div>
                <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('get_work_form_name')) has-error @endif">
                    <label for="get_work_form_name">Get work from (Full name KH) </label>
                    <input type="text" class="form-control" name="get_work_form_name" id="get_work_form_name"
                           placeholder="{{ __('label.get_work_from').' ('.__('label.full_name_kh').')' }}" value="{{ old('get_work_form_name') }}">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-12 col-md-12 @if($errors->has('note')) has-error @endif">
                    <label for="note">Note </label>
                    <textarea name="note" id="note" cols="30" rows="6" class="form-control"
                              placeholder="{{ __('label.reason') }}">{{ old('note') }}</textarea>
                </div>
            </div>

            <div class="row margin-bottom">
                <div class="col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-success btn-sm margin-r-5" id="btnSave"><i class="fa fa-save"></i> SAVE</button>
                    <button type="button" class="btn btn-danger btn-sm" id="btnDiscard" data-dismiss="modal"><i class="fa fa-remove"></i> DISCARD</button>
                </div>
            </div>
        </form>
    </div>
</div>