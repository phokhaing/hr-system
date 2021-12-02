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
        <th>Employment Date</th>
        <th>Effective Date</th>
        <th>Base Salary</th>
        <th>Addition</th>
        <th>Pension Fund 5%(Staff)</th>
        <th>Date</th>
    </tr>
    <tbody>

    @foreach($items as $key => $value)

        @php
            $contract = @$value->contract;
            $staffPersonalInfo = @$value->staffPersonalInfo;
            $contractObj = @$contract->contract_object;
        @endphp

        <tr>
            <td>{{ @$key+=1 }}</td>
            <td>{{ @$staffPersonalInfo->staff_id }}</td>
            <td>{{ @$staffPersonalInfo->last_name_kh . ' ' . @$staffPersonalInfo->first_name_kh }}</td>
            <td>{{ @$staffPersonalInfo->last_name_en . ' ' . @$staffPersonalInfo->first_name_en }}</td>
            <td>{{ @GENDER[@$staffPersonalInfo->gender] }}</td>
            <td>{{ @$contractObj['position']['name_kh'] ?? "N/A" }}</td>
            <td>{{ @$contractObj['branch_department']['name_kh'] ?? "N/A" }}</td>
            <td>{{ @$contractObj['company']['name_kh'] ?? "N/A" }}</td>
            <td>{{ date('d-M-Y', strtotime(@$value->json_data->date_of_employment)) }}</td>
            <td>{{ date('d-M-Y', strtotime(@$value->json_data->effective_date)) }}</td>
            <td>{{ @number_format(convertSalaryFromStrToFloatValue(@$value->json_data->gross_base_salary))}}</td>
            <td>{{ @number_format(@$value->json_data->addition) }}</td>
            <td>{{ @number_format(@$value->json_data->acr_balance_staff) }}</td>
            <td>{{ date('d-M-Y', strtotime(@$value->json_data->report_date)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>