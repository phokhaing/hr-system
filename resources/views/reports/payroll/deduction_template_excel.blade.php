
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Contract Id</th>
        <th>FUll name EN</th>
        <th>Transaction Code</th>
        <th>Company</th>
        <th>Branch/Department</th>
        <th>Amount</th>
        <th>Currency</th>
    </tr>
    </thead>
    @if(isset($temp_payrolls))
        <tbody>
        @foreach($temp_payrolls as $key => $payroll)
            <tr>
                <td>
                    {{ $key+1 }}
                </td>
                <td>{{ @$payroll->contract_id }}</td>
                <td>{{ @$payroll->staff_personal_info->full_name_english ?? 'N/A' }}</td>
                <td></td>
                <td>{{ @$payroll->transaction_object->company->name_kh ?? 'N/A' }}</td>
                <td>{{ @$payroll->transaction_object->branch_department->name_kh ?? 'N/A' }}</td>
                <td>0</td>
                <td>KHR</td>
            </tr>
        @endforeach
        </tbody>
    @endif
</table>