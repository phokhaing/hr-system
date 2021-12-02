<div class="col-xs-6 col-md-6 col-lg-6 section_item" id="section_item">
    <div class="card" >
        <div class="">
            <h4>
                <b id="section_title">{{ @$section->title }}</b>
            </h4>

            <div>
                <i class="fa fa-tag" aria-hidden="true"></i>
                <span id="section_item_type" class="section_item_type">
                     {{ COURSE_CONTENT_TYPE_KEY[5] }}
                </span>
            </div>

            <p class="max-line" id="section_desc">{{ @$section->description ?: 'N/A' }}</p>

            <div class="large-bottom-space">

                <form action="{{ route('hrtraining::course_content.setting.delete', @$content_id) }}" method="post">
                    {{ Form::token() }}

                    <a href="#" id="edit_section" class="edit_section" data-toggle="modal"
                       data-target="#form-modal-edit-{{ @$key }}">
                                                                <span class="badge bg-danger">
                                                                     <i class="fa fa-edit"></i>
                                                                    Edit
                                                                </span>
                    </a>

                    <a href="javascript:void(0);">
                        <button
                                onclick="var x = confirm('Are you sure want to delete this content?'); if(x){this.form.submit();} else return false;"
                                style="border: 0;background: none"
                                class="">
                            <span class="badge bg-danger">
                                                                     <i class="fa fa-remove"></i>
                                                                    Remove
                                                                </span>
                        </button>
                    </a>
                </form>


            </div>
        </div>
    </div>
</div>