
<table>
    <thead>
    <tr>
        <th>Position Level</th>
        <th>Probation</th>
        <th>Contract 1 Year</th>
        <th>Contract 2 Year</th>
        <th>Contract Regular</th>
        <th>Female</th>
        <th>Male</th>
        <th>Total Staff</th>
    </tr>
    </thead>
    @if(isset($contract_object))
        <tbody>
        @foreach($contract_object as $key => $value)
            <tr>
                <td>{{ @$value->position_level }}</td>
                <td>{{ @$value->probation }}</td>
                <td>{{ @$value->onw_year }}</td>
                <td>{{ @$value->two_year }}</td>
                <td>{{ @$value->regular }}</td>
                <td>{{ $value->total_female }}</td>
                <td>{{ $value->total_staff - $value->total_female }}</td>
                <td>{{ @$value->total_staff }}</td>
            </tr>
        @endforeach
        </tbody>
    @endif
</table>