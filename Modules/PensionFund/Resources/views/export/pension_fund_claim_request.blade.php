<table class="table table-striped">
    <tr>
        <th>No.</th>
        <th>Company ID Card</th>
        <th>Staff Full Name KH</th>
        <th>Staff Full Name EN</th>
        <th>Gender</th>
        <th>Position</th>
        <th>Department/Branch</th>
        <th>Company</th>
        <th>Pension Fund 5% Staff</th>
        <th>Pension Fund Company</th>
        <th>Interest Rate</th>
        <th>Total Benefit After Tax</th>
        <th>Net Pay</th>
        <th>Claim Request Date</th>
    </tr>
    <tbody>

    @foreach($items as $key => $value)

        @php
            $personal_info = @$value->staffPersonalInfo;
            $blockDateObj = @$value->json_data->block_date[0];
            $pensionFundInfo = @$value->json_data->pension_fund;

            $contract = @$value->contract;
            $contractObj = @$contract->contract_object;
        @endphp

        <tr>
            <td>{{ $key+=1 }}</td>
            <td>{{ substr(@$value->contract->staff_id_card, 3, (strlen(@$value->contract->staff_id_card))) }}</td>
            <td>{{ @$personal_info->last_name_kh.' '.@$personal_info->first_name_kh }}</td>
            <td>{{ @$personal_info->last_name_en.' '.@$personal_info->first_name_en }}</td>
            <td>{{ @GENDER[@$personal_info->gender] }}</td>
            <td>{{ @$contractObj['position']['name_kh'] ?? "N/A" }}</td>
            <td>{{ @$contractObj['branch_department']['name_kh'] ?? "N/A" }}</td>
            <td>{{ @$contractObj['company']['name_kh'] ?? "N/A" }}</td>
            <td>{{ @$pensionFundInfo->acr_balance_staff }}</td>
            <td>{{ @$pensionFundInfo->acr_balance_company }}</td>
            <td>{{ @$pensionFundInfo->interest_rate . "%" }}</td>
            <td>{{ @$value->json_data->total_benefit_after_tax }}</td>
            <td>{{ @$value->json_data->net_pay }}</td>
            <td>{{ date('d-M-Y', strtotime(@$value->created_at)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>