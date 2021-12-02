@php
    $route = @$edit ? route('hrtraining::course_content.setting.update') : route('hrtraining::course_content.setting.store');
@endphp

<form action="{{ $route }}"
      method="post"
      class="form-image-{{ @$key }}"
      role="form"
      enctype="multipart/form-data"
      id="form-image-{{ @$key }}">

    <div class="row">

        {{ csrf_field() }}

        <input type="hidden" name="course_id" id="course_id" class="course_id" value="{{ @$course->id }}"/>
        <input type="hidden" name="content_type" value="{{ COURSE_CONTENT_TYPE['IMAGE'] }}" id="content_type"
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

        <label class="col-sm-12 col-md-12 large-top-space">Upload Image <span class="text-danger">*</span></label>
        <div class="col-sm-12 col-md-12">
            <input type="file" id="section_photo" name="section_photo" accept="image/*"
                   onchange="previewImage(this, {{ @$key }});"
                   class="form-control section_photo"
                    {{ @$edit ? '' : 'required' }}/>
        </div>

        <div class="col-sm-12 col-md-12">
            <div id="img_container">
                <img id="section_image_preview" class="image_view section_image_preview"
                     src="{{ @$section->image_path ? Storage::url(@$section->image_path) : url('/images/image-gallery.png') }}"
                     alt="your image" title=''/>
            </div>
        </div>
    </div>

    <div class="row margin-bottom large-top-space">
        <div class="col-sm-12 col-md-12">
            <button type="submit" class="btn btn-info margin-r-5" id="btn-add-link">
                {{ @$edit ? 'Update' : 'Create' }}
            </button>
        </div>
    </div>
</form>