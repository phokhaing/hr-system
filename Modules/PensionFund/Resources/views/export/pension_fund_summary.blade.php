<table class="table table-striped">
    <tr>
        <th>No.</th>
        <th>Company Code</th>
        <th>Company</th>
        <th>Department/Branch</th>
        <th>Total Pension Fund 5% Staff</th>
    </tr>
    <tbody>

    @foreach($items as $key => $value)
        <tr>
            <td>{{ @$key+=1 }}</td>
            <td>{{ @$value->company_code }}</td>
            <td>{{ @$value->company_name }}</td>
            <td>{{ @$value->department_branch }}</td>
            <td>{{ @number_format(@$value->total_pension_fund) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>