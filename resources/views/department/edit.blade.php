@extends('adminlte::page')

@section('title', 'Edit department')

@section('content')

    @include('partials.breadcrumb')

    <div class="panel panel-default">
        <div class="panel-heading"><label for="">Edit department</label></div>
        <div class="panel-body">
            {{ session()->get('message') }}
            <div class="row">
                <div class="col-sm-12">
                    <form action="{{ url('/department/'.$department->id.'/update') }}" method="POST" role="form">
                        {{ Form::token() }}

                        <div class="row">
                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('name_en')) has-error @endif">
                                <?php
                                form_label([
                                    'for' => 'name_en',
                                    'value' => 'Name EN:',
                                ]);
                                form_text([
                                    'name' => 'name_en',
                                    'class' => 'form-control',
                                    'id' => 'name_en',
                                    'value' => $department->name_en
                                ]);
                                ?>
                            </div>
                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('name_kh')) has-error @endif">
                                <?php
                                form_label([
                                    'for' => 'name_kh',
                                    'value' => 'Name KH',
                                ]);
                                form_text([
                                    'name' => 'name_kh',
                                    'class' => 'form-control',
                                    'id' => 'name_kh',
                                    'value' => $department->name_kh
                                ]);
                                ?>
                            </div>
                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('short_name')) has-error @endif">
                                <?php
                                form_label([
                                    'for' => 'short_name',
                                    'value' => 'Short name',
                                ]);
                                form_text([
                                    'name' => 'short_name',
                                    'class' => 'form-control',
                                    'id' => 'short_name',
                                    'value' => $department->short_name
                                ]);
                                ?>
                            </div>
                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('company_id')) has-error @endif">
                                <?php
                                form_label([
                                    'for' => 'company_id',
                                    'value' => 'Company',
                                ]);
                                ?>
                                <select class="form-control" name="company_id" id="company_id" >
                                    <option value=""><< {{ __('label.company') }} >></option>
                                    @foreach($companies as $key => $value)
                                        <option value="{{ $value->id }}" @if($department->company_id == $value->id) selected @endif>{{ $value->code.'-'.$value->name_kh }}</option>
                                    @endforeach
                                    <option value="{{ $value->id }}">{{ $value->code.'-'.$value->name_kh }}</option>
                                </select>
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
