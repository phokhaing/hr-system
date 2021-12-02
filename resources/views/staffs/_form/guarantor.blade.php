<div class="row">
    <div class="col-sm-12 col-md-12 parent-guarantor">
        <form action="{{ route('guarantor.store') }}" role="form" method="post" id="edit_form_guarantor">

            <div class="row margin-bottom">
                <div class="col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-success btn-sm margin-r-5" id="btnSave"><i class="fa fa-save"></i> SAVE</button>
                    <button type="reset" class="btn btn-danger btn-sm" id="btnDiscard"><i class="fa fa-remove"></i> DISCARD</button>
                </div>
            </div>

            {{ csrf_field() }}
            <input type="hidden" name="staff_token" value="{{ encrypt($staff->id) }}">
            <input type="hidden" name="num_row" class="num_row" value="{{ old('num_row') }}">

            @if(!empty(old('num_row')))
                <?php  $num_row = old('num_row');  ?>
            @else
                <?php  $num_row = 1; ?>
            @endif

            @foreach(range(0, $num_row-1) as $i)
            <div class="child-guarantor">
                <div class="row">
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('guarantor.last_name_kh.'.$i)) has-error @endif">
                        <label for="g_last_name_kh">Last Name KH <span class="text-danger">*</span></label>
                        <input type="text" name="guarantor[last_name_kh][]" placeholder="{{ __('label.last_name_kh') }}"
                        value="{{ old('guarantor.last_name_kh.'.$i) }}">
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('guarantor.first_name_kh.'.$i)) has-error @endif">
                        <label for="g_first_name_kh">First Name KH <span class="text-danger">*</span></label>
                        <input type="text" name="guarantor[first_name_kh][]" placeholder="{{ __('label.first_name_kh') }}"
                               value="{{ old('guarantor.first_name_kh.'.$i) }}">
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('guarantor.last_name_en.'.$i)) has-error @endif">
                        <label for="g_last_name_en">Last Name EN <span class="text-danger">*</span></label>
                        <input type="text" name="guarantor[last_name_en][]" placeholder="{{ __('label.last_name_en') }}"
                               value="{{ old('guarantor.last_name_en.'.$i) }}">
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('guarantor.first_name_en.'.$i)) has-error @endif">
                        <label for="g_first_name_en">First Name EN <span class="text-danger">*</span></label>
                        <input type="text" name="guarantor[first_name_en][]" placeholder="{{ __('label.first_name_en') }}"
                               value="{{ old('guarantor.first_name_en.'.$i) }}">
                    </div>
                </div> <!-- /.row -->

                <div class="row">
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('guarantor.dob.'.$i)) has-error @endif">
                        <label>Date Of Birth <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right dob_guarantor" name="guarantor[dob][]" readonly
                                   placeholder="{{ __('label.date_of_birth') }}" value="{{ old('guarantor.dob.'.$i) }}">
                        </div>
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('guarantor.pob.'.$i)) has-error @endif">
                        <label>Place Of Birth <span class="text-danger">*</span></label>
                        <input type="text"  name="guarantor[pob][]" placeholder="{{ __('label.place_of_birth') }}"
                               value="{{ old('guarantor.pob.'.$i) }}">
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('guarantor.id_type.'.$i)) has-error @endif">
                        <label>Identify Type <span class="text-danger">*</span></label>
                        <select name="guarantor[id_type][]" >
                            <option value="">>> {{ __('label.identify_type') }} <<</option>
                            @foreach($idType as $key => $value)
                                @if(old('guarantor.id_type.'.$i) == $key)
                                    <option value="{{ $key }}" selected>{{ $value }}</option>
                                @else
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('guarantor.id_code.'.$i)) has-error @endif">
                        <label>Identify Code <span class="text-danger">*</span></label>
                        <input type="text" name="guarantor[id_code][]" placeholder="{{ __('label.id_type_code') }}"
                               value="{{ old('guarantor.id_code.'.$i) }}">
                    </div>
                </div> <!-- /.row -->

                <div class="row">
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('guarantor.gender.'.$i)) has-error @endif">
                        <label>Gender <span class="text-danger">*</span></label>
                        <select name="guarantor[gender][]" >
                            <option value="">>> {{ __('label.gender') }} <<</option>
                            @foreach($genders as $key => $value)
                                @if(old('guarantor.gender.'.$i) == $key)
                                    <option value="{{ $key }}" selected>{{ $value }}</option>
                                @else
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('guarantor.marital_status.'.$i)) has-error @endif">
                        <label>Marital Status <span class="text-danger">*</span></label>
                        <select name="guarantor[marital_status][]" >
                            <option value="">>> {{ __('label.marital_status') }} <<</option>
                            @foreach($marital_status as $key => $value)
                                @if(old('guarantor.marital_status.'.$i) == $key)
                                    <option value="{{ $key }}" selected>{{ $value }}</option>
                                @else
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('guarantor.career_id.'.$i)) has-error @endif">
                        <label>Occupation <span class="text-danger">*</span></label>
                        <select name="guarantor[career_id][]" class="form-control js-select2-single">
                            <option value="">>> {{ __('label.occupation') }} <<</option>
                            @foreach($occupations as $key => $value)
                                @if(old('guarantor.career_id.'.$i) == $key)
                                    <option value="{{ $key }}" selected>{{ $value }}</option>
                                @else
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('guarantor.related_id.'.$i)) has-error @endif">
                        <label>Relationship <span class="text-danger">*</span></label>
                        <select name="guarantor[related_id][]" >
                            <option value="">>> {{ __('label.relationship') }} <<</option>
                            @foreach($relationships as $key => $value)
                                @if(old('guarantor.related_id.'.$i) == $key)
                                    <option value="{{ $key }}" selected>{{ $value }}</option>
                                @else
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div> <!-- /.row -->

                <div class="row">
                    <div class="form-group col-sm-6 col-md-3">
                        <label>Province / Town / City</label>
                        <select name="guarantor[province_id][]" class="province_id form-control js-select2-single">
                            <option value="">>> {{ __('label.province') }} <<</option>
                            @foreach($provinces as $key => $value)
                                @if(old('guarantor.province_id.'.$i) == $value->id)
                                    <option value="{{ $value->id }}" selected>{{ $value->name_kh }}</option>
                                @else
                                    <option value="{{ $value->id }}">{{ $value->name_kh }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-6 col-md-3">
                        <label>District </label>
                        <select name="guarantor[district_id][]" class="district_id form-control js-select2-single">
                            <option value="">>> {{ __('label.district') }} <<</option>
                            @foreach($districts as $key => $value)
                                @if(old('guarantor.district_id.'.$i) == $value->id)
                                    <option value="{{ $value->id }}" selected>{{ $value->name_kh }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-6 col-md-3">
                        <label>Commune </label>
                        <select name="guarantor[commune_id][]" class="commune_id form-control js-select2-single">
                            <option value="">>> {{ __('label.commune') }} <<</option>
                            @foreach($communes as $key => $value)
                                @if(old('guarantor.commune_id.'.$i) == $value->id)
                                    <option value="{{ $value->id }}" selected>{{ $value->name_kh }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-6 col-md-3">
                        <label>Village </label>
                        <select name="guarantor[village_id][]" class="village_id form-control js-select2-single">
                            <option value="">>> {{ __('label.village') }} <<</option>
                            {{-- @foreach($villages as $key => $value)
                                @if(old('guarantor.village_id.'.$i) == $value->id)
                                    <option value="{{ $value->id }}" selected>{{ $value->name_kh }}</option>
                                @endif
                            @endforeach --}}
                        </select>
                    </div>
                </div> <!-- /.row -->

                <div class="row">
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('guarantor.house_no.'.$i)) has-error @endif">
                        <label>House Number </label>
                        <input type="text"  name="guarantor[house_no][]" placeholder="{{ __('label.house_no') }}"
                               value="{{ old('guarantor.house_no.'.$i) }}">
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('guarantor.street_no.'.$i)) has-error @endif">
                        <label>Street Number </label>
                        <input type="text"  name="guarantor[street_no][]" placeholder="{{ __('label.street_no') }}"
                               value="{{ old('guarantor.street_no.'.$i) }}">
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('guarantor.phone.'.$i)) has-error @endif">
                        <label>Mobile Number </label>
                        <input type="text"  name="guarantor[phone][]" placeholder="{{ __('label.mobile_no') }}"
                               value="{{ old('guarantor.phone.'.$i) }}">
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('guarantor.email.'.$i)) has-error @endif">
                        <label>Email </label>
                        <input type="text"  name="guarantor[email][]" placeholder="{{ __('label.email') }}"
                               value="{{ old('guarantor.email.'.$i) }}">
                    </div>
                </div> <!-- /.row -->

                <div class="row">
                    <div class="form-group col-sm-12 col-md-12 @if($errors->has('guarantor.other_location.'.$i)) has-error @endif">
                        <label>Other Location </label>
                        <input type="text"  name="guarantor[other_location][]" placeholder="{{ __('label.other_location') }}"
                               value="{{ old('guarantor.other_location.'.$i) }}">

                        <hr class="hr-border-bottom"> <!-- Don't remove it. if remove will error form when add new -->
                    </div>
                </div> <!-- /.row -->
            </div> <!-- /.child-guarantor -->
            @endforeach
        </form>
    </div> <!-- /.parent-guarantor -->

    <div class="col-sm-12 col-md-12">
        <div class="col-sm-12 col-md-12">
            <div class="pull-right margin-bottom">
                <a href="javascript:void(0);" class="btn btn-sm btn-danger btn-remove-guarantor hidden margin-r-5">
                    <i class="fa fa-remove"></i> REMOVE
                </a>
                <a href="javascript:void(0);" class="btn btn-sm btn-success btn-more-guarantor">
                    <i class="fa fa-plus-circle"></i> MORE
                </a>
            </div>
        </div>
    </div>
</div>