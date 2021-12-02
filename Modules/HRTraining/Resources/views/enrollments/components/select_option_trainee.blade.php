<option value="0"><< Select Trainee >></option>
@foreach(@$contracts as $key => $value)

    @php
        $staffInfo = @$value->staff;
        $fullname = @$staffInfo->first_name_kh . ' ' . @$staffInfo->last_name_kh;

        $companyObj = @$value->contract_object['company'];
        $positionObj = @$value->contract_object['position'];

        $gender = GENDER[@$staffInfo->gender] ?? 'N/A';
    @endphp

    <option value="{{ @$staffInfo->staff_id }}"
            data-staff_id="{{@$staffInfo->staff_id}}"
            data-fullname="{{@$fullname}}"
            data-gender="{{@$gender}}"
            data-position="{{@$positionObj['name_kh']}}"
            data-position_id="{{@$positionObj['id']}}"
            data-company="{{@$companyObj['name_kh']}}"
            data-company_id="{{@$companyObj['id']}}"
    >
        {{ @$fullname }}
    </option>