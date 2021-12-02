@extends('adminlte::page')

@section('title', 'Review Trainee Exam')

@section('content')

@section('css')
    <style>
        .modal-body {
            max-width: 100%;
            overflow-x: auto;
        }
    </style>

@endsection

@include('partials.breadcrumb')
@php
    $enrollmentObj = to_object($enrollment->json_data);
    $staff = @$trainee->staff;
@endphp

<div class="panel panel-default">
    <div class="panel-heading">
        <label for="">Review Trainee Exam > {{ @$staff->last_name_kh . ' ' . @$staff->first_name_kh }}</label>
    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">

            <dt>Training on Course:</dt>
            <dd>
                {{ @$course->json_data->title }}
            </dd>

            <dt>Start Date:</dt>
            <dd>
                {{ dateTimeToStr(@$enrollmentObj->start_date) }}
            </dd>

            <dt>End Date:</dt>
            <dd>
                {{ dateTimeToStr(@$enrollmentObj->end_date) }}
            </dd>
        </dl>
    </div>
</div>

<div class="panel panel-primary">

    <div class="panel-heading">
        <label for="">List Exam by Sections</label>
    </div>

    <div class="panel-body">

        <form
                action="{{ route('hrtraining::review_training.calculate_exam_result') }}"
                method="post">

            {{ Form::token() }}

            <input type="hidden" name="enrollment_id" value="{{ @$enrollment->id }}"/>
            <input type="hidden" name="trainee_id" value="{{ @$trainee->id }}"/>

            @foreach(@$exams as $key => $exam)
                <h4>
                    <label>Section:</label> {{ @$exam->courseContent->json_data->title }}
                </h4>
                <div class="row">
                    <div class="col-md-12">

                        <ul class="timeline">
                            @foreach(@$exam->questionAnswers as $key => $question)

                                @php
                                    $examHistory = findExamHistoryBaseOnQuestionId($exam->examHistories, $question->id);
                                @endphp

                                <li style="margin-bottom: 50px">
                                    <i class="fa bg-blue">{{ $key+=1 }}</i>

                                    <input type="hidden" name="question_ids[]" value="{{ @$question->id }}"/>
                                    <input type="hidden" name="question_types[]"
                                           value="{{ @$question->json_data->type }}"/>

                                    @if($question->json_data->type == QUESTION_TYPE['OPEN']['value'])
                                        @include('hrtraining::review_training_exam.components.open_question_layout', ['question' => @$question, 'exam_history' => @$examHistory])

                                    @elseif($question->json_data->type == QUESTION_TYPE['CLOSE']['value'])
                                        @include('hrtraining::review_training_exam.components.close_question_layout', ['question' => @$question, 'exam_history' => @$examHistory])

                                    @elseif($question->json_data->type == QUESTION_TYPE['MULTIPLE-CHOICE']['value'])
                                        @include('hrtraining::review_training_exam.components.multiple_choice_question_layout', ['question' => @$question, 'exam_history' => @$examHistory])

                                    @endif
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
                {{--                <label>Total Point: 100</label>--}}
                <hr/>
            @endforeach

            <div class="row">
                <div class="col-md-4">
                    <button type="submit" class="btn btn-danger">
                        Calculate Result
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

@endsection

@section('js')
    <script>

    </script>
@endsection