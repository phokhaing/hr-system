<table class="table table-striped">
    <tr>
        <th>No</th>
        <th>Staff ID</th>
        <th>Full Name KH</th>
        <th>Full Name EN</th>
        <th>Gender</th>
        <th>Request Date</th>
        <th>Reason</th>
        <th>Company</th>
        <th>Branch / Department</th>
        <th>Position</th>
    </tr>
    <tbody>
    @foreach($request_resigns as $key => $value)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $value->staffPersonalInfo->staff_id }}</td>
            <td>{{ $value->staffPersonalInfo->last_name_kh.' '.$value->staffPersonalInfo->first_name_kh }}</td>
            <td>{{ $value->staffPersonalInfo->last_name_en.' '.$value->staffPersonalInfo->first_name_en }}</td>
            <td>{{ GENDER[$value->staffPersonalInfo->gender] }}</td>
            <td>{{ date('d-M-Y', strtotime($value->resign_object->request_date)) }}</td>
            <td>{{ $value->resign_object->reason }}</td>
            <td>{{ $value->resign_object->company->name_kh }} {{ '('.$value->resign_object->company->short_name.')' }}</td>
            <td>{{ $value->resign_object->branch_department->name_kh }} {{ '('.$value->resign_object->branch_department->short_name.')' }}</td>
            <td>{{ $value->resign_object->position->name_kh }} {{ '('.$value->resign_object->position->short_name.')' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>