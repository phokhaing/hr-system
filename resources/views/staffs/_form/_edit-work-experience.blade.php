<div class="row">
    <div class="col-sm-12 col-md-12 parent-experience">

        <form action="{{ route('experience.update') }}" role="form" method="post" id="edit_form_experience">

            <div class="row margin-bottom">
                <div class="col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-success btn-sm margin-r-5" id="btnSave"><i class="fa fa-save"></i> UPDATE</button>
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

            {{--@foreach(range(0, $num_row-1) as $i)--}}
            @foreach($staff->experiences as $i => $experience)
            <div class="child-experience">
                <div class="row">
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('experience.company_name_kh.'.$i)) has-error @endif">
                        <label>Company Name KH <span class="text-danger">*</span></label>
                        <input type="text" name="experience[company_name_kh][]" placeholder="{{ __('label.company_kh') }}"
                        value="{{ $experience->company_name_kh }}" >
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('experience.company_name_en.'.$i)) has-error @endif"
                         value="{{ old('experience.company_name_en.'.$i) }}">
                        <label>Company Name EN <span class="text-danger">*</span></label>
                        <input type="text" name="experience[company_name_en][]" placeholder="{{ __('label.company_en') }}"
                               value="{{ $experience->company_name_en }}">
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('experience.position.'.$i)) has-error @endif">
                        <label>Position <span class="text-danger">*</span></label>
                        <input type="text" name="experience[position][]" class="form-control"
                               placeholder="{{ __('label.position') }}" value="{{ $experience->position }}">
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('experience.level_id.'.$i)) has-error @endif">
                        <label>Level <span class="text-danger">*</span></label>
                        <select name="experience[level_id][]">
                            <option value="">>> {{ __('label.level') }} <<</option>
                            @foreach($level_positions as $key => $value)
                                @if($experience->level_id == $value->id)
                                    <option value="{{ $value->id }}" selected>{{ $value->name_kh }}</option>
                                @else
                                    <option value="{{ $value->id }}">{{ $value->name_kh }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div> <!-- /.row -->

                <div class="row">
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('experience.start_date.'.$i)) has-error @endif">
                        <label for="w_start_date">Start Date <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control start_date" name="experience[start_date][]" readonly
                                   placeholder="{{ __('label.start_date') }}" value="{{ date('d-M-Y', strtotime($experience->start_date)) }}">
                        </div>
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('experience.end_date.'.$i)) has-error @endif">
                        <label for="w_end_date">End Date <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control end_date" name="experience[end_date][]" readonly
                                   placeholder="{{ __('label.end_date') }}" value="{{ date('d-M-Y', strtotime($experience->end_date)) }}">
                        </div>
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('experience.house_no.'.$i)) has-error @endif">
                        <label for="w_house_no">House Number </label>
                        <input type="text" name="experience[house_no][]" placeholder="{{ __('label.house_no') }}"
                               value="{{ $experience->house_no }}">
                    </div>
                    <div class="form-group col-sm-6 col-md-3 @if($errors->has('experience.street_no.'.$i)) has-error @endif">
                        <label for="w_street_no">Street Number </label>
                        <input type="text" name="experience[street_no][]" placeholder="{{ __('label.street_no') }}"
                               value="{{ $experience->street_no }}">
                    </div>
                </div> <!-- /.row -->

                <div class="row">
                    <div class="form-group col-sm-6 col-md-3 ">
                        <label for="w_province_id">Province / Town / City </label>
                        <select name="experience[province_id][]" class="province_id">
                            <option value="">>> {{ __('label.province') }} <<</option>
                            @foreach($provinces as $key => $value)
                                @if($experience->province_id == $value->id)
                                    <option value="{{ $value->id }}" selected>{{ $value->name_kh }}</option>
                                @else
                                    <option value="{{ $value->id }}">{{ $value->name_kh }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-6 col-md-9 @if($errors->has('experience.other_location.'.$i)) has-error @endif">
                        <label for="w_other_location">Other Location </label>
                        <input type="text" name="experience[other_location][]" placeholder="{{ __('label.other_location') }}"
                               value="{{ $experience->other_location }}">
                    </div>
                </div> <!-- /.row -->

                <div class="row">
                    <div class="form-group col-sm-12 col-md-12 @if($errors->has('experience.noted.'.$i)) has-error @endif">
                        <label for="w_noted">Note </label>
                        <textarea class="form-control" name="experience[noted][]" cols="30" rows="4" placeholder="{{ __('label.noted') }}">{{ $experience->noted }}</textarea>
                    </div>

                    <div class="col-sm-12 col-md-12">
                        <hr class="hr-border-bottom"> <!-- Don't remove it. if remove will error form when add new -->
                    </div>
                </div> <!-- /.row -->
            </div> <!-- /.child-experience -->
            @endforeach
            {{--@endforeach--}}
        </form>
    </div> <!-- /.parent-experience -->

    <div class="col-sm-12 col-md-12">
        <div class="col-sm-12 col-md-12">
            <div class="pull-right margin-bottom">
                <a href="javascript:void(0);" class="btn btn-sm btn-danger btn-remove-experience hidden margin-r-5">
                    <i class="fa fa-remove"></i> REMOVE
                </a>
                <a href="javascript:void(0);" class="btn btn-sm btn-success btn-more-experience">
                    <i class="fa fa-plus-circle"></i> MORE
                </a>
            </div>
        </div>
    </div>
</div>