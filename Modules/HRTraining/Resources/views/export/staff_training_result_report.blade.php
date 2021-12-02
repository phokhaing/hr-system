<table class="table table-striped">
    <tr>
        <th>No.</th>
        <th>Company ID Card</th>
        <th>Staff Full Name</th>
        <th>Gender</th>
        <th>Company</th>
        <th>Department/Branch</th>
        <th>Position</th>
        <th>Training Course</th>
        <th>Training Class</th>
        <th>Training Duration (Days)</th>
        <th>Training Start Date</th>
        <th>Training End Date</th>
        <th>Training Progress</th>
        <th>Enrollment Date</th>
        <th>Total Score</th>
        <th>Average</th>
    </tr>
    <tbody>

    @foreach($items as $key => $value)

        @php
            $staff = @$value->staff;
            $contract = @$value->contract;
            $company = @$contract->contract_object['company'];
            $department = @$contract->contract_object['branch_department'];
            $position = @$contract->contract_object['position'];
            $enrollment = @$value->enrollment;
            $course = @$enrollment->course;
            $trainingResult = @$value->traineeResult;

        @endphp
        <tr>
            <td>{{ @$key+=1 }}</td>
            <td>{{ substr(@$contract->staff_id_card, 3, (strlen(@$contract->staff_id_card))) }}</td>
            <td>{{ @$staff->last_name_kh.' '.@$staff->first_name_kh }}</td>
            <td>{{ @GENDER[@$staff->gender] }}</td>
            <td>{{ @$company['name_kh'] ?? "N/A" }}</td>
            <td>{{ @$department['name_kh'] ?? "N/A" }}</td>
            <td>{{ @$position['name_kh'] ?? "N/A" }}</td>
            <td>{{ @$course->json_data->title }}</td>
            <td>{{ @CLASS_TYPE_KEY[@$enrollment->class_type] }}</td>
            <td>{{ @$enrollment->json_data->duration }}</td>
            <td>{{ date('d/m/Y', strtotime(@$enrollment->json_data->start_date)) }}</td>
            <td>{{ date('d/m/Y', strtotime(@$enrollment->json_data->end_date)) }}</td>
            <td>{{ getTraineeProgressStatus(@$this->training_status) ?: "N/A" }}</td>
            <td>{{ date('d/m/Y', strtotime(@$enrollment->created_at)) }}</td>
            <td>{{ number_format(@$trainingResult->json_data->total_point, 2) }}</td>
            <td>{{ number_format(@$trainingResult->json_data->average, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>