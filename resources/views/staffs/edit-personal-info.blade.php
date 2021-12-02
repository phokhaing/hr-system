@extends('staffs.edit')

@section('tab')
    <div class="tab-pane active" id="personal_info">
        @include('staffs._form._edit-personal-info')
    </div>
@endsection