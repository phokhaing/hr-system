<div class="modal fade in form-create" id="form-modal-create" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add New Section</h4>
            </div>
            <div class="modal-body" id="section-body">
                <div class="row large-bottom-space">
                    <label class="col-sm-12 col-md-12">Content Type <span class="text-danger">*</span></label>
                    <div class="col-sm-12 col-md-12">
                        <select class="form-control select_content_type" name="select_content_type"
                                id="select_content_type"
                                data-live-search="true"
                                required>

                            @foreach(COURSE_CONTENT_TYPE_KEY as $key => $value)
                                <option value="{{ $key }}" data-value="{{ @$value }}" {{ @$key<1 ? 'selected' : '' }}>{{
                                @$value }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>

                <div class="form-container" id="form-container">
                    @include('hrtraining::course_content_setting.forms._form_link', ['course' => @$course, 'section' => null, 'edit' => false])
                    @include('hrtraining::course_content_setting.forms._form_plain_text', ['course' => @$course, 'section' => null, 'edit' => false])
                    @include('hrtraining::course_content_setting.forms._form_sound', ['course' => @$course, 'section' => null, 'edit' => false, 'key' => 0])
                    @include('hrtraining::course_content_setting.forms._form_image', ['course' => @$course, 'section' => null, 'edit' => false, 'key' => 0])
                    @include('hrtraining::course_content_setting.forms._form_pdf', ['course' => @$course, 'section' => null, 'edit' => false, 'key' => 0])
                </div>

            </div>
        </div>
    </div>
</div>