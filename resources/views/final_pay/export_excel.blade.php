@php
    @$finalPayObj = @$finalPay->json_data;
    @$contractObj = @$contract->contract_object;
    @$blockSalary = @$contractObj['block_salary'];
    $baseSalary = convertSalaryFromStrToFloatValue(@$contractObj['salary']);
@endphp
<table style="width: 100%">
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr>
        <th colspan="6" style="text-align: center">
            <b>Settlement of Claims</b>
        </th>
    </tr>
    <tr>
        <td>Staff Name:</td>
        <td colspan="5"
            style="text-align: left">{{ @$staffPersonalInfo->last_name_en . ' ' . @$staffPersonalInfo->first_name_en }}</td>
    </tr>
    <tr>
        <td>SID:</td>
        <td colspan="5" style="text-align: left">{{ @$staffPersonalInfo->staff_id }}</td>
    </tr>
    <tr>
        <td>Date of start working:</td>
        <td colspan="5" style="text-align: left">
            {{ date('d-M-Y', strtotime(@$contract->start_date)) }}
        </td>
    </tr>
    <tr>
        <td>Position:</td>
        <td colspan="5" style="text-align: left">{{ @$contractObj['position']['name_en'] }}</td>
    </tr>
    <tr>
        <td>Branch:</td>
        <td colspan="5" style="text-align: left">{{ @$contractObj['branch_department']['name_en'] }}</td>
    </tr>
    <tr>
        <td>Last day:</td>
        <td colspan="5"
            style="text-align: left">{{ date('d-M-Y', strtotime(@$blockSalary['until_date'])) }}</td>
    </tr>
    <tr>
        <td>Account Number:</td>
        <td colspan="5"
            style="text-align: left">
            @if(str_contains(@$staffPersonalInfo->bank_acc_no, '-'))
                {{ @$staffPersonalInfo->bank_acc_no }}
            @else
                {{ @implode('-', @str_split(@$staffPersonalInfo->bank_acc_no, 4))}}
            @endif
        </td>
    </tr>
</table>

