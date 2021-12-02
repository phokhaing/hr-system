<option value="">>> {{ __('label.branch') }} <<</option>
@foreach($branches as $key => $value)
    <option value="{{ $value->id }}">{{ $value->name_kh }}</option>
@endforeach