@php
    use App\Services\CreditService;
      $creditService = new CreditService();
@endphp
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
                    <h1>Car Payments</h1>
                </div>
            </section>
            @if($errors->any())
                <div class="d-flex justify-content-center">
                    @foreach($errors->all() as $error)
                        <p class="text-danger" style="margin: 2px 0 2px 0;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if(session('error'))
                <div class="d-flex justify-content-center">
                    <p class="text-danger" style="margin: 2px 0 2px 0;">{{ session('error') }}</p>
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
                                        <a href="#" class="btn btn-add" data-toggle="modal" data-target="#addModal">
                                            <i class="fa fa-plus"></i> Add Car Payment
                                        </a>
                                    </div>
                                </div>
                                {{-- Add Carpayment Modal--}}
                                <div class="modal fade" id="addModal" tabindex="-1"
                                     role="dialog" style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header modal-header-primary">
                                                <h3><i class="fa fa-user m-r-5"></i>
                                                    Add Car Payment
                                                </h3>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-hidden="true">×
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <form class="form-horizontal"
                                                              action="{{route('carpayment.store')}}"
                                                              method="post">
                                                            @csrf

                                                            <div class="row">

                                                                <div class="col-md-4 form-group text-center">
                                                                    <input autocomplete="off" type="hidden"
                                                                           name="customer_id"
                                                                           id="customerID">
                                                                    <label class="text-center">Customer</label>
                                                                    <input required autocomplete="off" id="customerName"
                                                                           name="full_name"
                                                                           type="text"
                                                                           onfocus="document.getElementById('searchmodalbtn').click()">
                                                                </div>
                                                                <div class="col-md-8 form-group text-center">
                                                                    <input autocomplete="off" type="hidden"
                                                                           name="car_id" id="carID">
                                                                    <label class="text-center">Make / Model</label>
                                                                    <input required autocomplete="off"
                                                                           style="width: 100%" type="text" id="carName"
                                                                           onfocus="
                                                                if(document.getElementById('customerID').value !== '') {
                                                                    document.getElementById('searchCarBtn').click();
                                                                }
                                                                "
                                                                    >
                                                                </div>
                                                            </div>

                                                            <div class="row justify-content-center mb-4">
                                                                <div class="col-md-6 form-group text-center">
                                                                    <label class="text-center">Date</label>
                                                                    <input type="hidden" name="car_id2" id="carID2">
                                                                    <input type="hidden" name="due2" id="due2">
                                                                    <input id="payment_date" required disabled
                                                                           autocomplete="off" name="payment_date"
                                                                           type="date"
                                                                           hx-trigger="change"
                                                                           hx-get="{{route('car.calculate.percenttilldate')}}"
                                                                           hx-target="#percenttarget"
                                                                           hx-include="#carID2,#due2"
                                                                    >


                                                                </div>
                                                                <div id="percenttarget"></div>
                                                            </div>

                                                            <div class="row justify-content-center">
                                                                <div class="col-md-3 form-group text-center">
                                                                    <label class="text-center">Deposit</label>
                                                                    <input style="max-width:150px;text-align: center"
                                                                           disabled id="deposit" type="text">
                                                                </div>
                                                                <div class="col-md-3 form-group text-center">
                                                                    <label class="text-center">Amount due</label>
                                                                    <input style="max-width:150px;text-align: center"
                                                                           disabled id="due" type="text">
                                                                </div>
                                                                <div class="col-md-3 form-group text-center">
                                                                    <label class="text-center">
                                                                        Accrued Percent

                                                                        <span style="font-size: 8px">(till today)</span>
                                                                    </label>
                                                                    <input style="max-width:150px;text-align: center"
                                                                           disabled id="total_percent" type="text">
                                                                </div>
                                                                <div class="col-md-3 form-group text-center">
                                                                    <label class="text-center">Total Due</label>
                                                                    <input style="max-width:150px;text-align: center"
                                                                           id="totaldue" disabled type="text">
                                                                </div>
                                                            </div>
                                                            <div class="row justify-content-center">
                                                                <div class="col-md-4 form-group text-center">
                                                                    <label class="text-center">Amount</label>
                                                                    <input style="max-width:150px;text-align: center"
                                                                           required autocomplete="off" name="amount"
                                                                           type="text">
                                                                </div>
                                                                <div class="col-md-8 form-group text-center">
                                                                    <label class="text-center">Comment</label>
                                                                    <input style="width: 100%;text-align: center"
                                                                            autocomplete="off" name="comment"
                                                                           type="text">
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
                                <a id="searchmodalbtn" data-toggle="modal" data-target="#searchCustomer"></a>
                                <div class="modal fade" id="searchCustomer" tabindex="-1"
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
                                                                <div id="searchtarget" class="text-center mt-3"></div>

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
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Search Customer Cars Modal--}}
                                <a id="searchCarBtn" data-toggle="modal" data-target="#searchCarModal"></a>
                                <div class="modal fade" id="searchCarModal" tabindex="-1"
                                     role="dialog" style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header modal-header-primary">
                                                <h3><i class="fa fa-user m-r-5"></i>
                                                    Search Customer Cars
                                                </h3>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-hidden="true">×
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <fieldset>
                                                            <div class="col-md-12 form-group user-form-group">

                                                                <div class="d-flex justify-content-center">
                                                                    <input hx-get="{{route('car.search.htmx')}}"
                                                                           hx-target="#carsearchtarget"
                                                                           hx-include="#customerID"
                                                                           hx-trigger="keyup delay:500"
                                                                           type="text"
                                                                           name="search"
                                                                           placeholder="Search Car">
                                                                </div>
                                                                <div id="carsearchtarget"
                                                                     class="text-center mt-3"></div>

                                                                <div>
                                                                    <div class="d-flex justify-content-center mt-3">
                                                                        <button id="closeSearch2" type="button"
                                                                                data-dismiss="modal"
                                                                                class="btn btn-danger btn-sm mr-2">
                                                                            Close Search
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Carpayments Data--}}
                                <div class="table-responsive">
                                    <table id="dataTableExample1"
                                           class="table table-bordered table-striped table-hover">
                                        <thead class="back_table_color">
                                        <tr class="info">
                                            <th>#</th>
                                            <th>Create Date</th>
                                            <th>Payment Date</th>
                                            <th>CAR</th>
                                            <th>Customer</th>
                                            <th>Paid</th>
                                            <th>Is Approved</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($payment_reports as $index => $payment_report)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    {{ $payment_report->created_at->format('d-m-Y') }}
                                                </td>
                                                <td>
                                                    {{  $payment_report->carpayment_date->format('d-m-Y') }}
                                                </td>
                                                <td>{{ $payment_report->car->make_model_year }}</td>
                                                <td>{{ $payment_report->customer->contact_name }}</td>
                                                <td>${{ $payment_report->amount*-1 }}</td>
                                                <td><span
                                                            class="label-{{ $payment_report->is_approved == 1 ? 'custom' : 'danger' }} label label-default">{{ $payment_report->is_approved == 1 ? 'YES' : 'NO' }}</span>
                                                </td>

                                                <td>
                                                    <button type="button" class="btn-edit-record btn btn-add btn-sm"
                                                            data-toggle="modal"
                                                            data-record-id="{{ $payment_report->id }}"
                                                            data-target="#edit{{$index}}"><i class="fa fa-pencil"></i>
                                                    </button>

                                                    {{-- EDIT Carpayment Modal--}}
                                                    <div class="modal fade" id="edit{{$index}}" tabindex="-1"
                                                         role="dialog" style="display: none;" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header modal-header-primary">
                                                                    <h3><i class="fa fa-user m-r-5"></i>
                                                                        Edit Car Payment
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
                                                                                  action="{{route('carpayment.update')}}"
                                                                                  method="post">
                                                                                @csrf
                                                                                <input type="hidden" name="payment_id"
                                                                                       value="{{$payment_report->id}}">
                                                                                <div class="row justify-content-center mb-4">
                                                                                    <div class="col-md-6 form-group text-center">
                                                                                        <label class="text-center">Date</label>
                                                                                        <input value="{{$payment_report->carpayment_date->format('Y-m-d')}}"
                                                                                               required
                                                                                               min="{{$payment_report->car->created_at->format('Y-m-d')}}"
                                                                                               autocomplete="off"
                                                                                               name="payment_date"
                                                                                               type="date">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-4 form-group text-center">
                                                                                        <input value="{{$payment_report->customer_id}}"
                                                                                               type="hidden"
                                                                                               name="customer_id"
                                                                                               id="customerID{{$index}}">
                                                                                        <label class="text-center">Customer</label>
                                                                                        <input style="width: 100%"
                                                                                               id="customerName{{$index}}"
                                                                                               name="full_name"
                                                                                               value="{{$payment_report->customer->contact_name}}"
                                                                                               type="text"
                                                                                               onfocus="document.getElementById('searchmodalbtn{{$index}}').click()">
                                                                                    </div>

                                                                                    <div class="col-md-8 form-group text-center">

                                                                                        <input value="{{$payment_report->car->id}}"
                                                                                               type="hidden"
                                                                                               name="car_id"
                                                                                               id="carID{{$index}}">
                                                                                        <label class="text-center">Make
                                                                                            / Model</label>
                                                                                        <input style="width: 100%"
                                                                                               type="text"
                                                                                               id="carName{{$index}}"
                                                                                               value="{{$payment_report->car->make_model_year}}"
                                                                                               onfocus="
                                                                                        if(document.getElementById('customerID{{$index}}').value !== '') {
                                                                                            document.getElementById('searchCarBtn{{$index}}').click();
                                                                                        }
                                                                                        ">

                                                                                    </div>
                                                                                </div>

                                                                                <div class="row justify-content-center">
                                                                                    <div class="col-md-3 form-group text-center">
                                                                                        <label class="text-center">Deposit</label>
                                                                                        <input value="{{$deposit}}"
                                                                                               style="max-width:150px;text-align: center"
                                                                                               disabled="" type="text">
                                                                                    </div>
                                                                                    <div class="col-md-3 form-group text-center">
                                                                                        <label class="text-center">Amount
                                                                                            due</label>
                                                                                        <input value="{{$payment_report->car->amount_due}}"
                                                                                               style="max-width:150px;text-align: center"
                                                                                               disabled="" type="text">
                                                                                    </div>
                                                                                    <div class="col-md-3 form-group text-center">
                                                                                        <label class="text-center">Accrued
                                                                                            Percent</label>
                                                                                        <input value=""
                                                                                               style="max-width:150px;text-align: center"
                                                                                               disabled="" type="text">
                                                                                    </div>
                                                                                    <div class="col-md-3 form-group text-center">
                                                                                        <label class="text-center">Total
                                                                                            Due
                                                                                            <span style="font-size: 8px">(till today)</span>
                                                                                        </label>
                                                                                        <input value=""
                                                                                               style="max-width:150px;text-align: center"
                                                                                               disabled="" type="text">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row justify-content-center">
                                                                                    <div class="col-md-4 form-group text-center">
                                                                                        <label class="text-center">Amount</label>
                                                                                        <input value="{{($payment_report->amount)*-1}}"
                                                                                               name="amount"
                                                                                               type="text">

                                                                                        <input type="checkbox"
                                                                                               name="approve" @checked($payment_report->is_approved)>

                                                                                    </div>
                                                                                    <div class="col-md-8 form-group text-center">
                                                                                        <label class="text-center">Comment</label>
                                                                                        <input style="width: 100%" value="{{($payment_report->Comment1)}}"
                                                                                               name="comment"
                                                                                               type="text">


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
                                                    <a id="searchmodalbtn{{$index}}" data-toggle="modal"
                                                       data-target="#searchCustomer{{$index}}"></a>
                                                    <div class="modal fade" id="searchCustomer{{$index}}" tabindex="-1"
                                                         role="dialog" style="display: none;" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header modal-header-primary">
                                                                    <h3><i class="fa fa-user m-r-5"></i>
                                                                        Search Customer
                                                                    </h3>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal"
                                                                            aria-hidden="true">×
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <fieldset>
                                                                                <div class="col-md-12 form-group user-form-group">
                                                                                    <input type="hidden"
                                                                                           id="customer_id{{$index}}"
                                                                                           name="customer_id">
                                                                                    <div class="d-flex justify-content-center">
                                                                                        <input hx-get="{{route('customer.search.htmx')}}"
                                                                                               hx-target="#searchtarget{{$index}}"
                                                                                               hx-vals='{"index": {{$index}}}'
                                                                                               hx-trigger="keyup delay:500"
                                                                                               type="text"
                                                                                               name="search"
                                                                                               placeholder="Search Customer">
                                                                                    </div>
                                                                                    <div id="searchtarget{{$index}}"
                                                                                         class="text-center mt-3"></div>

                                                                                    <div>
                                                                                        <div class="d-flex justify-content-center mt-3">
                                                                                            <button id="closeSearch{{$index}}"
                                                                                                    type="button"
                                                                                                    data-dismiss="modal"
                                                                                                    class="btn btn-danger btn-sm mr-2">
                                                                                                Close Search
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </fieldset>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Search Customer Cars Modal--}}
                                                    <a id="searchCarBtn{{$index}}" data-toggle="modal"
                                                       data-target="#searchCarModal{{$index}}"></a>
                                                    <div class="modal fade" id="searchCarModal{{$index}}" tabindex="-1"
                                                         role="dialog" style="display: none;" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header modal-header-primary">
                                                                    <h3><i class="fa fa-user m-r-5"></i>
                                                                        Search Customer Cars
                                                                    </h3>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal"
                                                                            aria-hidden="true">×
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <fieldset>
                                                                                <div class="col-md-12 form-group user-form-group">

                                                                                    <div class="d-flex justify-content-center">
                                                                                        <input hx-get="{{route('car.search.htmx')}}"
                                                                                               hx-target="#carsearchtarget{{$index}}"
                                                                                               hx-include="#customerID{{$index}}"
                                                                                               hx-vals='{"index": {{$index}}}'
                                                                                               hx-trigger="keyup delay:500"
                                                                                               type="text"
                                                                                               name="search"
                                                                                               placeholder="Search Car">
                                                                                    </div>
                                                                                    <div id="carsearchtarget{{$index}}"
                                                                                         class="text-center mt-3"></div>

                                                                                    <div>
                                                                                        <div class="d-flex justify-content-center mt-3">
                                                                                            <button id="closeSearch2{{$index}}"
                                                                                                    type="button"
                                                                                                    data-dismiss="modal"
                                                                                                    class="btn btn-danger btn-sm mr-2">
                                                                                                Close Search
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </fieldset>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button type="button" class="btn btn-danger btn-sm"
                                                            data-toggle="modal" data-target="#deleteModal{{$index}}"
                                                            data-record-id="{{ $payment_report->id }}">
                                                        <i class="fa fa-trash-o"></i>
                                                    </button>
                                                    <!-- DELETE Record Modal -->
                                                    <div class="modal fade" id="deleteModal{{$index}}" tabindex="-1"
                                                         role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <form action="{{ route('carpayment.delete')}}"
                                                                  method="POST">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                       value="{{ $payment_report->id}}">
                                                                <div class="modal-content">
                                                                    <div class="modal-header modal-header-primary">
                                                                        <h3> Delete Car Payment</h3>
                                                                        <button type="button" class="close"
                                                                                data-dismiss="modal" aria-hidden="true">
                                                                            ×
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p>Are you sure you want to delete this
                                                                            Record?</p>
                                                                        <br>
                                                                        <p>
                                                                            Car: {{ $payment_report->car->make_model_year}}</p>
                                                                        <p>
                                                                            Dealer: {{ $payment_report->customer->contact_name}}</p>
                                                                        <p>Amount: {{ $payment_report->amount*-1}}</p>

                                                                        <!-- Hidden field to hold the user ID -->
                                                                    </div>
                                                                    <div class="modal-footer justify-content-center">
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
