<form action="{{ route('contract.storeResign') }}" method="post" enctype="multipart/form-data" role="form" id="FORM-RESIGN">
    <div class="row">
        <input type="hidden" name="staff_token" id="staff_token" value="{{ old('staff_token') }}">
        <input type="hidden" name="contract_type" value="{{CONTRACT_TYPE['RESIGN']}}">
        {{ csrf_field() }}
    </div>

    <div class="row">
        <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('staff_personal_info_id')) has-error @endif">
            <label for="staff_personal_info_id">Select staff resign <span class="text-danger">*</span></label>
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

        <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('resign_date')) has-error @endif">
            <label for="resign_date">Resign date <span class="text-danger">*</span></label>
            <input type="text" class="pull-right form-control contract_resign_date" id="contract_resign_date"
                   name="resign_date"
                   placeholder="ថ្ងៃ-ខែ-ឆ្នាំ ដាក់ពាក្យ" autocomplete="off" value="{{ old('resign_date') }}" readonly>
        </div>
        <div class="form-group col-sm-6 col-md-6 col-lg-6">
            <label for="approve_date">Approved date </label>
            <input type="text" class="pull-right form-control contract_resign_approve_date"
                   id="contract_resign_approve_date" name="approve_date"
                   placeholder="ថ្ងៃ-ខែ-ឆ្នាំ អនុម័ត" autocomplete="off" value="{{ old('approve_date') }}" readonly>
        </div>
        <div class="form-group col-sm-6 col-md-6 col-lg-6">
            <label for="last_day">Last day </label>
            <input type="text" class="pull-right form-control contract_resign_last_day" id="contract_resign_last_day"
                   name="last_day"
                   placeholder="ថ្ងៃ-ខែ-ឆ្នាំ ចុងក្រោយ" autocomplete="off" value="{{ old('last_day') }}" readonly>
        </div>
        <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('file_reference')) has-error @endif">
            <label for="file_reference">Reference file <span class="text-danger">*</span></label>
            <input type="file" class="form-control" name="file_reference" id="file_reference" required>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('staff_id_replace')) has-error @endif">
            <label for="staff_id_replace">Staff ID replaced <span class="">*</span></label>
            <input type="text" class="form-control" name="staff_id_replace" id="staff_id_replace"
                   placeholder="{{ __('label.employee_id') }}" value="{{ old('staff_id_replace') }}">

            @if($errors->has('staff_id_replace'))
            <h4>{{$errors->first()}}</h4>
            @endif
        </div>
        <div class="form-group col-sm-6 col-md-6 col-lg-6">
            <label for="staff_replace_name">Full name KH</label>
            <input type="text" class="form-control" name="staff_replace_name[]" id="staff_replace_name"
                   placeholder="{{ __('label.staff_replace_name') }}" value="{{ old('staff_replace_name') }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('company_reason_id')) has-error @endif">
            <label for="company_reason_id">Reason (Company noted) <span class="text-danger">*</span></label>
            <select class="form-control select2" name="company_reason_id" id="company_reason_id" required>
                <option value="">>> {{ __('label.reason') }} <<</option>
                @foreach($reasons as $key => $reason)
                @if(old('reason_id') == $reason->id)
                <option value="{{ $reason->id }}" selected>{{ $reason->name_kh }}</option>
                @else
                <option value="{{ $reason->id }}">{{ $reason->name_kh }}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="checkbox">
                <label class="text-danger">
                    <input type="checkbox" name="is_fraud" id="is_fraud" value=1> <b>Staff fraud</b>
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-12 col-md-12 @if($errors->has('reason')) has-error @endif">
            <label for="reason">Reason <span class="text-danger">*</span></label>
            <textarea name="reason" id="reason" cols="30" rows="6" class="form-control" required
                      placeholder="{{ __('label.reason') }}">{{ old('reason') }}</textarea>
        </div>
    </div>

    <div class="row margin-bottom">
        <div class="col-sm-12 col-md-12">
            <button type="submit" class="btn btn-success btn-sm margin-r-5" id="btnSave"><i class="fa fa-save"></i> SAVE
            </button>
        </div>
    </div>
</form> <!-- /form -->