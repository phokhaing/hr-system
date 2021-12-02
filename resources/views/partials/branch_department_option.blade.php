@foreach($data as $key => $value)
@if($value->code == request('branch_department_code'))
<option value="{{ $value->code }}" selected>{{ $value->name_km }}</option>
@else
<option value="{{ $value->code }}">{{ $value->name_km }}</option>
@endif
@endforeach