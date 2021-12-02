@extends('staffs.edit')

@section('tab')
    <div class="tab-pane active" id="education">
        @if($staff->educations()->exists())
            @include('staffs._form._edit-education')
        @else
            @include('staffs._form.education')
        @endif
    </div>
@endsection