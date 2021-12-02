{{--<div class="row">--}}
    {{--<div class="col-sm-12 col-md-12 parent-document">--}}
        {{--<form action="{{ route('document.update') }}" method="post" role="form" enctype="multipart/form-data" id="edit_form_document">--}}
            {{--{{ csrf_field() }}--}}
            {{--<input type="hidden" name="staff_token" value="{{ encrypt($staff->id) }}">--}}
            {{--<input type="hidden" name="num_row" class="num_row" value="{{ old('num_row') }}">--}}

            {{--@if(!empty(old('num_row')))--}}
                {{--<?php  $num_row = old('num_row');  ?>--}}
            {{--@else--}}
                {{--<?php  $num_row = 1; ?>--}}
            {{--@endif--}}

            {{--@foreach(range(0, $num_row-1) as $i)--}}
            {{--@foreach($staff->documents as $i => $document)--}}
                {{--<div class="child-document">--}}
                {{--<div class="form-group col-sm-5 col-md-5 @if($errors->has('doc_type_id.'.$i)) has-error @endif">--}}
                    {{--<label>Document type <span class="text-danger">*</span></label>--}}
                    {{--<select name="doc_type_id[]" class="form-control">--}}
                        {{--<option value="">>> {{ __('label.doc_type') }} <<</option>--}}
                        {{--@foreach($idType as $key => $value)--}}
                            {{--@if($document->id == $key)--}}
                                {{--<option value="{{ $key }}" selected>{{ $value }}</option>--}}
                            {{--@else--}}
                                {{--<option value="{{ $key }}">{{ $value }}</option>--}}
                            {{--@endif--}}
                        {{--@endforeach--}}
                    {{--</select>--}}
                {{--</div>--}}
                {{--<div class="form-group col-sm-5 col-md-4 @if($errors->has('doc_file_name.'.$i) or $errors->has('extension.'.$i)) has-error @endif">--}}
                    {{--<label>Click to attache file</label>--}}
                    {{--<input type="file" class="form-control btn btn-default margin-r-5" name="doc_file_name[]"--}}
                           {{--value="{{ public_path().$document->rename }}">--}}
                {{--</div>--}}
                {{--<div class="form-group col-sm-2 col-md-1">--}}
                    {{--<label>-</label>--}}
                    {{--<button type="button" class="form-control btn btn-sm btn-danger btn-remove-doc hidden">--}}
                        {{--<i class="fa fa-minus-circle"></i>--}}
                    {{--</button>--}}
                {{--</div>--}}
                {{--<div class="col-sm-12 col-md-12">--}}
                    {{--<hr class="hidden"> <!-- Don't remove it. if remove will error form when add new -->--}}
                {{--</div>--}}
            {{--</div> <!-- /.child-document -->--}}
            {{--@endforeach--}}
            {{--@endforeach--}}
        {{--</form>--}}
    {{--</div> <!-- /.parent-document -->--}}

    {{--<div class="col-sm-12 col-md-12">--}}
        {{--<div class="col-sm-12 col-md-12">--}}
            {{--<div class="pull-right margin-bottom">--}}
               {{-- <a href="javascript:void(0);" class="btn btn-sm btn-danger btn-remove-document hidden">--}}
                    {{--<i class="fa fa-remove"></i> REMOVE--}}
                {{--</a>--}}
                {{--<a href="javascript:void(0);" class="btn btn-sm btn-success btn-more-document">--}}
                    {{--<i class="fa fa-plus-circle"></i> MORE--}}
                {{--</a>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}