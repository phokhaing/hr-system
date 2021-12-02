@extends('adminlte::page')

@section('title', 'HR-MIS')

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @can('add_permission')
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <nav class="navbar navbar-default">
                    <form class="navbar-form navbar-left">
                        <a class="btn btn-success btn-sm" href="{{ route('permissions.create') }}">NEW PERMISSION</a>
                    </form>
                </nav>
            </div>
        </div>
    @endcan

    <div class="panel panel-default">
        <div class="panel-heading">
            Manage permission
        </div>
        <div class="panel-body">
            <table class="table table-striped">
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Module Name</th>
                    <th>Create Date</th>
                    <th width="280px">Action</th>
                </tr>
                @foreach ($permissions as $key => $permission)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $permission->name }}</td>
                        <td>{{ $permission->module_name }}</td>
                        <td>{{ date_format($permission->created_at, 'd-M-Y') }}</td>
                        <td>
                            @can('view_permission')
                                <a class="btn btn-info btn-xs margin-r-5" href="{{ route('permissions.show',$permission->id) }}"><i class="fa fa-eye"></i></a>
                            @endcan
                            @can('edit_permission')
                                <a class="btn btn-primary btn-xs margin-r-5" href="{{ route('permissions.edit',$permission->id) }}"><i class="fa fa-edit"></i></a>
                            @endcan
                            @can('delete_permission')
                                <a href="javascript:void(0);" class="btn btn-danger btn-xs btn-delete" delete-id="{{ $permission->id }}"><i class="fa fa-trash"></i></a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </table>

        </div>
    </div>

@endsection

@section('js')

    <script type="text/javascript">
        $(document).ready(function () {
            $('.btn-delete').on('click', function (e) {
                e.preventDefault();
                var id = $(this).attr('delete-id');
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Permission will delete.",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/permissions/'+id,
                            method: 'DELETE',
                            dataType: 'JSON',
                            data: {
                                _token: CSRF_TOKEN,
                            },
                            success:function (data) {
                                if(data.status == 1) {
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
                            fail:function (error) {
                                console.log(error);
                            }
                        });
                    }
                })
            });
        });
    </script>

@endsection