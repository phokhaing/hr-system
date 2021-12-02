@extends('adminlte::page')

@section('title', 'Edit position')

@section('content')

    @include('partials.breadcrumb')

    <div class="panel panel-default">
        <div class="panel-heading"><label for="">Edit position</label></div>
        <div class="panel-body">
            {{ session()->get('message') }}
            <div class="row">
                <div class="col-sm-12">
                    <form action="/position/{{ $position->id }}/update" method="POST">
                        {{ Form::token() }}
                        <div class="row">

                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('name_kh')) has-error @endif">
                                <?php
                                form_label([
                                    'for' => 'name_en',
                                    'value' => 'Name EN *',
                                ]);
                                form_text([
                                    'name' => 'name_en',
                                    'class' => 'form-control',
                                    'id' => 'name_en',
                                    'value' => $position->name_en
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
                                    'value' => $position->name_kh
                                ]);
                                ?>
                            </div>
                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('range')) has-error @endif">
                                <?php
                                form_label([
                                    'for' => 'range',
                                    'value' => 'Salary Range:',
                                ]);
                                form_text([
                                    'name' => 'range',
                                    'class' => 'form-control',
                                    'id' => 'range',
                                    'value' => $position->range,
//                                    'pattern'   => '^$\d{1,3}(,\d{3})*(\.\d+)?$',
                                    'data-type' => 'currency',
                                ]);
                                ?>
                            </div>

                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('company_code')) has-error @endif">
                                <?php
                                form_label([
                                    'for' => 'company_code',
                                    'value' => 'Company *',
                                ]);
                                ?>
                                <select class="form-control" name="company_code" id="company_code">
                                    <option value=""><< {{ __('label.company') }} >></option>
                                    @foreach($companies as $key => $value)
                                        <option value="{{ $value->company_code }}" {{ ($value->company_code == $position->company_code) ? 'selected' : '' }}>{{ $value->company_code.'-'.$value->name_kh }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('position_level')) has-error @endif">
                            <?php
                            form_label([
                                'for' => 'positionLevel',
                                'value' => 'Position Level *',
                            ]);
                            ?>
                            <select class="form-control select2" name="position_level">
                                <option value=""><< {{ __('label.position_level') }} >></option>
                                @foreach($position_level as $key => $value)
                                    <option value="{{ $value->group_level }}" {{ ($value->group_level == $position->group_level) ? 'selected' : '' }}>{{ $value->group_level }}</option>
                                @endforeach
                            </select>
                        </div>
                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('short_name')) has-error @endif">
                                <?php
                                form_label([
                                    'for' => 'short_name',
                                    'value' => 'Short Name:',
                                ]);
                                form_text([
                                    'name' => 'short_name',
                                    'class' => 'form-control',
                                    'id' => 'short_name',
                                    'value' => $position->short_name
                                ]);
                                ?>
                            </div>
                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('desc_en')) has-error @endif">
                                <?php
                                form_label([
                                    'for' => 'desc_en',
                                    'value' => 'Description EN:',
                                ]);
                                form_textarea([
                                    'name' => 'desc_en',
                                    'class' => 'summernote form-control',
                                    'id' => 'desc_en',
                                    'value' => $position->desc_en
                                ]);
                                ?>
                            </div>
                            <div class="form-group col-sm-6 col-md-6 @if($errors->has('desc_kh')) has-error @endif">
                                <?php
                                form_label([
                                    'for' => 'desc_kh',
                                    'value' => 'Description KH:',
                                ]);
                                form_textarea([
                                    'name' => 'desc_kh',
                                    'class' => 'summernote form-control',
                                    'id' => 'desc_kh',
                                    'value' => $position->desc_kh
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

@section('js')
<script>
    $(document).ready(function() {
        $(".select2").select2({
            tags: true
        });
    });
</script>
@endsection