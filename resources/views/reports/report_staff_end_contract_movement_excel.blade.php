
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Staff ID</th>
            <th>Full name KH</th>
            <th>FUll name EN</th>
            <th>Gender</th>
            <th>phone</th>
            <th>Position</th>
            <th>Branch/Department</th>
            <th>Company</th>
            <th>Contract Type</th>
            <th>Contract Start Date</th>
            <th>Contract End Date</th>
            <th>Contract (Month)</th>
            {{-- <th>កិច្ចសន្យាការងារ</th>
            <th>លិខិតធានា</th>
            <th>តាមដានប្រវត្ដិរូប(Home-visit)</th>
            <th>កិច្ចសន្យាបន្ថែម</th>
            <th>បទពិពណ៌នាការងារ</th> --}}
        </tr>
    </thead>
    @if(isset($contracts))
    <tbody>
    @foreach($contracts as $key => $contract)
    @php

    $profile = @$contract->staffPersonalInfo;
    $position = @$contract->contract_object['position'];
    $company = @$contract->contract_object['company'];
    $branchDepartment = @$contract->contract_object['branch_department'];
    $array_flip = array_flip(CONTRACT_TYPE);

    $documentContract = @$profile->documents->where('staff_document_type_id', 21)->first();
    $ensure = @$profile->documents->where('staff_document_type_id', 7)->first();
    $home_visit = @$profile->documents->where('staff_document_type_id', 8)->first();
    $contract_more = @$profile->documents->where('staff_document_type_id', 6)->first();
    $descript_job = @$profile->documents->where('staff_document_type_id', 10)->first();

    @endphp
    <tr>
        <td>
            {{ $key+1 }}
        </td>
        <td>{{ @$profile->staff_id }}</td>
        <td>{{ @$profile->last_name_kh." ".@$profile->first_name_kh }}</td>
        <td>{{ @$profile->last_name_en." ".@$profile->first_name_en }}</td>
        <td>{{ @GENDER[@$profile->gender] }}</td>
        <td>{{ @$profile->phone}}</td>
        <td>{{ @$position['name_kh'] ?? 'N/A' }}</td>
        <td>{{ @$branchDepartment['name_kh'] ?? 'N/A' }}</td>
        <td>{{ @$company['short_name'] ?? 'N/A' }}</td>
        <td>
            <label class="label label-info">{{ isset($contract->contract_type) ?
                $array_flip[$contract->contract_type] : '' }}</label>
        </td>
        <td>
            {{ date('d-M-Y', strtotime(@$contract->start_date)) }}
        </td>
        <td>
            {{ date('d-M-Y', strtotime(@$contract->end_date)) }}
        </td>
        <td>
            {{ find_duration_contract(@$contract->start_date, @$contract->end_date) }}
        </td>
    </tr>
    @endforeach
    </tbody>
    @endif
</table>