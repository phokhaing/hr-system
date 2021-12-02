@php
    $questionObj = @$question->json_data
@endphp
<div class="timeline-item">
    <label class="time">Point: {{ @$questionObj->point}}</label>

    <h3 class="timeline-header"><label>Multiple-Choice: {{ $questionObj->title }}</label></h3>

    <div class="timeline-body">
        <div class="row">

            @foreach(@$questionObj->answer as $key => $answer)
                <div class="col-md-2">
                    <div class="checkbox">
                        <label><input type="checkbox"  name="multiple_answers[{{ @$question->id }}][]" value="{{ @$answer->id }}">{{ $answer->title }}</label>
                    </div>
                </div>

            @endforeach

        </div>
    </div>
</div>