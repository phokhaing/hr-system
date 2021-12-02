<div class="row">
    <div class="col-sm-12 col-md-12">
        <form action="{{ route('request_resign.update') }}" role="form" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="staff_personal_info_id" value="{{ encrypt($staff->id) }}">
            <input type="hidden" name="resign_id" value="{{ encrypt($staff->requestResign->id) }}">
            <div class="row" id="block-fix-contract">
                <div class="form-group col-sm-6 col-md-6 col-lg-6 @if($errors->has('request_date')) has-error @endif">
                    <label for="request_date">Request Date <span class="text-danger">*</span></label>
                    <input type="text" class="form-control pull-right request_date" id="request_date"
                           readonly required
                           value="{{ date('d-M-Y', strtotime($staff->requestResign->resign_object->request_date))  }}"
                           name="request_date" placeholder="ថ្ងៃស្នើសុំឈប់">
                </div>

                <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('company_code')) has-error @endif">
                    <label for="company_code">Company</label>
                    <select name="company_code" id="company_code" class="form-control company_code select2" required>
                        <option value="">>> {{ __('label.company') }} <<</option>
                        @foreach($companies as $key => $value)
                            @if($staff->requestResign->resign_object->company->code == $value->company_code)
                                <option value="{{ $value->company_code }}" selected>{{ $value->name_kh }}</option>
                            @else
                                <option value="{{ $value->company_code }}">{{ $value->name_kh }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-sm-12 col-md-6 col-lg-6">
                    <label for="branch_department_code">Department or Branch </label>
                    <select name="branch_department_code" id="branch_department_code" class="form-control branch_department_code select2" required>
                        <option value=""> << {{__('label.department_or_branch') }} >></option>
                        @foreach($branchesDepartments as $key => $value)
                            @if($value->code == $staff->requestResign->resign_object->branch_department->code)
                                <option value="{{ $value->code }}" selected>{{ $value->name_km }}</option>
                            @else
                                <option value="{{ $value->code }}">{{ $value->name_km }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-sm-12 col-md-6 col-lg-6 @if($errors->has('position_code')) has-error @endif">
                    <label for="position_code">Position</label>
                    <select name="position_code" id="position_code" class="form-control position_code select2" required>
                        <option value="">>> {{ __('label.position') }} <<</option>
                        @foreach($positions as $key => $value)
                            @if($staff->requestResign->resign_object->position->code == $value->code)
                                <option value="{{ $value->code }}" selected>{{ $value->name_kh }}</option>
                            @else
                                <option value="{{ $value->code }}">{{ $value->name_kh }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row" id="container_reason">
                <div class="form-group col-sm-12 col-md-12 @if($errors->has('reason')) has-error @endif">
                    <label for="reason">Reason</label>
                    <textarea name="reason" id="reason" cols="30" rows="6" class="form-control"
                              placeholder="{{ __('label.reason') }}">{{ $staff->requestResign->resign_object->reason }}</textarea>
                </div>
            </div>
            <!-- /.row -->

            <div class="row margin-bottom">
                <div class="col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-success btn-sm margin-r-5"><i
                                class="fa fa-save"></i> SAVE
                    </button>
                    <button type="reset" class="btn btn-danger btn-sm"><i class="fa fa-remove"></i> DISCARD
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>