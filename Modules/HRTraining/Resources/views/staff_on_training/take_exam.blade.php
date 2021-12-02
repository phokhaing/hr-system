@extends('adminlte::page')

@section('title', 'Take an Examination')

@section('content')

@section('css')
    <style>
    </style>
@endsection

@php
    $courseObj = to_object(@$course->json_data);
    $examObj = to_object(@$exam->json_data);
    $formRoute = route('hrtraining::staff_on_training.take_exam.save_continue');
@endphp

@include('partials.breadcrumb')

<div class="panel panel-default">
    <div class="panel-heading">
        <h4>
            <i class="fa fa-book"></i>
            {{ @$courseObj->title }}
        </h4>
    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">

            <dt>Total Exams:</dt>
            <dd>
               <span class="pull-right-container">
                   <small class="label pull-left bg-yellow">
                       {{ @$countExamInCurrentCourse }}
                   </small>
               </span>
            </dd>

            <dt>Course Section:</dt>
            <dd>
                {{ @$exam->courseContent->json_data->title }}
            </dd>

            <dt>Description:</dt>
            <dd>
                {{ @$exam->json_data->description }}
            </dd>

            <dt>Total Point:</dt>
            <dd>
                {{ @$exam->json_data->grade }}
            </dd>

            <dt>Duration:</dt>
            <dd>
                {{ @$exam->json_data->duration }}
            </dd>
        </dl>

    </div>
</div>

<div class="panel panel-default">

    <div class="panel-body">

        <form
                action="{{ @$formRoute }}"
                method="post">

            {{ Form::token() }}

            <input type="hidden" name="current_exam_id" value="{{ @$exam->id }}">
            <input type="hidden" name="course_id" value="{{ @$course->id }}">
            <input type="hidden" name="enrollment_id" value="{{ @$enrollment->id }}">
            <input type="hidden" name="is_last_exam" value="{{ $isLastExam }}"/>

            <div class="row">
                <div class="col-md-12">

                    <ul class="timeline">
                        @foreach(@$exam->questionAnswers as $key => $question)
                            <li style="margin-bottom: 50px">
                                <i class="fa bg-blue">{{ $key+=1 }}</i>

                                <input type="hidden" name="question_ids[]" value="{{ @$question->id }}"/>
                                <input type="hidden" name="question_types[]" value="{{ @$question->json_data->type }}"/>

                                @if($question->json_data->type == QUESTION_TYPE['OPEN']['value'])
                                    @include('hrtraining::staff_on_training.components.open_question_layout', ['question' => @$question])

                                @elseif($question->json_data->type == QUESTION_TYPE['CLOSE']['value'])
                                    @include('hrtraining::staff_on_training.components.close_question_layout', ['question' => @$question])

                                @elseif($question->json_data->type == QUESTION_TYPE['MULTIPLE-CHOICE']['value'])
                                    @include('hrtraining::staff_on_training.components.multiple_choice_question_layout', ['question' => @$question])

                                @endif
                            </li>
                        @endforeach

                    </ul>

                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <button type="submit" class="btn btn-danger">
                        {{ @$isLastExam ? 'Completed' : 'Save and Continue' }}
                    </button>
                </div>
            </div>
        </form>

    </div>

</div>
</div>

@endsection

@section('js')
    <script>
    </script>
@endsection