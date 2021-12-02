<form class="form-verticle" method="post" action="{{ route('contract.new_salary_store') }}" id="form_new_salary">
    <div class="modal fade" id="modal_new_salary" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h5 class="modal-title">Add New Salary</h5>

                </div>
                <div class="modal-body">
                    {{ csrf_field() }}

                    <input type="hidden" name="contract_id" id="contract_id" value="{{ @$contract->id }}">

                    <div class="form-group">
                        <label class="control-label" for="amount">Salary:</label>
                        <input type="number" class="form-control" name="amount" id="amount" required/>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="currency">Currency:</label>
                        <select class="form-control" name="currency" id="currency" required disabled>
                            @foreach($currency as $key => $value)
                                @if(@$contract->contract_object['currency'] == $key)
                                    <option value="{{ $key }}" selected>{{ $value }}</option>
                                @else
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Effective Date:</label>
                        <input type="text" class="form-control effective_date" id="effective_date" readonly required name="effective_date" placeholder="dd-mm-yyyy">
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label">Notice:</label>
                        <textarea rows="3" class="form-control" name="notice" id="notice"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</form>