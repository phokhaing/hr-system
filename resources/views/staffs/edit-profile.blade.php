@extends('staffs.edit')

@section('tab')
    <div class="tab-pane active" id="profile">
        @if(!empty($staff->profile))
            @include('staffs._form._edit-staff-profile')
        @else
            @include('staffs._form.staff-profile')
        @endif
    </div>
@endsection