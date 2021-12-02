<form class="form-verticle" method="get" action="{{ route('users.index') }}" id="form_user_advance_search">
    <div class="modal fade" id="user_advance_filter" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"><i class="fa fa-filter"></i> Advance Filter</h5>

                </div>
                <div class="modal-body">
                     {{ csrf_field() }}

                    <input type="hidden" name="keyword" id="keyword" value="{{ @request('keyword') }}">

                    <div class="form-group">
                        <label class="control-label">Role:</label>
                        <select class="form-control" id="role" name="role">
                            <option></option>
                            @foreach(@$roles as $key => $value)
                            <option value="{{ @$value->name }}" {{ @request('role') == @$value->name ? 'selected' : '' }}>{{ @$value->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Company:</label>
                        <company-selection my-selection="<?=@request('company_code')?>"></company-selection>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</form>