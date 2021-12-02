<form class="form-verticle" method="get" action="{{ route('contract.index') }}" id="form_contract_advance_search">
    <div class="modal fade" id="contract_advance_filter" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"><i class="fa fa-filter"></i> Advance Filter</h5>

                </div>
                <div class="modal-body">
                    {{ csrf_field() }}

                    <input type="hidden" name="key_word" id="key_word" value="{{ @request('key_word') }}">
                    
                    <div class="form-group">
                        <label class="control-label">Contract Type:</label>
                        <select class="form-control" id="contract_type" name="contract_type">
                            <option></option>
                            @foreach(@CONTRACT_TYPE as $key => $value)
                            <option value="{{ @$value }}" {{ @request('contract_type') == @$value ? 'selected' : '' }}>{{ @$key }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="row form-group">
                        <div class="col-md-12 col-lg-12">
                            <label class="control-label">Company:</label>
                            <company-selection my-selection="<?= @request('company_code') ?>"></company-selection>
                        </div>

                        <!-- <div class="col-md-6 col-lg-6">
                            <label class="control-label">Branch/Department:</label>
                            <branch-department-selection my-selection="<?= @request('branch_department_code') ?>"></branch-department-selection>
                        </div> -->
                    </div>

                    <div class="form-group">
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