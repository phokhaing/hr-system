@extends('adminlte::page')

@section('title', 'Create New Company')

@section('content')

    @include('partials.breadcrumb')

    <div class="panel panel-default">
        <div class="panel-heading">
            <label for="">Create New Company</label>
        </div>
        <div class="panel-body">
            {{ session()->get('message') }}
            <div class="row">
                <div class="col-sm-12">
                    <form action="{{ url('/company/store') }}" method="POST">
                        {{ Form::token() }}

                        <div class="row">
                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('company_code')) has-error @endif">
                                <?php
                                form_label([
                                    'for' => 'company_code',
                                    'value' => 'Code *',
                                ]);
                                form_number([
                                    'name' => 'company_code',
                                    'class' => 'form-control',
                                    'id' => 'company_code',
                                    'value' => old('company_code')
                                ]);
                                ?>
                            </div>
                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('short_name')) has-error @endif">
                                <?php
                                form_label([
                                    'for' => 'short_name',
                                    'value' => 'Short Name *',
                                ]);
                                form_text([
                                    'name' => 'short_name',
                                    'class' => 'form-control',
                                    'id' => 'short_name',
                                    'value' => old('short_name')
                                ]);
                                ?>
                            </div>
                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('name_en')) has-error @endif">
                                <?php
                                form_label([
                                    'for' => 'name_en',
                                    'value' => 'Name EN *',
                                ]);
                                form_text([
                                    'name' => 'name_en',
                                    'class' => 'form-control',
                                    'id' => 'name_en',
                                    'value' => old('name_en')
                                ]);
                                ?>
                            </div>
                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('name_kh')) has-error @endif">
                                <?php
                                form_label([
                                    'for' => 'name_kh',
                                    'value' => 'Name KH *',
                                ]);
                                form_text([
                                    'name' => 'name_kh',
                                    'class' => 'form-control',
                                    'id' => 'name_kh',
                                    'value' => old('name_kh')
                                ]);
                                ?>
                            </div>

                            <div class="col-xs-12">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div> <!-- .row -->

                    </form>
                </div>
            </div>
        </div>
    </div> <!-- .panel-default -->

@stop
