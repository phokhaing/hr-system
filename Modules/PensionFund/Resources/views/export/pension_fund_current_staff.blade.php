<table class="table table-striped">
    <tr>
        <th>No.</th>
        <th>Staff ID</th>
        <th>Staff Full Name KH</th>
        <th>Staff Full Name En</th>
        <th>Gender</th>
        <th>Position</th>
        <th>Department/Branch</th>
        <th>Company</th>
        <th>Effective Date</th>
        <th>Total Pension Fund 5% Staff</th>
        <th>Total Pension Fund 5% Company</th>
        <th>Balance to be Paid</th>
    </tr>
    <tbody>

    @foreach($items as $key => $value)
        <tr>
            <td>{{ @$key+=1 }}</td>
            <td>{{ @$value->staff_id}}</td>
            <td>{{ @$value->last_name_kh . ' ' . @$value->first_name_kh }}</td>
            <td>{{ @$value->last_name_en . ' ' . @$value->first_name_en }}</td>
            <td>{{ @GENDER[@$value->gender] }}</td>
            <td>{{ @$value->position_name }}</td>
            <td>{{ @$value->department_branch }}</td>
            <td>{{ @$value->company }}</td>
            <td>{{ date('d-M-Y', strtotime(@$value->contract_start_date)) }}</td>
            <td>{{ @$value->total_pension_fund_staff }}</td>
            <td>{{ @$value->total_acr_company }}</td>
            <td>{{ @number_format(@$value->balance_to_paid) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>