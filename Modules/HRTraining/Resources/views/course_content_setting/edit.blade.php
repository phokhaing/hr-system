@extends('adminlte::page')

@section('title', 'Modify Course Content')
@section('content')

@section('css')
    <style>

        .card {
            border-radius: 5px;
            border-left: 4px solid #0d6aad;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            margin-top: 16px;
            padding: 15px;
        }

        .card:hover {
            box-shadow: 0 6px 8px 0 rgba(0, 0, 0, 0.2);
        }

        .normal-top-space {
            margin-top: 6px;
        }

        .large-top-space {
            margin-top: 12px;
        }

        .large-bottom-space {
            margin-bottom: 12px;
        }

        .max-line {
            color: #636b6f;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 1; /* number of lines to show */
            -webkit-box-orient: vertical;
        }

        #section_remove {
            position: absolute;
            top: 25px;
            right: 20px;
        }

        #image_view {
            max-height: 256px;
            height: auto;
            width: auto;
            display: block;
            margin-left: auto;
            margin-right: auto;
            left: auto;
            right: auto;
        }

        #img_container {
            border-radius: 5px;
            margin-top: 12px;
            width: auto;
        }

        .input-group {
            margin-left: calc(calc(100vw - 100%) / 2);
            margin-top: 40px;
            width: 320px;
        }

        .imgInp {
            width: 150px;
            margin-top: 10px;
            padding: 10px;
            background-color: #d3d3d3;
        }

        .loading {
            animation: blinkingText ease 2.5s infinite;
        }

        @keyframes blinkingText {
            0% {
                color: #000;
            }
            50% {
                color: #transparent;
            }
            99% {
                color: transparent;
            }
            100% {
                color: #000;
            }
        }

        .custom-file-label {
            cursor: pointer;
        }

        .img_container {
            border-radius: 5px;
            margin-top: 12px;
            width: auto;
        }

        .image_view {
            max-height: 256px;
            height: auto;
            width: auto;
            display: block;
            /*margin-left: auto;*/
            /*margin-right: auto;*/
            /*left: auto;*/
            /*right: auto;*/
        }

        .course-desc-container {
            color: #636b6f;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3; /* number of lines to show */
            -webkit-box-orient: vertical;
            padding: 6px;
            border: 1px solid lightgrey;
            border-radius: 2px;
            min-height: 75px;
            margin-bottom: 20px;
        }

        /*.section_item {*/
        /*    height: 250px;*/
        /*}*/
    </style>
@endsection

@php
    $courseObj = @to_object(@$course->json_data);
    $contentObj = $course->contents;
@endphp

@include('partials.breadcrumb')

<div class="panel panel-default">

    <div class="panel-heading"><label for="">Modify Course Content (E-Library)</label></div>
    <div class="panel-body">
        {{ session()->get('message') }}
        <div class="row">
            <div class="nav-tabs-custom">

                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="row">
                            <div class="col-lg-12">
                                <h4 class="content-max-width"><i class="fa fa-book"></i> <b>{{ $courseObj->title }}</b>
                                </h4>

                                <p class="course-desc-container">
                                    {{ @$courseObj->description ?: 'N/A' }}
                                </p>

                                <button class="btn btn-sm btn-primary" id="btn-add-course-section" data-toggle="modal"
                                        data-target="#form-modal-create">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    Add New Section
                                </button>

                                <hr/>
                            </div>
                        </div>

                        <div class="row" id="section_item_container">
                            @if(@$contentObj)
                                @foreach(@$contentObj as $key => $value)

                                    @php
                                        $section = @to_object($value->json_data);
                                    @endphp

                                    @if($value->type == COURSE_CONTENT_TYPE['PLAIN_TEXT'])
                                        @include('hrtraining::course_content_setting.component.section_item_plain_text',
                                        ['section'
                                        => $section,
                                        'content_id' => @$value->id])

                                    @elseif($value->type == COURSE_CONTENT_TYPE['LINK'])
                                        @include('hrtraining::course_content_setting.component.section_item_link',
                                        ['section'
                                        => $section,
                                        'key' => @$key,
                                        'content_id' => @$value->id])

                                    @elseif($value->type == COURSE_CONTENT_TYPE['SOUND'])
                                        @include('hrtraining::course_content_setting.component.section_item_sound',
                                        ['section'
                                        => $section,
                                        'key' => @$key,
                                        'content_id' => @$value->id])

                                    @elseif($value->type == COURSE_CONTENT_TYPE['IMAGE'])
                                        @include('hrtraining::course_content_setting.component.section_item_image',
                                        ['section'
                                        => $section,
                                        'key' => @$key,
                                        'content_id' => @$value->id])

                                    @elseif($value->type == COURSE_CONTENT_TYPE['PDF'])
                                        @include('hrtraining::course_content_setting.component.section_item_pdf',
                                        ['section'
                                        => $section,
                                        'key' => @$key,
                                        'content_id' => @$value->id])

                                    @endif

                                    @include('hrtraining::course_content_setting.modals.form_edit', [
                                    'section' => @$section,
                                    'course' => @$course,
                                    'key' => @$key,
                                    'content_id' => @$value->id
                                    ])

                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div> <!-- .panel-default -->
