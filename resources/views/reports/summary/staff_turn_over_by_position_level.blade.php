
<table>
    <thead>
    <tr>
        <th>Position Level</th>
        <th>Total Active</th>
        <th>Total Resigned</th>
        <th>Turn Over Rate</th>
        <th>Resigned Male</th>
        <th>Resigned Female</th>
    </tr>
    </thead>
    @if(isset($contract_object))
        <tbody>
        @foreach($contract_object as $key => $value)
            <tr>
                <td>{{ @$value->position_level }}</td>
                <td>{{ @$value->total_active }}</td>
                <td>{{ @$value->total_resigned }}</td>
                <td>{{ (@$value->total_active !== 0) ? ($value->total_resigned / $value->total_active) : 0 }}</td>
                <td>{{ (@$value->total_resigned - $value->resign_female) }}</td>
                <td>{{ @$value->resign_female }}</td>
            </tr>
        @endforeach
        </tbody>
    @endif
</table>