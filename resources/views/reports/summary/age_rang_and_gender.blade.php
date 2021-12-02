
<table>
    <thead>
    <tr>
        <th>Branch Name</th>
        <th>Male</th>
        <th>Female</th>
        <th>Less than 20</th>
        <th>Between 20 to 30</th>
        <th>Between 30 to 40</th>
        <th>Between 40 to 50</th>
        <th>Between 50 to 55</th>
        <th>Greater than 55</th>
        <th>Total Staff</th>
    </tr>
    </thead>
    @if(isset($age_gender_object))
        <tbody>
        @foreach($age_gender_object as $key => $value)
            <tr>
                <td>{{ @$value->branch_name }}</td>
                <td>{{ $value->total_staff - $value->total_female }}</td>
                <td>{{ $value->total_female }}</td>
                <td>{{ @$value->below_or_equal_20_y }}</td>
                <td>{{ @$value->between_21_and_30_y }}</td>
                <td>{{ @$value->between_31_and_40_y }}</td>
                <td>{{ @$value->between_41_and_50_y }}</td>
                <td>{{ @$value->between_51_and_55_y }}</td>
                <td>{{ @$value->over_55_y }}</td>
                <td>{{ @$value->total_staff }}</td>
            </tr>
        @endforeach
        </tbody>
    @endif
</table>