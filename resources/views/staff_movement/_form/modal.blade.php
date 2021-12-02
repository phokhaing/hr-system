<!-- Modal -->
<div class="modal fade" id="EditResign" tabindex="-1" role="dialog" aria-labelledby="EditResign" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Edit resign</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form action="{{ route('resign.edit') }}" role="form" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="staff_token" id="staff_token">
                        <div class="form-group col-sm-6 col-md-6 @if($errors->has('approved_date')) has-error @endif">
                            <label for="approve_date">Approved date <label class="text-danger">*</label></label>
                            <input type="text" class="form-control approve_date" name="approved_date" id="approve_date" readonly
                                   placeholder="ថ្ងៃ-ខែ-ឆ្នាំ អនុម័ត">
                        </div>
                        <div class="form-group col-sm-6 col-md-6 @if($errors->has('last_day')) has-error @endif">
                            <label for="last_day">Last day <label class="text-danger">*</label></label>
                            <input type="text" class="form-control last_day" name="last_day" id="last_day"​​ readonly
                                   placeholder="ថ្ងៃ-ខែ-ឆ្នាំ ចុងក្រោយ">
                        </div>

                        <div class="form-group col-sm-12 col-md-12">
                            <div class="pull-right">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> SAVE</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('js')
    <script type="text/javascript" src="{{ asset('js/staff_resign/index.js') }}"></script>
    <script>
        $('#EditResign').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var staff_id = button.data('staff_id'); // Extract info from data-* attributes
            var modal = $(this);

            modal.find('.modal-body #approve_date #last_day').val("");
            modal.find('.modal-body #staff_token').val(staff_id);
        })
    </script>
@endsection