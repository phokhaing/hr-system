@extends('adminlte::page')

@section('title', 'HR-MIS')

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @can('add_role')
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <nav class="navbar navbar-default">
                    <form class="navbar-form navbar-left">
                        <a class="btn btn-success btn-sm" href="{{ route('roles.create') }}">NEW ROLE</a>
                    </form>
                </nav>
            </div>
        </div>
    @endcan

    <div class="panel panel-default">
        <div class="panel-heading"><i class="fa fa-filter"></i> <label for="">Filter</label></div>
        <div class="panel-body">
            <form action="{{ route('roles.index') }}" role="form" method="get" id="filter-form">
                {{ csrf_field() }}
                <div class="row">
                    <div class="form-group col-sm-6 col-md-6">
                        <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Keyword"
                               value="{{ request('keyword') }}">
                    </div>

                    <div class="form-group col-sm-6 col-md-6">
                        <button type="submit" class="btn btn-primary margin-r-5"><i class="fa fa-search"></i> Search
                        </button>
                        <button type="button" class="btn btn-danger margin-r-5" id="btn-clear"><i
                                    class="fa fa-remove"></i> Clear
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Manage role
        </div>
        <div class="panel-body">
            <table class="table table-striped">
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Create Date</th>
                    <th width="280px">Action</th>
                </tr>
                @foreach ($roles as $key => $role)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $role->name }}</td>
                        <td>{{ date_format($role->created_at, 'd-M-Y') }}</td>
                        <td>
                            @can('view_role')
                                <a class="btn btn-info btn-xs margin-r-5" href="{{ route('roles.show',$role->id) }}"><i
                                            class="fa fa-eye"></i></a>
                            @endcan
                            @can('edit_role')
                                <a class="btn btn-primary btn-xs margin-r-5" href="{{ route('roles.edit',$role->id) }}"><i
                                            class="fa fa-edit"></i></a>
                            @endcan
                            @can('delete_role')
                                <a href="javascript:void(0);" class="btn btn-danger btn-xs btn-delete"
                                   delete-id="{{ $role->id }}"><i class="fa fa-trash"></i></a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </table>

            {!! $roles->appends(request()->query())->links() !!}
        </div>
    </div>

@endsection

@section('js')

    <script type="text/javascript">

        $(document).ready(function () {
            $('#btn-clear').click(function (e) {
                e.preventDefault();
                $("#keyword").val(null);
                $("#filter-form").submit();
            });

            $('.btn-delete').on('click', function (e) {
                e.preventDefault();
                var id = $(this).attr('delete-id');
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Role will delete without recovery.",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/roles/' + id,
                            method: 'DELETE',
                            dataType: 'JSON',
                            data: {
                                _token: CSRF_TOKEN,
                            },
                            success: function (data) {
                                if (data.status == 1) {
                                    Swal.fire({
                                        title: 'successfully!',
                                        text: 'Your record delete successfully!',
                                        type: 'success',
                                        showConfirmButton: false,
                                        timer: 3000,
                                    }).then(window.location.reload(true));

                                } else {
                                    console.log(data.error);
                                }
                            },
                            fail: function (error) {
                                console.log(error);
                            }
                        });
                    }
                })
            });
        });
    </script>

@endsection