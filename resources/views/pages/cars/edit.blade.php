@extends('layouts.app')
@section('content')
    @push('css')
        <link href="{{asset('assets/select/select2.min.css')}}" rel="stylesheet"/>
        <!-- FilePond CSS -->
        <link href="{{asset('assets/filepond/filepond.css')}}" rel="stylesheet"/>
        <link href="{{asset('assets/filepond/filepond-plugin-image-preview.css')}}" rel="stylesheet"/>

        <link href="{{asset('assets/select/tomselect.css')}}" rel="stylesheet">
        <script src="{{asset('assets/select/tomselect.js')}}"></script>
        <style>
            .ts-wrapper {
                max-width: 250px;
                padding: 0;
            }
        </style>
    @endpush
    @section('body-class', 'hold-transition sidebar-mini')
    @php
        use App\Services\CreditService;
    use Carbon\Carbon  ;
        $creditService = new CreditService();

    @endphp
            <!--preloader-->
{{--        <div id="preloader">--}}
{{--            <div id="status"></div>--}}
{{--        </div>--}}

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
                    <i class="fa fa-automobile"></i>
                </div>
                <div class="header-title">
                    <h1>Edit Car</h1>
                    <small>Car list</small>
                </div>
            </section>
            <!-- Main content -->
            <div class="content">
                <div class="row">
                    <!-- Form controls -->
                    <div class="col-sm-12">
                        <div class="card lobicard all_btn_card" id="lobicard-custom-control1" data-sortable="true">
                            <div class="card-header all_card_btn">
                                <div class="card-title custom_title">
                                    <a class="btn btn-add" href="{{ route('cars.index') }}"><i class="fa fa-list"></i>
                                        Car List </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <style>
                                    .form-group {
                                        max-width: 50%;
                                        width: 100%;
                                    }

                                    form .container {
                                        padding: 0;
                                        margin: 0;
                                    }
                                </style>
                                <form action="{{ route('car.update', $car->id) }}" id="carForm" method="POST">
                                    @csrf
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="row">
                                                {{-- dealer --}}
                                                <div class="form-group">
                                                    <label>Dealer</label>
                                                    <select
                                                            hx-get="{{route('htmx.get.extraexpense')}}"
                                                            hx-target="#extraexpense"
                                                            autocomplete="nope"
                                                            name="customer_id"
                                                            class="form-control"
                                                            id="customerTomSelect" required>
                                                        <option value=""></option>
                                                        @foreach ($customers as $customer)
                                                            <option @selected($car->customer_id==$customer->id) value="{{ $customer->id }}"
                                                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>

                                                                {{ $customer->contact_name }} Extra
                                                                : {{ $customer->extra_for_team }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                {{-- broker --}}
                                                <div class="form-group">
                                                    <label>Broker</label>
                                                    <select name="dispatch_id" class="form-control" id="dispatch_id">
                                                        <option value=""></option>
                                                        @foreach ($brokers as $broker)
                                                            <option value="{{ $broker->id }}"
                                                                    {{ $broker->id == $car->dispatch_id ? 'selected' : '' }}>
                                                                {{ $broker->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                {{-- car--}}
                                                <div class="form-group">
                                                    <label>Make/Model/Year</label>
                                                    <input type="text" value="{{ $car->make_model_year }}"
                                                           name="make_model_year" class="form-control">
                                                </div>
                                                {{-- vin--}}
                                                <div class="form-group">
                                                    <label>Vin</label>
                                                    <input type="text" value="{{ $car->vin }}" name="vin"
                                                           class="form-control">
                                                </div>
                                            </div>

                                            {{--comment--}}
                                            <div style="max-width: 500px" class="form-group" id="customercomment">
                                                <label>Customer Comment</label>
                                                <textarea style="color:red;" disabled rows="3" class="form-control"
                                                          id="extraexpense"></textarea>
                                            </div>


                                            <div class="row mb-5">

                                                {{-- lot --}}
                                                <div class="col-sm-6 col-md-6 col-lg-3">
                                                    <label>Lot</label>
                                                    <input value="{{ $car->lot }}" type="text" name="lot"
                                                           class="form-control">
                                                </div>
                                                {{-- gate --}}
                                                <div class="col-sm-6 col-md-6 col-lg-3">
                                                    <label>Gate or Member</label>
                                                    <input type="text" value="{{ $car->gate_or_member }}"
                                                           name="gate_or_member" class="form-control">
                                                </div>
                                                {{-- title--}}
                                                <div class="col-sm-6 col-md-6 col-lg-3">
                                                    <label>Title</label>
                                                    <select name="title" class="form-control" id="title">
                                                        <option value=""></option>
                                                        <option value="no" {{ $car->title == 'no' ? 'selected' : '' }}>
                                                            NO
                                                        </option>
                                                        <option value="yes" {{ $car->title == 'yes' ? 'selected' : '' }}>
                                                            YES
                                                        </option>
                                                        <option value="bypost" {{ $car->title == 'bypost' ? 'selected' : '' }}>
                                                            BY POST
                                                        </option>
                                                        <option value="pending"
                                                                {{ $car->title == 'pending' ? 'selected' : '' }}>
                                                            PENDING
                                                        </option>
                                                    </select>
                                                </div>
                                                {{-- fuel --}}
                                                <div class=" col-sm-6 col-md-6 col-lg-3 "
                                                     style="align-items: center; width: max-content">
                                                    <label class="text-center">Type of Fuel</label><br>
                                                    <div class="d-flex justify-content-center align-middle text-center mt-3 ">

                                                        <label style="margin-right: 0" class="radio-inline">
                                                            <input name="type_of_fuel" value="Petrol" type="radio"
                                                                   required
                                                                    {{ old('type_of_fuel', $car->type_of_fuel ?? '') == 'Petrol' ? 'checked' : '' }}>
                                                            Petrol
                                                        </label>

                                                        <label style="margin-right: 0" class="radio-inline">
                                                            <input name="type_of_fuel" value="Hybrid" type="radio"
                                                                   required
                                                                    {{ old('type_of_fuel', $car->type_of_fuel ?? '') == 'Hybrid' ? 'checked' : '' }}>
                                                            Hybrid
                                                        </label>
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="container mb-3">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label for="auction">Auction</label>
                                                        <select id="auction" name="auction"
                                                                class="form-control select2">
                                                            <option value="">Select an option</option>
                                                            @foreach ($auctions as $auction)
                                                                <option value="{{ $auction->id }}"
                                                                        {{ $auction->id == $car->auction ? 'selected' : '' }}>
                                                                    {{ $auction->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="loadType">Load Type</label>
                                                        <select name="load_type" id="loadType"
                                                                class="form-control select2">
                                                            <option value="">Select an option</option>
                                                            @foreach ($load_types as $load_type)
                                                                <option value="{{ $load_type->id }}"
                                                                        {{ $load_type->id == $car->load_type ? 'selected' : '' }}>
                                                                    {{ $load_type->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="fromState">From State</label>
                                                        <select name="from_state" id="fromState"
                                                                class="form-control select2">
                                                            <option value="">Select an option</option>
                                                            <!-- This will be populated dynamically based on the auction selection -->
                                                        </select>
                                                    </div>

                                                    <!-- Add this for To Port and Load Type, etc. -->
                                                    <div class="col-md-2">
                                                        <label for="to_port_id">To Port Id</label>
                                                        <select name="to_port_id" id="to_port_id"
                                                                class="form-control select2">
                                                            <option value="">Select an option</option>
                                                            <!-- This will be populated dynamically -->
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="zip_code">Exact location-zip</label>
                                                        <input type="text" class="form-control" name="zip_code"
                                                               id="zip_code"
                                                               value="{{ old('zip_code', $car->zip_code ?? '') }}" >
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="warehouse">Warehouse</label>

                                                        <input type="text"
                                                               class="form-control"  id="warehouse"
                                                               value="{{ old('warehouse', $car->warehouse ?? '') }}" >

                                                        <select style="width: 273px!important" name="warehouse" class="form-control" style="width: min-content" required>
                                                            <option value="">Select Warehouse</option>
                                                            @foreach($warehouses as $warehouse)
                                                                <option @selected($car->warehouse_id==$warehouse->id) value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mt-3" id="extraexpense">
                                                    @php
                                                        $customerExtraExpenses = json_decode($selectedcustomer->extra_expenses, true);
                                                    @endphp


                                                    @foreach($extra_expenses as $index10 => $extraexpence)
                                                        @php
                                                            $foundExpense = collect($customerExtraExpenses)->firstWhere('name', $extraexpence->name);
                                                            // If found, get its value; otherwise default to an empty string
                                                            $expenseValue = $foundExpense ? $foundExpense['value'] : '';
                                                        @endphp

                                                        <div class="col-md-2 form-group">
                                                            <div class="d-flex align-middle">
                                                                <label style="max-width: max-content;"
                                                                       class="control-label">
                                                                    {{ $extraexpence->name }}
                                                                </label>
                                                            </div>
                                                            <input type="text"
                                                                   class="form-control"
                                                                   value="{{ $expenseValue }}"
                                                                   disabled>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="container">
                                        <div x-data="$store.balanceAccountingStore" class="mt-4">
                                            <p x-text="'Number of items: ' + balance_accounting.length"></p>

                                            <!-- Display the total of values -->
                                            <p>Total Value: <span x-text="calculateTotal()"></span></p>

                                            <div id="shippingwithoutextra" style="color:blue;display: none" class="row repeater-item mt-2 align-items-center">
                                                <div class="col-md-2 col-6"  >
                                                    <input  style="color:blue" disabled  class=" form-control"  value="Without Extra ">
                                                </div>
                                                <div class="col-md-2 col-6">
                                                    <input style="color:blue" id="originalshipping" type="number" class="form-control" value="" disabled>
                                                </div>
                                            </div>
                                            <!-- Repeater Fields in One Row -->
                                            <template x-for="(item, index) in balance_accounting" :key="index">
                                                <div class="row repeater-item mt-2 align-items-center">
                                                    <div class="col-md-2 col-6">
                                                        <input required type="text"
                                                               :name="`balance_accounting[${index}][name]`"
                                                               x-model="item.name"
                                                               class="name-autocomplete form-control"
                                                               placeholder="Enter item name">
                                                    </div>
                                                    <div class="col-md-2 col-6">
                                                        <input required type="number"
                                                               :name="`balance_accounting[${index}][value]`"
                                                               x-model.number="item.value" class="form-control"
                                                               placeholder="Enter item value">
                                                    </div>
                                                    {{-- DATE--}}
                                                    <div>
                                                        <input required
                                                               style="{{ auth()->user()->hasAnyRole('Developer') ? '' : 'display: none' }}"
                                                               type="date"
                                                               :name="`balance_accounting[${index}][date]`"
                                                               x-model="item.date"
                                                               class="form-control"
                                                               x-init="item.date = item.date || new Date().toISOString().split('T')[0]">
                                                    </div>
                                                    {{-- cost id--}}
                                                    <div>
                                                        <input required

                                                               {{--                                                               style="{{ auth()->user()->hasAnyRole('Developer') ? '' : 'display: none' }}"--}}
                                                               style="display: none"
                                                               type="number"
                                                               :name="`balance_accounting[${index}][id]`"
                                                               x-model="item.id"
                                                               class="form-control"
                                                               x-init="item.id = item.id || Math.floor(Math.random() * (99999 - 10000 + 1)) + 10000"
                                                        >
                                                    </div>
                                                    {{-- include charge for credit --}}
                                                    <div class="col-md-1 col-4 text-center mt-1">
                                                        <input
                                                                type="number"
                                                                {{--                                                                style="{{ auth()->user()->hasAnyRole('Developer') ? 'max-width: 50px' : 'display: none;' }}"--}}
                                                                style="display: none"
                                                                :name="`balance_accounting[${index}][forcredit]`"
                                                                x-model.number="item.forcredit">

                                                        <input type="checkbox"
                                                               style="max-width: 50px"
                                                               :name="`balance_accounting[${index}][forcredit]`"
                                                               x-model="item.forcredit"
                                                               class="form-control"
                                                               @change="item.forcredit = $event.target.checked ? 1 : 0"
                                                               :checked="item.forcredit == 1">
                                                    </div>

                                                    @can('CarUpdate')
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-danger"
                                                                    @click="confirmRemoveField(index)">Remove
                                                            </button>
                                                        </div>
                                                    @endcan
                                                </div>
                                            </template>

                                            <!-- Add Button -->
                                            <button type="button" class="btn btn-primary mt-3" @click="addField">Add
                                                Another Item
                                            </button>

                                            {{--                                        <div x-data="{--}}
                                            {{--                                            payed: {{ $car->payed }}--}}
                                            {{--                                            get amountDue() {--}}
                                            {{--                                                return calculateTotal() - parseFloat(this.payed || 0);--}}
                                            {{--                                            },--}}
                                            {{--                                            validateNumber() {--}}
                                            {{--                                                this.payed = this.payed.replace(/[^0-9.]/g, ''); // Only keep numbers and a single decimal point--}}
                                            {{--                                            }--}}
                                            {{--                                        }">--}}
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label for="allcost">All Cost</label>
                                                    <input type="text" name="total_cost" id="allcost"
                                                           class="form-control" x-bind:value="calculateTotal()"
                                                           readonly>
                                                </div>

                                                {{--                                                <div class="col-md-4">--}}
                                                {{--                                                    <label for="payed">Payed</label>--}}
                                                {{--                                                    <input type="text" value="{{ $car->payed }}" name="payed"--}}
                                                {{--                                                        id="payed" class="form-control" x-model="payed"--}}
                                                {{--                                                        x-on:input="validateNumber" required>--}}
                                                {{--                                                </div>--}}
                                                <div class="col-md-2">
                                                    <label for="amountDue">Payed</label>
                                                    <input type="text"
                                                           class="form-control" readonly
                                                           value="{{$payment}}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="amountDue">Amount Due</label>
                                                    <input type="text"
                                                           class="form-control" readonly
                                                           value="{{round($car->amount_due+round( ((new CreditService())->totalInterestFromLastCalc($car->id))) )}}">
                                                </div>
                                                {{--Percent--}}
                                                @if(!$car->latestCredit)
                                                    <div class="col-md-2 form-group">
                                                        <label>Percent <span
                                                                    style="color:blue">For Credit</span></label>
                                                        <input autocomplete="nope" type="text" name="percent"
                                                               class="form-control"
                                                               value="{{ old('percent') }}">
                                                    </div>
                                                @endif
                                            </div>

                                        </div>
                                    </div>


                                    <div class="container mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="vehicle_owner_name">Vehicle Owner name
                                                </label>
                                                <input type="text" value="{{ $car->vehicle_owner_name }}"
                                                       name="vehicle_owner_name" id="vehicle_owner_name"
                                                       class="form-control">
                                            </div>

                                            <div class="col-md-4">
                                                <label for="owner_id_number">Owner ID Number
                                                </label>
                                                <input type="text" value="{{ $car->owner_id_number }}"
                                                       name="owner_id_number" id="owner_id_number" class="form-control">
                                            </div>

                                            <div class="col-md-2">
                                                <label for="owner_phone_number">Owner Phone number
                                                </label>
                                                <input type="text" value="{{ $car->owner_phone_number }}"
                                                       name="owner_phone_number" id="owner_phone_number"
                                                       class="form-control">
                                            </div>
                                            <div class="col-md-2">
                                                <label>Container #</label><br>
                                                <input name="container_number" class="form-control"
                                                       id="container_number"
                                                       value="{{$container_id}}"
                                                       disabled
                                                       type="text">
                                            </div>

                                        </div>
                                    </div>
                                    {{-- red green defaulr color--}}
                                    <div class="container mb-3">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label style="padding: 10px;border-radius: 20px;text-align: center"
                                                       class="border">
                                                    <input @checked($car->record_color===null)  type="radio"
                                                           name="record_color" value=" "> No Color
                                                </label>
                                            </div>
                                            <div class="col-md-2">
                                                <label style="padding: 10px;color:white;border-radius: 20px;text-align: center;background: green">
                                                    <input @checked($car->record_color=='#82f98261') type="radio"
                                                           name="record_color" value="#82f98261"> Green
                                                </label>
                                            </div>
                                            <div class="col-md-2">
                                                <label style="padding: 10px;background:red;border-radius: 20px;text-align: center;color:white">
                                                    <input @checked($car->record_color=='#F6CBCC') type="radio"
                                                           name="record_color" value="#F6CBCC"> Red
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" style="max-width: 100%">
                                        @can('CarUpdate')
                                            <label>Add Car Images</label><br>
                                            <input type="file" data-car_id="{{ $car->id }}" id="filepond"
                                                   name="images[]" multiple>
                                        @endcan
                                        <style>
                                            .img-fluid {
                                                height: 150px;
                                                width: 200px;
                                                object-fit: cover;
                                            }
                                        </style>

                                        @if ($car->getMedia('images')->isNotEmpty())
                                            <div class="existing-images mt-4">
                                                <h6>Car Images</h6>
                                                <div class="row mt-2">
                                                    @foreach ($car->getMedia('images') as $image)
                                                        <div class=" col-sm-12 col-md-2">
                                                            <div class="image-thumbnail mb-2 d-flex justify-content-center">
                                                                <a href="{{ $image->getUrl() }}" target="_blank">
                                                                    <img src="{{ $image->getUrl() }}" class="img-fluid"
                                                                         alt="Uploaded Image">
                                                                </a>
                                                            </div>
                                                            @can('CarUpdate')
                                                                <div style="display: flex;justify-content: center!important;">

                                                                    <button style="all:unset;cursor: pointer"
                                                                            hx-post="{{route('arrived.image.delete')}}"
                                                                            hx-vals='{ "image_id": "{{$image->id}}","_token": "{{csrf_token()}}" }'
                                                                            onclick="window.location.reload()"
                                                                    >
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                             width="35"
                                                                             height="35" viewBox="0 0 24 24">
                                                                            <path fill="#b72525"
                                                                                  d="M7 21q-.825 0-1.412-.587T5 19V6q-.425 0-.712-.288T4 5t.288-.712T5 4h4q0-.425.288-.712T10 3h4q.425 0 .713.288T15 4h4q.425 0 .713.288T20 5t-.288.713T19 6v13q0 .825-.587 1.413T17 21zM17 6H7v13h10zm-7 11q.425 0 .713-.288T11 16V9q0-.425-.288-.712T10 8t-.712.288T9 9v7q0 .425.288.713T10 17m4 0q.425 0 .713-.288T15 16V9q0-.425-.288-.712T14 8t-.712.288T13 9v7q0 .425.288.713T14 17M7 6v13z"/>
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                            @endcan
                                                        </div>

                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                            @if ($car->getMedia('bl_images')->isNotEmpty())
                                                <div class="existing-images mt-4">
                                                    <h6>BL Images</h6>
                                                    <div class="row mt-2">
                                                        @foreach ($car->getMedia('bl_images') as $image)
                                                            <div class=" col-sm-12 col-md-2">
                                                                <div class="image-thumbnail mb-2 d-flex justify-content-center">
                                                                    <a href="{{ $image->getUrl() }}" target="_blank">
                                                                        <img src="{{ $image->getUrl() }}" class="img-fluid"
                                                                             alt="Uploaded Image">
                                                                    </a>
                                                                </div>
                                                                @can('CarUpdate')
                                                                    <div style="display: flex;justify-content: center!important;">

                                                                        <button style="all:unset;cursor: pointer"
                                                                                hx-post="{{route('arrived.image.delete')}}"
                                                                                hx-vals='{ "image_id": "{{$image->id}}","_token": "{{csrf_token()}}" }'
                                                                                onclick="window.location.reload()"
                                                                        >
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                 width="35"
                                                                                 height="35" viewBox="0 0 24 24">
                                                                                <path fill="#b72525"
                                                                                      d="M7 21q-.825 0-1.412-.587T5 19V6q-.425 0-.712-.288T4 5t.288-.712T5 4h4q0-.425.288-.712T10 3h4q.425 0 .713.288T15 4h4q.425 0 .713.288T20 5t-.288.713T19 6v13q0 .825-.587 1.413T17 21zM17 6H7v13h10zm-7 11q.425 0 .713-.288T11 16V9q0-.425-.288-.712T10 8t-.712.288T9 9v7q0 .425.288.713T10 17m4 0q.425 0 .713-.288T15 16V9q0-.425-.288-.712T14 8t-.712.288T13 9v7q0 .425.288.713T14 17M7 6v13z"/>
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                @endcan
                                                            </div>

                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                    </div>

                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="note">Note</label>
                                                <textarea name="comment" class="form-control" id="note" cols="30"
                                                          rows="2">{{ $car->comment }}</textarea>
                                            </div>

                                            <div class="col-md-4">
                                                <label>CAR Status</label>
                                                <select name="status" class="form-control" id="customer_id">
                                                    <option value=""></option>
                                                    @foreach ($car_status as $status)
                                                        <option value="{{ $status->id }}"
                                                                {{ $status->id == $car->car_status_id ? 'selected' : '' }}>
                                                            {{ $status->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label>Satart dispatch?</label>
                                                <select name="is_dispatch" class="form-control" id="is_dispatch">
                                                    <option value="yes"
                                                            {{ $car->is_dispatch == 'yes' ? 'selected' : '' }}>YES
                                                    </option>
                                                    <option value="no" {{ $car->is_dispatch == 'no' ? 'selected' : '' }}>
                                                        NO
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="reset-button">
                                        @can('CarUpdate')
                                            <button type="submit" class="btn btn-success"> Save</button>
                                        @endcan


                                        <a href="{{route('cars.index')}}" type="submit" class="btn btn-primary">
                                            Current Cars
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content -->
        </div>


        @include('partials.footer')

        @push('js')
            <script src="{{asset('assets/select/select2.min.js')}}"></script>
            <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
            <script src="{{asset('assets/alpine/cdn.min.js')}}" defer></script>

            <!-- FilePond JS -->
            <script src="{{asset('assets/filepond/filepond.js')}}"></script>

            <!-- Plugins for image preview and file type validation -->
            <script src="{{asset('assets/filepond/filepond-plugin-image-preview.js')}}"></script>
            <script src="{{asset('assets/filepond/filepond-plugin-file-validate-type.js')}}"></script>
            <script src="{{asset('assets/filepond/filepond-plugin-file-validate-size.js')}}"></script>
            <script src="{{asset('assets/filepond/filepond-plugin-file-encode.min.js')}}"></script>
            <script
                    src="{{asset('assets/filepond/filepond-plugin-image-exif-orientation.min.js')}}">
            </script>
            <script src="{{asset('assets/filepond/filepond-plugin-image-edit.js')}}"></script>


            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    // Register FilePond plugins
                    FilePond.registerPlugin(
                        FilePondPluginImagePreview, // for showing image preview
                        FilePondPluginFileValidateType, // for validating file types
                        FilePondPluginFileValidateSize // for validating file size
                    );

                    // Turn input element into a pond
                    // Get the car_id from the input element
                    const inputElement = document.querySelector('input[type="file"]');
                    const carId = inputElement.getAttribute('data-car_id');

                    // Initialize FilePond with car_id passed in the process data
                    const pond = FilePond.create(inputElement, {
                        allowMultiple: true, // Allow multiple file uploads
                        maxFiles: 15, // Limit to 10 files
                        acceptedFileTypes: ['image/*'], // Only accept image files
                        maxFileSize: '10MB', // Maximum file size of 2MB

                        // FilePond server configuration
                        server: {
                            process: {
                                url: '{{ route('upload.images.spatie') }}',
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                // Add extra data (car_id) to the request
                                ondata: (formData) => {
                                    formData.append('car_id', carId);
                                    return formData;
                                },
                                onload: (response) => console.log('Upload successful!', response),
                                onerror: (response) => console.error('Upload error:', response)
                            },
                            revert: null, // Revert uploaded image if necessary
                        }
                    });

                });
            </script>

            <script>
                // Ensure Alpine.js Reactive Store is initialized properly
                document.addEventListener('alpine:init', () => {
                    Alpine.store('balanceAccountingStore', {
                        {{--balance_accounting: @json($balanceAccounting ?? [['name' => '', 'value' => 0,'date'=>'']]), // Load existing data or default--}}
                        balance_accounting: {!! json_encode($balanceAccounting ?? [['name' => '', 'value' => 0, 'date' => '', 'id' => '' , 'forcredit' => '']]) !!},


                        addField() {
                            this.balance_accounting.push({
                                name: '',
                                value: 0,
                                date: '',
                                id: '',
                                forcredit: ''
                            });
                        },
                        // New method with confirmation
                        confirmRemoveField(index) {
                            if (confirm("Are you sure you want to remove this item?")) {
                                this.removeField(index);
                            }
                        },
                        removeField(index) {
                            this.balance_accounting.splice(index, 1);
                        },

                        calculateTotal() {
                            return this.balance_accounting.reduce((total, item) => {
                                return total + (parseFloat(item.value) || 0);
                            }, 0);
                        },

                        // Update or add Shipping cost
                        updateShippingCost(shippingCost) {
                            let shippingFound = false;

                            for (let i = 0; i < this.balance_accounting.length; i++) {
                                if (this.balance_accounting[i].name === 'Shipping cost') {
                                    this.balance_accounting[i].value = shippingCost;
                                    shippingFound = true;
                                    break;
                                }
                            }

                            if (!shippingFound) {
                                this.balance_accounting.push({
                                    name: 'Shipping cost',
                                    value: shippingCost
                                });
                            }
                        }
                    });
                });

                $(function () {
                    var availableWarehouse = [
                        'TRT - GA 31326',
                        'TRT - CA 90248',
                        'TRT - NJ 07114',
                        'TRT - TX 77571'
                    ];

                    $("#warehouse").autocomplete({
                        source: availableWarehouse,
                        minLength: 0 // Set to 0 to show suggestions immediately
                    });

                    // Trigger the autocomplete suggestions on input focus (click)
                    $("#warehouse").on('focus', function () {
                        $(this).autocomplete('search', ''); // Force the dropdown to show on click/focus
                    });

                });

                // Example words for autocomplete
                const suggestedWords = [
                    'Vehicle cost',
                    'Shipping cost',
                    'Fee amount',
                    'Bank fee',
                    'Insurance cost',
                    'Hybrid',
                    'Storage',
                    'TITLE',
                    'Sublot',
                    'Credit',
                ];

                // Initialize jQuery UI autocomplete
                function initializeAutocomplete() {
                    $(document).on('focus', '.name-autocomplete', function () {
                        if (!$(this).data("autocomplete-initialized")) {
                            $(this).autocomplete({
                                source: suggestedWords,
                                minLength: 0 // Show suggestions even when no input is typed
                            }).data("autocomplete-initialized", true); // Mark as initialized to avoid re-initializing
                        }
                        $(this).autocomplete("search", "");
                    });
                }

                // Initialize autocomplete on page load
                $(document).ready(function () {
                    initializeAutocomplete();
                });
            </script>


            <script>
                $(document).ready(function () {

                    var savedAuction = "{{ $car->auction }}"; // Saved auction value
                    var savedFromState = "{{ $car->from_state }}"; // Saved from_state value
                    var savedToPortId = "{{ $car->to_port_id }}"; // Saved to_port_id value


                    // On page load, trigger fetchFromStates if auction is preloaded
                    if (savedAuction) {
                        fetchFromStates(savedAuction, savedFromState, savedToPortId);
                    }


                    // Event listener for auction change
                    $('#auction').change(function () {
                        var auctionId = $(this).val();
                        fetchFromStates(auctionId, null,
                            null); // Fetch from states but no preselected values on auction change
                    });

                    // Function to fetch and populate from_state dropdown based on auction
                    function fetchFromStates(auctionId, savedFromState = null, savedToPortId = null) {
                        if (auctionId) {
                            $.ajax({
                                url: '{{ route('fetch.from.states') }}',
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}', // CSRF token
                                    auction_id: auctionId, // Send selected auction ID
                                    location_name: '',
                                },
                                success: function (response) {
                                    // Populate the from_state dropdown with the received states
                                    $('#fromState').empty(); // Clear existing options
                                    $('#fromState').append(
                                        '<option value="">Select an option</option>'); // Default option

                                    $.each(response.states, function (key, state) {
                                        var selected = (key == savedFromState) ? 'selected' :
                                            ''; // Preselect saved from_state
                                        $('#fromState').append(
                                            `<option value="${key}" ${selected}>${state}</option>`);
                                    });

                                    // Trigger fetchPortsForEdit if from_state is preloaded
                                    if (savedFromState) {
                                        fetchPortsForEdit(savedFromState,
                                            savedToPortId); // Fetch ports with preselected to_port_id
                                    }

                                    // Event listener for from_state change
                                    $('#fromState').change(function () {
                                        var fromStateId = $(this).val();
                                        fetchPortsForEdit(fromStateId,
                                            null); // Fetch ports but no preselected value on change
                                    });
                                },
                                error: function (error) {
                                    alert('Error fetching from states')
                                    console.log('Error fetching from states:', error);
                                }
                            });
                        } else {
                            $('#fromState').empty();
                            $('#fromState').append(
                                '<option value="">Select an option</option>'); // Reset if no auction selected
                        }
                    }

                    // Function to fetch and populate to_port_id dropdown based on from_state
                    function fetchPortsForEdit(fromStateId, savedToPortId = null) {

                        if (fromStateId) {
                            $.ajax({
                                url: '{{ route('fetch.ports') }}',
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    from_state_id: fromStateId,
                                    location_name: $('#fromState option:selected').text(),
                                    auction_id: $('#auction').val(),


                                },
                                success: function (response) {
                                    // Populate the to_port_id dropdown with the received ports
                                    $('#to_port_id').empty(); // Clear the existing options
                                    $('#to_port_id').append(
                                        '<option value="">Select an option</option>'); // Default option

                                    $.each(response.ports, function (key, port) {
                                        var selected = (key == savedToPortId) ? 'selected' :
                                            ''; // Preselect saved to_port_id
                                        $('#to_port_id').append(
                                            `<option value="${key}" ${selected}>${port}</option>`);
                                    });
                                },
                                error: function (error) {
                                    alert('Error fetching ports')
                                    console.log('Error fetching ports:', error);
                                }
                            });
                        } else {
                            $('#to_port_id').empty();
                            $('#to_port_id').append(
                                '<option value="">Select an option</option>'); // Reset if no from_state selected
                        }
                    }

                    $('#auction').change(function () {
                        var auctionId = $(this).val();

                        if (auctionId) {
                            // Show the loading spinner
                            $('#loading').show();

                            $.ajax({
                                url: '{{ route('fetch.locations') }}',
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    auction_id: auctionId
                                },
                                success: function (response) {
                                    $('#fromState').empty();
                                    $('#fromState').append(
                                        '<option value="">Select an option</option>');

                                    $.each(response.locations, function (key, location) {
                                        $('#fromState').append(
                                            `<option value="${location.id}">${location.name}</option>`
                                        );
                                    });

                                    // Hide the loading spinner
                                    $('#loading').hide();
                                },
                                error: function (error) {
                                    alert('Auction Error')
                                    console.log('Error:', error);
                                    // Hide the loading spinner in case of error
                                    $('#loading').hide();
                                }
                            });
                        } else {
                            $('#fromState').empty();
                            $('#fromState').append('<option value="">Select an option</option>');
                        }
                    });

                    $('#fromState').change(function () {
                        var fromStateId = $(this).val(); // Get the selected from_state ID
                        console.log(fromStateId)
                        console.log($('#fromState option:selected').text())
                        if (fromStateId) {
                            // Send AJAX request to get to_port_ids based on from_state_id
                            $.ajax({
                                url: '{{ route('fetch.ports') }}', // Adjust route accordingly
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}', // CSRF token
                                    from_state_id: fromStateId,// Send selected from_state ID
                                    auction_id: $('#auction').val(),
                                    location_name: $('#fromState option:selected').text()
                                },
                                success: function (response) {
                                    // Populate the to_port_id dropdown with the received ports
                                    $('#to_port_id').empty(); // Clear the existing options
                                    $.each(response.ports, function (key, port) {
                                        console.log(key, port)
                                        $('#to_port_id').append(`<option value="${key}">${port}</option>`);
                                    });
                                    $('#to_port_id').append('<option value selected="">Select an option</option>');

                                },
                                error: function (error) {
                                    alert('Error fetching ports')
                                    console.log('Error fetching ports:', error);
                                }
                            });
                        } else {
                            $('#to_port_id').empty();
                            $('#to_port_id').append(
                                '<option value="">Select an option</option>'
                            ); // Reset the dropdown if no from_state is selected
                        }
                    });

                    // Trigger calculation on change of any select field
                    $('#to_port_id').change(function () {
                        calculateShippingCost();
                    });

                    function calculateShippingCost() {
                        var auction = $('#auction').val();
                        var load_type = $('#loadType').val();
                        var from_state = $('#fromState').val();
                        var to_port_id = $('#to_port_id').val();
                        var customer_id = $('#customerTomSelect').val();
                        console.log(customer_id)

                        // Send AJAX request to calculate shipping cost
                        $.ajax({
                            url: '{{ route('calculate.shipping.cost') }}', // Adjust route accordingly
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}', // Include CSRF token for security
                                auction: auction,
                                load_type: load_type,
                                from_state: from_state,
                                to_port_id: to_port_id,
                                location_name: $('#fromState option:selected').text(),
                                customer_id: customer_id
                            },
                            success: function (response) {
                                // Update the shipping cost on the page
                                $('#shippingCost').text(response.shipping_cost);
                                document.getElementById('shippingwithoutextra').style.display = 'flex';
                                document.getElementById('originalshipping').value = response.original_shipping;

                                // Ensure the Alpine store is accessed correctly
                                try {
                                    // Access the store and update the shipping cost
                                    // Alpine.store('balanceAccountingStore').updateShippingCost(response
                                    //     .shipping_cost);

                                    let store = Alpine.store('balanceAccountingStore');

                                    // Check if the first row exists, if not, create it
                                    if (store.balance_accounting.length > 0) {
                                        // Update the first row with the shipping cost
                                        store.balance_accounting[0].name = 'Shipping cost';
                                        store.balance_accounting[0].value = response.shipping_cost;
                                    } else {
                                        // If there's no row, push the shipping cost as the first row
                                        store.balance_accounting.push({
                                            name: 'Shipping cost',
                                            value: response.shipping_cost
                                        });
                                    }
                                } catch (error) {
                                    alert("Error Calculation");
                                    console.log("Error updating Alpine store:", error);
                                }

                            },
                            error: function (error) {
                                console.log('Error:', error);
                            }
                        });
                    }
                });

                // Function to update the shipping cost in the repeater
                function updateShippingCostInRepeater(shippingCost) {


                    document.addEventListener('alpine:init', () => {
                        Alpine.data('dropdown', () => ({
                            open: false,

                            toggle() {
                                this.open = !this.open
                            },
                        }))
                    })


                    // Access the Alpine component via x-ref
                    let alpineComponent = document.querySelector('[x-ref=repeaterComponent]');

                    let shippingFound = false;

                    console.log(alpineComponent.balance_accounting);


                    // Find if "Shipping cost" already exists in the repeater
                    for (let i = 0; i < alpineComponent.balance_accounting.length; i++) {
                        if (alpineComponent.balance_accounting[i].name === 'Shipping cost') {
                            // Update the existing shipping cost
                            alpineComponent.balance_accounting[i].value = shippingCost;
                            console.log("Updated Shipping Cost: ", alpineComponent.balance_accounting[i].value);
                            shippingFound = true;
                            break;
                        }
                    }

                    // If "Shipping cost" doesn't exist, add a new row for it
                    if (!shippingFound) {
                        alpineComponent.balance_accounting.push({
                            name: 'Shipping cost',
                            value: shippingCost
                        });
                        console.log("Added Shipping Cost: ", shippingCost);
                    }
                }
            </script>


            <script>
                $(document).ready(function () {
                    // Predefined suggestions for the name field
                    const predefinedNames = [
                        'Vehicle cost',
                        'Shipping cost',
                        'Fee amount',
                        'Insurance cost',
                        'Hybrid',
                        'STORAGE',
                        'TITLE',
                        'Sublot',
                        'Credit',
                    ];

                    // Function to add a new balance accounting entry
                    function addEntry() {
                        const entryHtml = `
            <div class="balance-entry row mb-3">
                <div class="col-md-5">
                    <label>Name</label>
                    <input type="text" class="form-control entry-name" placeholder="Enter name">
                </div>
                <div class="col-md-5">
                    <label>Value</label>
                    <input type="number" class="form-control entry-value" placeholder="Enter value" value="0">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-danger remove-entry">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
                        $('#balanceAccountingContainer').append(entryHtml);

                        // Initialize autocomplete for the new entry with click to trigger suggestions
                        const $newEntryName = $('.entry-name').last();

                        $newEntryName.autocomplete({
                            source: predefinedNames,
                            minLength: 0 // Set to 0 to show suggestions immediately
                        });

                        // Trigger the autocomplete suggestions on input focus (click)
                        $newEntryName.on('focus', function () {
                            $(this).autocomplete('search', ''); // Force the dropdown to show on click/focus
                        });

                        calculateSubtotal();
                    }

                    // Add a default blank entry on page load
                    addEntry();

                    // Event listener for adding a new entry
                    $('#addBalanceAccounting').click(function () {
                        addEntry();
                    });

                    // Event listener for removing an entry
                    $(document).on('click', '.remove-entry', function () {
                        $(this).closest('.balance-entry').remove();
                        calculateSubtotal();
                    });

                    // Event listener for updating the subtotal when a value changes
                    $(document).on('input', '.entry-value', function () {
                        calculateSubtotal();
                    });

                    // Function to calculate the subtotal
                    function calculateSubtotal() {
                        let subtotal = 0;
                        $('.entry-value').each(function () {
                            let value = parseFloat($(this).val());
                            if (!isNaN(value)) {
                                subtotal += value;
                            }
                        });
                        $('#subtotal').text(subtotal.toFixed(2)); // Display the subtotal
                    }

                    // Function to gather all balance accounting data for submission
                    function getBalanceAccountingData() {
                        let data = {};
                        $('.balance-entry').each(function () {
                            let name = $(this).find('.entry-name').val();
                            let value = $(this).find('.entry-value').val();
                            if (name && value) {
                                data[name] = parseFloat(value); // Store key-value pair in object
                            }
                        });
                        return data;
                    }

                    // Example function to handle form submission
                    $('#carForm').submit(function (event) {
                        event.preventDefault(); // Prevent default form submission

                        // Get balance accounting data and store as JSON in the hidden field
                        let balanceAccountingData = getBalanceAccountingData();
                        $('#balance_accounting').val(JSON.stringify(balanceAccountingData));

                        // Submit the form programmatically using native form submission
                        this
                            .submit(); // Use the native DOM element's submit method to avoid triggering the event again
                    });

                });
            </script>

            <script>
                // Set up the CSRF token for all AJAX requests
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $(document).ready(function () {
                    $('.select2').select2();
                });


                // Open delete modal and set user ID
                $('#deleteUserModal').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var userId = button.data('user-id'); // Extract user ID from data-* attribute
                    var modal = $(this);

                    // Set the user ID in the hidden input field in the form
                    modal.find('#deleteUserId').val(userId);
                });

                // Handle the form submission for deleting the user
                $('#deleteUserForm').on('submit', function (e) {
                    e.preventDefault(); // Prevent the default form submission

                    var userId = $('#deleteUserId').val(); // Get the user ID from the hidden input
                    var formData = $(this).serialize(); // Serialize form data for submission

                    $.ajax({
                        url: `/dashboard/users/${userId}`, // URL for deleting the user
                        type: 'DELETE', // HTTP method for deletion
                        data: formData, // Send the serialized form data (including the CSRF token)
                        success: function (response) {
                            alert(response.message); // Show success message
                            $('#deleteUserModal').modal('hide'); // Hide the modal
                            location.reload(); // Optionally reload the page to update the user list
                        },
                        error: function (xhr) {
                            alert('Something went wrong.'); // Handle errors
                        }
                    });
                });
            </script>
        @endpush
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                new TomSelect("#customerTomSelect", {
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });
            }, 200)
        })
    </script>
@endsection
