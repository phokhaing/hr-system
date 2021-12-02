<form class="form-verticle" method="post" action="{{ route('payroll.setting.storeTaxOnSalary') }}" id="form_add_new_tax_on_salary">
    <div class="modal fade" id="add_new_tax_on_salary" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Add New Tax On Salary Rate</h5>

                </div>
                <div class="modal-body">
                     {{ csrf_field() }}

                    <div class="form-group">
                        <label class="control-label" for="rate">Rate (%):</label>
                        <input type="number" step="0.01" name="rate" class="form-control" id="rate" required />
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="salary_range_from">Salary Range From:</label>
                        <input type="number" step="0.01" name="salary_range_from" class="form-control" id="salary_range_from" required />
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label" for="salary_range_to">Salary Range To:</label>
                        <input type="number" step="0.01" name="salary_range_to" class="form-control" id="salary_range_to" required />
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</form>