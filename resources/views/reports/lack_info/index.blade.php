@extends('adminlte::page')

@section('title', 'Lack of information')

@section('content')

    @include('partials.breadcrumb')

    <div class="row">
        <div class="col-sm-12 col-md-12">

            <lack-info-component
                    v-for="post in posts"
                    v-bind:full_name="post.title"
                    v-bind:description="post.title"
                    v-bind:key="post.id"
            ></lack-info-component>

        </div> <!-- .col -->
    </div> <!-- .row -->

@endsection
