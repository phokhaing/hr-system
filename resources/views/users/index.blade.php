@extends('adminlte::page')

@section('title', 'HR-MIS')

@section('content')

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

@can('add_users')
<div class="row">
    <div class="col-sm-12 col-md-12">
        <nav class="navbar navbar-default">
            <form class="navbar-form navbar-left">
                <a class="btn btn-success btn-sm" href="{{ route('users.create') }}"> NEW USER</a>
            </form>
        </nav>
    </div>
</div>
@endcan

<div class="panel panel-default">
    <div class="panel-heading"><i class="fa fa-filter"></i> <label for="">Filter</label></div>
    <div class="panel-body">
        <form action="{{ route('users.index') }}" role="form" method="get" id="filter-form">
            {{ csrf_field() }}
            <input type="hidden" name="role" id="role" value="{{ @request('role') }}">
            <input type="hidden" name="company_code" id="company_code" value="{{ @request('company_code') }}">
            <div class="row">
                <div class="form-group col-sm-6 col-md-6">
                    <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Keyword" value="{{ request('keyword') }}">
                </div>

                <div class="form-group col-sm-6 col-md-6">
                    <button type="submit" class="btn btn-primary margin-r-5"><i class="fa fa-search"></i> Search</button>

                    @can('view_users_advance_search')
                    <button type="button" class="btn btn-primary margin-r-5" data-toggle="modal" data-target="#user_advance_filter"><i class="fa fa-filter"></i> Advabce Search</button>
                    @endcan

                    <button type="button" class="btn btn-danger margin-r-5" id="btn-clear"><i class="fa fa-remove"></i> Clear</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">Users Management
    </div>
    <div class="panel-body" style="overflow-x:auto;">
        <table class="table table-striped">
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Company</th>
                <th>Create Date</th>
                <th width="150px">Action</th>
            </tr>
            @foreach ($data as $key => $user)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if(!empty($user->getRoleNames()))
                    @foreach($user->getRoleNames() as $v)
                    <label class="badge badge-success">{{ $v }}</label>
                    @endforeach
                    @endif
                </td>
                <td>{{ $user->company->short_name ?? 'N/A' }}</td>
                <td>
                    {{ date_format($user->created_at, 'd-M-Y') }}
                </td>
                <td>
                    @can('view_users')
                    <a class="btn btn-info btn-xs margin-r-5" href="{{ route('users.show',$user->id) }}"><i class="fa fa-eye"></i></a>
                    @endcan
                    @can('edit_users')
                    <a class="btn btn-primary btn-xs margin-r-5" href="{{ route('users.edit',$user->id) }}"><i class="fa fa-edit"></i></a>
                    @endcan
                    @can('delete_users')
                    <a href="javascript:void(0)" class="btn btn-danger btn-xs btn-delete" delete-id="{{ $user->id }}"><i class="fa fa-trash"></i></a>
                    @endcan
                </td>
            </tr>
            @endforeach
        </table>
        {!! $data->appends(request()->query()) !!}
    </div>
</div>

@include('users.components.user_advance_filter')
@endsection

@section('js')

<script type="text/javascript">
    $(document).ready(function() {
        $('#btn-clear').click(function(e) {
            e.preventDefault();
            $("#keyword").val(null);
            $("#company_code").val(null);
            $("#role").val(null);
            $("#filter-form").submit();
        });

        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            var id = $(this).attr('delete-id');
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            Swal.fire({
                title: 'Are you sure?',
                text: "User will disable this user.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/users/' + id,
                        method: 'DELETE',
                        dataType: 'JSON',
                        data: {
                            _token: CSRF_TOKEN,
                        },
                        success: function(data) {
                            if (data.status == 1) {
                                Swal.fire({
                                    title: 'successfully!',
                                    text: 'Your record delete successfully!',
                                    type: 'success',
                                    showConfirmButton: false,
                                    timer: 1800,
                                }).then(window.location.reload(true));

                            } else {
                                console.log(data.error);
                            }
                        },
                        fail: function(error) {
                            console.log(error);
                        }
                    });
                }
            })
        });
    });
</script>
@endsection