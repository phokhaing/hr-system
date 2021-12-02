<div class="modal fade in form-modal-edit-{{ $key }}" id="form-modal-edit-{{ $key }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Update Section</h4>
            </div>
            <div class="modal-body" id="section-body">
                <div class="row large-bottom-space">
                    <label class="col-sm-12 col-md-12">Content Type <span class="text-danger">*</span></label>
                    <div class="col-sm-12 col-md-12">
                        <select class="form-control select_content_type" name="select_content_type"
                                disabled
                                id="select_content_type"
                                data-live-search="true"
                                required>

                            @foreach(COURSE_CONTENT_TYPE_KEY as $key => $value)
                                <option value="{{ $key }}"
                                        data-value="{{ @$value }}" {{ @$key==@$section->content_type ? 'selected' : '' }}>{{
                                @$value }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>

                @if(@$section->content_type == COURSE_CONTENT_TYPE['LINK'])
                    @include('hrtraining::course_content_setting.forms._form_link', ['course' => @$course, 'section' => @$section, 'edit' => true, 'content_id' => @$content_id])

                @elseif(@$section->content_type == COURSE_CONTENT_TYPE['PLAIN_TEXT'])
                    @include('hrtraining::course_content_setting.forms._form_plain_text', ['course' => @$course, 'section' => @$section, 'edit' => true, 'content_id' => @$content_id])

                @elseif(@$section->content_type == COURSE_CONTENT_TYPE['SOUND'])
                    @include('hrtraining::course_content_setting.forms._form_sound', ['course' => @$course, 'section' => @$section, 'edit' => true, 'content_id' => @$content_id, 'key' => @$content_id])

                @elseif(@$section->content_type == COURSE_CONTENT_TYPE['IMAGE'])
                    @include('hrtraining::course_content_setting.forms._form_image', ['course' => @$course, 'section' => @$section, 'edit' => true, 'content_id' => @$content_id, 'key' => @$content_id])

                @elseif(@$section->content_type == COURSE_CONTENT_TYPE['PDF'])
                    @include('hrtraining::course_content_setting.forms._form_pdf', ['course' => @$course, 'section' => @$section, 'edit' => true, 'content_id' => @$content_id, 'key' => @$content_id])

                @endif()

            </div>
        </div>
    </div>
</div>