<div class="row">
    <div class="col-sm-12 col-md-12 parent-document">
        <form action="{{ route('document.store') }}" method="post" role="form" enctype="multipart/form-data" id="edit_form_document">

            <div class="row margin-bottom">
                <div class="col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-success btn-sm margin-r-5" id="btnSave"><i class="fa fa-save"></i> SAVE</button>
{{--                    <button type="reset" class="btn btn-danger btn-sm" id="btnDiscard"><i class="fa fa-remove"></i> DISCARD</button>--}}
                </div>
            </div>

            {{ csrf_field() }}
            <input type="hidden" name="staff_token" value="{{ encrypt($staff->id) }}">
            <table class="table table-bordered">
                <tr>
                    <th width="20px">No</th>
                    <th width="20px">Action</th>
                    <th width="300px">Document Type</th>
                    <th width="300px">Attache File</th>
                    <th width="50px">Have</th>
                    <th width="80px">Not have</th>
                    <th>File Name</th>
                </tr>
                <?php $documents = $staff->documents; $i = 1; ?>
                @foreach($staffDocumentType as $key => $value)
                    <tr>
                        <?php  $document = $documents->where('staff_document_type_id', $key)->first(); ?>
                        <td>{{ $i++ }}</td>
                        @if (isset($document))
                            <td>
                                <a
                                        class="btn"
                                        href="/staff/document/{{ encrypt($document->id) }}/destroy"
                                        onclick="var x = confirm('Are you sure?');if(!x){return false;}"
                                >
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                            <td>{{ $value }} </td>
                            <td><input type="file" class="" name="files[{{ $key }}]"></td>
                            <td>
                                <input type="checkbox" value="1" name="checks[{{ $key }}]" class="checks_{{$key}}"
                                @if($document->check) {{ 'checked disabled' }} @endif
                                @if($document->not_have) {{ 'disabled' }} @endif>
                            </td>
                            <td>
                                <input type="checkbox" value="1" name="not_have[{{ $key }}]" class="not_have_{{$key}}"
                                @if($document->not_have) {{ 'checked disabled' }} @endif
                                @if($document->check) {{ 'disabled' }} @endif>
                            </td>
                            <td><a target="_blank" href="{{ asset($document->src) }}">{{ $document->name }}</a></td>
                        @else
                            <td><button class="btn" disabled style="border: 0;background: none; opacity: 0.3"><i class="fa fa-trash"></i></button></td>
                            <td>{{ $value }}</td>
                            <td><input type="file" class="" name="files[{{ $key }}]"></td>
                            <td><input type="checkbox" value="1" name="checks[{{ $key }}]" class="checks_{{$key}}"></td>
                            <td><input type="checkbox" value="1" name="not_have[{{ $key }}]" class="not_have_{{$key}}"></td>
                        @endif
                    </tr>
                @endforeach
            </table>
        </form>
    </div> <!-- /.parent-document -->
</div>
