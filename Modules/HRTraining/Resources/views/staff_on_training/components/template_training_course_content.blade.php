@php
    $currentContentObj = to_object(@$current_content->json_data);
@endphp
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-title">
            <div class="panel-heading">
                <label>
                    Course Sections
                </label>
            </div>

            <div class="panel-body">
                @foreach(@$course_contents as $key => $value)
                    @php
                        $contentObj = to_object(@$value->json_data);
                    @endphp

                    <blockquote>
                        <p>
                            <a href="{{ route('hrtraining::staff_on_training.start_training', ['enrollment_id' => @request('enrollment_id'), 'content_id' => @$value->id]) }}"
                               class="{{ @$current_content->id == @$value->id ? 'text-red' : '' }}">
                                <i class="fa fa-play-circle" aria-hidden="true" style="margin-right: 6px;"> </i>
                                {{ @$contentObj->title }}
                            </a>
                        </p>
                        <small class="max-line">
                            {{ @$contentObj->description ?: 'N/A' }}
                        </small>
                    </blockquote>

                @endforeach
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                        <label>
                            {{ @$currentContentObj->title }}
                        </label>
                    </div>

                    <div class="col-md-4 text-right">
                        @include('hrtraining::staff_on_training.components.layout_course_pagination', [
                           'nextCourseContentId' => @$nextCourseContentId,
                           'previousCourseContentId' => @$previousCourseContentId,
                           'enrollmentId' => @request('enrollment_id')
                        ])
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <div style="margin-bottom: 20px;">
                      <span class="badge bg-yellow">
                            <i class="i fa fa-tag"></i>
                            {{ COURSE_CONTENT_TYPE_KEY[@$current_content->type] }}
                      </span>
                    <hr/>
                </div>

                @if(@$current_content->type == COURSE_CONTENT_TYPE['PLAIN_TEXT'])
                    <?= @$currentContentObj->plain_text ?>

                @elseif(@$current_content->type == COURSE_CONTENT_TYPE['LINK'])
                    <label>Please click on link below to detail about this section:</label>
                    <div>
                        <a href="{{ @$currentContentObj->link }}" target="_blank">
                            <h4>
                                {{ @$currentContentObj->link }}
                            </h4>
                        </a>
                    </div>


                @elseif(@$current_content->type == COURSE_CONTENT_TYPE['IMAGE'])
                    <img class="img-responsive" src="{{ Storage::url(@$currentContentObj->image_path) }}"/>

                @elseif(@$current_content->type == COURSE_CONTENT_TYPE['SOUND'])
                    <label>Please click on audio below to detail about this section:</label>
                    <div>
                        <audio controls src="{{ Storage::url(@$currentContentObj->sound_path) }}" id="audio"></audio>
                    </div>

                @elseif(@$current_content->type == COURSE_CONTENT_TYPE['PDF'])
                    <label>Click on Button to View PDF in Fullscreen: </label>
                    <a style="margin-top: 20px; margin-bottom: 20px;" id="preview_pdf_full_scree" class="preview_pdf_full_scree btn btn-sm btn-success" target="_blank" href="{{ Storage::url(@$currentContentObj->pdf_path) }}">
                        <i class="fa fa-eye"></i>
                        View PDF
                    </a>
                    <div>
                        <embed style='border:2px solid grey;' src="{{ Storage::url(@$currentContentObj->pdf_path) }}" width="100%" height="500px"
                               type="application/pdf">
                    </div>
                @endif

                {{--                Last Section Course Content--}}
                @if(!@$nextCourseContentId)
                    <hr/>

                    <h5>
                        This is a last section for this training course. Please click on button below to complete your
                        training and start taking an exam!
                    </h5>

                    <form action="{{route('hrtraining::staff_on_training.complete_my_training')}}" method="post"
                          id="form-complete-my-training">
                        {{ Form::token() }}
                        <input type="hidden" name="enrollment_id" value="{{ @$enrollment_id }}"/>
                        <button class="btn btn-sm btn-danger" id="btn-complete-training" type="button">
                            Complete My Training
                        </button>

                    </form>
                @endif
            </div>
        </div>
    </div>
</div>