<div class="row">
    <div class="col-sm-12 col-md-12 parent-training">

        <form action="{{ route('training.update') }}" method="post" role="form" id="edit_form_training">

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
            @foreach($staff->trainings as $i => $training)
                <div class="child-training">
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-3 @if($errors->has('training.subject.'.$i)) has-error @endif">
                            <label>Subject Name <span class="text-danger">*</span></label>
                            <input type="text" name="training[subject][]" placeholder="{{ __('label.subject_name') }}"
                                   value="{{ $training->subject }}">
                        </div>
                        <div class="form-group col-sm-6 col-md-3 @if($errors->has('training.place.'.$i)) has-error @endif">
                            <label>Training Place <span class="text-danger">*</span></label>
                            <input type="text" name="training[place][]" placeholder="{{ __('label.training_place') }}" value="{{ $training->place }}">
                        </div>
                        <div class="form-group col-sm-6 col-md-3 @if($errors->has('training.school.'.$i)) has-error @endif">
                            <label>School / Institute / University <span class="text-danger">*</span></label>
                            <input type="text" name="training[school][]" placeholder="{{ __('label.school_name') }}" value="{{ $training->school }}">
                        </div>
                        <div class="form-group col-sm-6 col-md-3">
                            <label>Province / Town / City </label>
                            <select name="training[province_id][]" class="province_id">
                                <option value="">>> {{ __('label.province') }} <<</option>
                                @foreach($provinces as $key => $value)
                                    @if($training->province_id == $value->id)
                                        <option value="{{ $value->id }}" selected>{{ $value->name_kh }}</option>
                                    @else
                                        <option value="{{ $value->id }}">{{ $value->name_kh }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div> <!-- /.row -->

                    <div class="row">
                        <div class="form-group col-sm-6 col-md-3 @if($errors->has('training.start_date.'.$i)) has-error @endif">
                            <label>Start Date <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="pull-right start_date" name="training[start_date][]" readonly
                                       placeholder="{{ __('label.start_date') }}" value="{{ date('d-M-Y', strtotime($training->start_date)) }}" >
                            </div>
                        </div>
                        <div class="form-group col-sm-6 col-md-3 @if($errors->has('training.end_date.'.$i)) has-error @endif">
                            <label>End Date <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="pull-right end_date" name="training[end_date][]" readonly
                                       placeholder="{{ __('label.end_date') }}" value="{{ date('d-M-Y', strtotime($training->end_date)) }}" >
                            </div>
                        </div>
                        <div class="form-group col-sm-6 col-md-6 @if($errors->has('training.other_location.'.$i)) has-error @endif">
                            <label>Other Location </label>
                            <input type="text" class="pull-right" name="training[other_location][]" placeholder="{{ __('label.other_location') }}"
                                   value="{{ $training->other_location }}">
                        </div>
                    </div> <!-- /.row -->
                    <div class="row">
                        <div class="col-sm-12 col-md-12 margin-bottom @if($errors->has('training.description.'.$i)) has-error @endif">
                            <label>Descriptions </label>
                            <textarea name="training[description][]" rows="4" placeholder="{{ __('label.description') }}"
                                      class="form-control">{{ $training->description }}</textarea>
                            <hr class="hr-border-bottom"> <!-- Don't remove it. if remove will error form when add new -->
                        </div>
                    </div> <!-- /.row -->
                </div> <!-- /.child-training -->
            @endforeach
            {{--@endforeach--}}
        </form>
    </div> <!-- /.parent-training -->

    <div class="col-sm-12 col-md-12">
        <div class="col-sm-12 col-md-12">
            <div class="pull-right margin-bottom">
                <a href="javascript:void(0);" class="btn btn-sm btn-danger btn-remove-training hidden margin-r-5">
                    <i class="fa fa-remove"></i> REMOVE
                </a>
                <a href="javascript:void(0);" class="btn btn-sm btn-success btn-more-training">
                    <i class="fa fa-plus-circle"></i> MORE
                </a>
            </div>
        </div>
    </div>
</div>