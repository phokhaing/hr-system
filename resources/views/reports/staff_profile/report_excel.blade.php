
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Staff ID</th>
            <th>Full name KH</th>
            <th>FUll name EN</th>
            <th>Gender</th>
            <th>Position</th>
            <th>Department</th>
            <th>Branch</th>
            <th>Company</th>
            <th>Employment Date</th>
            <th>Probation (Month)</th>
            <th>Contract (Month)</th>
            <th>កិច្ចសន្យាការងារ</th>
            <th>លិខិតធានា</th>
            <th>តាមដានប្រវត្ដិរូប(Home-visit)</th>
            <th>កិច្ចសន្យាបន្ថែម</th>
            <th>លិខិតអះអាង</th>
            <th>បទពិពណ៌នាការងារ</th>
        </tr>
    </thead>
    @if(isset($profiles))
    <tbody>
        @foreach($profiles as $key => $profile)
            @php
                $contract = $profile->documents->where('staff_document_type_id', 21)->first();
                $ensure = $profile->documents->where('staff_document_type_id', 7)->first();
                $home_visit = $profile->documents->where('staff_document_type_id', 8)->first();
                $contract_more = $profile->documents->where('staff_document_type_id', 6)->first();
                $descript_job = $profile->documents->where('staff_document_type_id', 10)->first();
            @endphp

            <tr>
                <td>
                    {{ $key+1 }}
                </td>
                <td>{{ $profile->profile->emp_id_card }}</td>
                <td>{{ $profile->last_name_kh." ".$profile->first_name_kh }}</td>
                <td>{{ $profile->last_name_en." ".$profile->first_name_en }}</td>
                <td>{{ ($profile->gender === 0) ? 'Male' : 'Female' }}</td>
                <td>{{ $profile->profile->position->name_kh ?? 'N/A' }}</td>
                <td>{{ $profile->profile->department->name_kh ?? 'N/A' }}</td>
                <td>{{ $profile->profile->branch->name_kh ?? 'N/A' }}</td>
                <td>{{ $profile->profile->company->short_name ?? 'N/A' }}</td>
                <td>{{ date('d-M-Y', strtotime($profile->profile->employment_date)) ?? 'N/A' }}</td>
                <td>{{ $profile->profile->probation_duration ?? 'N/A' }}</td>
                <td>{{ $profile->profile->contract_duration ?? 'N/A' }}</td>
                <td>
                    @if($contract)
                        @if($contract->check == 1)
                            Yes
                        @elseif($contract->not_have == 1)
                            No
                        @else
                        @endif
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if($ensure)
                        @if($ensure->check == 1)
                            Yes
                        @elseif($ensure->not_have == 1)
                            No
                        @else
                        @endif
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if($home_visit)
                        @if($home_visit->check == 1)
                            Yes
                        @elseif($home_visit->not_have == 1)
                            No
                        @else
                        @endif
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if($contract_more)
                        @if($contract_more->check == 1)
                            Yes
                        @elseif($contract_more->not_have == 1)
                            No
                        @else
                        @endif
                    @else
                        N/A
                    @endif
                </td>
                <td>

                </td>
                <td>
                    @if($descript_job)
                        @if($descript_job->check == 1)
                            Yes
                        @elseif($descript_job->not_have == 1)
                            No
                        @else
                        @endif
                    @else
                        N/A
                    @endif
                </td>

            </tr>
        @endforeach
    </tbody>
    @endif
</table>