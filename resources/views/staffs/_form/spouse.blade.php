<div class="row">
    <div class="col-sm-12 col-md-12 parent-spouse">
        <form action="{{ route('spouse.store') }}" role="form" method="post" id="edit_form_spouse" enctype="multipart/form-data">

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
                <div class="child-spouse">
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-3 @if($errors->has('spouse.full_name.'.$i)) has-error @endif">
                            <label for="s_full_name">Spouse Full Name <span class="text-danger">*</span></label>
                            <input type="text"  id="s_full_name" name="spouse[full_name][]" placeholder="{{ __('label.spouse_full_name') }}"
                            value="{{ old('spouse.full_name.'.$i) }}">
                        </div>
                        <div class="form-group col-sm-6 col-md-3 @if($errors->has('spouse.gender.'.$i)) has-error @endif">
                            <label for="s_province_id">Gender <span class="text-danger">*</span></label>
                            <select name="spouse[gender][]" id="gender" class="gender">
                                <option value="">>> {{ __('label.gender') }} <<</option>
                                @foreach($genders as $key => $value)
                                    @if(old('spouse.gender.'.$i) == $key)
                                        <option value="{{ $key }}" selected>{{ $value }}</option>
                                    @else
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6 col-md-3 @if($errors->has('spouse.dob.'.$i)) has-error @endif">
                            <label for="s_date_of_birth">Date Of Birth <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control dob" id="s_date_of_birth" name="spouse[dob][]"
                                       placeholder="{{ __('label.date_of_birth') }}" readonly value="{{ old('spouse.spouse.dob.'.$i) }}">
                            </div>
                        </div>
                        <div class="form-group col-sm-6 col-md-3 @if($errors->has('spouse.children_no.'.$i)) has-error @endif">
                            <label for="s_children_no">Child Number <span class="text-danger">*</span></label>
                            <input type="text"  id="s_children_no" name="spouse[children_no][]" placeholder="{{ __('label.child_no') }}"
                                   value="{{ old('spouse.children_no.'.$i) }}">
                        </div>
                    </div> <!-- /.row -->

                    <div class="row">
                        <div class="form-group col-sm-6 col-md-3">
                            <label for="s_province_id">Province / Town / City </label>
                            <select name="spouse[province_id][]" id="s_province_id" class="province_id form-control js-select2-single">
                                <option value="">>> {{ __('label.province') }} <<</option>
                                @foreach($provinces as $key => $value)
                                    @if(old('spouse.province_id.'.$i) == $value->id)
                                        <option value="{{ $value->id }}" selected>{{ $value->name_kh }}</option>
                                    @else
                                        <option value="{{ $value->id }}">{{ $value->name_kh }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6 col-md-3">
                            <label for="s_district_id">District </label>
                            <select name="spouse[district_id][]" id="s_district_id" class="district_id form-control js-select2-single">
                                <option value="">>> {{ __('label.district') }} <<</option>
                                @foreach($districts as $key => $value)
                                    @if(old('spouse.district_id.'.$i) == $value->id)
                                        <option value="{{ $value->id }}" selected>{{ $value->name_kh }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6 col-md-3">
                            <label for="s_commune_id">Commune </label>
                            <select name="spouse[commune_id][]" id="s_commune_id" class="commune_id form-control js-select2-single">
                                <option value="">>> {{ __('label.commune') }} <<</option>
                                @foreach($communes as $key => $value)
                                    @if(old('spouse.commune_id.'.$i) == $value->id)
                                        <option value="{{ $value->id }}" selected>{{ $value->name_kh }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6 col-md-3">
                            <label for="s_village_id">Village </label>
                            <select name="spouse[village_id][]" id="s_village_id" class="village_id form-control js-select2-single">
                                <option value="">>> {{ __('label.village') }} <<</option>
                            </select>
                        </div>
                    </div> <!-- /.row -->

                    <div class="row">
                        <div class="form-group col-sm-6 col-md-3 @if($errors->has('spouse.house_no.'.$i)) has-error @endif">
                            <label for="s_house_no">House Number </label>
                            <input type="text" class="form-control " id="s_house_no" name="spouse[house_no][]"
                                   placeholder="{{ __('label.house_no') }}" value="{{ old('spouse.house_no.'.$i) }}">
                        </div>
                        <div class="form-group col-sm-6 col-md-3 @if($errors->has('spouse.street_no.'.$i)) has-error @endif">
                            <label for="s_street_no">Street Number </label>
                            <input type="text" class="form-control " id="s_street_no" name="spouse[street_no][]"
                                   placeholder="{{ __('label.street_no') }}" value="{{ old('spouse.street_no.'.$i) }}">
                        </div>
                        <div class="form-group col-sm-12 col-md-6 @if($errors->has('spouse.other_location.'.$i)) has-error @endif">
                            <label for="s_other_location">Other Location </label>
                            <input type="text" class="form-control " id="s_other_location" name="spouse[other_location][]"
                                   placeholder="{{ __('label.other_location') }}" value="{{ old('spouse.other_location.'.$i) }}">
                        </div>
                    </div> <!-- /.row -->

                    <div class="row">
                        <div class="form-group col-sm-6 col-md-3 @if($errors->has('spouse.phone.'.$i)) has-error @endif">
                            <label for="s_phone">Mobile Number </label>
                            <input type="text" class="form-control " id="s_phone" name="spouse[phone][]"
                                   placeholder="{{ __('label.mobile_no') }}" value="{{ old('spouse.phone.'.$i) }}">
                        </div>
                        <div class="form-group col-sm-6 col-md-3 @if($errors->has('spouse.spouse_tax.'.$i)) has-error @endif">
                            <label for="s_spouse_tax">Spouse Tax <span class="text-danger">*</span></label>
                            <select name="spouse[spouse_tax][]" id="s_spouse_tax" >
                                <option value="">>> {{ __('label.spouse_tax') }} <<</option>
                                @foreach($spouses as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6 col-md-3 @if($errors->has('spouse.children_tax.'.$i)) has-error @endif">
                            <label for="s_children_tax">Number of Child Tax <span class="text-danger">*</span></label>
                            <input type="text"  id="s_children_tax" name="spouse[children_tax][]" placeholder="{{ __('label.no_child_tax') }}"
                                   value="{{ old('spouse.children_tax.'.$i) }}">
                        </div>
                        <div class="form-group col-sm-6 col-md-3 @if($errors->has('spouse.occupation_id.'.$i)) has-error @endif">
                            <label for="s_spouse_tax">Spouse Occupation <span class="text-danger">*</span></label>
                            <select name="spouse[occupation_id][]" id="s_spouse_tax" class="form-control js-select2-single">
                                <option value="">>> {{ __('label.occupation') }} <<</option>
                                @foreach($occupations as $key => $value)
                                    @if(old('spouse.occupation_id.'.$i) == $key)
                                        <option value="{{ $key }}" selected>{{ $value }}</option>
                                    @else
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <hr class="hr-border-bottom"> <!-- Don't remove it. if remove will error form when add new -->
                        </div>
                    </div>
                </div>
            @endforeach
        </form>
    </div> <!-- /.col-12 -->
    <!-- <div class="col-sm-12 col-md-12">
        <div class="col-sm-12 col-md-12">
            <div class="pull-right margin-bottom">
                <a href="javascript:void(0);" class="btn btn-sm btn-danger btn-remove-spouse hidden margin-r-5">
                    <i class="fa fa-remove"></i> REMOVE
                </a>
                <a href="javascript:void(0);" class="btn btn-sm btn-success btn-more-spouse">
                    <i class="fa fa-plus-circle"></i> MORE
                </a>
            </div>
        </div>
    </div> -->
</div> <!-- /.row -->