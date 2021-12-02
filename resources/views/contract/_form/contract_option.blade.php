{{--
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="form-group col-sm-12 col-md-12 col-lg-12">
            <label for="contract_type">Contract Type </label>
            <select class="form-control input-sm">
                <option value=""> << {{ __('label.contract_type') }} >></option>
                @foreach(CONTRACT_TYPE as $key => $value)
                    <option data-toggle="modal" data-target="#{{$key}}">{{ $key }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

@include('contract.modals.resign_modal')

@section('js')
    <script>
        $('#PROBATION').on('shown.bs.modal', function () {
            // $('#myInput').focus()
        });
        $('#REGULAR').on('shown.bs.modal', function () {
            // $('#myInput').focus()
        });
        $('#RESIGN').on('show.bs.modal', function () {
            // $('#myInput').focus()
        });
        $('#DEATH').on('shown.bs.modal', function () {
            // $('#myInput').focus()
        });
        $('#TERMINATE').on('shown.bs.modal', function () {
            // $('#myInput').focus()
        });
        $('#DEMOTE').on('shown.bs.modal', function () {
            // $('#myInput').focus()
        });
        $('#PROMOTE').on('shown.bs.modal', function () {
            // $('#myInput').focus()
        });
        $('#LAY_OFF').on('shown.bs.modal', function () {
            // $('#myInput').focus()
        });
        $('#CHANGE_LOCATION').on('shown.bs.modal', function () {
            // $('#myInput').focus()
        });

    </script>
@stop
--}}
