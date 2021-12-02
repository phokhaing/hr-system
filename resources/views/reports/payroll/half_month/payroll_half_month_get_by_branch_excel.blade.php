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
            <b>Office:</b>
        </td>
        <td colspan="11">
            <b>Consolidate</b>
        </td>
    </tr>
    <tr>
        <td colspan="13">
            <b>Summary Payroll By Branch</b>
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

<table>
    <thead>
    <tr>
        <th style="background-color: #d5d5d5">
            <b>No</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Official Code</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Short Name</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Branch Name In English</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Status</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>M</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>F</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b># of staff</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Gross base salary</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Gross basic Salary</b>
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
        @php
            $totalM = 0;
            $totalF = 0;
            $totalGender = 0;
            $totalGbaseSalary = 0;
            $totalGbasicSalary = 0;
            $totalHalfMonth = 0;
            $totalNetPay = 0;
        @endphp
        <tbody>
        @foreach($payroll_object as $key => $payroll)
            <?php
            @$totalM += @$payroll->male;
            @$totalF += @$payroll->female;
            @$totalGender += @$payroll->num_of_staff;
            @$totalGbaseSalary += @$payroll->gross_base_salary;
            @$totalGbasicSalary += @$payroll->gross_basic_salary;
            @$totalHalfMonth += @$payroll->half_month;
            @$totalNetPay += @$payroll->net_half_month;
            ?>
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ @$payroll->branch_code }}</td>
                <td>{{ @$payroll->branch_short_name  }}</td>
                <td>{{ @$payroll->branch_name_en  }}</td>
                <td>{{ @$payroll->company_short_name  }}</td>
                <td>{{ @$payroll->male  }}</td>
                <td>{{ @$payroll->female  }}</td>
                <td>{{ @$payroll->num_of_staff  }}</td>
                <td>{{ @$payroll->gross_base_salary  }}</td>
                <td>{{ @$payroll->gross_basic_salary  }}</td>
                <td>{{ @$payroll->half_month  }}</td>
                <td>{{ @$payroll->net_half_month  }}</td>
            </tr>
        @endforeach
        <tr></tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
                <b>Grand Total</b>
            </td>
            <td>
                <b>{{ @$totalM }}</b>
            </td>
            <td>
                <b>{{ @$totalF }}</b>
            </td>
            <td>
                <b>{{ @$totalGender }}</b>
            </td>
            <td>
                <b>{{ @$totalGbaseSalary }}</b>
            </td>
            <td>
                <b>{{ @$totalGbasicSalary }}</b>
            </td>
            <td>
                <b>{{ @$totalHalfMonth }}</b>
            </td>
            <td>
                <b>{{ @$totalNetPay }}</b>
            </td>
        </tr>
        </tbody>
    @endif
</table>