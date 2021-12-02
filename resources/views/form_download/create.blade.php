@extends('adminlte::page')

@section('title', 'Create new form download')

@section('content')
    @include('partials.breadcrumb')

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel panel-heading"><label for="">Create new form download</label></div>
                <div class="panel-body">
                    {{ session()->get('message') }}
                    <form action="{{ url('/form_download/store') }}" method="POST" enctype="multipart/form-data">

                        <div class="row margin-bottom">
                            <div class="col-sm-12 col-md-12">
                                <button type="submit" class="btn btn-success btn-sm margin-r-5" id="btnSave"><i class="fa fa-save"></i> SAVE</button>
                                <button type="reset" class="btn btn-danger btn-sm" id="btnDiscard"><i class="fa fa-remove"></i> DISCARD</button>
                            </div>
                        </div>

                        {{ Form::token() }}
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12">
                                <?php
                                form_label([
                                    'for' => 'title',
                                    'value' => 'Title:',
                                ]);
                                form_text([
                                    'name' => 'title',
                                    'class' => 'form-control',
                                    'id' => 'title',
                                    'value' => old('title'),
                                    'placeholder' => 'ចំណងជើង'
                                ]);
                                ?>
                            </div>
                            <div class="form-group col-sm-6 col-md-6">
                                <?php
                                form_label([
                                    'for' => 'pdf_src',
                                    'value' => 'PDF File',
                                ]);
                                form_file([
                                    'name' => 'pdf_src',
                                    'class' => 'form-control',
                                    'id' => 'pdf_src',
                                    'value' => old('pdf_src')
                                ]);
                                ?>
                            </div>
                            <div class="form-group col-sm-6 col-md-6">
                                <?php
                                form_label([
                                    'for' => 'doc_src',
                                    'value' => 'Other File',
                                ]);
                                form_file([
                                    'name' => 'doc_src',
                                    'class' => 'form-control',
                                    'id' => 'doc_src',
                                    'value' => old('doc_src')
                                ]);
                                ?>
                            </div>
                        </div>
                    </form>

                </div>
            </div> <!-- panel-default -->
        </div>
    </div>

@stop