@stop

@include('hrtraining::course_content_setting.modals.form_create', ['course' => $course])

@section('js')
    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <script>
        const TYPE_LINK = "<?=COURSE_CONTENT_TYPE['LINK']?>";
        const TYPE_VIDEO = "<?=COURSE_CONTENT_TYPE['VIDEO']?>";
        const TYPE_PLAIN_TEXT = "<?=COURSE_CONTENT_TYPE['PLAIN_TEXT']?>";
        const TYPE_IMAGE = "<?=COURSE_CONTENT_TYPE['IMAGE']?>";
        const TYPE_SOUND = "<?=COURSE_CONTENT_TYPE['SOUND']?>";
        const TYPE_PDF = "<?=COURSE_CONTENT_TYPE['PDF']?>";

        $(document).ready(function (e) {
            setPlaintextEditor();
            const FORM_PLAIN_TEXT = $('#form-container form.form-plain-text').clone();
            const FORM_LINK = $('#form-container form.form-link').clone();
            const FORM_SOUND = $('#form-container form.form-sound-0').clone();
            const FORM_IMAGE = $('#form-container form.form-image-0').clone();
            const FORM_PDF = $('#form-container form.form-pdf-0').clone();
            $('#form-container form.form-link').remove();
            $('#form-container form.form-sound-0').remove();
            $('#form-container form.form-image-0').remove();
            $('#form-container form.form-pdf-0').remove();

            $("select.select_content_type").change(function (e) {
                const selectedType = $(this).find(':selected').val();
                console.log("selectedType: " + selectedType);
                showHideForm(selectedType,
                    FORM_PLAIN_TEXT,
                    FORM_LINK,
                    FORM_SOUND,
                    FORM_IMAGE,
                    FORM_PDF);
            });

            function showHideForm(selectedType, formPlainText, formLink, formSound, formImage, formPdf) {
                if (selectedType === TYPE_LINK) {
                    $('#form-container').empty().append(formLink);
                } else if (selectedType === TYPE_PLAIN_TEXT) {
                    $('#form-container').empty().append(formPlainText);
                } else if (selectedType === TYPE_SOUND) {
                    $('#form-container').empty().append(formSound);
                } else if (selectedType === TYPE_IMAGE) {
                    $('#form-container').empty().append(formImage);
                } else if (selectedType === TYPE_PDF) {
                    $('#form-container').empty().append(formPdf);
                }
            }

            function setPlaintextEditor() {
                $('.section_content').summernote({
                    tabsize: 2,
                    height: 120
                });
            }
        });

        function previewFile(input, formKey) {
            console.log('formkey: ' + formKey);
            let formEditKey = $(".form-sound-" + formKey);
            let file = formEditKey.find("input[type=file]").get(0).files[0];
            if (file) {
                formEditKey.find(".section_audio_preview").attr("src", URL.createObjectURL(file));
            }
        }

        function previewImage(input, formKey) {
            console.log('formkey: ' + formKey);
            let formEditKey = $(".form-image-" + formKey);
            let file = formEditKey.find("input[type=file]").get(0).files[0];
            console.log('previewImage: ' + file);
            if (file) {
                let reader = new FileReader();
                reader.onload = function () {
                    formEditKey.find(".section_image_preview").attr("src", reader.result);
                };
                reader.readAsDataURL(file);
            }
        }

        function previewPdf(input, formKey) {
            console.log('formkey: ' + formKey);
            let formEditKey = $(".form-pdf-" + formKey);
            let file = formEditKey.find("input[type=file]").get(0).files[0];
            console.log('previewPdf: ' + file);
            if (file) {
                formEditKey.find(".preview_pdf_full_scree").attr("href", URL.createObjectURL(file));
            }

        }
    </script>
@endsection
