<table>
    <tr style="background: antiquewhite; font-weight: bold;">
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
        <th>Resign Date</th>
        <th>CEO Approved</th>
        <th>Reason</th>
        <th>លិខិតលាឈប់</th>
    </tr>
    @if(isset($resigns))
        @foreach($resigns as $key => $resign)
            <tr>
                <td>
                    {{ $key+1 }}
                </td>
                <td>{{ $resign->staffInfo->emp_id_card }}</td>
                <td>{{ $resign->personalInfo->last_name_kh." ".$resign->personalInfo->first_name_kh }}</td>
                <td>{{ $resign->personalInfo->last_name_en." ".$resign->personalInfo->first_name_en }}</td>
                <td>{{ ($resign->personalInfo->gender === 0) ? 'Male' : 'Female' }}</td>
                <td>{{ $resign->staffInfo->position->name_kh ?? 'N/A' }}</td>
                <td>{{ $resign->staffInfo->department->name_kh ?? 'N/A' }}</td>
                <td>{{ $resign->staffInfo->branch->name_kh ?? 'N/A' }}</td>
                <td>{{ $resign->staffInfo->company->short_name ?? 'N/A' }}</td>
                <td>
                    {{ ($resign->staffInfo->employment_date != "") ?
                     date('d-M-Y', strtotime($resign->staffInfo->employment_date)) : 'N/A' }}
                </td>
                <td>
                    {{ ($resign->resign_date != "") ?
                    date('d-M-Y', strtotime($resign->resign_date)) : 'N/A' }}
                </td>
                <td>
                    {{ ($resign->approved_date != "") ?
                    date('d-M-Y', strtotime($resign->approved_date)) : 'N/A' }}
                </td>
                <td>{{ ($resign->reason) ?? 'N/A' }}</td>
                <td>{{ isset($resign->file_reference) ? 'Yes' : 'No' }}</td>
            </tr>
        @endforeach
    @endif
</table>