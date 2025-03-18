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
                    @if(isset($archive))
                        <h1>Archived Deposit Transfers</h1>
                    @else
                        <h1>Deposit Requests</h1>
                    @endif
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
            @if(session()->has('error'))
                <div style="padding: 5px!important;"
                     class="ml-3 alert custom_alerts alert-danger alert-dismissible fade show w-25" role="alert">
                    <p>{{session()->get('error')}}</p>
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
                                        <input type="text" name="search" class="form-control"
                                               value="{{request()->query('search')}}">
                                        <input type="hidden" name="archived" value="{{request()->query('archived')}}">
                                        <button type="submit" class="btn green_btn custom_grreen2 ml-2 mb-3 ">Search
                                        </button>
                                    </form>

                                    <a href="{{route('customer.balance.index')}}" style="color: white;max-width: 120px"
                                       type="submit" class="btn green_btn custom_grreen2 ml-2 mb-3 ">Clear Search
                                    </a>

                                    <a href="{{route('customer.balance.index',['archived'=>'true'])}}"
                                       style="color: white;max-width: 120px"
                                       class="btn btn-danger ml-2 mb-3 ">Archived
                                        <span>{{$trashedCount}}</span>
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
                                                                           type="text" required
                                                                           onfocus="btn= document.getElementById('searchmodalbtn').click()">
                                                                </div>
                                                                <div class="col-md-6 form-group text-center">
                                                                    <label class="text-center">Transfer Date</label>
                                                                    <input autocomplete="off" type="date"
                                                                           name="transfer_date" id="" required>
                                                                </div>

                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 form-group text-center">
                                                                    <label class="text-center">Full Name</label>
                                                                    <input autocomplete="off" id="" name="full_name"
                                                                           type="text" required>
                                                                </div>
                                                                <div class="col-md-6 form-group text-center">
                                                                    <label class="text-center">Amount</label>
                                                                    <input autocomplete="off" type="text"
                                                                           name="bank_payment" id="" required>
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
                                <div style="max-height: 600px; overflow:auto">
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
                                            @if($prequest->balance_fill_date)
                                                <tr class="text-center">
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $prequest->balance_fill_date?->format('m-d-Y') }}</td>
                                                    <td>
                                                        <div style="align-items: center;gap: 8px"
                                                             class="d-flex justify-content-center">
                                                            <p style="margin: 0!important;">  {{ $prequest->amount }}</p>
                                                            @if($prequest->media->first())
                                                                <a href="{{$prequest->media->first()->getUrl()}}"
                                                                   target="_blank">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30"
                                                                         height="30" viewBox="0 0 1024 1024">
                                                                        <path fill="currentColor"
                                                                              d="M512 64C264.6 64 64 264.6 64 512s200.6 448 448 448s448-200.6 448-448S759.4 64 512 64m0 820c-205.4 0-372-166.6-372-372s166.6-372 372-372s372 166.6 372 372s-166.6 372-372 372m47.7-395.2l-25.4-5.9V348.6c38 5.2 61.5 29 65.5 58.2c.5 4 3.9 6.9 7.9 6.9h44.9c4.7 0 8.4-4.1 8-8.8c-6.1-62.3-57.4-102.3-125.9-109.2V263c0-4.4-3.6-8-8-8h-28.1c-4.4 0-8 3.6-8 8v33c-70.8 6.9-126.2 46-126.2 119c0 67.6 49.8 100.2 102.1 112.7l24.7 6.3v142.7c-44.2-5.9-69-29.5-74.1-61.3c-.6-3.8-4-6.6-7.9-6.6H363c-4.7 0-8.4 4-8 8.7c4.5 55 46.2 105.6 135.2 112.1V761c0 4.4 3.6 8 8 8h28.4c4.4 0 8-3.6 8-8.1l-.2-31.7c78.3-6.9 134.3-48.8 134.3-124c-.1-69.4-44.2-100.4-109-116.4m-68.6-16.2c-5.6-1.6-10.3-3.1-15-5c-33.8-12.2-49.5-31.9-49.5-57.3c0-36.3 27.5-57 64.5-61.7zM534.3 677V543.3c3.1.9 5.9 1.6 8.8 2.2c47.3 14.4 63.2 34.4 63.2 65.1c0 39.1-29.4 62.6-72 66.4"/>
                                                                    </svg>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>{{ $prequest->full_name }}</td>
                                                    <td>{{ $prequest->customer->company_name }}</td>
                                                    <td>{{ $prequest->customer->contact_name }}</td>
                                                    <td>
                                                        <form action="{{route('customer.balance.approve')}}"
                                                              method="post">
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
                                                        <button type="button" class="btn btn-add btn-sm"
                                                                data-toggle="modal"
                                                                data-target="#update{{$index}}">
                                                            <i class="fa fa-pencil"></i>
                                                        </button>
                                                        <div class="modal fade" id="update{{$index}}" tabindex="-1"
                                                             role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header modal-header-primary">
                                                                        <h3>Edit Payment
                                                                            Request {{$prequest->amount}}</h3>
                                                                        <button type="button" class="close"
                                                                                data-dismiss="modal" aria-hidden="true">
                                                                            ×
                                                                        </button>
                                                                    </div>
                                                                    <form action="{{route('customer.balance.update')}}"
                                                                          enctype="multipart/form-data"
                                                                          method="post">
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <input type="hidden"
                                                                                           name="customer_id"
                                                                                           id="customerID">
                                                                                    <input type="hidden"
                                                                                           name="customerID"
                                                                                           value="{{$prequest->customer->id}}">
                                                                                    <input type="hidden"
                                                                                           name="balance_id"
                                                                                           value="{{$prequest->id}}">
                                                                                    @csrf
                                                                                    <div class="row justify-content-center">
                                                                                        <div class="col-md-6 form-group text-center ">
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
                                                                                    <div class="row">
                                                                                        <div class="col-md-12 form-group text-center">
                                                                                            <label class="text-center">Payment
                                                                                                Order</label>
                                                                                            <input accept="image/*"
                                                                                                   type="file"
                                                                                                   name="paymentorder">
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
                                                                        <h3><i class="fa fa-user m-r-5"></i>Delete
                                                                            Payment?
                                                                        </h3>
                                                                        <button type="button" class="close"
                                                                                data-dismiss="modal" aria-hidden="true">
                                                                            ×
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <form class="form-horizontal"
                                                                                      action="{{route('customer.balance.delete')}}"
                                                                                      method="post">
                                                                                    @csrf
                                                                                    @if(request()->query('archived'))
                                                                                        <input type="hidden"
                                                                                               name="archived"
                                                                                               value="1">
                                                                                    @endif
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

                                                        {{-- restore softdeleted --}}
                                                        @if(request()->query('archived'))
                                                            <form action="{{route('deposit.archived.restore')}}"
                                                                  style="display: inline" method="post">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                       value="{{$prequest->id}}">
                                                                <button class="btn btn-warning btn-sm">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="17"
                                                                         height="17" viewBox="0 0 512 512">
                                                                        <path fill="#f0e3e3" fill-rule="evenodd"
                                                                              d="M256 448c-97.974 0-178.808-73.383-190.537-168.183l42.341-5.293c9.123 73.734 71.994 130.809 148.196 130.809c82.475 0 149.333-66.858 149.333-149.333S338.475 106.667 256 106.667c-50.747 0-95.581 25.312-122.567 64h79.9v42.666H64V64h42.667v71.31C141.866 91.812 195.685 64 256 64c106.039 0 192 85.961 192 192s-85.961 192-192 192"
                                                                              clip-rule="evenodd"/>
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>

                                                </tr>
                                            @endif
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


        <input type="hidden" id="deposit">
        @include('partials.footer')


    </div>

@endsection
