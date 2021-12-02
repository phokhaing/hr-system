<table class="table table-striped">
    <tr>
        <th>Staff ID</th>
        <th>Staff Name</th>
        <th>Course</th>
        <th>Category</th>
        <th>Department/Branch</th>
        <th>Training Start Date</th>
        <th>Training End Date</th>
        <th>Is Trained</th>
    </tr>
    <tbody>

    @foreach($items as $key => $value)
        <tr>
            <td>{{ @$value->staff_id }}</td>
            <td>{{ @$value->last_name_en.' '.@$value->first_name_en }}</td>
            <td>{{ @$value->course_title }}</td>
            <td>{{ @$value->category_title }}</td>
            <td>{{ @$value->branch_department_title . ' (' . @$value->company_short_name . ')'}}</td>
            <td>{{ date('d/m/Y', strtotime(@$value->start_date)) }}</td>
            <td>{{ date('d/m/Y', strtotime(@$value->end_date)) }}</td>
            <td>{{ @$value->is_trained ? 'YES' : 'NO' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>