<table style="border: 1px solid black; width: 100%">
    <thead>
    <tr>
        <th colspan="5" style="text-align: center; background-color: #d5d5d5">
            <b>NATURE OF REMUNERATION</b>
        </th>
        <th style="text-align: center; background-color: #d5d5d5">
            <b>AMOUNT</b>
        </th>
    </tr>
    <tr></tr>
    </thead>

    <tbody>
    {{--        Set block salary date--}}
    @if(!is_null(@$newDateRange) && count(@$newDateRange))
        @foreach(@$newDateRange as $key => $value)
            <tr>
                <td style="text-align: right">Salary Earned from</td>
                <td>{{ date('d', strtotime(@$value['first_date_of_month'])) }}
                    -{{ date('d', strtotime(@$value['last_date_of_month'])) }}
                    , {{ date('M-y', strtotime(@$value['last_date_of_month'])) }}</td>
                <td>
                    <b>{{ @$contractObj['currency'] . ' ' . number_format($baseSalary) }}</b>
                </td>
                <td>
                    {{ @$value['total_days'] }}
                </td>
                <td>days</td>
                <td style="text-align: right">{{ number_format(@$value['earned_salary']) }}</td>
            </tr>
        @endforeach
    @endif

    <tr>
        <td style="text-align: right">Retro Salary from</td>
        <td>{{ @$finalPayObj->retro_salary->desc }}</td>
        <td>
            <b>{{ @$contractObj['currency'] . ' ' . number_format($baseSalary) }}</b>
        </td>
        <td>
            {{ @$finalPayObj->retro_salary->total_days }}
        </td>
        <td>days</td>
        <td style="text-align: right">{{ number_format(@$finalPayObj->retro_salary->amount) }}</td>
    </tr>

    {{--        Set leave without pay--}}
    @foreach(@$finalPayObj->leave_without_pay as $key => $value)
        @if(is_null(@$value->days) || @$value->days <= 0)
            @continue
        @endif
        <tr>
            <td style="text-align: right">Leave Without Pay</td>
            <td>Take leave from {{ date('d', strtotime(@$value->start_date)) }}
                -{{ date('d', strtotime(@$value->end_date)) }}
                , {{ date('M-y', strtotime(@$value->end_date)) }}</td>
            <td>
                <b>{{ @$contractObj['currency'] . ' ' . number_format(@$baseSalary) }}</b>
            </td>
            <td>
                {{ @$value->days }}
            </td>
            <td>days</td>
            <td style="text-align: right">({{ number_format(@$value->amount) }})</td>
        </tr>
    @endforeach

    {{--    Set Finger Print--}}
    <tr>
        <td style="text-align: right">Finger Print</td>
        <td>
            {{ @$finalPayObj->figer_print->description }}
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right;">{{ number_format(floatval(@$finalPayObj->figer_print->amount)) }}</td>
    </tr>

    {{--    Set Special Branch Allowance as of--}}
    <tr>
        <td style="text-align: right">Special Branch Allowance as of</td>
        <td>
            {{ @$finalPayObj->specail_branch_alloance->description }}
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right;">{{ number_format(floatval(@$finalPayObj->specail_branch_alloance->amount)) }}</td>
    </tr>

    {{--    Set Bonus Khmer New year--}}
    <tr>
        <td style="text-align: right">Bonus Khmer New Year</td>
        <td>
            {{ @$finalPayObj->bonus_kny->description }}
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right;">{{ number_format(floatval(@$finalPayObj->bonus_kny->amount)) }}</td>
    </tr>

    {{--    Set Bonus Pchum Ben --}}
    <tr>
        <td style="text-align: right">Bonus Pchum Ben</td>
        <td>
            {{ @$finalPayObj->bonus_pcb->description }}
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right;">{{ number_format(floatval(@$finalPayObj->bonus_pcb->amount)) }}</td>
    </tr>

    {{--    Set Incentive --}}
    <tr>
        <td style="text-align: right">Incentive</td>
        <td>
            {{ @$finalPayObj->incentive->description }}
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right;">{{ number_format(floatval(@$finalPayObj->incentive->amount)) }}</td>
    </tr>

    {{--    Set Total --}}
    <tr>
        <td style="text-align: right">
            <b>Total</b>
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right;">
            <b>
                {{ number_format(floatval(@$finalPayObj->salary_before_tax)) }}
            </b>
        </td>
    </tr>
    <tr></tr>

    {{--    Set Less Dependents--}}
    <tr>
        <td style="text-align: right">Less Dependents</td>
        <td>
            {{ @$finalPayObj->less_dependents->description }}
        </td>
        <td></td>
        <td>
            {{ @$finalPayObj->less_dependents->number_of_spouse }}
        </td>
        <td>នាក់</td>
        <td style="text-align: right;">{{ number_format(floatval(@$finalPayObj->less_dependents->amount)) }}</td>
    </tr>

    {{--    Set Amount Taxable--}}
    <tr>
        <td style="text-align: right">Amount Taxable</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right;">{{ number_format(floatval(@$finalPayObj->salary_for_taxable)) }}</td>
    </tr>

    {{--    Set Tax on salary--}}
    <tr>
        <td style="text-align: right">WITHHOLDING TAX on Salary</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right;">({{ number_format(floatval(@$finalPayObj->tax_on_salary)) }})</td>
    </tr>
    <tr></tr>

    {{--    Set Tax on salary--}}
    <tr>
        <td style="text-align: right">
            <b>Total Benefit after Tax</b>
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right;">
            <b>
                {{ number_format(floatval(@$finalPayObj->salary_after_tax)) }}
            </b>
        </td>
    </tr>
    <tr></tr>

    {{--    Set Motor Rental--}}
    <tr>
        <td style="text-align: right">Motor Rental</td>
        <td>
            @if(!is_null(@$finalPayObj->motorcycle_rental->days))
                From {{ date('d', strtotime(@$finalPayObj->motorcycle_rental->start_date)) }}
                -{{ date('d', strtotime(@$finalPayObj->motorcycle_rental->end_date)) }}
                , {{ date('M-y', strtotime(@$finalPayObj->motorcycle_rental->end_date)) }}
            @endif
        </td>
        <td>
            @if(!is_null(@$finalPayObj->motorcycle_rental->amount))
                <b>
                    {{ @$contractObj['currency'] . ' ' . number_format(floatval(@$finalPayObj->motorcycle_rental->amount)) }}
                </b>
            @endif
        </td>
        <td>{{ @$finalPayObj->motorcycle_rental->days }}</td>
        <td>days</td>
        <td style="text-align: right;">{{ number_format(floatval(@$finalPayObj->motorcycle_rental->total)) }}</td>
    </tr>

    {{--    Set WHT on Motor Rental--}}
    <tr>
        <td style="text-align: right">WHT on Motor Rental</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right;">{{ number_format(floatval(@$finalPayObj->wht_on_motorcycle_rental)) }}</td>
    </tr>

    {{--    Set Gasoline--}}
    <tr>
        <td style="text-align: right">Gasoline</td>
        <td>
            @if(!is_null(@$finalPayObj->gasoline->days))
                From {{ date('d', strtotime(@$finalPayObj->gasoline->start_date)) }}
                -{{ date('d', strtotime(@$finalPayObj->gasoline->end_date)) }}
                , {{ date('M-y', strtotime(@$finalPayObj->gasoline->end_date)) }}
            @endif
        </td>
        <td>
            @if(!is_null(@$finalPayObj->gasoline->amount))
                <b>
                    {{ @$contractObj['currency'] . ' ' . number_format(floatval(@$finalPayObj->gasoline->amount)) }}
                </b>
            @endif
        </td>
        <td>{{ @$finalPayObj->gasoline->days }}</td>
        <td>days</td>
        <td style="text-align: right;">{{ number_format(floatval(@$finalPayObj->gasoline->total)) }}</td>
    </tr>

    {{--    Set Settlement --}}
    <tr>
        <td style="text-align: right">Settlement</td>
        <td>
            {{ @$finalPayObj->settlement->description }}
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right;">{{ number_format(floatval(@$finalPayObj->settlement->amount)) }}</td>
    </tr>

    {{--    Set Salary 50% Half Month --}}
    <tr>
        <td style="text-align: right">Salary 50% Half Month</td>
        <td>
            {{ @$finalPayObj->half_pay->description }}
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right;">({{ number_format(floatval(@$finalPayObj->half_pay->amount)) }})</td>
    </tr>

    {{--   Set Salary Full Month --}}
    <tr>
        <td style="text-align: right">Salary Full Month</td>
        <td>
            {{ @$finalPayObj->full_pay->description }}
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right;">({{ number_format(floatval(@$finalPayObj->full_pay->amount)) }})</td>
    </tr>

    {{--    Set Compensation --}}
    <tr>
        <td style="text-align: right">សំណង (Compensation)</td>
        <td>
            {{ @$finalPayObj->compensation->description }}
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right;">{{ number_format(floatval(@$finalPayObj->compensation->amount)) }}</td>
    </tr>

    {{--    Set Pension Fund Staff 5% --}}
    <tr>
        <td style="text-align: right">Pension Fund Staff</td>
        <td>
            {{ @$finalPayObj->pension_fund->pf_staff_description }}
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right;">{{ number_format(floatval(@$finalPayObj->pension_fund->acr_balance_staff)) }}</td>
    </tr>

    {{--    Set Pension Fund Company 5% --}}
    <tr>
        <td style="text-align: right">Pension Fund Company</td>
        <td>
            {{ @$finalPayObj->pension_fund->pf_company_description }}
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right;">{{ number_format(floatval(@$finalPayObj->pension_fund->acr_balance_company)) }}</td>
    </tr>

    {{--    Set Seniority --}}
    <tr>
        <td style="text-align: right">Seniority</td>
        <td>
            {{ @$finalPayObj->seniority->description }}
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right;">{{ number_format(floatval(@$finalPayObj->seniority->amount)) }}</td>
    </tr>

    {{--    Set Telephone --}}
    <tr>
        <td style="text-align: right">Telephone</td>
        <td>
            {{ @$finalPayObj->telephone->description }}
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right;">{{ number_format(floatval(@$finalPayObj->telephone->amount)) }}</td>
    </tr>

    {{--    Set Net Pay --}}
    <tr>
        <td style="text-align: right">
            <b>Net Pay</b>
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: right;">
            <b>
                {{ number_format(floatval(@$finalPayObj->net_pay)) }}
            </b>
        </td>
    </tr>

    </tbody>

</table>