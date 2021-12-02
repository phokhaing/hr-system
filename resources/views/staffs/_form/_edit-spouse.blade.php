@php
$sp = @$staff->spouse;
@endphp
<div class="row">
    <div class="col-sm-12 col-md-12 parent-spouse">
        <form action="{{ route('spouse.update') }}" role="form" method="post" id="edit_form_spouse" enctype="multipart/form-data">

            <div class="row margin-bottom">
                <div class="col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-success btn-sm margin-r-5" id="btnSave"><i class="fa fa-save"></i> UPDATE</button>
                    <button type="reset" class="btn btn-danger btn-sm" id="btnDiscard"><i class="fa fa-remove"></i> DISCARD</button>
                </div>
            </div>

            {{ csrf_field() }}
            <input type="hidden" name="staff_token" value="{{ encrypt($staff->id) }}">
            <input type="hidden" name="spouse_id" value="{{ encrypt(@$sp->id) }}">
            <input type="hidden" name="num_row" class="num_row" value="{{ old('num_row') }}">

            @if(!empty(old('num_row')))
            <?php $num_row = old('num_row');  ?>
            @else
            <?php $num_row = 1; ?>
            @endif

            <div class="child-spouse">
                <div class="row">
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('full_name')) has-error @endif">
                        <label for="s_full_name">Spouse Full Name <span class="text-danger">*</span></label>
                        <input type="text" id="s_full_name" name="full_name" placeholder="{{ __('label.spouse_full_name') }}" value="{{ @$sp->full_name }}">
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('gender')) has-error @endif">
                        <label for="gender">Gender <span class="text-danger">*</span></label>
                        <select name="gender" id="gender" class="gender">
                            <option value="">>> {{ __('label.gender') }}
                                <<< /option>
                                    @foreach($genders as $key => $value)
                                    @if($sp->gender == $key)
                            <option value="{{ $key }}" selected>{{ $value }}</option>
                            @else
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('dob')) has-error @endif">
                        <label for="s_date_of_birth">Date Of Birth <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control dob" id="s_date_of_birth" name="dob" readonly placeholder="{{ __('label.date_of_birth') }}" value="{{ date('d-M-Y', strtotime($sp->dob)) }}">
                        </div>
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('children_no')) has-error @endif">
                        <label for="s_children_no">Child Number <span class="text-danger">*</span></label>
                        <input type="text" id="s_children_no" name="children_no" placeholder="{{ __('label.child_no') }}" value="{{ @$sp->children_no }}">
                    </div>
                </div> <!-- /.row -->

                <div class="row">
                    <div class="form-group col-sm-6 col-md-3">
                        <label for="s_province_id">Province / Town / City </label>
                        <select name="province_id" id="s_province_id" class="province_id form-control js-select2-single">
                            <option value="">>> {{ __('label.province') }}
                                <<< /option>
                                    @foreach($provinces as $key => $value)
                                    @if($sp->province_id == $value->id)
                            <option value="{{ $value->id }}" selected>{{ $value->name_kh }}</option>
                            @else
                            <option value="{{ $value->id }}">{{ $value->name_kh }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-6 col-md-3">
                        <label for="s_district_id">District </label>
                        <select name="district_id" id="s_district_id" class="district_id form-control js-select2-single">
                            <option value="">>> {{ __('label.district') }}
                                <<< /option>
                                    @foreach(\App\Unity::getDistrict(@@$sp->province_id) as $key => $value)
                                    @if(@$sp->district_id == $value->id)
                            <option value="{{ $value->id }}" selected> {{ $value->name_kh }} </option>
                            @else
                            <option value="{{ $value->id }}"> {{ $value->name_kh }} </option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-6 col-md-3">
                        <label for="s_commune_id">Commune </label>
                        <select name="commune_id" id="s_commune_id" class="commune_id form-control js-select2-single">
                            <option value="">>> {{ __('label.commune') }}
                                <<< /option>
                                    @foreach(\App\Unity::getCommune(@$sp->district_id) as $key => $value)
                                    @if(@$sp->commune_id == $value->id)
                            <option value="{{ $value->id }}" selected> {{ $value->name_kh }} </option>
                            @else
                            <option value="{{ $value->id }}"> {{ $value->name_kh }} </option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-6 col-md-3">
                        <label for="s_village_id">Village </label>
                        <select name="village_id" id="s_village_id" class="village_id form-control js-select2-single">
                            <option value="">>> {{ __('label.village') }}
                                <<< /option>
                                    @foreach(\App\Unity::getVillage(@$sp->commune_id) as $key => $value)
                                    @if(@$sp->village_id == $value->id)
                            <option value="{{ $value->id }}" selected> {{ $value->name_kh }} </option>
                            @else
                            <option value="{{ $value->id }}"> {{ $value->name_kh }} </option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div> <!-- /.row -->

                <div class="row">
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('house_no')) has-error @endif">
                        <label for="s_house_no">House Number </label>
                        <input type="text" class="form-control " id="s_house_no" name="house_no" placeholder="{{ __('label.house_no') }}" value="{{ @$sp->house_no }}">
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('street_no')) has-error @endif">
                        <label for="s_street_no">Street Number </label>
                        <input type="text" class="form-control " id="s_street_no" name="street_no" placeholder="{{ __('label.street_no') }}" value="{{ @$sp->street_no }}">
                    </div>
                    <div class="form-group col-sm-12 col-md-6 @if($errors->has('other_location')) has-error @endif">
                        <label for="s_other_location">Other Location </label>
                        <input type="text" class="form-control " id="s_other_location" name="other_location" placeholder="{{ __('label.other_location') }}" value="{{ @$sp->other_location }}">
                    </div>
                </div> <!-- /.row -->

                <div class="row">
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('phone')) has-error @endif">
                        <label for="s_phone">Mobile Number </label>
                        <input type="text" class="form-control " id="s_phone" name="phone" placeholder="{{ __('label.mobile_no') }}" value="{{ @$sp->phone }}">
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('spouse_tax')) has-error @endif">
                        <label for="s_spouse_tax">Spouse Tax <span class="text-danger">*</span></label>
                        <select name="spouse_tax" id="s_spouse_tax">
                            <option value="">>> {{ __('label.spouse_tax') }}
                                <<< /option>
                                    @foreach(@$spouses as $key => $value)
                                    @if(@$sp->spouse_tax == $key)
                            <option value="{{ $key }}" selected>{{ $value }}</option>
                            @else
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('children_tax')) has-error @endif">
                        <label for="s_children_tax">Number of Child Tax <span class="text-danger">*</span></label>
                        <input type="text" id="s_children_tax" name="children_tax" placeholder="{{ __('label.no_child_tax') }}" value="{{ @$sp->children_tax }}">
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('occupation_id')) has-error @endif">
                        <label for="s_spouse_tax">Spouse Occupation </label>
                        <select name="occupation_id" id="s_spouse_tax" class="form-control js-select2-single">
                            <option value="">>> {{ __('label.occupation') }}
                                <<< /option>
                                    @foreach($occupations as $key => $value)
                                    @if(@$sp->occupation_id == $key)
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