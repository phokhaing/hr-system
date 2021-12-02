@php
    $questionObj = @$question->json_data;
    $exam_historyObj = @$exam_history->json_data;
    $answered = @$exam_historyObj->answer;

@endphp

<div class="timeline-item">
    <label class="time">
        Point: {{ @$questionObj->point}}
    </label>

    <h3 class="timeline-header"><label>{{ $questionObj->title }}</label></h3>

    <div class="timeline-body">
        @foreach(@$questionObj->answer as $key => $answer)
            @php
                $isChecked = @$answered->id == @$answer->id;
            @endphp

            <div class="radio">
                <label>
                    <input type="radio" id="radio-{{ @$question->id }}" name="close_answers[{{ @$question->id }}][]"
                           value="{{ @$answer->id }}" disabled {{ @$isChecked ? 'checked' : '' }}>
                    {{ @$answer->title }}
                    @if($isChecked)
                        <i class="text-red">{{ ANSWER_STATUS_KEY[$answered->status] }}</i>
                    @endif
                </label>
            </div>
        @endforeach
    </div>
</div>