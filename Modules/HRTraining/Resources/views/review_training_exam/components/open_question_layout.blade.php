@php
    $questionObj = @$question->json_data
@endphp
<div class="timeline-item">
    <label class="time">Point: {{ @$questionObj->point}}</label>

    <h3 class="timeline-header"><label>{{ $questionObj->title }}</label></h3>

    <div class="timeline-body">
        <textarea name="open_answers[{{ @$question->id }}]" class="form-control" rows="4"
                  placeholder="Type your answer here..."
                  disabled>{{ @$exam_history->json_data->answer }}</textarea>
        <br/>
        <label for="give_open_answers_point-{{@$question->id}}">Give Point: </label>
        <input type="number" class="inline" id="give_open_answers_point-{{@$question->id}}"
               required
               value="{{ @$exam_history->json_data->answer_point }}"
               name="give_open_answers_point[{{ @$exam_history->id }}]"/>
    </div>
</div>