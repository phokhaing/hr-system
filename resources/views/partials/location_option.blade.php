@foreach($data as $value)
    <option value="{{ $value->id }}">{{ $value->name_kh }}</option>
@endforeach
