<div class="row">
    <div class="col-sm-12 col-md-12">
        <form action="{{ route('contract.storeTerminate') }}" role="form" method="post" id="create_contract" enctype="multipart/form-data">

            {{ csrf_field() }}
            <input type="hidden" name="contract_type" value="{{CONTRACT_TYPE['TERMINATE']}}">

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

            </div> <!-- /.row -->

            <div class="row">
                <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('terminate_effective_date')) has-error @endif">
                    <label for="promote_effective_date">Effective date <span class="text-danger">*</span></label>
                    <input type="text" class="pull-right form-control terminate_effective_date" id="terminate_effective_date" name="terminate_effective_date" readonly
                           placeholder="ថ្ងៃ-ខែ-ឆ្នាំ {{ __('label.effective_date') }}" autocomplete="off" value="{{ old('terminate_effective_date') }}" required>
                </div>
                <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('file_reference')) has-error @endif">
                    <label for="file_reference">Reference file <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" name="file_reference" id="file_reference" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('contract_terminate_transfer_to_id')) has-error @endif">
                    <label for="contract_terminate_transfer_to_id">Transfer work to (Staff ID) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="contract_terminate_transfer_to_id" id="contract_terminate_transfer_to_id"
                           placeholder="{{ __('label.transfer_work_to').' ('.__('label.employee_id').')' }}" value="{{ old('contract_terminate_transfer_to_id') }}" required>
                </div>
                <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('transfer_to_name')) has-error @endif">
                    <label for="transfer_to_name">Transfer work to (Full name KH) </label>
                    <input type="text" class="form-control" name="transfer_to_name" id="transfer_to_name"
                           placeholder="{{ __('label.transfer_work_to').' ('.__('label.full_name_kh').')' }}" value="{{ old('transfer_to_name') }}">
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