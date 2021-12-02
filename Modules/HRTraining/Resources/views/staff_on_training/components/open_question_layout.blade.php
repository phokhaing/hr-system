@php
    $questionObj = @$question->json_data
@endphp
<div class="timeline-item">
    <label class="time">Point: {{ @$questionObj->point}}</label>

    <h3 class="timeline-header"><label>{{ $questionObj->title }}</label></h3>

    <div class="timeline-body">
        <textarea name="open_answers[{{ @$question->id }}][]" class="form-control" rows="4" placeholder="Type your answer here..." required>

        </textarea>
    </div>
</div>