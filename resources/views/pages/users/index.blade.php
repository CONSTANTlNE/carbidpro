@extends('layouts.app')
@section('content')
    @push('css')
    @endpush
    @section('body-class', 'hold-transition sidebar-mini')

    <!--preloader-->
    {{--<div id="preloader">--}}
    {{--    <div id="status"></div>--}}
    {{--</div>--}}

    <!-- Site wrapper -->
    <div class="wrapper">
        @include('partials.header')
        <!-- =============================================== -->
        <!-- Left side column. contains the sidebar -->
        @include('partials.aside')
        <!-- =============================================== -->

        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="header-icon">
                    <i class="fa fa-user-plus"></i>
                </div>
                <div class="header-title">
                    <h1>Users</h1>
                    <small>List of User</small>
                </div>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-lg-12 pinpin">
                        <div class="card lobicard" id="lobicard-custom-control" data-sortable="true">
                            @session('success')
                            <div class="d-flex justify-content-center mt-5">
                                <div style="max-width: 300px" class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            </div>
                            @endsession
                            @session('error')
                            <div class="d-flex justify-content-center mt-5">
                                <div style="max-width: 300px" class="alert alert-error">
                                    {{ session('error') }}
                                </div>
                            </div>
                            @endsession
                            <div class="card-body">
                                {{--New User Modal--}}
                                <div class="btn-group d-flex" role="group">
                                    <div class="buttonexport">
                                        <a href="#" class="btn btn-add" data-toggle="modal"
                                           data-target="#addUserModal"><i class="fa fa-plus"></i> Add Users</a>
                                    </div>
                                </div>
                                <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header modal-header-primary">
                                                <h3><i class="fa fa-plus m-r-5"></i> Add New User</h3>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="addUserForm" class="form-horizontal" method="POST"
                                                      enctype="multipart/form-data" action="{{route('users.store')}}" >
                                                    @csrf <!-- CSRF token -->
                                                    <!-- User Name -->
                                                    <div class="row">
                                                        <div class="col-md-6 form-group">
                                                            <label class="control-label">Name</label>
                                                            <input type="text" id="add_name" name="name" placeholder="User Name"
                                                                   class="form-control" required autocomplete="off">
                                                        </div>

                                                        <!-- Email -->
                                                        <div class="col-md-6 form-group">
                                                            <label class="control-label">Email</label>
                                                            <input type="email" id="add_email" name="email" placeholder="Email"
                                                                   class="form-control" required value="">
                                                        </div>

                                                        <!-- Password -->
                                                        <div class="col-md-6 form-group">
                                                            <label class="control-label">Password</label>
                                                            <input type="text" id="add_password" name="password"
                                                                   placeholder="Password" class="form-control" required value="">
                                                        </div>

                                                        <!-- Confirm Password -->
                                                        <div class="col-md-6 form-group">
                                                            <label class="control-label">Confirm Password</label>
                                                            <input type="text" id="add_password_confirmation"
                                                                   name="password_confirmation" placeholder="Confirm Password"
                                                                   class="form-control" required>
                                                        </div>

                                                        <!-- Role -->
                                                        <div class="col-md-6 form-group">
                                                            <label class="control-label">Role</label>
                                                            <select id="add_role" name="role" class="form-control" required>
                                                                @foreach($roles as $role)
                                                                    <option value="{{$role->name}}">{{ $role->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <!-- Submit Button -->
                                                        <div class="col-md-12 form-group">
                                                            <button type="submit" class="btn btn-add">Add User</button>
                                                        </div>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ./Plugin content:powerpoint,txt,pdf,png,word,xl -->
                                <div class="table-responsive">
                                    <table id="dataTableExample1"
                                           class="table table-bordered table-striped table-hover">
                                        <thead class="back_table_color">
                                        <tr class="info">
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Created at</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($users as $index => $user)

                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    <span
                                                            class="label-custom label label-default">{{ $user->getRoleNames()->first() }}</span>
                                                </td>
                                                <td>
                                                    {{ $user->created_at }}
                                                </td>
                                                <td>
                                                    <button type="button" class="btn-edit-user btn btn-add btn-sm"
                                                            data-toggle="modal" data-user-id="{{ $user->id }}"
                                                            data-target="#update{{$index}}"><i class="fa fa-pencil"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                            data-toggle="modal" data-target="#deleteUserModal{{$index}}"
                                                            data-user-id="{{ $user->id }}">
                                                        <i class="fa fa-trash-o"></i>
                                                    </button>
                                                    {{--Delete Modal--}}
                                                    <div class="modal fade" id="deleteUserModal{{$index}}" tabindex="-1"
                                                         role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header modal-header-primary">
                                                                    <h3><i class="fa fa-user m-r-5"></i> Delete User
                                                                    </h3>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal" aria-hidden="true">×
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to delete <span
                                                                                style="font-weight: bolder"
                                                                                class="text-danger">{{$user->name}}</span>
                                                                        this user?</p>
                                                                    <form action="{{route('users.destroy')}}"
                                                                          method="post">
                                                                        @csrf
                                                                        <input type="hidden" id="deleteUserId"
                                                                               name="user_id" value="{{$user->id}}">
                                                                        <div class="form-group user-form-group">
                                                                            <div class="float-right">
                                                                                <button type="button"
                                                                                        class="btn btn-danger btn-sm"
                                                                                        data-dismiss="modal">NO
                                                                                </button>
                                                                                <button type="submit"
                                                                                        class="btn btn-add btn-sm">YES
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{--Update Modal--}}
                                                    <div class="modal fade" id="update{{$index}}" tabindex="-1"
                                                         role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header modal-header-primary">
                                                                    <h3><i class="fa fa-plus m-r-5"></i> Update User
                                                                    </h3>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal" aria-hidden="true">×
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <form id="updateUserForm"
                                                                                  action="{{route('users.update')}}"
                                                                                  class="form-horizontal" method="post">
                                                                                @csrf
                                                                                <input type="hidden" id="userId"
                                                                                       name="id"
                                                                                       value="{{$user->id}}">
                                                                                <!-- Hidden field for user ID -->
                                                                                <div class="row">
                                                                                    <!-- Text input for User Name -->
                                                                                    <div class="col-md-6 form-group">
                                                                                        <label class="control-label">Name</label>
                                                                                        <input type="text" id="name"
                                                                                               name="name"
                                                                                               value="{{$user->name}}"
                                                                                               placeholder="User Name"
                                                                                               class="form-control"
                                                                                               required>
                                                                                    </div>
                                                                                    <!-- Text input for Status -->
                                                                                    <div class="col-md-6 form-group">
                                                                                        <label class="control-label">Email</label>
                                                                                        <input type="email" id="email"
                                                                                               name="email"
                                                                                               placeholder="email"
                                                                                               value="{{$user->email}}"
                                                                                               class="form-control">
                                                                                    </div>

                                                                                    <!-- Password Field -->
                                                                                    <div class="col-md-6 form-group">
                                                                                        <label class="control-label">Password</label>
                                                                                        <div class="input-group">
                                                                                            <input type="text"
                                                                                                   id="password"
                                                                                                   name="password"
                                                                                                   placeholder="Password"
                                                                                                   class="form-control">

                                                                                        </div>
                                                                                    </div>

                                                                                    <!-- Confirm Password Field -->
                                                                                    <div class="col-md-6 form-group">
                                                                                        <label class="control-label">Confirm
                                                                                            Password</label>
                                                                                        <div class="input-group">
                                                                                            <input type="text"
                                                                                                   id="password_confirmation"
                                                                                                   name="password_confirmation"
                                                                                                   placeholder="Confirm Password"
                                                                                                   class="form-control">

                                                                                        </div>
                                                                                    </div>

                                                                                    <!-- Text input for Role/Type -->
                                                                                    <div class="col-md-6 form-group">
                                                                                        <label class="control-label">Role</label>
                                                                                        <select id="userRole"
                                                                                                name="role"
                                                                                                class="form-control">
                                                                                            @foreach($roles as $role)
                                                                                                <option @selected($user->roles->first()->name == $role->name)  value="{{$role->name}}">{{ $role->name }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-12 form-group user-form-group">
                                                                                        <div class="float-right">
                                                                                            <button type="button"
                                                                                                    class="btn btn-danger btn-sm"
                                                                                                    data-dismiss="modal">
                                                                                                Cancel
                                                                                            </button>
                                                                                            <button type="submit"
                                                                                                    class="btn btn-add btn-sm">
                                                                                                Update
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- /.modal-content -->
                                                        </div>
                                                        <!-- /.modal-dialog -->
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
            <!-- /.content -->
        </div>

        @include('partials.footer')
    </div>
@endsection
