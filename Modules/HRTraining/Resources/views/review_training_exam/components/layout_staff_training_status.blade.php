@if(@$training_status)
    <span class="badge bg-yellow">
        {{ getTraineeProgressStatus(@$training_status) }}
    </span>
@else
    N/A
@endif