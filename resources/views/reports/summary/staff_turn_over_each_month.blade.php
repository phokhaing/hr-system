
<table>
    <thead>
    <tr>
        <th>Branch Name</th>
        <th>Branch Code</th>
        <th>January</th>
        <th>February</th>
        <th>March</th>
        <th>April</th>
        <th>May</th>
        <th>June</th>
        <th>July</th>
        <th>Auguest</th>
        <th>September</th>
        <th>October</th>
        <th>November</th>
        <th>December</th>
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
                <td>{{ @$value->january }}</td>
                <td>{{ @$value->february }}</td>
                <td>{{ @$value->march }}</td>
                <td>{{ @$value->april }}</td>
                <td>{{ @$value->may }}</td>
                <td>{{ @$value->june }}</td>
                <td>{{ @$value->july }}</td>
                <td>{{ @$value->auguest }}</td>
                <td>{{ @$value->september }}</td>
                <td>{{ @$value->october }}</td>
                <td>{{ @$value->november }}</td>
                <td>{{ @$value->december }}</td>
                <td>{{ @$value->total_staff - $value->total_female }}</td>
                <td>{{ @$value->total_female }}</td>
                <td>{{ @$value->total_staff }}</td>
            </tr>
        @endforeach
        </tbody>
    @endif
</table>