@php
    $route = @$edit ? route('hrtraining::course_content.setting.update') : route('hrtraining::course_content.setting.store');
@endphp

<form action="{{ $route }}"
      method="post"
      class="form-pdf-{{ @$key }}"
      role="form"
      enctype="multipart/form-data"
      id="form-pdf-{{ @$key }}">

    <div class="row">

        {{ csrf_field() }}

        <input type="hidden" name="course_id" id="course_id" class="course_id" value="{{ @$course->id }}"/>
        <input type="hidden" name="content_type" value="{{ COURSE_CONTENT_TYPE['PDF'] }}" id="content_type"
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

        <label class="col-sm-12 col-md-12 large-top-space">Upload PDF <span class="text-danger">*</span></label>
        <div class="col-sm-12 col-md-12">
            <input type="file" id="section_pdf" name="section_pdf" accept="application/pdf"
                   class="form-control section_pdf"
                   onchange="previewPdf(this, {{ @$key }});"
                    {{ @$edit ? '' : 'required' }}/>
        </div>

        <div class="col-sm-12 col-md-12">

            <a style="margin-top: 20px; margin-bottom: 20px;" id="preview_pdf_full_scree" class="preview_pdf_full_scree btn btn-sm btn-default" target="_blank" href="{{ Storage::url(@$section->pdf_path) }}">
                <i class="fa fa-eye"></i>
                View PDF
            </a>
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