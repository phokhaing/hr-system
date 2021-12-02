
<table>
    <thead>
    <tr>
        <th>Staff ID</th>
        <th>Name In English</th>
        <th>Position</th>
        <th>Location</th>
        <th>Base Salary</th>
        <th>Half Amount</th>
        <th>Currency</th>
        <th>Transaction Date</th>
        <th>Posted By</th>
    </tr>
    </thead>
    @if(isset($temp_payrolls))
        <tbody>
        @foreach($temp_payrolls as $key => $payroll)
            <?php
            if (!is_null(@$payroll->transaction_object->gross_base_salary)) {
                $baseSalary = @$payroll->transaction_object->gross_base_salary;
            } else {
                $baseSalary = @$payroll->contract->contract_object["salary"];
            }
            ?>
            <tr>
                <td>{{ @$payroll->staff_personal_info->staff_id }}</td>
                <td>{{ @$payroll->staff_personal_info->full_name_english ?? 'N/A' }}</td>
                <td>{{ @$payroll->contract->contract_object['position']['name_kh'] }}</td>
                <td>{{ @$payroll->contract->contract_object['branch_department']['name_kh'] }}</td>
                <td>{{ @$baseSalary }}</td>
                <td>{{ @$payroll->transaction_object->amount ?? 'N/A' }}</td>
                <td>{{ @$payroll->transaction_object->ccy ?? 'N/A' }}</td>
                <td>
                    {{ date('d-M-Y', strtotime(@$payroll->transaction_date)) }}
                </td>
                <td>{{ @$payroll->user->full_name }}</td>
            </tr>
        @endforeach
        </tbody>
    @endif
</table>