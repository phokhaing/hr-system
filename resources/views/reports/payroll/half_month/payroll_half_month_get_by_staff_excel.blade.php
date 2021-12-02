<table style="width: 100%">
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr>
        <td colspan="13">
            <b>{{ @$company->name_en }}</b>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Branch:</b>
        </td>
        <td colspan="11">
            <b>{{ @$company->short_name }}</b>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>For:</b>
        </td>
        <td colspan="11">
            <b>{{ @$transaction_date ? date('d-M-Y', strtotime(@$transaction_date)) : '' }}</b>
        </td>
    </tr>
</table>

<table style="width: 100%">
    <thead>
    <tr>
        <th style="background-color: #d5d5d5">
            <b>No</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>SID</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Name In English</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Sex</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Position</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Bank Account Number</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Location</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>D.O.E</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Effective date</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Gross base salary</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Gross basic salary</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Half Month</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Net Pay Half Month</b>
        </th>
    </tr>
    </thead>
    @if(isset($payroll_object))
        <tbody>
        @php
            $grandTotalGrossBaseSalary = 0;
            $grandTotalGrossBasicSalary = 0;
            $totalHalfPay = 0;
            $grandTotalNetPay = 0;
        @endphp
        @foreach($payroll_object as $key => $payroll)
            <?php
            $grandTotalGrossBaseSalary += @convertSalaryFromStrToFloatValue(@$payroll->contract->contract_object['salary']);
            $grandTotalGrossBasicSalary += @$payroll->transaction_object->gross_base_salary;
            $grandTotalNetPay += @$payroll->transaction_object->amount;
            ?>
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ @$payroll->staff_personal_info->staff_id }}</td>
                <td>{{ @$payroll->staff_personal_info->full_name_english }}</td>
                <td>{{ (@$payroll->staff_personal_info->gender == 0) ? "M" : "F" }}</td>
                <td>{{ @$payroll->contract->contract_object['position']['name_en'] }}</td>
                <td>{{ @$payroll->staff_personal_info->bank_acc_no }}</td>
                <td>{{ @$payroll->contract->contract_object['branch_department']['name_en'] }}</td>
                <td>{{ date_readable($payroll->contract->start_date) }}</td>
                <td>{{ date_readable($payroll->contract->start_date) }}</td>
                <td>{{ convertSalaryFromStrToFloatValue(@$payroll->contract->contract_object['salary']) }}</td>
                <td>{{ @$payroll->transaction_object->gross_base_salary }}</td>
                <td>{{ @$payroll->transaction_object->amount }}</td>
                <td>{{ @$payroll->transaction_object->amount }}</td>
            </tr>
        @endforeach
        <tr></tr>
        <tr>
            <td></td>
            <td></td>
            <td>
                <b>Grand Total</b>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
                <b>{{ @$grandTotalGrossBaseSalary }}</b>
            </td>
            <td>
                <b>{{ @$grandTotalGrossBasicSalary }}</b>
            </td>
            <td>
                <b>{{ @$grandTotalNetPay }}</b>
            </td>
            <td>
                <b>{{ @$grandTotalNetPay }}</b>
            </td>
        </tr>
        </tbody>
    @endif
</table>