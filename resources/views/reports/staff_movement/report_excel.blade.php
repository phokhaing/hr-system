
<table class="table table-striped">
    <tr class="bg-gray-active">
        <th>ID</th>
        <th>Staff ID</th>
        <th>Full name KH</th>
        <th>FUll name EN</th>
        <th>Gender</th>
        <th>Old company</th>
        <th>New company</th>
        <th>Old Branch</th>
        <th>New Branch</th>
        <th>Old department</th>
        <th>New department</th>
        <th>Old position</th>
        <th>New position</th>
        <th>Effective date</th>
        <th>Transfer work to</th>
        <th>Get work from</th>
    </tr>
    @if(isset($movements))
        @foreach($movements as $key => $movement)
            <tr>
                <td>
                    {{ $key+1 }}
                </td>
                <td>{{ $movement->profile->emp_id_card }}</td>
                <td>{{ $movement->personalInfo->last_name_kh." ".$movement->personalInfo->first_name_kh }}</td>
                <td>{{ $movement->personalInfo->last_name_en." ".$movement->personalInfo->first_name_en }}</td>
                <td>{{ ($movement->personalInfo->gender === 0) ? 'Male' : 'Female' }}</td>
                <td>{{ $movement->company->short_name ?? 'N/A' }}</td>
                <td>{{ $movement->to_company->short_name ?? 'N/A' }}</td>
                <td>{{ $movement->branch->name_kh ?? 'N/A' }}</td>
                <td>{{ $movement->to_branch->name_kh ?? 'N/A' }}</td>
                <td>{{ $movement->department->name_kh ?? 'N/A' }}</td>
                <td>{{ $movement->to_department->name_kh ?? 'N/A' }}</td>
                <td>{{ $movement->position->name_kh ?? 'N/A' }}</td>
                <td>{{ $movement->to_position->name_kh ?? 'N/A' }}</td>
                <td>{{ date('d-M-Y', strtotime($movement->effective_date)) ?? 'N/A' }}</td>
                <td>{{ $movement->transfer_to_name ?? 'N/A' }}</td>
                <td>{{ $movement->get_work_form_name ?? 'N/A' }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="16">
                <h4 class="text-center">Empty</h4>
            </td>
        </tr>
    @endif
</table>

