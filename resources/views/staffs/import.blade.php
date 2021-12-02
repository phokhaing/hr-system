@extends('adminlte::page')

@section('title', 'Import staff personal info')

@section('content')

    @include('partials.breadcrumb')

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><label for="">Import staff profile</label></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 margin-bottom">
                            <a href="#" class="btn btn-sm btn-primary margin-r-5"><i class="fa fa-download"></i> DOWNLOAD SAMPLE FILE</a>
                        </div>

                        <form action="{{ route('staff.import-file') }}" role="form" method="POST" enctype="multipart/form-data" id="formImport">
                            {{ csrf_field() }}

                            <div class="form-group col-sm-6 col-md-6">
                                <input class="form-control" type="file" name="import_file">
                            </div>
                            <div class="form-group col-sm-6 col-md-6">
                                <button type="submit" class="btn btn-sm btn-success">SUBMIT</button>
                            </div>
                        </form>

                        <div id="previewExcel"></div>

                    </div>
                </div>
            </div>
        </div>
    </div> <!-- .row -->

@endsection