@foreach($data as $key => $value)
@if(old('position_code') == $value->code)
<option value="{{ $value->code }}" selected>{{ $value->name_kh }}</option>
@else
<option value="{{ $value->code }}">{{ $value->name_kh }}</option>
@endif
@endforeach