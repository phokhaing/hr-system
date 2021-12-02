@extends('staffs.edit')

@section('tab')
    <div class="tab-pane active" id="guarantor">
        @if($staff->guarantors()->exists())
            @include('staffs._form._edit-guarantor')
        @else
            @include('staffs._form.guarantor')
        @endif
    </div>
@endsection