<div class="panel-body" style="overflow-x: auto;">
    <table class="table table-striped">
        <tr class="bg-gray-active">
            <th>ID</th>
            <th>Staff ID</th>
            <th>Full Name KH</th>
            <th>FUll Name EN</th>
            <th>Gender</th>
            <th>phone</th>
            <th>Position</th>
            <th>Branch/Department</th>
            <th>Company</th>
            <th>Contract Type</th>
            <th>Contract Start Date</th>
            <th>Contract End Date</th>
            <th>Contract (Month)</th>
            <th>កិច្ចសន្យាការងារ</th>
            <th>លិខិតធានា</th>
            <th>តាមដានប្រវត្ដិរូប(Home-Visit)</th>
            <th>កិច្ចសន្យាបន្ថែម</th>
            <th>បទពិពណ៌នាការងារ</th>

        </tr>
        @if(isset($contracts))
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
                    <td>{{ GENDER[$profile->gender] }}</td>
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
                    <td>
                        @if($documentContract)
                            @if($documentContract->check == 1)
                                Yes
                            @elseif($documentContract->not_have == 1)
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
        @else
            <tr>
                <td colspan="18">
                    <h4 class="text-center">Empty</h4>
                </td>
            </tr>
        @endif
    </table>
    @if(isset($contracts))
        {{ $contracts->appends($page)->links() }}
    @endif
</div>