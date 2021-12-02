@extends('adminlte::page')

@section('title', 'Create New Position')

@section('content')

@include('partials.breadcrumb')

<div class="panel panel-default">
    <div class="panel-heading"><label for="">Create New Category</label></div>
    <div class="panel-body">
        {{ session()->get('message') }}
        <div class="row">
            <div class="col-sm-12">
                <form action="{{ route('hrtraining::category.store') }}" method="POST" role="form">
                    {{ Form::token() }}
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-6 @if($errors->has('title_en')) has-error @endif">
                            <?php
                            form_label([
                                'for' => 'title_en',
                                'value' => 'Title EN *',
                            ]);
                            form_text([
                                'name' => 'title_en',
                                'class' => 'form-control',
                                'id' => 'title_en',
                                'value' => old('title_en')
                            ]);
                            ?>
                        </div>
                        <div class="form-group col-sm-6 col-md-6 @if($errors->has('title_kh')) has-error @endif">
                            <?php
                            form_label([
                                'for' => 'title_kh',
                                'value' => 'Title KH *',
                            ]);
                            form_text([
                                'name' => 'title_kh',
                                'class' => 'form-control',
                                'id' => 'title_kh',
                                'value' => old('title_kh')
                            ]);
                            ?>
                        </div>
                        <div class="form-group col-sm-12 col-md-12 @if($errors->has('desc_en')) has-error @endif">
                            <?php
                            form_label([
                                'for' => 'desc_en',
                                'value' => 'Description EN:',
                            ]);
                            form_textarea([
                                'name' => 'desc_en',
                                'class' => 'summernote form-control',
                                'id' => 'desc_en',
                                'value' => old('desc_en')
                            ]);
                            ?>
                        </div>
                        <div class="form-group col-sm-12 col-md-12 @if($errors->has('desc_kh')) has-error @endif">
                            <?php
                            form_label([
                                'for' => 'desc_kh',
                                'value' => 'Description KH:',
                            ]);
                            form_textarea([
                                'name' => 'desc_kh',
                                'class' => 'summernote form-control',
                                'id' => 'desc_kh',
                                'value' => old('desc_kh')
                            ]);
                            ?>
                        </div>
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> <!-- .panel-default -->

@stop
