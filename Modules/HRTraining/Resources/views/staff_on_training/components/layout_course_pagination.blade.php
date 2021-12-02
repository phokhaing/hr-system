<a class="btn btn-sm btn-primary {{ !@$previousCourseContentId ? 'disabled' : '' }}" href="{{ route('hrtraining::staff_on_training.start_training', [
        'enrollment_id' => @$enrollmentId,
        'content_id' => @$previousCourseContentId,
    ])}}">
    Previous
</a>

<a class="btn btn-sm btn-primary {{ !@$nextCourseContentId ? 'disabled' : '' }}" href="{{ route('hrtraining::staff_on_training.start_training', [
        'enrollment_id' => @$enrollmentId,
        'content_id' => @$nextCourseContentId
    ])}}">
    Next
</a>