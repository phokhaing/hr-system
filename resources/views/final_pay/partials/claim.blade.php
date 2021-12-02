<div class="panel panel-default">
    <div class="panel-heading"><label for=""><i class="fa fa-money"></i> Checking Final Pay </label></div>
    <div class="panel-body">
        <form action="{{ @$route }}" method="POST" role="form" enctype="multipart/form-data" id="post_final_pay">
            {{ csrf_field() }}
            <input type="hidden" class="value-data" value="0" name="staff_personal_id"
                   id="staff_personal_id"/>
            <input type="hidden" class="value-data" value="0" name="contract_id" id="contract_id"/>
            <input type="hidden" value="0" name="final_pay_id" id="final_pay_id"/>

            <div style="overflow: auto" class="table-responsive">
                <table class="table table-striped table-hover" id="container_blocking_date_range">
                    <thead>
                    <tr>
                        <th colspan="6" class="text-center">NATURE OF REMUNERATION</th>
                        <th class="text-center" style="min-width: 120px;">AMOUNT (KHR)</th>
                    </tr>

                    </thead>

                    <tbody>
                    <tr id="blocking_date_range">
                        <td class="text-right" style="min-width: 150px;" id="salary_earned_from_label">Salary Block From
                            1 :
                        </td>
                        <td style="min-width: 150px;">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="block_from_date[]" readonly id="block_from_date"
                                       placeholder="{{ __('ថ្ងៃ Block ប្រាក់ខែ') }}" readonly
                                       class="data form-control pull-right" value="{{ old('start_block_1') }}">
                            </div>
                        </td>
                        <td style="min-width: 150px;">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="block_end_date[]" readonly id="block_end_date"
                                       placeholder="{{ __('ថ្ងៃធ្វើការចុងក្រោយ') }}" readonly
                                       class="data form-control pull-right" value="{{ old('end_block_1') }}">
                            </div>
                        </td>
                        <td class="text-left" style="min-width: 150px;">
                        </td>
                        <td style="min-width: 150px;">
                            <input type="number" name="total_days_label" id="total_days_label" readonly
                                   placeholder="{{ __('ចំនួនថ្ងៃ') }}" class="data form-control"
                                   value="{{ old('num_earn_1') }}">
                        </td>
                        <td class="text-left">Days</td>
                        <td class="text-right">
                            <span id="earned_from_amount" class="money">0</span>
                            <input type="hidden" class="value-data" value="0" name="block_total_days[]"
                                   id="block_total_days">
                            <input type="hidden" class="value-data" value="0" name="block_days_of_month[]"
                                   id="block_days_of_month">
                            <input type="hidden" class="value-data" value="0" name="total_earn[]" id="total_earn">
                        </td>
                    </tr>

                    <tr id="retro_salary">
                        <td class="text-right">Retro Salary From :</td>
                        <td colspan="2">
                            <input type="text" name="retro_salary_desc" id="retro_salary_desc"
                                   placeholder="{{ __('Description') }}" class="data form-control" readonly>
                        </td>
                        <td class="text-center"></td>
                        <td>
                            <input type="number" name="retro_salary_total_days" id="retro_salary_total_days"
                                   placeholder="{{ __('ចំនួនថ្ងៃ') }}" class="data form-control" readonly>
                        </td>
                        <td class="text-left">KHR</td>
                        <td class="text-right">
                            <span class="money" id="total_retro_salary_label">0</span>
                            <input type="hidden" class="value-data" value="0" name="total_retro_salary"
                                   id="total_retro_salary">
                        </td>
                    </tr>

                    <tr id="show_leave_1">
                        <td class="text-right">Leave Without Pay 1 :</td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="start_leave_1" id="start_leave_1"
                                       placeholder="{{ __('ថ្ងៃសុំច្បាប់') }}" readonly
                                       class="data form-control pull-right date" value="{{ old('start_leave_1') }}">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="end_leave_1" id="end_leave_1"
                                       placeholder="{{ __('ថ្ងៃសុំច្បាប់') }}" readonly
                                       class="data form-control pull-right date" value="{{ old('end_leave_1') }}">
                            </div>
                        </td>
                        <td class="text-left">
                            <a class="btn btn-xs btn-primary margin-r-5 add_leave" id="add_leave_1" title="Add more">
                                Add
                            </a>
                            <a disabled class="btn btn-xs btn-danger margin-r-5 remover_leave" title="Remove row">
                                Remove
                            </a>
                        </td>
                        <td>
                            <input type="number" name="num_leave_1" id="num_leave_1" placeholder="{{ __('ចំនួនថ្ងៃ') }}"
                                   class="data form-control" value="{{ old('num_leave_1') }}">
                        </td>
                        <td class="text-left">Days</td>
                        <td class="text-right">
                            <span class="money" id="total_leave_1_label">0</span>
                            <input type="hidden" class="value-data" value="0" name="total_leave_1" id="total_leave_1">
                        </td>
                    </tr>
                    <tr id="show_leave_2" hidden>
                        <td class="text-right">Leave Without Pay 2 :</td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="start_leave_2" id="start_leave_2"
                                       placeholder="{{ __('ថ្ងៃសុំច្បាប់') }}" readonly
                                       class="data form-control pull-right date" value="{{ old('start_leave_2') }}">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="end_leave_2" id="end_leave_2"
                                       placeholder="{{ __('ថ្ងៃសុំច្បាប់') }}" readonly
                                       class="data form-control pull-right date" value="{{ old('end_leave_2') }}">
                            </div>
                        </td>
                        <td class="text-left">
                            <a class="btn btn-xs btn-primary margin-r-5 add_leave" id="add_leave_2" title="Add more">
                                Add
                            </a>
                            <a class="btn btn-xs btn-danger margin-r-5 remover_leave" id="remover_leave_2"
                               title="Remove row">
                                Remove
                            </a>
                        </td>
                        <td>
                            <input type="number" name="num_leave_2" id="num_leave_2" placeholder="{{ __('ចំនួនថ្ងៃ') }}"
                                   class="data form-control" value="{{ old('num_leave_2') }}">
                        </td>
                        <td class="text-left">Days</td>
                        <td class="text-right">
                            <span class="money" id="total_leave_2_label">0</span>
                            <input type="hidden" class="value-data" value="0" name="total_leave_2" id="total_leave_2">
                        </td>
                    </tr>
                    <tr id="show_leave_3" hidden>
                        <td class="text-right">Leave Without Pay 3 :</td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="start_leave_3" id="start_leave_3"
                                       placeholder="{{ __('ថ្ងៃសុំច្បាប់') }}" readonly
                                       class="data form-control pull-right date" value="{{ old('start_leave_3') }}">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="end_leave_3" id="end_leave_3"
                                       placeholder="{{ __('ថ្ងៃសុំច្បាប់') }}" readonly
                                       class="data form-control pull-right date" value="{{ old('end_leave_3') }}">
                            </div>
                        </td>
                        <td class="text-left">
                            <a class="btn btn-xs btn-primary margin-r-5 add_leave" id="add_leave_3" title="Add more">
                                Add
                            </a>
                            <a class="btn btn-xs btn-danger margin-r-5 remover_leave" id="remover_leave_3"
                               title="Remove row">
                                Remove
                            </a>
                        </td>
                        <td>
                            <input type="number" name="num_leave_3" id="num_leave_3" placeholder="{{ __('ចំនួនថ្ងៃ') }}"
                                   class="data form-control" value="{{ old('num_leave_3') }}">
                        </td>
                        <td class="text-left">Days</td>
                        <td class="text-right">
                            <span class="money" id="total_leave_3_label">0</span>
                            <input type="hidden" class="value-data" value="0" name="total_leave_3" id="total_leave_3">
                        </td>
                    </tr>
                    <tr id="show_leave_4" hidden>
                        <td class="text-right">Leave Without Pay 4 :</td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="start_leave_4" id="start_leave_4"
                                       placeholder="{{ __('ថ្ងៃសុំច្បាប់') }}" readonly
                                       class="data form-control pull-right date" value="{{ old('start_leave_4') }}">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="end_leave_4" id="end_leave_4"
                                       placeholder="{{ __('ថ្ងៃសុំច្បាប់') }}" readonly
                                       class="data form-control pull-right date" value="{{ old('end_leave_4') }}">
                            </div>
                        </td>
                        <td class="text-left">
                            <a class="btn btn-xs btn-primary margin-r-5 add_leave" id="add_leave_4" title="Add more">
                                Add
                            </a>
                            <a class="btn btn-xs btn-danger margin-r-5 remover_leave" id="remover_leave_4"
                               title="Remove row">
                                Remove
                            </a>
                        </td>
                        <td>
                            <input type="number" name="num_leave_4" id="num_leave_4" placeholder="{{ __('ចំនួនថ្ងៃ') }}"
                                   class="data form-control" value="{{ old('num_leave_4') }}">
                        </td>
                        <td class="text-left">Days</td>
                        <td class="text-right">
                            <span class="money" id="total_leave_4_label">0</span>
                            <input type="hidden" class="value-data" value="0" name="total_leave_4" id="total_leave_4">
                        </td>
                    </tr>
                    <tr id="show_leave_5" hidden>
                        <td class="text-right">Leave Without Pay 5 :</td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="start_leave_5" id="start_leave_5"
                                       placeholder="{{ __('ថ្ងៃសុំច្បាប់') }}" readonly
                                       class="data form-control pull-right date" value="{{ old('start_leave_5') }}">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="end_leave_5" id="end_leave_5"
                                       placeholder="{{ __('ថ្ងៃសុំច្បាប់') }}" readonly
                                       class="data form-control pull-right date" value="{{ old('end_leave_5') }}">
                            </div>
                        </td>
                        <td class="text-left">
                            <a disabled class="btn btn-xs btn-primary margin-r-5 add_leave" title="Add more">
                                Add
                            </a>
                            <a class="btn btn-xs btn-danger margin-r-5 remover_leave" id="remover_leave_5"
                               title="Remove row">
                                Remove
                            </a>
                        </td>
                        <td>
                            <input type="number" name="num_leave_5" id="num_leave_5" placeholder="{{ __('ចំនួនថ្ងៃ') }}"
                                   class="data form-control" value="{{ old('num_leave_5') }}">
                        </td>
                        <td class="text-left">Days</td>
                        <td class="text-right">
                            <span class="money" id="total_leave_5_label">0</span>
                            <input type="hidden" class="value-data" value="0" name="total_leave_5" id="total_leave_5">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">Finger Print :</td>
                        <td colspan="2">
                            <input type="text" name="finger_print" id="finger_print"
                                   placeholder="{{ __('Description') }}" class="data form-control"
                                   value="{{ old('finger_print') }}">
                        </td>
                        <td class="text-center"></td>
                        <td>
                            <input type="number" name="num_finger_print" id="num_finger_print"
                                   placeholder="{{ __('ចំនួនទឺកប្រាក់') }}" class="data form-control"
                                   value="{{ old('num_finger_print') }}">
                        </td>
                        <td class="text-left">KHR</td>
                        <td class="text-right">
                            <span class="money" id="total_finger_print_label">0</span>
                            <input type="hidden" class="value-data" value="0" name="total_finger_print"
                                   id="total_finger_print">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">Special Branch Allowance as of :</td>
                        <td colspan="2">
                            <input type="text" name="alloance" id="alloance" placeholder="{{ __('Description') }}"
                                   class="data form-control" value="{{ old('alloance') }}">
                        </td>
                        <td class="text-center"></td>
                        <td>
                            <input type="number" name="num_alloance" id="num_alloance"
                                   placeholder="{{ __('ចំនួនទឺកប្រាក់') }}" class="data form-control"
                                   value="{{ old('num_alloance') }}">
                        </td>
                        <td class="text-left">KHR</td>
                        <td class="text-right">
                            <span class="money" id="total_alloance_label">0</span>
                            <input type="hidden" class="value-data" value="0" name="total_alloance" id="total_alloance">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">Incentive :</td>
                        <td colspan="2">
                            <input type="text" name="incentive" id="incentive" placeholder="{{ __('Description') }}"
                                   class="data form-control" value="{{ old('incentive') }}">
                        </td>
                        <td class="text-center"></td>
                        <td>
                            <input type="number" name="num_incentive" id="num_incentive"
                                   placeholder="{{ __('ចំនួនទឺកប្រាក់') }}" class="data form-control"
                                   value="{{ old('num_incentive') }}">
                        </td>
                        <td class="text-left">KHM</td>
                        <td class="text-right">
                            <span class="money" id="total_incentive_label">0</span>
                            <input type="hidden" class="value-data" value="0" name="total_incentive"
                                   id="total_incentive">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">Bonus Khmer New Year :</td>
                        <td colspan="2">
                            <input type="text" name="bonus_kny" id="bonus_kny" placeholder="{{ __('Description') }}"
                                   class="data form-control" value="{{ old('bonus_kny') }}">
                        </td>
                        <td class="text-center"></td>
                        <td>
                            <input type="number" name="num_bonus_kny" id="num_bonus_kny"
                                   placeholder="{{ __('ចំនួនទឺកប្រាក់') }}" class="data form-control"
                                   value="{{ old('num_bonus_kny') }}">
                        </td>
                        <td class="text-left">KHR</td>
                        <td class="text-right">
                            <span class="money" id="total_bonus_kny_label">0</span>
                            <input type="hidden" class="value-data" value="0" name="total_bonus_kny"
                                   id="total_bonus_kny">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">Bonus Pchum Ben :</td>
                        <td colspan="2">
                            <input type="text" name="bonus_pcb" id="bonus_pcb" placeholder="{{ __('Description') }}"
                                   class="data form-control" value="{{ old('bonus_pcb') }}">
                        </td>
                        <td class="text-center"></td>
                        <td>
                            <input type="number" name="num_bonus_pcb" id="num_bonus_pcb"
                                   placeholder="{{ __('ចំនួនទឺកប្រាក់') }}" class="data form-control"
                                   value="{{ old('num_bonus_pcb') }}">
                        </td>
                        <td class="text-left">KHR</td>
                        <td class="text-right">
                            <span class="money" id="total_bonus_pcb_label">0</span>
                            <input type="hidden" class="value-data" value="0" name="total_bonus_pcb"
                                   id="total_bonus_pcb">
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right">Total :</th>
                        <td></td>
                        <td></td>
                        <td class="text-center"></td>
                        <td></td>
                        <td></td>
                        <th class="text-right">
                            <label class="money" id="total_total_label">0</label>
                            <input type="hidden" class="value-data" value="0" name="total_total" id="total_total">
                        </th>
                    </tr>
                    <tr>
                        <td class="text-right">Less Dependents :</td>
                        <td colspan="2">
                            <input type="text" name="dependent" id="dependent" placeholder="{{ __('Description') }}"
                                   class="data form-control" value="{{ old('dependent') }}">
                        </td>
                        <td class="text-center"></td>
                        <td>
                            <input type="number" name="num_dependent" id="num_dependent"
                                   placeholder="{{ __('ចំនួនកូន (+សម្ព័ន្ធជាមេផ្ទះ)') }}" class="data form-control"
                                   value="{{ old('num_dependent') }}" readonly>
                        </td>
                        <td class="text-left">Numbers</td>
                        <td class="text-right">
                            <span id="total_dependent_label" class="money">0</span>
                            <input type="hidden" class="value-data" value="0" name="total_dependent"
                                   id="total_dependent">
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right">Amount Taxable :</th>
                        <td></td>
                        <td></td>
                        <td class="text-center"></td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <th class="text-right">
                            <label class="money" id="total_amount_taxable_label">0</label>
                            <input type="hidden" class="value-data" value="0" name="total_amount_taxable"
                                   id="total_amount_taxable">
                        </th>
                    </tr>
                    <tr>
                        <td class="text-right">Withholding Tax on Salary :</td>
                        <td></td>
                        <td></td>
                        <td class="text-center"></td>
                        <td>
                            <a class="btn btn-primary margin-r-5 add_block" id="checking_tax">
                                Checking Tax
                            </a>
                        </td>
                        <td></td>
                        <td class="text-right">
                            <span class="money" id="total_tax_label">0</span>
                            <input type="hidden" class="value-data" value="0" name="total_tax" id="total_tax">
                            <input type="hidden" class="value-data" value="0" name="tax_on_salary_rate"
                                   id="tax_on_salary_rate">
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right">Total Benefit after Tax :</th>
                        <td></td>
                        <td></td>
                        <td class="text-center"></td>
                        <td></td>
                        <td></td>
                        <th class="text-right">
                            <label class="money" id="total_beneft_after_label">0</label>
                            <input type="hidden" class="value-data" value="0" name="total_beneft_after"
                                   id="total_beneft_after">
                        </th>
                    </tr>
                    <tr>
                        <td class="text-right">Motorcycle Rent :</td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="start_moto" id="start_moto"
                                       placeholder="{{ __('ថ្ងៃចាប់ផ្តើម') }}" readonly
                                       class="data form-control pull-right date" value="{{ old('start_moto') }}">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="end_moto" id="end_moto" placeholder="{{ __('ថ្ងៃចុងក្រោយ') }}"
                                       readonly class="data form-control pull-right date" value="{{ old('end_moto') }}">
                            </div>
                        </td>
                        <td class="text-center">
                            <input type="number" name="num_amount_moto" id="num_amount_moto"
                                   placeholder="{{ __('ចំនួនទឹកប្រាក់') }}" class="data form-control"
                                   value="{{ old('num_amount_moto') }}">
                        </td>
                        <td>
                            <input type="number" name="total_day_moto" id="total_day_moto"
                                   placeholder="{{ __('ចំនួនថ្ងៃ') }}" class="data form-control"
                                   value="{{ old('total_day_moto') }}">
                        </td>
                        <td class="text-left">Days</td>
                        <td class="text-right">
                            <span class="money" id="total_moto_label">0</span>
                            <input type="hidden" class="value-data" value="0" name="total_moto" id="total_moto">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">WHT on motorcycle rental :</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <input type="number" name="num_wht_moto" id="num_wht_moto"
                                   placeholder="{{ __('ចំនួនទឹកប្រាក់') }}" class="data form-control"
                                   value="{{ old('num_wht_moto') }}">
                        </td>
                        <td class="text-left">KHR</td>
                        <td class="text-right">
                            <span class="money" id="total_wht_moto_label">0</span>
                            <input type="hidden" class="value-data" value="0" name="total_wht_moto" id="total_wht_moto">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">Gasoline :</td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="start_gasoline" id="start_gasoline"
                                       placeholder="{{ __('ថ្ងៃចាប់ផ្តើម') }}" readonly
                                       class="data form-control pull-right date" value="{{ old('start_gasoline') }}">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="end_gasoline" id="end_gasoline"
                                       placeholder="{{ __('ថ្ងៃចុងក្រោយ') }}" readonly
                                       class="data form-control pull-right date" value="{{ old('end_gasoline') }}">
                            </div>
                        </td>
                        <td class="text-center">
                            <input type="number" name="num_amount_gasoline" id="num_amount_gasoline"
                                   placeholder="{{ __('ចំនួនទឹកប្រាក់') }}" class="data form-control"
                                   value="{{ old('num_amount_gasoline') }}">
                        </td>
                        <td>
                            <input type="number" name="total_day_gasoline" id="total_day_gasoline"
                                   placeholder="{{ __('ចំនួនថ្ងៃ') }}" class="data form-control"
                                   value="{{ old('total_day_gasoline') }}">
                        </td>
                        <td class="text-left">Days</td>
                        <td class="text-right">
                            <span class="money" id="total_gasoline_label">0</span>
                            <input type="hidden" class="value-data" value="0" name="total_gasoline" id="total_gasoline">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">Settlement :</td>
                        <td colspan="2">
                            <input type="text" name="settlement" id="settlement" placeholder="{{ __('Description') }}"
                                   class="data form-control" value="{{ old('settlement') }}">
                        </td>
                        <td class="text-center"></td>
                        <td>
                            <input type="number" name="num_settlement" id="num_settlement"
                                   placeholder="{{ __('ចំនួនទឺកប្រាក់') }}" class="data form-control"
                                   value="{{ old('num_settlement') }}">
                        </td>
                        <td class="text-left">KHR</td>
                        <td class="text-right">
                            <span class="money" id="total_settlement_label">0</span>
                            <input type="hidden" class="value-data" value="0" name="total_settlement"
                                   id="total_settlement">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">Salary 50% Half Month :</td>
                        <td colspan="2">
                            <input type="text" name="half_month" id="half_month" placeholder="{{ __('Description') }}"
                                   class="data form-control" value="{{ old('half_month') }}">
                        </td>
                        <td class="text-center"></td>
                        <td>
                            <input type="number" name="half_month_amount" id="half_month_amount"
                                   placeholder="{{ __('ចំនួនទឺកប្រាក់') }}" class="data form-control"
                                   value="{{ old('half_month_amount') }}" readonly>
                        </td>
                        <td class="text-left">KHR</td>
                        <td class="text-right">
                            <span id="label_half_month_amount" class="money">0</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">Full Month :</td>
                        <td colspan="2">
                            <input type="text" name="full_month" id="full_month" placeholder="{{ __('Description') }}"
                                   class="data form-control" value="{{ old('full_month') }}">
                        </td>
                        <td class="text-center"></td>
                        <td>
                            <input type="number" name="full_month_amount" id="full_month_amount"
                                   placeholder="{{ __('ចំនួនទឺកប្រាក់') }}" class="data form-control"
                                   value="{{ old('full_month_amount') }}" readonly>
                        </td>
                        <td class="text-left">KHR</td>
                        <td class="text-right">
                            <span id="label_full_month_amount" class="money">0</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">សំណង (Compensation) :</td>
                        <td colspan="2">
                            <input type="text" name="compensation" id="compensation"
                                   placeholder="{{ __('Description') }}" class="data form-control"
                                   value="{{ old('compensation') }}">
                        </td>
                        <td class="text-center"></td>
                        <td>
                            <input type="number" name="num_compensation" id="num_compensation"
                                   placeholder="{{ __('ចំនួនទឺកប្រាក់') }}" class="data form-control"
                                   value="{{ old('num_compensation') }}">
                        </td>
                        <td class="text-left">KHR</td>
                        <td class="text-right">
                            <span class="money" id="total_compensation_label">0</span>
                            <input type="hidden" class="value-data" value="0" name="total_compensation"
                                   id="total_compensation">
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right">Pension Fund Staff :</th>
                        <td colspan="2">
                            <input type="text" name="pf_staff" id="pf_staff" placeholder="{{ __('Description') }}"
                                   class="data form-control" value="{{ old('pf_staff') }}">
                        </td>
                        <td class="text-center"></td>
                        <td>
                            <input type="number" name="num_pf_staff" id="num_pf_staff"
                                   placeholder="{{ __('ចំនួនទឺកប្រាក់') }}" class="data form-control"
                                   value="{{ old('num_pf_staff') }}">
                        </td>
                        <td class="text-left">KHR</td>
                        <th class="text-right">
                            <label class="money" id="total_pf_staff_label">0</label>
                            <input type="hidden" class="value-data" value="0" name="total_pf_staff" id="total_pf_staff">
                            <input type="hidden" class="value-data" value="0" name="pf_id" id="pf_id">
                        </th>
                    </tr>
                    <tr>
                        <th class="text-right">Pension Fund Company :</th>
                        <td colspan="2">
                            <input type="text" name="pf_company" id="pf_company" placeholder="{{ __('Description') }}"
                                   class="data form-control" value="{{ old('pf_company') }}">
                        </td>
                        <td class="text-center"></td>
                        <td>
                            <input type="number" name="num_pf_company" id="num_pf_company"
                                   placeholder="{{ __('ចំនួនទឺកប្រាក់') }}" class="data form-control"
                                   value="{{ old('num_pf_company') }}">
                        </td>
                        <td class="text-left">KHR</td>
                        <th class="text-right">
                            <label class="money" id="total_pf_company_label">0</label>
                            <input type="hidden" class="value-data" value="0" name="total_pf_company"
                                   id="total_pf_company">
                            <input type="hidden" class="value-data" value="0" name="pf_company_interest_rate"
                                   id="pf_company_interest_rate">
                        </th>
                    </tr>
                    <tr>
                        <td class="text-right">Seniority :</td>
                        <td colspan="2">
                            <input type="text" name="seniority" id="seniority" placeholder="{{ __('Description') }}"
                                   class="data form-control" value="{{ old('seniority') }}">
                        </td>
                        <td class="text-center"></td>
                        <td>
                            <input type="number" name="num_seniority" id="num_seniority"
                                   placeholder="{{ __('ចំនួនទឺកប្រាក់') }}" class="data form-control"
                                   value="{{ old('num_seniority') }}">
                        </td>
                        <td class="text-left">KHR</td>
                        <td class="text-right">
                            <span class="money" id="total_seniority_label">0</span>
                            <input type="hidden" class="value-data" value="0" name="total_seniority"
                                   id="total_seniority">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">Telephone :</td>
                        <td colspan="2">
                            <input type="text" name="telephone" id="telephone" placeholder="{{ __('Description') }}"
                                   class="data form-control" value="{{ old('telephone') }}">
                        </td>
                        <td class="text-center"></td>
                        <td>
                            <input type="number" name="num_telephone" id="num_telephone"
                                   placeholder="{{ __('ចំនួនទឺកប្រាក់') }}" class="data form-control"
                                   value="{{ old('num_telephone') }}">
                        </td>
                        <td class="text-left">KHR</td>
                        <td class="text-right">
                            <span class="money" id="total_telephone_label">0</span>
                            <input type="hidden" class="value-data" value="0" name="total_telephone"
                                   id="total_telephone">
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right">NET PAY :</th>
                        <td></td>
                        <td></td>
                        <td class="text-center"></td>
                        <td></td>
                        <td></td>
                        <th class="text-right">
                            <label class="money" id="net_pay_label">0</label>
                            <input type="hidden" class="value-data" value="0" name="net_pay" id="net_pay">
                        </th>
                    </tr>
                    </tbody>

                </table>
            </div>

            @if(is_null(@$disableEdit) || !@$disableEdit)
                <div class="text-right">
                    <input type="button" value="Save" id="btn_submit" disabled class="btn btn-success" name="">
                    <a href="{{route('pensionfund::claim')}}" class="btn btn-danger">Clear</a>
                </div>
            @endif
        </form>

    </div>
</div>