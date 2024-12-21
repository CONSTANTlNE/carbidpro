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
                    <i class="fa fa-money"></i>
                </div>
                <div class="header-title">
                    <h1>Balance Fill</h1>
                </div>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-lg-12 pinpin">
                        <div class="card lobicard" id="lobicard-custom-control" data-sortable="true">

                            <div class="card-body">
                                <div class="btn-group d-flex" role="group">
                                    <div class="buttonexport">
                                        <a href="#" class="btn btn-add" data-toggle="modal" data-target="#addModal"><i
                                                    class="fa fa-plus"></i> Add Report
                                        </a>
                                    </div>
                                </div>
                                {{-- Add Payment to Balance Modal--}}
                                <div class="modal fade" id="addModal" tabindex="-1"
                                     role="dialog" style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header modal-header-primary">
                                                <h3><i class="fa fa-user m-r-5"></i>
                                                    Add Payment
                                                </h3>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-hidden="true">×
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <form class="form-horizontal"
                                                              action="{{route('customer.payment_registration_submit')}}"
                                                              method="post">
                                                            <input type="hidden" name="customer_id" id="customerID">
                                                            @csrf
                                                                <div class="row">
                                                                    <div class="col-md-6 form-group text-center">
                                                                        <label  class="text-center" >Customer</label>
                                                                        <input id="customerName" name="full_name" type="text" onfocus="btn= document.getElementById('searchmodalbtn').click()">
                                                                    </div>
                                                                    <div class="col-md-6 form-group text-center">
                                                                        <label  class="text-center" >Amount</label>
                                                                        <input type="text" name="bank_payment" id="">
                                                                    </div>

                                                                </div>
                                                            <div>
                                                                <div class="d-flex justify-content-center mt-3">
                                                                    <button type="button"
                                                                            data-dismiss="modal"
                                                                            class="btn btn-danger btn-sm mr-2">
                                                                        Close
                                                                    </button>

                                                                    <button
                                                                            class="btn btn-success btn-sm mr-2">
                                                                        Save
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Search Customer Modal--}}
                                <a id="searchmodalbtn" data-toggle="modal" data-target="#searchmodal" ></a>
                                <div class="modal fade" id="searchmodal" tabindex="-1"
                                     role="dialog" style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header modal-header-primary">
                                                <h3><i class="fa fa-user m-r-5"></i>
                                                    Search Customer
                                                </h3>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-hidden="true">×
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <form class="form-horizontal"
                                                              action="{{route('customer.payment_registration_submit')}}"
                                                              method="post">
                                                            @csrf
                                                            <fieldset>
                                                                <div class="col-md-12 form-group user-form-group">
                                                                    <input type="hidden" id="customer_id"
                                                                           name="customer_id">
                                                                    <div class="d-flex justify-content-center">
                                                                        <input hx-get="{{route('customer.search.htmx')}}"
                                                                               hx-target="#searchtarget"
                                                                               hx-trigger="keyup delay:500"
                                                                               type="text"
                                                                               name="search"
                                                                               placeholder="Search Customer">
                                                                    </div>
                                                                    <div id="searchtarget" class="text-center mt-3">   </div>

                                                                        <div>
                                                                            <div class="d-flex justify-content-center mt-3">
                                                                                <button id="closeSearch" type="button"
                                                                                        data-dismiss="modal"
                                                                                        class="btn btn-danger btn-sm mr-2">
                                                                                    Close Search
                                                                                </button>
                                                                            </div>
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


                                {{-- Payments Data--}}
                                <div class="table-responsive">
                                    <table id="dataTableExample1"
                                           class="table table-bordered table-striped table-hover">
                                        <thead class="back_table_color">
                                        <tr class="info">
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Dealer</th>
                                            <th>Approve</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($payment_requests as $index => $prequest)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $prequest->created_at->format('m-d-Y') }}</td>
                                                <td>{{ $prequest->amount }}</td>
                                                <td>{{ $prequest->customer->name }}</td>
                                                <td>
                                                    <form action="{{route('customer.balance.approve')}}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{$prequest->id}}">
                                                        @if($prequest->is_approved===1)
                                                            <button class="btn btn-success btn-rounded w-md m-b-5">
                                                                Approved
                                                            </button>
                                                        @else
                                                            <button class="btn btn-danger btn-rounded w-md m-b-5">Needs
                                                                Approval
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
                                                                    <h3> Edit Payment Request {{$prequest->amount}}</h3>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal"
                                                                            aria-hidden="true">×
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <form class="form-horizontal"
                                                                                  action="{{route('customer.balance.update')}}"
                                                                                  method="post">
                                                                                @csrf
                                                                                <input type="hidden" name="id"
                                                                                       value="{{$prequest->id}}" id="">
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
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                            data-toggle="modal"
                                                            data-target="#customer2{{$index}}"><i
                                                                class="fa fa-trash-o"></i>
                                                    </button>
                                                    <div class="modal fade" id="customer2{{$index}}" tabindex="-1"
                                                         role="dialog" style="display: none;" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header modal-header-primary">
                                                                    <h3><i class="fa fa-user m-r-5"></i>
                                                                        Delete Payment?
                                                                    </h3>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal"
                                                                            aria-hidden="true">×
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <form class="form-horizontal"
                                                                                  action="{{route('customer.balance.delete')}}"
                                                                                  method="post">
                                                                                @csrf
                                                                                <input type="hidden" name="id"
                                                                                       value="{{$prequest->id}}">
                                                                                <fieldset>
                                                                                    <div class="col-md-12 form-group user-form-group">

                                                                                        <div class="d-flex justify-content-center mt-3">
                                                                                            <button type="button"
                                                                                                    data-dismiss="modal"
                                                                                                    class="btn btn-danger btn-sm mr-2">
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


        @include('partials.footer')


    </div>
@endsection
