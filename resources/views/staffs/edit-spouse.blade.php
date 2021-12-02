@extends('staffs.edit')

@section('tab')
    <div class="tab-pane active" id="spouse">
        @if($staff->spouse()->exists())
            @include('staffs._form._edit-spouse')
        @else
            @include('staffs._form.spouse')
        @endif
    </div>
@endsection