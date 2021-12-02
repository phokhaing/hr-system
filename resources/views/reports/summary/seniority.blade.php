
<table>
    <thead>
    <tr>
        <th>Branch Name</th>
        <th>Branch Code</th>
        <th>Less than 3M</th>
        <th>Between >3-6M</th>
        <th>Between >6-12M</th>
        <th>Between >1-2Y</th>
        <th>Between >2-5Y</th>
        <th>Between >5-10Y</th>
        <th>Between >10Y</th>
        <th>Male</th>
        <th>Female</th>
        <th>Total Staff</th>
    </tr>
    </thead>
    @if(isset($contract_object))
        <tbody>
        @foreach($contract_object as $key => $value)
            <tr>
                <td>{{ @$value->branch_name }}</td>
                <td>{{ @$value->branch_code }}</td>
                <td>{{ @$value->less_than_3m }}</td>
                <td>{{ @$value->month_3_to_6 }}</td>
                <td>{{ @$value->month_6_to_12 }}</td>
                <td>{{ @$value->year_1_to_2 }}</td>
                <td>{{ @$value->year_2_to_5 }}</td>
                <td>{{ @$value->year_5_to_10 }}</td>
                <td>{{ @$value->greater_than_10y }}</td>
                <td>{{ @$value->total_staff - $value->total_female }}</td>
                <td>{{ @$value->total_female }}</td>
                <td>{{ @$value->total_staff }}</td>
            </tr>
        @endforeach
        </tbody>
    @endif
</table>