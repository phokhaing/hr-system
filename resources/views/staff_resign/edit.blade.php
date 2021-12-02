@extends('adminlte::page')

@section('title', 'Create New Company')

@section('content')
    @include('partials.breadcrumb')
    {{ session()->get('message') }}
    <div class="row">
        <div class="col-sm-12">
            <form action="/company/{{ $company->id }}/update" method="POST">
                {{ Form::token() }}
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <?php
                            form_label([
                                'for' => 'code',
                                'value' => 'Code:',
                            ]);
                            form_number([
                                'name' => 'code',
                                'class' => 'form-control',
                                'id' => 'code',
                                'value' => $company->code
                            ]);
                            ?>
                        </div>
                        <div class="form-group">
                            <?php
                            form_label([
                                'for' => 'short_name',
                                'value' => 'Short name:',
                            ]);
                            form_text([
                                'name' => 'short_name',
                                'class' => 'form-control',
                                'id' => 'short_name',
                                'value' => $company->short_name
                            ]);
                            ?>
                        </div>
                        <div class="form-group">
                            <?php
                            form_label([
                                'for' => 'name_en',
                                'value' => 'Name EN:',
                            ]);
                            form_text([
                                'name' => 'name_en',
                                'class' => 'form-control',
                                'id' => 'name_en',
                                'value' => $company->name_en
                            ]);
                            ?>
                        </div>
                        <div class="form-group">
                            <?php
                            form_label([
                                'for' => 'name_kh',
                                'value' => 'Name KH',
                            ]);
                            form_text([
                                'name' => 'name_kh',
                                'class' => 'form-control',
                                'id' => 'name_kh',
                                'value' => $company->name_kh
                            ]);
                            ?>
                        </div>

                    </div>

                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
