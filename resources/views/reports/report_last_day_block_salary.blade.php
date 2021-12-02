<table style="width: 100%; border-color: black">
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tbody>
    <tr>
        <td colspan="9">
            <b>
                {{ is_null(@$company) ? 'All' : @$company->name_en }}
            </b>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Branch:</b>
        </td>
        <td colspan="7">
            <b>{{ is_null(@$company) ? 'All' : @$company->short_name }}</b>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>For:</b>
        </td>
        <td colspan="7">
            <b>
                {{ @$date_from . ' - ' . @$date_end }}
            </b>
        </td>
    </tr>
    </tbody>
</table>

<table style="width: 100%; border-color: black">
    <thead>
    <tr>
        <th style="background-color: #d5d5d5"><b>No.</b></th>
        <th style="background-color: #d5d5d5"><b>SID</b></th>
        <th style="background-color: #d5d5d5"><b>Name in English</b></th>
        <th style="background-color: #d5d5d5"><b>Sex</b></th>
        <th style="background-color: #d5d5d5"><b>Position</b></th>
        <th style="background-color: #d5d5d5"><b>Location</b></th>
        <th style="background-color: #d5d5d5"><b>D.O.E</b></th>
        <th style="background-color: #d5d5d5"><b>Date Block</b></th>
        <th style="background-color: #d5d5d5"><b>Remark</b></th>
    </tr>
    </thead>
    <tbody>
    @php
        $index = 0;
    @endphp
    @foreach(@$items as $key => $value)
        <?php
        $index++;
        $personal_info = @$value->staffPersonalInfo;
        $contractObj = @$value->contract_object;
        ?>
        <tr>
            <td>{{ @$index }}</td>
            <td>{{ @$personal_info->staff_id }}</td>
            <td>
                {{ @$personal_info->last_name_en . ' ' . @$personal_info->first_name_en }}
            </td>
            <td>
                {{ @GENDER_EN[@$personal_info->gender ?? '0'] }}
            </td>
            <td>
                {{ @$contractObj['position']['name_en'] }}
            </td>
            <td>
                {{ @$contractObj['branch_department']['name_en'] }}
            </td>
            <td>
                {{ date('d-M-Y', strtotime(@$value->start_date)) }}
            </td>
            <td>
                {{ date('d-M-Y', strtotime(@$contractObj['block_salary']['from_date'])) }}
            </td>
            <td>
                {{ @$contractObj['block_salary']['notice'] }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>