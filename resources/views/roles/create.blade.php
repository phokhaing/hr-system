@extends('adminlte::page')

@section('title', 'HR-MIS')

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel panel-default">
        <div class="panel-heading">
            Create New Role
            <div class="pull-right">
                <a class="btn btn-primary btn-xs" href="{{ route('roles.index') }}"> Back</a>
            </div>
        </div>
        <div class="panel-body">

            {!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Role name :</strong>
                        {!! Form::text('name', null, array('placeholder' => 'Role name','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Permission:</strong>
                        <br/>
                        @foreach($permission as $value)
                            <label class="col-xs-12 col-sm-4 col-md-3">
                                {{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
                                {{ $value->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top: 20px">
                    <div class="pull-left">
                        <label for="check-all">Check all </label>
                        <input type="checkbox" id="check-all">
                    </div>
                    <div class="pull-right">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            var ckbox = $('#check-all');

            $('#check-all').on('click', function (e) {

                e.preventDefault();
                if (ckbox.is(':checked')) {
                    $(".name").attr('checked', true);
                } else {
                    $(".name").attr('checked', false);
                }
            });
        });
    </script>
@endsection 