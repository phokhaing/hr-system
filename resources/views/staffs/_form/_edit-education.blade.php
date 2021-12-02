<div class="row">
    <div class="col-sm-12 col-md-12 parent-education">
        <form action="{{ route('education.update') }}" role="form" method="post" id="edit_form_education">

            <div class="row margin-bottom">
                <div class="col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-success btn-sm margin-r-5" id="btnSave"><i class="fa fa-save"></i> UPDATE</button>
                    <button type="reset" class="btn btn-danger btn-sm margin-r-5" id="btnDiscard"><i class="fa fa-remove"></i> DISCARD</button>
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
                @foreach($staff->educations as $i => $education)
                    <div class="child-education">
                        <div class="row">
                            <div class="form-group col-sm-6 col-md-3 @if($errors->has('education.school_name.'.$i)) has-error @endif">
                                <label>School Name <span class="text-danger">*</span></label>
                                <input type="text" name="education[school_name][]" placeholder="{{ __('label.school_name') }}"
                                       value="{{ $education->school_name }}">
                            </div>
                            <div class="form-group col-sm-6 col-md-3 @if($errors->has('education.subject.'.$i)) has-error @endif">
                                <label for="e_subject">Subject Name <span class="text-danger">*</span></label>
                                <input type="text" name="education[subject][]" placeholder="{{ __('label.subject_name') }}"
                                       value="{{ $education->subject }}">
                            </div>
                            <div class="form-group col-sm-6 col-md-3">
                                <label>Degree </label>
                                <select name="education[degree_id][]">
                                    <option value="">>> {{ __('label.degree') }} <<</option>
                                    @foreach($degree as $key => $value)
                                        @if($education->degree_id == $key)
                                            <option value="{{ $key }}" selected>{{ $value }}</option>
                                        @else
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-6 col-md-3">
                                <label>Year </label>
                                <select name="education[study_year][]" value="{{ old('education.study_year.'.$i) }}">
                                    <option value="">>> {{ __('label.study_year') }} <<</option>
                                    @foreach($study_year as $key => $value)
                                        @if($education->study_year == $key)
                                            <option value="{{ $key }}" selected>{{ $value }}</option>
                                        @else
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-6 col-md-3 @if($errors->has('education.start_date.'.$i)) has-error @endif">
                                <label>Start Date <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="start_date" name="education[start_date][]" readonly
                                           placeholder="{{ __('label.start_date') }}" value="{{ date('d-M-Y', strtotime($education->start_date)) }}" >
                                </div>
                            </div>
                            <div class="form-group col-sm-6 col-md-3 @if($errors->has('education.end_date.'.$i)) has-error @endif">
                                <label>End Date <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="end_date" name="education[end_date][]" readonly
                                           placeholder="{{ __('label.end_date') }}" value="{{ date('d-M-Y', strtotime($education->end_date)) }}" >
                                </div>
                            </div>
                            <div class="form-group col-sm-6 col-md-3">
                                <label>Province / Town / City</label>
                                <select name="education[province_id][]" class="province_id">
                                    <option value="">>> {{ __('label.province') }} <<</option>
                                    @foreach($provinces as $key => $value)
                                        @if($education->province_id == $value->id)
                                            <option value="{{ $value->id }}" selected>{{ $value->name_kh }}</option>
                                        @else
                                            <option value="{{ $value->id }}">{{ $value->name_kh }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-6 col-md-3">
                                <label>Other Location </label>
                                <input type="text" name="education[other_location][]" placeholder="{{ __('label.other_location') }}"
                                       value="{{ $education->other_location }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12">
                                <label>Note </label>
                                <textarea type="text" name="education[noted][]" rows="4" placeholder="{{ __('label.noted') }}"
                                          class="form-control" >{{ $education->noted }}</textarea>

                                <hr class="hr-border-bottom"> <!-- Don't remove hr element -->
                            </div>
                        </div>
                    </div> <!-- /.child-education -->
                @endforeach
            {{--@endforeach--}}
        </form>

    </div> <!-- ./col-12 .parent-education -->

    <div class="col-sm-12 col-md-12">
        <div class="pull-right margin-bottom">
            <a href="javascript:void(0);" class="btn btn-sm btn-danger btn-remove-education hidden margin-r-5">
                <i class="fa fa-remove"></i> REMOVE
            </a>
            <a href="javascript:void(0);" class="btn btn-sm btn-success btn-more-education">
                <i class="fa fa-plus-circle"></i> MORE
            </a>
        </div>
    </div>
</div> <!-- /.row -->