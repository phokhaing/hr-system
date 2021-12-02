@extends('staffs.edit')

@section('tab')
    <div class="tab-pane active" id="requestResign">
        @if($staff->requestResign()->exists())
            @include('staffs._form._edit-resign')
        @else
            @include('staffs._form.resign')
        @endif
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $("#request_date").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-M-yy',
            });
        });
    </script>
@endsection