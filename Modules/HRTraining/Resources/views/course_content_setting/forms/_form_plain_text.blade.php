@php
    $route = @$edit ? route('hrtraining::course_content.setting.update') : route('hrtraining::course_content.setting.store');
@endphp
<form action="{{ $route }}"
      method="post"
      class="form-plain-text"
      id="form-plain-text">

    <div class="row">

        {{ csrf_field() }}

        <input type="hidden" name="course_id" id="course_id" class="course_id" value="{{ @$course->id }}"/>
        <input type="hidden" name="content_type" value="{{ COURSE_CONTENT_TYPE['PLAIN_TEXT'] }}"
               id="content_type"
               class="content_type"/>
        <input type="hidden" name="content_id" id="content_id" value="{{ @$content_id }}"/>

        <label class="col-sm-12 col-md-12">Section Title <span class="text-danger">*</span></label>
        <div class="col-sm-12 col-md-12">
            <input type="text" required name="section_title" id="section_title"
                   value="{{ @$section->title }}"
                   class="form-control section_title"/>
        </div>

        <label class="col-sm-12 col-md-12 large-top-space">Description</label>
        <div class="col-sm-12 col-md-12">
                            <textarea id="section_desc" name="section_desc"
                                      rows="4"
                                      class="form-control section_desc">{{ @$section->description }}</textarea>
        </div>

        <label class="col-sm-12 col-md-12" style="margin-top: 12px">Section Content <span
                    class="text-danger">*</span></label>
        <div class="col-sm-12 col-md-12">
                            <textarea id="section_content" name="section_content" required
                                      class="form-control section_content">

                                {{ @$section->plain_text }}

                            </textarea>
        </div>

    </div>

    <div class="row margin-bottom">
        <div class="col-sm-12 col-md-12">
            <button type="submit" class="btn btn-info margin-r-5" id="btn-save-plain-text">
                {{ @$edit ? 'Update' : 'Create' }}
            </button>
        </div>
    </div>
</form>