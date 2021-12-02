<form action="{{ route('resign.store') }}" method="post" enctype="multipart/form-data" role="form" id="formResign">
    <div class="row">
        <input type="hidden" name="staff_token" id="staff_token" value="{{ old('staff_token') }}">
        {{ csrf_field() }}
        <div class="col-sm-12 col-md-12">
            <div class="checkbox">
                <label class="text-danger">
                    <input type="checkbox" name="is_fraud" id="is_fraud" value=1> <b>Staff fraud</b>
                </label>
            </div>
        </div>
    </div>
    <div class="row">
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
            <label for="company">Company </label>
            <input type="text" class="form-control" name="company" id="company" value="{{ old('company') }}" readonly>
        </div>
        <div class="form-group col-sm-6 col-md-3">
            <label for="branch">Branch </label>
            <input type="text" class="form-control" name="branch" id="branch" value="{{ old('branch') }}" readonly>
        </div>
        <div class="form-group col-sm-6 col-md-3">
            <label for="department">Department </label>
            <input type="text" class="form-control" name="department" id="department" value="{{ old('department') }}" readonly>
        </div>
        <div class="form-group col-sm-6 col-md-3">
            <label for="position">Position </label>
            <input type="text" class="form-control" name="position" id="position" value="{{ old('position') }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6 col-md-3">
            <label for="employment_date">Employment date </label>
            <input type="text" class="form-control" name="employment_date" id="employment_date" value="{{ old('employment_date') }}" readonly>
        </div>
        <div class="form-group col-sm-6 col-md-3 @if($errors->has('resign_date')) has-error @endif">
            <label for="resign_date">Resign date <span class="text-danger">*</span></label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="pull-right form-control resign_date" id="resign_date" name="resign_date"
                       placeholder="ថ្ងៃ-ខែ-ឆ្នាំ ដាក់ពាក្យ" autocomplete="off" value="{{ old('resign_date') }}" readonly>
            </div>
        </div>
        <div class="form-group col-sm-6 col-md-3">
            <label for="approve_date">Approved date </label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="pull-right form-control approve_date" id="approve_date" name="approve_date"
                   placeholder="ថ្ងៃ-ខែ-ឆ្នាំ អនុម័ត" autocomplete="off" value="{{ old('approve_date') }}" readonly>
            </div>
        </div>
        <div class="form-group col-sm-6 col-md-3">
            <label for="last_day">Last day </label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="pull-right form-control last_day" id="last_day" name="last_day"
                   placeholder="ថ្ងៃ-ខែ-ឆ្នាំ ចុងក្រោយ" autocomplete="off" value="{{ old('last_day') }}" readonly>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6 col-md-3">
            <label for="staff_id_replace_1">Staff ID replaced <span class="">*</span> (1)</label>
            <input type="text" class="form-control" name="staff_id_replace_1" id="staff_id_replace_1"
                   placeholder="{{ __('label.employee_id') }} (1)" value="{{ old('staff_id_replace_1') }}">
        </div>
        <div class="form-group col-sm-6 col-md-3">
            <label for="staff_replace_name_1">Staff replace (Full name KH) (1)</label>
            <input type="text" class="form-control" name="staff_replace_name_1" id="staff_replace_name_1" readonly
                   placeholder="{{ __('label.staff_replace_name') }} (1)" value="{{ old('staff_replace_name_1') }}">
        </div>
        <div class="form-group col-sm-6 col-md-3">
            <label for="staff_id_replace_2">Staff ID replaced <span class="">*</span> (2)</label>
            <input type="text" class="form-control" name="staff_id_replace_2" id="staff_id_replace_2"
                   placeholder="{{ __('label.employee_id') }} (2)" value="{{ old('staff_id_replace_2') }}">
        </div>
        <div class="form-group col-sm-6 col-md-3">
            <label for="staff_replace_name_2">Staff replace (Full name KH) (2)</label>
            <input type="text" class="form-control" name="staff_replace_name_2" id="staff_replace_name_2" readonly
                   placeholder="{{ __('label.staff_replace_name') }} (2)" value="{{ old('staff_replace_name_2') }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6 col-md-3 @if($errors->has('reason_id')) has-error @endif">
            <label for="reason_company_id">Reason (Company noted) <span class="text-danger">*</span></label>
            <select class="form-control" name="reason_id" id="reason_id">
                <option value="">>> {{ __('label.reason') }} <<</option>
                @foreach($reasons as $key => $reason)
                    @if(old('reason_id') == $reason->id)
                        <option value="{{ $reason->id }}" selected>{{ $reason->name_kh }}</option>
                    @else
                        <option value="{{ $reason->id }}" >{{ $reason->name_kh }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="form-group col-sm-6 col-md-3 @if($errors->has('file_reference')) has-error @endif">
            <label for="file_reference">Reference file <span class="text-danger">*</span></label>
            <input type="file" class="form-control" name="file_reference" id="file_reference" >
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12 col-md-12 @if($errors->has('reason')) has-error @endif">
            <label for="reason">Reason <span class="text-danger">*</span></label>
            <textarea name="reason" id="reason" cols="30" rows="6" class="form-control" placeholder="{{ __('label.reason') }}">{{ old('reason') }}</textarea>
        </div>
    </div>
</form> <!-- /form -->
