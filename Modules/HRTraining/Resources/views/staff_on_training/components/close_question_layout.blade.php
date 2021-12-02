@php
    $questionObj = @$question->json_data
@endphp
<div class="timeline-item">
    <label class="time">Point: {{ @$questionObj->point}}</label>

    <h3 class="timeline-header"><label>{{ $questionObj->title }}</label></h3>

    <div class="timeline-body">
        @foreach(@$questionObj->answer as $key => $answer)
            <div class="radio">
                <label><input type="radio" id="radio-{{ @$question->id }}" name="close_answers[{{ @$question->id }}][]" value="{{ @$answer->id }}" required>{{ @$answer->title }}</label>
            </div>
        @endforeach
    </div>
</div>