@php
    $questionObj = @$question->json_data;
    $exam_historyObj = @$exam_history->json_data;
    $answers = @$exam_historyObj->answer;

@endphp

<div class="timeline-item">
    <label class="time">Point: {{ @$questionObj->point}}</label>

    <h3 class="timeline-header"><label>Multiple-Choice: {{ $questionObj->title }}</label></h3>

    <div class="timeline-body">
        <div class="row">

            @foreach(@$questionObj->answer as $key => $answer)
                @php
                    $answered = findMultipleChoiceAnswerById(@$answers, @$answer->id);
                    $isNotNull = !is_null($answered);
                @endphp

                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" disabled
                                   {{ $isNotNull ? 'checked' : '' }}
                                   name="multiple_answers[{{ @$question->id }}][]"
                                   value="{{ @$answer->id }}"/>
                            {{ $answer->title }}
                            @if($isNotNull)
                                <i class="text-red">{{ ANSWER_STATUS_KEY[$answered->status] }}</i>
                            @endif
                        </label>
                    </div>
                </div>

            @endforeach

        </div>
    </div>
</div>