<form action="{{ route('movement.store') }}" method="post" enctype="multipart/form-data" role="form" id="formMovement">
    <div class="row">
        <input type="hidden" name="staff_token" id="staff_token" value="{{ old('staff_token') }}">
        {{ csrf_field() }}
        <div class="form-group col-sm-6 col-md-3 @if($errors->has('staff_id')) has-error @endif">
            <label for="staff_id">Staff ID <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="staff_id" id="staff_id" value="{{ old('staff_id') }}"
                   placeholder="{{ __('label.employee_id') }}">
        </div>
        <div class="form-group col-sm-6 col-md-3">
            <label for="full_name_kh">FUll name KH </label>
            <input type="text" class="form-control" name="full_name_kh" id="full_name_kh" value="{{ old('full_name_kh') }}" readonly>
        </div>
        <div class="form-group col-sm-6 col-md-3">
            <label for="full_name_en">FUll name EN </label>
            <input type="text" class="form-control" name="full_name_en" id="full_name_en" value="{{ old('full_name_en') }}" readonly>
        </div>
        <div class="form-group col-sm-6 col-md-3">
            <label for="gender">Gender </label>
            <input type="text" class="form-control" name="gender" id="gender" value="{{ old('gender') }}" readonly>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6 col-md-3">
            <label for="old_company">Old company </label>
            <input type="text" class="form-control" name="old_company" id="old_company" value="{{ old('old_company') }}" readonly>
        </div>
        <div class="form-group col-sm-6 col-md-3">
            <label for="old_branch">Old branch </label>
            <input type="text" class="form-control" name="old_branch" id="old_branch" value="{{ old('old_branch') }}" readonly>
        </div>
        <div class="form-group col-sm-6 col-md-3">
            <label for="old_department">Old department </label>
            <input type="text" class="form-control" name="old_department" id="old_department" value="{{ old('old_department') }}" readonly>
        </div>
        <div class="form-group col-sm-6 col-md-3">
            <label for="position">Old position </label>
            <input type="text" class="form-control" name="old_position" id="old_position" value="{{ old('old_position') }}" readonly>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6 col-md-3 @if($errors->has('company_id')) has-error @endif">
            <label for="company_id">New company<span class="text-danger">*</span></label>
            <select class="form-control js-select2-single" name="company_id" id="company_id">
                <option value="">>> {{ __('label.company') }} <<</option>
                @foreach($companies as $key => $company)
                    @if(old('company_id') == $company->id)
                        <option value="{{ $company->id }}" selected>{{ $company->short_name }}</option>
                    @else
                        <option value="{{ $company->id }}" >{{ $company->short_name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-6 col-md-3 @if($errors->has('branch_id')) has-error @endif">
            <label for="branch_id">New branch <span class="text-danger">*</span></label>
            <select class="form-control js-select2-single" name="branch_id" id="branch_id">
                <option value="">>> {{ __('label.branch') }} <<</option>
                @foreach($branches as $key => $branch)
                    @if(old('branch_id') == $branch->id)
                        <option value="{{ $branch->id }}" selected>{{ $branch->name_kh }}</option>
                    @else
                        <option value="{{ $branch->id }}" >{{ $branch->name_kh }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-6 col-md-3 @if($errors->has('department_id')) has-error @endif">
            <label for="department_id">New department <span class="text-danger">*</span></label>
            <select class="form-control js-select2-single" name="department_id" id="department_id">
                <option value="">>> {{ __('label.department') }} <<</option>
                @foreach($departments as $key => $department)
                    @if(old('department_id') == $department->id)
                        <option value="{{ $department->id }}" selected>{{ $department->name_kh }}</option>
                    @else
                        <option value="{{ $department->id }}" >{{ $department->name_kh }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-6 col-md-3 @if($errors->has('position_id')) has-error @endif">
            <label for="position_id">New position <span class="text-danger">*</span></label>
            <select class="form-control js-select2-single" name="position_id" id="position_id">
                <option value="">>> {{ __('label.position') }} <<</option>
                @foreach($positions as $key => $position)
                    @if(old('position_id') == $position->id)
                        <option value="{{ $position->id }}" selected>{{ $position->name_kh }}</option>
                    @else
                        <option value="{{ $position->id }}" >{{ $position->name_kh }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6 col-md-3 @if($errors->has('old_salary')) has-error @endif">
            <label for="old_salary">Old salary </label>
            <input type="text" class="form-control" name="old_salary" id="old_salary" value="{{ old('old_salary') }}"
                   placeholder="{{ __('label.old_salary') }}" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency">
        </div>
        <div class="form-group col-sm-6 col-md-3 @if($errors->has('new_salary')) has-error @endif">
            <label for="new_salary">New salary <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="new_salary" id="new_salary" value="{{ old('new_salary') }}"
                   placeholder="{{ __('label.new_salary') }}" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency">
        </div>
        <div class="form-group col-sm-6 col-md-3 @if($errors->has('effective_date')) has-error @endif">
            <label for="effective_date">Effective date <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="pull-right form-control effective_date" id="effective_date" name="effective_date" readonly
                   placeholder="ថ្ងៃ-ខែ-ឆ្នាំ {{ __('label.effective_date') }}" autocomplete="off" value="{{ old('effective_date') }}">
            </div>
        </div>
        <div class="form-group col-sm-6 col-md-3 @if($errors->has('file_reference')) has-error @endif">
            <label for="file_reference">Reference file <span class="text-danger">*</span></label>
            <input type="file" class="form-control" name="file_reference" id="file_reference" >
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6 col-md-3 @if($errors->has('transfer_to_id')) has-error @endif">
            <label for="transfer_to_id">Transfer work to (Staff ID) <span class="">*</span></label>
            <input type="text" class="form-control" name="transfer_to_id" id="transfer_to_id"
                   placeholder="{{ __('label.transfer_work_to').' ('.__('label.employee_id').')' }}" value="{{ old('transfer_to_id') }}">
        </div>
        <div class="form-group col-sm-6 col-md-3 @if($errors->has('transfer_to_name')) has-error @endif">
            <label for="transfer_to_name">Transfer work to (Full name KH) </label>
            <input type="text" class="form-control" name="transfer_to_name" id="transfer_to_name"
                   placeholder="{{ __('label.transfer_work_to').' ('.__('label.full_name_kh').')' }}" value="{{ old('transfer_to_name') }}">
        </div>
        <div class="form-group col-sm-6 col-md-3 @if($errors->has('get_work_form_id')) has-error @endif">
            <label for="get_work_form_id">Get work from (Staff ID) <span class="">*</span></label>
            <input type="text" class="form-control" name="get_work_form_id" id="get_work_form_id"
                   placeholder="{{ __('label.get_work_from').' ('.__('label.employee_id').')' }}" value="{{ old('get_work_form_id') }}">
        </div>
        <div class="form-group col-sm-6 col-md-3 @if($errors->has('get_work_form_name')) has-error @endif">
            <label for="get_work_form_name">Get work from (Full name KH) </label>
            <input type="text" class="form-control" name="get_work_form_name" id="get_work_form_name"
                   placeholder="{{ __('label.get_work_from').' ('.__('label.full_name_kh').')' }}" value="{{ old('get_work_form_name') }}">
        </div>
    </div>

</form> <!-- /form -->
