@extends('layouts.app')



@section('customers')
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
                                <h1>Customers  {{$count}}</h1>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="btn-group flex">
                                {{-- Location Create Modal--}}
                                <button type="button" class="btn green_btn custom_grreen2 mb-3" data-toggle="modal"
                                        data-target="#mymodals">
                                    Add New Customer
                                </button>
                                <div class="modal fade" id="mymodals" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content ">
                                            <div class="modal-header">
                                                <h5 class="modal-title">New Customer</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{route('customers.store')}}" method="post">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-4 form-group">
                                                            <label class="control-label">Company Name</label>
                                                            <input name="company_name" required type="text" placeholder="" class="form-control">
                                                        </div>
                                                        <!-- Text input-->
                                                        <div class="col-md-4 form-group">
                                                            <label class="control-label">Company/Personal ID</label>
                                                            <input name="company_id" required type="text" placeholder="" class="form-control">
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <label class="control-label">Contact Person</label>
                                                            <input name="contact_person" required type="text" placeholder="" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4 form-group">
                                                            <label class="control-label">Phone</label>
                                                            <input name="phone" required type="text" placeholder="" class="form-control">
                                                        </div>
                                                        <!-- Text input-->
                                                        <div class="col-md-4 form-group">
                                                            <label class="control-label">Email</label>
                                                            <input name="email" required type="text" placeholder="" class="form-control">
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <label class="control-label">Car purchase per Month</label>
                                                            <select  name="car_number" id="" class="form-control">
                                                                <option value="10">10</option>
                                                                <option value="20">20</option>
                                                                <option value="50">50</option>
                                                                <option value="100">100 +</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4 form-group">
                                                            <label class="control-label">Extra For Calculator</label>
                                                            <input name="extra" required type="text" placeholder="" class="form-control">
                                                        </div>
                                                        <!-- Text input-->
                                                        <div class="col-md-4 form-group text-center">
                                                            <label class="control-label">Status Active</label>
                                                            <input name="status" required type="checkbox" checked  class="form-control">
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <label class="control-label">Role</label>
                                                            <select  name="role" id="" class="form-control">
                                                                <option value="10">10</option>
                                                                <option value="20">20</option>
                                                                <option value="50">50</option>
                                                                <option value="100">100 +</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4 form-group">
                                                            <label class="control-label">password</label>
                                                            <input name="password" required type="text" placeholder="" class="form-control">
                                                        </div>
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

                                {{-- Per Page--}}
                                <form action="{{route('customers.index')}}">
                                    <select style="width: 70px" class="ml-3 form-control" name="perpage" id=""
                                            onchange="this.form.submit()">
                                        <option value="10" {{request('perpage') == 10 ? 'selected' : ''}}>10</option>
                                        <option value="25" {{request('perpage') == 25 ? 'selected' : ''}}>25</option>
                                        <option value="50" {{request('perpage') == 50 ? 'selected' : ''}}>50</option>
                                    </select>
                                </form>
                                {{-- Search--}}
                                <form style="display: flex!important;" class="ml-3 "
                                      action="{{route('locations.index')}}">
                                    <input type="text" name="search" class="form-control"
                                           value="{{request()->query('search')}}">
                                    <button type="submit"
                                            class="btn green_btn custom_grreen2 ml-2 mb-3 ">Search
                                    </button>
                                </form>
                                {{--Return All Data--}}
                                @if(request()->query('search'))
                                    <form action="{{route('locations.index')}}">
                                        <button type="submit"
                                                class="btn green_btn custom_grreen2 ml-2 mb-3 ">All
                                        </button>
                                    </form>
                                @endif
                                {{-- Auction--}}
                                <form action="{{route('locations.index')}}">
                                    <select style="width: 120px" class="ml-3 form-control" name="auction" id=""
                                            onchange="this.form.submit()">
                                        <option value="all">All Auctions</option>
{{--                                        @foreach($auctions as $auction)--}}
{{--                                            <option @selected(request()->query('auction') == $auction->id) value="{{$auction->id}}" >{{$auction->name}}</option>--}}
{{--                                        @endforeach--}}
                                    </select>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
                                    <thead class="back_table_color">
                                    <tr class="info text-center">
                                        <th>Company Name</th>
                                        <th>Contact Person</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Dealer</th>
                                        <th>Login</th>
                                        <th>Status</th>
                                        <th>Crated At</th>
                                        <th>Crated Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($customers as $index=> $customer)
                                        <tr class="text-center">
                                            <td>{{$customer->company_name}}</td>
                                            <td>{{$customer->contact_name}}</td>
                                            <td>{{$customer->phone}}</td>
                                            <td>{{$customer->email}}</td>
                                            <td>dealer</td>
                                            <td>login</td>
                                            <td>{{$customer->is_active}}</td>
                                            <td>{{$customer->created_at->format('d-m-Y')}}</td>
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
                                                                <h3> Edit customer</h3>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-hidden="true">×
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <form class="form-horizontal"
                                                                              action="{{route('locations.update')}}"
                                                                              method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="id"
                                                                                   value="{{$customer->id}}" id="">
                                                                            <div class="row flex justify-content-center">

                                                                                Content

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
                                                                <h3><i class="fa fa-user m-r-5"></i> Delete Customer
                                                                </h3>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-hidden="true">×
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <form class="form-horizontal"
                                                                              action="{{route('locations.destroy')}}"
                                                                              method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="id"
                                                                                   value="{{$customer->id}}">
                                                                            <fieldset>
                                                                                <div class="col-md-12 form-group user-form-group">
                                                                                    <label class="control-label">Delete
                                                                                        Customer : {{$customer->contact_name}}
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
                                @if($customers->isEmpty())
                                    <div style="width: 100%;display: flex;justify-content: center">
                                        <span style="font-size: 20px" class="label label-pill label-danger m-r-15">No Records Found</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-content-center">
                {{ $customers->links() }}
            </div>
        </section>
        <!-- /.content -->
    </div>

@endsection