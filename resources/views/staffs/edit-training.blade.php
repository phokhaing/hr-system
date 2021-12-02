@extends('staffs.edit')

@section('tab')
    <div class="tab-pane active" id="training">
        @if($staff->trainings()->exists())
            @include('staffs._form._edit-training-course')
        @else
            @include('staffs._form.training-course')
        @endif
    </div>
@endsection