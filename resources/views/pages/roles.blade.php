@extends('layouts.app')

@section('roles')
    @include('partials.header')
    <!-- =============================================== -->
    <!-- Left side column. contains the sidebar -->
    @include('partials.aside')
    @push('css')
        <link href="{{asset('assets/plugins/modals/component.css')}}" rel="stylesheet"/>
    @endpush
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            {{--            <div class="header-icon"> --}}
            {{--                <i class="fa fa-money"></i> --}}
            {{--            </div> --}}
            {{--            <div class="header-title"> --}}
            {{--                <h1>Auctions</h1> --}}
            {{--            </div> --}}
        </section>
        <!-- Main content -->
        @if($errors->any())
            <div style="padding: 5px!important;"
                 class="ml-3 alert custom_alerts alert-danger alert-dismissible fade show w-25" role="alert">
                @foreach($errors->all() as $error)
                    <p>{{$error}}</p>
                @endforeach
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        @endif

        <section style="margin-left: 15px" class="content">
            <div class="row">
                <div class="col-lg-12 pinpin">
                    <div class="card lobicard" id="lobicard-custom-controls" data-sortable="true">
                        <div class="card-header">
                            <div class="card-title custom_title">
                                <h1>Roles</h1>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="btn-group">
                                {{-- Auction Create Modal--}}
                                <button type="button" class="btn green_btn custom_grreen2 mb-3" data-toggle="modal"
                                        data-target="#mymodals">
                                    Add New Role
                                </button>
                                <div class="modal fade" id="mymodals" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">New Role</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{route('roles.store')}}" method="post">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="col-md-12 form-group">
                                                        <input required type="text" name="name"
                                                               placeholder="Role Name" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <button type="submit" class="btn green_btn custom_grreen2">Create
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {{--End Auction Create Modal--}}
                            </div>

                            <div class="table-responsive">
                                <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
                                    <thead class="back_table_color">
                                    <tr class="info text-center">
                                        <th>Name</th>
                                        <th>Permissions</th>
                                        <th>Created</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($roles as $index=> $role)
                                        <tr class="text-center">
                                            <td>{{$role->name}}</td>
                                            <td>
                                                <button type="button" class="btn green_btn custom_grreen2"
                                                        data-toggle="modal"
                                                        data-target="#assignrole{{$index}}">
                                                    Permissions
                                                </button>
                                                <div class="modal fade" id="assignrole{{$index}}" tabindex="-1"
                                                     role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Permissions for
                                                                    <span style="color:red">{{$role->name}}</span>
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="{{route('role.permission.assign')}}"
                                                                  method="post">
                                                                @csrf
                                                                <input type="hidden" name="role_id"
                                                                       value="{{$role->id}}">
                                                                <div class="modal-body">
                                                                    <div class="row  text-center">
                                                                        @foreach($permissions as $permission2)
                                                                            <div class=" col-sm-6 col-md-6 col-lg-3">
                                                                                <span>{{$permission2->name}}</span>
                                                                                <input type="checkbox"
                                                                                       style="height: 20px; width: 20px"
                                                                                       name="permission[]"
                                                                                       value="{{$permission2->name}}"
                                                                                       @checked(in_array($permission2->name, $role->permissions->pluck('name')->toArray()))
                                                                                       class="btn btn-default w-md m-b-5">
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger"
                                                                            data-dismiss="modal">
                                                                        Close
                                                                    </button>
                                                                    <button type="submit"
                                                                            class="btn green_btn custom_grreen2">
                                                                        Assign
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{$role->created_at->format('d-m-Y')}}</td>
                                            <td>
                                                {{--Edit Modal--}}
                                                <button type="button" class="btn btn-add btn-sm" data-toggle="modal"
                                                        data-target="#update{{$index}}"><i class="fa fa-pencil"></i>
                                                </button>
                                                <div class="modal fade" id="update{{$index}}" tabindex="-1"
                                                     role="dialog" style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header modal-header-primary">
                                                                <h3> Edit Role</h3>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-hidden="true">×
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <form class="form-horizontal"
                                                                              action="{{route('roles.update')}}"
                                                                              method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="id"
                                                                                   value="{{$role->id}}" id="">
                                                                            <div class="row flex justify-content-center">

                                                                                <div class="col-md-6 form-group">
                                                                                    <label class="control-label">Name</label>
                                                                                    <input type="text"
                                                                                           name="name"
                                                                                           value="{{$role->name}}"
                                                                                           class="form-control">

                                                                                </div>
                                                                                <div class="col-md-12 form-group user-form-group mt-3">
                                                                                    <div class="flex justify-content-center">
                                                                                        <button type="button"
                                                                                                data-dismiss="modal"
                                                                                                class="btn btn-danger btn-sm">
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
                                                            {{--                                                            <div class="modal-footer">--}}
                                                            {{--                                                                <button type="button" class="btn btn-danger float-left"--}}
                                                            {{--                                                                        data-dismiss="modal">Close--}}
                                                            {{--                                                                </button>--}}
                                                            {{--                                                            </div>--}}
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>

                                                {{--Delete Modal--}}
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                        data-target="#customer2{{$index}}"><i class="fa fa-trash-o"></i>
                                                </button>
                                                <div class="modal fade" id="customer2{{$index}}" tabindex="-1"
                                                     role="dialog" style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header modal-header-primary">
                                                                <h3><i class="fa fa-user m-r-5"></i> Delete Auction</h3>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-hidden="true">×
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <form class="form-horizontal"
                                                                              action="{{route('roles.delete')}}"
                                                                              method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="id"
                                                                                   value="{{$role->id}}">
                                                                            <fieldset>
                                                                                <div class="col-md-12 form-group user-form-group">
                                                                                    <label class="control-label">Delete
                                                                                        Auction : {{$role->name}}
                                                                                        ?</label>
                                                                                    <div class="flex justify-content-center mt-3">
                                                                                        <button type="button"
                                                                                                data-dismiss="modal"
                                                                                                class="btn btn-danger btn-sm">
                                                                                            NO
                                                                                        </button>
                                                                                        <button type="submit"
                                                                                                class="btn btn-add btn-sm">
                                                                                            YES
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </fieldset>
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

        <section style="margin-left: 15px" class="content">
            <div class="row">
                <div class="col-lg-12 pinpin">
                    <div class="card lobicard" id="lobicard-custom-controls" data-sortable="true">
                        <div class="card-header">
                            <div class="card-title custom_title">
                                <h1>Permissions</h1>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="btn-group">
                                {{-- Auction Create Modal--}}
                                <button type="button" class="btn green_btn custom_grreen2 mb-3" data-toggle="modal"
                                        data-target="#createpermission">
                                    Add New Permission
                                </button>
                                <div class="modal fade" id="createpermission" tabindex="-1" role="dialog"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">New Permission</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{route('permission.store')}}" method="post">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="col-md-12 form-group">
                                                        <input required type="text" name="name"
                                                               placeholder="Permission Name" class="form-control">
                                                    </div>
                                                    <div class="col-md-12 form-group">
                                                        <textarea required name="description" id="" cols="30" rows="10"
                                                                  class="form-control"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <button type="submit" class="btn green_btn custom_grreen2">
                                                        Create
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {{--End Auction Create Modal--}}
                            </div>

                            <div class="table-responsive">
                                <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
                                    <thead class="back_table_color">
                                    <tr class="info text-center">
                                        <th>Name</th>
                                        <th>Permissions</th>
                                        <th>Created</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($permissions as $permIndex=> $permission)
                                        <tr class="text-center">
                                            <td>{{$permission->name}}</td>
                                            <td>
                                                {{$permission->description}}
                                            </td>
                                            <td>{{$permission->created_at->format('d-m-Y')}}</td>
                                            <td>
                                                {{--Edit Modal--}}
                                                <button type="button" class="btn btn-add btn-sm" data-toggle="modal"
                                                        data-target="#updatepermission{{$permIndex}}"><i
                                                            class="fa fa-pencil"></i>
                                                </button>
                                                <div class="modal fade" id="updatepermission{{$permIndex}}"
                                                     tabindex="-1"
                                                     role="dialog" style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header modal-header-primary">
                                                                <h3> Edit Role</h3>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-hidden="true">×
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <form class="form-horizontal"
                                                                              action="{{route('permission.update')}}"
                                                                              method="post">
                                                                            @csrf

                                                                            <input type="hidden" name="id"
                                                                                   value="{{$permission->id}}" id="">
                                                                            <div class="flex justify-content-center mb-3">
                                                                                <label class="control-label">Name</label>
                                                                                <input type="text"
                                                                                       name="name"
                                                                                       value="{{$permission->name}}"
                                                                                       class="form-control">
                                                                            </div>
                                                                            <div class="col-md-12 form-group">
                                                                                <label class="control-label">Description</label>
                                                                                <textarea required name="description"
                                                                                          id="" cols="30" rows="10"
                                                                                          class="form-control">{{$permission->description}}</textarea>
                                                                            </div>
                                                                            <div class="col-md-12 form-group user-form-group mt-3">
                                                                                <div class="flex justify-content-center">
                                                                                    <button type="button"
                                                                                            data-dismiss="modal"
                                                                                            class="btn btn-danger btn-sm">
                                                                                        Cancel
                                                                                    </button>
                                                                                    <button type="submit"
                                                                                            class="btn btn-add btn-sm">
                                                                                        Update
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{--    <div class="modal-footer">--}}
                                                            {{--    <button type="button" class="btn btn-danger float-left"--}}
                                                            {{--     data-dismiss="modal">Close--}}
                                                            {{--     </button>--}}
                                                            {{--   </div>--}}
                                                        </div>
                                                    </div>
                                                </div>

                                                {{--Delete Modal--}}
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                        data-target="#deletepermission{{$permIndex}}"><i
                                                            class="fa fa-trash-o"></i>
                                                </button>
                                                <div class="modal fade" id="deletepermission{{$permIndex}}"
                                                     tabindex="-1"
                                                     role="dialog" style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header modal-header-primary">
                                                                <h3><i class="fa fa-user m-r-5"></i> Delete Auction</h3>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-hidden="true">×
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <form class="form-horizontal"
                                                                              action="{{route('permission.delete')}}"
                                                                              method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="id"
                                                                                   value="{{$permission->id}}">
                                                                            <fieldset>
                                                                                <div class="col-md-12 form-group user-form-group">
                                                                                    <label class="control-label">Delete
                                                                                        Auction : {{$permission->name}}
                                                                                        ?</label>
                                                                                    <div class="flex justify-content-center mt-3">
                                                                                        <button type="button"
                                                                                                data-dismiss="modal"
                                                                                                class="btn btn-danger btn-sm">
                                                                                            NO
                                                                                        </button>
                                                                                        <button type="submit"
                                                                                                class="btn btn-add btn-sm">
                                                                                            YES
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </fieldset>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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

    </div>

    <!-- Modal js -->
    <script src="{{asset('assets/plugins/modals/classie.js')}}"></script>
    <script src="{{asset('assets/plugins/modals/modalEffects.js')}}"></script>
    <!-- End Page Lavel Plugins






@endsection