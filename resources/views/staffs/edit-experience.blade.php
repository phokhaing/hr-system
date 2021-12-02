@extends('staffs.edit')

@section('tab')
    <div class="tab-pane active" id="experience">
        @if($staff->experiences()->exists())
            @include('staffs._form._edit-work-experience')
        @else
            @include('staffs._form.work-experience')
        @endif
    </div>
@endsection