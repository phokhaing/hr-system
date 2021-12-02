@extends('adminlte::page')

@section('title', 'Show category')

@section('content')

    @include('partials.breadcrumb')

    <div class="panel panel-default">
        <div class="panel-heading">
            <label for="">Show category</label>
            <a class="btn btn-xs btn-primary pull-right" href="{{ route('hrtraining::category.setting') }}"> Back</a>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
                    <label for="">Title in Khmer:</label> {{ @$category->json_data->title_kh }}
                    <hr>
                    <label for="">Title in English:</label> {{ @$category->json_data->title_en }}
                    <hr>
                    <label for="">Description Khmer: </label>
                    {{ @$category->json_data->desc_kh }}
                    <hr>
                    <label for="">Description English:</label>
                    {{ @$category->json_data->desc_en }}
                </div>
            </div>
        </div>
    </div> <!-- .panel-heading -->

@endsection