@extends('layouts.app')



@section('shipping_lines')
    @include('partials.header')
    <!-- Left side column. contains the sidebar -->
    @include('partials.aside')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            {{--            <div class="header-icon">--}}
            {{--            <i class="fa fa-money"></i>--}}
            {{--            </div>--}}
            {{--            <div class="header-title">--}}
            {{--            <h1>Auctions</h1>--}}
            {{--            </div>--}}
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
                                <h2>Shipping Lines </h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="btn-group flex">
                                {{-- Location Create Modal--}}
                                <button type="button" class="btn green_btn custom_grreen2 mb-3" data-toggle="modal"
                                        data-target="#mymodals">
                                    Add Line
                                </button>
                                <div class="modal fade" id="mymodals" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">New Line</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{route('shippingline.create')}}" method="post"
                                                  enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div style="display: flex" class="col-md-6 m-auto">
                                                        <input required type="text" name="name"
                                                               placeholder="Line Name" class="form-control mb-3">
                                                    </div>
                                                    <div style="display: flex" class="flex">
                                                        <input required type="text" name="tracking_url"
                                                               placeholder="tracking URL" class="form-control mb-3">
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
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Tracking</th>
                                        <th>Active</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($shippingLines as $index=> $line)
                                        <tr class="text-center">
                                            <td>{{$line->id}}</td>
                                            <td>{{$line->name}}</td>
                                            <td>{{$line->tracking_url}}</td>
                                            <td>
                                                <form action="{{route('shippingline.activate')}}" method="post" novalidate>
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$line->id}}">
                                                    @if($line->is_active==1)
                                                        <button type="submit" class="btn btn-success btn-rounded w-md m-b-5">Active
                                                        </button>
                                                    @else
                                                        <button type="submit" class="btn btn-danger btn-rounded w-md m-b-5">Inactive
                                                        </button
                                                    @endif
                                                </form>
                                            </td>
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
                                                                <h3> Edit Line</h3>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-hidden="true">×
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <form class="form-horizontal"
                                                                              action="{{route('shippingline.update')}}"
                                                                              method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="id"
                                                                                   value="{{$line->id}}" id="">
                                                                            <div class="row flex justify-content-center">
                                                                                <div class="w-100">
                                                                                    <div class="col-md-6 form-group m-auto" >
                                                                                        <label class="control-label">Name</label>
                                                                                        <input type="text"
                                                                                               name="name"
                                                                                               value="{{$line->name}}"
                                                                                               class="form-control">
                                                                                    </div>
                                                                                    <div class="col-md-12 form-group mt-2">
                                                                                        <label class="control-label">Tracking</label>
                                                                                        <input type="text"
                                                                                               name="tracking_url"
                                                                                               value="{{$line->tracking_url}}"
                                                                                               class="form-control">
                                                                                    </div>
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
                                                            {{--<div class="modal-footer">--}}
                                                            {{--<button type="button" class="btn btn-danger float-left"--}}
                                                            {{--data-dismiss="modal">Close--}}
                                                            {{--</button>--}}
                                                            {{-- </div>--}}
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>

                                                {{--Delete Modal--}}
{{--                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"--}}
{{--                                                        data-target="#customer2{{$index}}"><i class="fa fa-trash-o"></i>--}}
{{--                                                </button>--}}
                                                <div class="modal fade" id="customer2{{$index}}" tabindex="-1"
                                                     role="dialog" style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header modal-header-primary">
                                                                <h3><i class="fa fa-user m-r-5"></i> Delete Load Type
                                                                </h3>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-hidden="true">×
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <form class="form-horizontal"
                                                                              action="{{route('shippingline.delete')}}"
                                                                              method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="id"
                                                                                   value="{{$line->id}}">
                                                                            <fieldset>
                                                                                <div class="col-md-12 form-group user-form-group">
                                                                                    <label class="control-label">Delete
                                                                                        Load Type : {{$line->name}}
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
        <!-- /.content -->
    </div>

@endsection