@extends('layouts.app')
@section('content')

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
                    <h1>Deposit Requests</h1>
                </div>
            </section>
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

                                    <form style="display: flex!important;" class="ml-3 "
                                          action="{{route('customer.balance.index')}}">
                                        <input type="text" name="search" class="form-control" value="">
                                        <button type="submit" class="btn green_btn custom_grreen2 ml-2 mb-3 ">Search
                                        </button>
                                    </form>

                                    <a href="{{route('customer.balance.index')}}" style="color: white;max-width: 50px"
                                       type="submit" class="btn green_btn custom_grreen2 ml-2 mb-3 ">All
                                    </a>

                                </div>
                                {{-- Add Payment to Balance Modal--}}
                                <div class="modal fade" id="addModal" tabindex="-1"
                                     role="dialog" style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header modal-header-primary">
                                                <h3><i class="fa fa-user m-r-5"></i>
                                                    Add Balance Payment
                                                </h3>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-hidden="true">×
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <form class="form-horizontal"
                                                              action="{{route('customer.balance.store')}}"
                                                              method="post">
                                                            <input type="hidden" name="customer_id" id="customerID">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-md-6 form-group text-center">
                                                                    <label class="text-center">Customer</label>
                                                                    <input autocomplete="off" id="customerName"
                                                                           type="text"
                                                                           onfocus="btn= document.getElementById('searchmodalbtn').click()">
                                                                </div>
                                                                <div class="col-md-6 form-group text-center">
                                                                    <label class="text-center">Transfer Date</label>
                                                                    <input autocomplete="off" type="date"
                                                                           name="transfer_date" id="">
                                                                </div>

                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 form-group text-center">
                                                                    <label class="text-center">Full Name</label>
                                                                    <input autocomplete="off" id="" name="full_name"
                                                                           type="text">
                                                                </div>
                                                                <div class="col-md-6 form-group text-center">
                                                                    <label class="text-center">Amount</label>
                                                                    <input autocomplete="off" type="text"
                                                                           name="bank_payment" id="">
                                                                </div>

                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 form-group text-center">
                                                                    <label class="text-center">Comment</label>
                                                                    <textarea name="comment" id="" class="w-100"
                                                                              rows="5"></textarea>
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
                                <a id="searchmodalbtn" data-toggle="modal" data-target="#searchmodal"></a>
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
                                                                    <div id="searchtarget"
                                                                         class="text-center mt-3"></div>

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
                                        <tr class="info text-center">
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Payer Full Name</th>
                                            <th>Company Name</th>
                                            <th>Contact Name</th>
                                            <th>Approve</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($payment_requests as $index => $prequest)
                                            <tr class="text-center">
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $prequest->balance_fill_date->format('m-d-Y') }}</td>
                                                <td>{{ $prequest->amount }}</td>
                                                <td>{{ $prequest->full_name }}</td>
                                                <td>{{ $prequest->customer->company_name }}</td>
                                                <td>{{ $prequest->customer->contact_name }}</td>
                                                <td>
                                                    <form action="{{route('customer.balance.approve')}}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{$prequest->id}}">
                                                        @if($prequest->is_approved===1)
                                                            <button class="btn btn-success btn-rounded w-md m-b-5">
                                                                Approved
                                                            </button>
                                                        @else
                                                            <button class="btn btn-danger btn-rounded w-md m-b-5">
                                                                Needs Approval
                                                            </button>
                                                        @endif
                                                    </form>
                                                </td>
                                                <td>
                                                    {{--Edit Modal--}}
                                                    <button type="button" class="btn btn-add btn-sm" data-toggle="modal"
                                                            data-target="#update{{$index}}">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    <div class="modal fade" id="update{{$index}}" tabindex="-1"
                                                         role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header modal-header-primary">
                                                                    <h3>Edit Payment Request {{$prequest->amount}}</h3>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal" aria-hidden="true">×
                                                                    </button>
                                                                </div>
                                                                <form action="{{route('customer.balance.update')}}"
                                                                      method="post">
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <input type="hidden" name="customer_id"
                                                                                       id="customerID">
                                                                                <input type="hidden" name="customerID"
                                                                                       value="{{$prequest->customer->id}}">
                                                                                <input type="hidden" name="balance_id"
                                                                                       value="{{$prequest->id}}">
                                                                                @csrf
                                                                                <div class="row">
                                                                                    <div class="col-md-6 form-group text-center">
                                                                                        <label class="text-center">Transfer
                                                                                            Date</label>
                                                                                        <input autocomplete="off"
                                                                                               value="{{ $prequest->balance_fill_date?->format('Y-m-d') }}"
                                                                                               type="date"
                                                                                               name="transfer_date">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-6 form-group text-center">
                                                                                        <label class="text-center">Full
                                                                                            Name</label>
                                                                                        <input autocomplete="off"
                                                                                               value="{{$prequest->full_name}}"
                                                                                               name="full_name"
                                                                                               type="text">
                                                                                    </div>
                                                                                    <div class="col-md-6 form-group text-center">
                                                                                        <label class="text-center">Amount</label>
                                                                                        <input autocomplete="off"
                                                                                               value="{{$prequest->amount}}"
                                                                                               type="text"
                                                                                               name="bank_payment">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-12 form-group text-center">
                                                                                        <label class="text-center">Comment</label>
                                                                                        <textarea name="comment"
                                                                                                  class="w-100"
                                                                                                  rows="5">{{$prequest->comment}}</textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div>
                                                                                    <div class="d-flex justify-content-center mt-3">
                                                                                        <button type="button"
                                                                                                data-dismiss="modal"
                                                                                                class="btn btn-danger btn-sm mr-2">
                                                                                            Close
                                                                                        </button>
                                                                                        <button type="submit"
                                                                                                class="btn btn-success btn-sm mr-2">
                                                                                            Save
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{--Delete Modal--}}
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                            data-toggle="modal" data-target="#customer2{{$index}}">
                                                        <i class="fa fa-trash-o"></i>
                                                    </button>
                                                    <div class="modal fade" id="customer2{{$index}}" tabindex="-1"
                                                         role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header modal-header-primary">
                                                                    <h3><i class="fa fa-user m-r-5"></i>Delete Payment?
                                                                    </h3>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal" aria-hidden="true">×
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
                <div class="flex justify-content-center">
                    {{ $payment_requests->links() }}
                </div>
            </section>
            <!-- /.content -->
        </div>


        @include('partials.footer')


    </div>

@endsection
