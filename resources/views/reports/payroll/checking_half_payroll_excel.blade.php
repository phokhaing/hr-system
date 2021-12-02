
<table>
    <thead>
    <tr>
        <th>No</th>
        <th>Staff ID</th>
        <th>Name In English</th>
        <th>Position</th>
        <th>Location</th>
        <th>Base Salary</th>
        <th>Transaction Type</th>
        <th>Half Month</th>
        <th>Currency</th>
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
                <td>
                    {{ $key+1 }}
                </td>
                <td>{{ @$payroll->staff_personal_info->staff_id }}</td>
                <td>{{ @$payroll->staff_personal_info->full_name_english ?? 'N/A' }}</td>
                <td>{{ @$payroll->transaction_object->position->name_kh ?? 'N/A' }}</td>
                <td>{{ @$payroll->transaction_object->branch_department->name_kh ?? 'N/A' }}</td>
                <td>{{ @$baseSalary }}</td>
                <td>{{ @$payroll->transaction_code->name_en ?? 'N/A' }}</td>
                <td>{{ @$payroll->transaction_object->amount ?? '0.00' }}</td>
                <td>{{ @$payroll->transaction_object->ccy ?? 'N/A' }}</td>
            </tr>
        @endforeach
        </tbody>
    @endif
</table>