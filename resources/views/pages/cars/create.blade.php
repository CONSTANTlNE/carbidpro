@extends('layouts.app')
@section('content')
    @push('css')
        <link href="{{asset('assets/select/select2.min.css')}}" rel="stylesheet"/>
        <!-- FilePond CSS -->
        <link href="{{asset('assets/filepond/filepond.css')}}" rel="stylesheet"/>
        <link href="{{asset('assets/filepond/filepond-plugin-image-preview.css')}}"
              rel="stylesheet"/>
        <link href="{{asset('assets/select/tomselect.css')}}" rel="stylesheet">
        <script src="{{asset('assets/select/tomselect.js')}}"></script>

        <style>
            .ts-wrapper {
                max-width: 295px;
                padding: 0;
            }

            [data-selectable] {
                font-weight: bold;
                font-size: 14px;
            }

        </style>
    @endpush

    @section('body-class', 'hold-transition sidebar-mini')

    <!--preloader-->
    {{--    <div id="preloader">--}}
    {{--        <div id="status"></div>--}}
    {{--    </div>--}}

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
                    <h1>Add Car</h1>
                    <small>Car list</small>
                </div>
            </section>

            <div class="container">
                @if ($errors->any())
                    <div class="alert alert-danger" style="margin-top: 1rem;margin-bottom: 0;">
                        <ul style="margin:0;padding:0;">
                            @foreach ($errors->all() as $error)
                                <li style="list-style: none;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>


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
                                <form action="{{ route('car.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input style="display: none" id="images2" type="file" name="images2[]" multiple>

                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="row">
                                                {{-- Dealer--}}
                                                <div class="form-group">
                                                    <label>Dealer</label>
                                                    <select
                                                            class="form-control"
                                                            hx-get="{{route('htmx.get.extraexpense')}}"
                                                            hx-target="#extraexpense"
                                                            autocomplete="nope"
                                                            name="customer_id"
                                                            id="customerTomSelect"
                                                            required>
                                                        <option value=""></option>
                                                        @foreach ($customers as $customer)
                                                            <option value="{{ $customer->id }}"
                                                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>

                                                                {{ $customer->contact_name }} Extra
                                                                : {{ $customer->extra_for_team }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                {{-- Broker--}}
                                                <div class="form-group">
                                                    <label>Broker</label>
                                                    <select autocomplete="nope" name="dispatch_id" class="form-control"
                                                            id="dispatch_id" required>
                                                        <option value=""></option>
                                                        @foreach ($brokers as $broker)
                                                            <option value="{{ $broker->id }}"
                                                                    {{ old('dispatch_id') == $broker->id ? 'selected' : '' }}>
                                                                {{ $broker->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                {{-- car--}}
                                                <div class="form-group">
                                                    <label>Make/Model/Year</label>
                                                    <input autocomplete="nope" type="text" name="make_model_year"
                                                           class="form-control"
                                                           value="{{ old('make_model_year') }}" required>
                                                </div>
                                                {{--vin--}}
                                                <div class="form-group">
                                                    <label>Vin</label>
                                                    <input autocomplete="nope" type="text" name="vin"
                                                           class="form-control"
                                                           value="{{ old('vin') }}">
                                                </div>
                                            </div>

                                            {{--comment--}}
                                            <div style="max-width: 500px" class="form-group" id="customercomment">
                                                <label>Customer Comment</label>
                                                <textarea style="color:red;" disabled rows="3" class="form-control"
                                                          id="extraexpense"></textarea>
                                            </div>

                                            <div class="row mb-5">
                                                {{--lot--}}
                                                <div class=" col-sm-6 col-md-6 col-lg-3">
                                                    <label>Lot</label>
                                                    <input autocomplete="nope" type="text" name="lot"
                                                           class="form-control"
                                                           value="{{ old('lot') }}" required>
                                                </div>
                                                {{--gate or member--}}
                                                <div class=" col-sm-6 col-md-6 col-lg-3">
                                                    <label>Gate or Member</label>
                                                    <input autocomplete="nope" type="text" name="gate_or_member"
                                                           class="form-control"
                                                           value="{{ old('gate_or_member') }}" required>
                                                </div>
                                                {{--Title--}}
                                                <div class=" col-sm-6 col-md-6 col-lg-3">
                                                    <label>Title</label>
                                                    <select autocomplete="nope" name="title" class="form-control"
                                                            id="title"
                                                            required>
                                                        <option value=""></option>
                                                        <option value="no" {{ old('title') == 'no' ? 'selected' : '' }}>
                                                            NO
                                                        </option>
                                                        <option value="yes" {{ old('title') == 'yes' ? 'selected' : '' }}>
                                                            YES
                                                        </option>
                                                        <option value="bypost"
                                                                {{ old('title') == 'bypost' ? 'selected' : '' }}>BY POST
                                                        </option>
                                                        <option value="pending"
                                                                {{ old('title') == 'pending' ? 'selected' : '' }}>
                                                            PENDING
                                                        </option>
                                                    </select>

                                                </div>
                                                {{--Fuel--}}
                                                <div class=" col-sm-6 col-md-6 col-lg-3 "
                                                     style="align-items: center; width: max-content">
                                                    <label class="text-center">Type of Fuel</label>

                                                    <div class="d-flex justify-content-center align-middle text-center mt-3 ">
                                                        <label style="margin-right: 0" class="radio-inline"
                                                               for="petrol">
                                                            Petrol
                                                        </label>
                                                        <input id="petrol" autocomplete="nope" name="type_of_fuel"
                                                               value="Petrol" type="radio" style="margin-right: 10px"
                                                               {{ old('type_of_fuel') == 'Petrol' ? 'checked' : '' }}
                                                               required>


                                                        <label style="margin-right: 0" class="radio-inline"
                                                               for="hybrid">
                                                            Hybrid
                                                        </label>
                                                        <input id="hybrid" autocomplete="nope" name="type_of_fuel"
                                                               value="Hybrid" type="radio"
                                                               {{ old('type_of_fuel') == 'Hybrid' ? 'checked' : '' }}
                                                               required>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="container mb-3">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label for="auction">Auction</label>
                                                        <select autocomplete="nope" id="auction" name="auction"
                                                                class="form-control select2">
                                                            <option value="">Select an option</option>
                                                            @foreach ($auctions as $auction)
                                                                <option value="{{ $auction->id }}"
                                                                        {{ old('auction') == $auction->id ? 'selected' : '' }}>
                                                                    {{ $auction->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="loadType">Load Type</label>
                                                        <select autocomplete="nope" name="load_type" id="loadType"
                                                                class="form-control select2">
                                                            <option value="">Select an option</option>
                                                            @foreach ($load_types as $load_type)
                                                                <option value="{{ $load_type->id }}">
                                                                    {{ $load_type->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="fromState">From State</label>
                                                        <select autocomplete="nope" name="from_state" id="fromState"
                                                                class="form-control select2">
                                                            <option value="">Select an option</option>
                                                            <!-- This will be populated dynamically based on the auction selection -->
                                                        </select>
                                                    </div>

                                                    <!-- Add this for To Port and Load Type, etc. -->
                                                    <div class="col-md-2">
                                                        <label for="to_port_id">To Port Id</label>
                                                        <select autocomplete="nope" name="to_port_id" id="to_port_id"
                                                                class="form-control select2">
                                                            <option value="">Select an option</option>
                                                            <!-- This will be populated dynamically -->
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="zip_code">Exact location-zip</label>
                                                        <input autocomplete="nope" type="text" class="form-control"
                                                               name="zip_code"
                                                               id="zip_code" value="{{ old('zip_code') }}" required>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="warehouse">Warehouse</label>
{{--                                                        <input autocomplete="nope" type="text"--}}
{{--                                                               value="{{ old('warehouse') }}"--}}
{{--                                                               class="form-control" name="warehouse" id="warehouse"--}}
{{--                                                               required>--}}
                                                        <select style="width: 273px!important" name="warehouse" class="form-control" style="width: min-content" required>
                                                            <option value="">Select Warehouse</option>
                                                            @foreach($warehouses as $warehouse)
                                                                <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                                {{--Extra Expenses inserted via HTMX on customer select change--}}
                                                <div class="row mt-3" id="extraexpense">

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Here Comes Content from HTMX alpine initialized--}}
                                    <div class="container" id="alpinehtml">
                                        <div x-data="$store.balanceAccountingStore" class="mt-4">
                                            <div class="d-flex" style="gap:10px">
                                                <p x-text="'Number of items: ' + balance_accounting.length"></p>

                                                <!-- Display the total of values -->
                                                <p>Total Value: <span x-text="calculateTotal()"></span></p>
                                            </div>

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
                                                        <input type="text" :name="`balance_accounting[${index}][name]`"
                                                               x-model="item.name"
                                                               class="name-autocomplete form-control"
                                                               placeholder="Enter item name" required>
                                                    </div>

                                                    <div class="col-md-2 col-6">
                                                        <input type="number"
                                                               :name="`balance_accounting[${index}][value]`"
                                                               x-model.number="item.value" class="form-control"
                                                               placeholder="Enter item value" required>
                                                    </div>
                                                    {{-- DATE--}}
                                                    <div
                                                            style="display: none"
                                                            class="col-md-4">
                                                        <input type="date" :name="`balance_accounting[${index}][date]`"
                                                               x-model="item.date" class="form-control"
                                                               x-init="item.date = item.date || '{{ now()->format('Y-m-d') }}'">
                                                    </div>
                                                    {{-- cost id--}}
                                                    <div
                                                            style="display: none"
                                                            class="col-md-4 ">
                                                        <input type="number" :name="`balance_accounting[${index}][id]`"
                                                               x-model="item.id" class="form-control"
                                                               x-init="item.id = item.id || Math.floor(Math.random() * (99999 - 10000 + 1)) + 10000">
                                                    </div>
                                                    {{-- include charge for credit --}}
                                                    <div class="col-md-1 col-4 text-center mt-1">
                                                        <input type="hidden"
                                                               :name="`balance_accounting[${index}][forcredit]`"
                                                               x-init="item.forcredit = item.forcredit || 1"
                                                               x-model.number="item.forcredit">

                                                        <input type="checkbox"
                                                               style="max-width: 50px"
                                                               :name="`balance_accounting[${index}][forcredit]`"
                                                               x-model="item.forcredit"
                                                               class="form-control"
                                                               @change="item.forcredit = $event.target.checked ? 1 : 0"
                                                               :checked="1">
                                                    </div>

                                                    <div class="col-md-2 col-4 mt-1">
                                                        <button type="button" class="btn btn-danger"
                                                                @click="confirmRemoveField(index)">Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            </template>

                                            <!-- Add Button -->
                                            <button type="button" class="btn btn-primary mt-3" @click="addField">
                                                Add Another Item
                                            </button>

                                            <div x-data="{
                                            payed: '0',
                                            get amountDue() {
                                                return calculateTotal() - parseFloat(this.payed || 0);
                                            },
                                            validateNumber() {
                                                this.payed = this.payed.replace(/[^0-9.]/g, ''); // Only keep numbers and a single decimal point
                                            }
                                        }">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="allcost">All Cost</label>
                                                        <input type="text" name="total_cost" id="allcost"
                                                               class="form-control" x-bind:value="calculateTotal()"
                                                               readonly>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="payed">Payed</label>
                                                        <input type="text" name="payed"
                                                               id="payed" class="form-control" x-model="payed"
                                                               x-on:input="validateNumber">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="amountDue">Amount Due</label>
                                                        <input type="text" name="balance" id="amountDue"
                                                               class="form-control" x-bind:value="amountDue" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="container mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="vehicle_owner_name">Vehicle Owner name
                                                </label>
                                                <input type="text" name="vehicle_owner_name" id="vehicle_owner_name"
                                                       class="form-control">
                                            </div>

                                            <div class="col-md-4">
                                                <label for="owner_id_number">Owner ID Number
                                                </label>
                                                <input type="text" name="owner_id_number" id="owner_id_number"
                                                       class="form-control">
                                            </div>

                                            <div class="col-md-4">
                                                <label for="owner_phone_number">Owner Phone number
                                                </label>
                                                <input type="text" name="owner_phone_number" id="owner_phone_number"
                                                       class="form-control">
                                            </div>

                                        </div>
                                    </div>
                                    {{-- red green defaulr color--}}
                                    <div class="container mb-3">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label style="padding: 10px;border-radius: 20px;text-align: center"
                                                       class="border">
                                                    <input checked type="radio" name="record_color" value=" "> No Color
                                                </label>
                                            </div>
                                            <div class="col-md-2">
                                                <label style="padding: 10px;color:white;border-radius: 20px;text-align: center;background: green">
                                                    <input type="radio" name="record_color" value="#82f98261"> Green
                                                </label>
                                            </div>
                                            <div class="col-md-2">
                                                <label style="padding: 10px;background:red;border-radius: 20px;text-align: center;color:white">
                                                    <input type="radio" name="record_color" value="#F6CBCC"> Red
                                                </label>
                                            </div>
                                        </div>
                                    </div>


                                    {{--                                    <div class="form-group" style="max-width: 100%">--}}
                                    {{--                                        <label>Container #</label><br>--}}
                                    {{--                                        <input name="container_number" class="form-control" id="container_number"--}}
                                    {{--                                               type="text">--}}

                                    {{--                                    </div>--}}

                                    <div class="form-group" style="max-width: 100%">
                                        <label>Images</label><br>
                                        <input type="file" id="filepond" name="images[]" multiple>

                                        <style>
                                            .img-fluid {
                                                height: 150px;
                                                width: 200px;
                                                object-fit: cover;
                                            }
                                        </style>

                                    </div>


                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="note">Note</label>
                                                <textarea name="comment" class="form-control" id="note" cols="30"
                                                          rows="2"></textarea>
                                            </div>

                                            <div class="col-md-4">
                                                <label>CAR Status</label>
                                                <select name="status" class="form-control" >
                                                    @foreach ($car_status as $status)
                                                        <option @selected($status->name=='For Dispatch') value="{{ $status->id }}">
                                                            {{ $status->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label>Start dispatch?</label>
                                                <select name="is_dispatch" class="form-control" id="is_dispatch">
                                                    <option value="yes" selected>YES</option>
                                                    <option value="no">NO</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="reset-button">
                                        @can('CarCreate')
                                            <button type="submit" class="btn btn-success"> Save</button>
                                        @endcan
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
            <script src="{{asset('assets/filepond/filepond-plugin-image-exif-orientation.min.js')}}"></script>
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
                    const inputElement = document.getElementById('filepond');
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


                    setTimeout(function () {
                        document.querySelector('[name="images[]"]').addEventListener('change', function (event) {
                            // Get the files selected in the filepond input
                            const filepondFiles = event.target.files;

                            // Get the hidden images2 input element
                            const images2Input = document.getElementById('images2');

                            // Create a new DataTransfer object to manage files
                            const dataTransfer = new DataTransfer();

                            // Add all files from filepond to the DataTransfer object
                            for (let i = 0; i < filepondFiles.length; i++) {
                                dataTransfer.items.add(filepondFiles[i]);
                            }

                            // Assign the files to the images2 input
                            images2Input.files = dataTransfer.files;
                        })
                    }, 300)
                });
            </script>




            <script id="alpinetarget">

                document.addEventListener('alpine:init', () => {


                    Alpine.store('balanceAccountingStore', {

                        {{--balance_accounting: @json($balanceAccounting ?? [['name' => '', 'value' => 0,'date'=>'', 'id'=>'']]), // Load existing data or default--}}
                        balance_accounting: {!! json_encode($balanceAccounting ?? [['name' => '', 'value' => 0, 'date' => '', 'id' => '' , 'forcredit' => '']]) !!},


                        addField() {

                            initializeAutocomplete();

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
            </script>


            <script id="checkdepositscript"></script>


            <script>
                // Ensure Alpine.js Reactive Store is initialized properly


                // Example words for autocomplete
                const suggestedWords = [
                    'Shipping cost',
                    'Vehicle cost',
                    'Fee amount',
                    'Bank fee',
                    'Insurance cost',
                    'Hybrid',
                    'Storage',
                    'TITLE',
                    'Sublot',
                    'Credit',
                ];

                // $(function () {
                //     var availableWarehouse = [
                //         // 'TRT - GA 31326',
                //         // 'TRT - CA 90248',
                //         // 'TRT - NJ 07114',
                //         // 'TRT - TX 77571',
                //         '250 Port Street,Newark,NJ 07114-TRT',
                //         '501 N 16th St,La Porte,TX 77571-TRT',
                //         '142 Commerce Ct,Rincon,GA 31326-TRT',
                //         '340 W Compton Blvd,Gardena,CA 90248-TRT'
                //     ];
                //
                //     $("#warehouse").autocomplete({
                //         source: availableWarehouse,
                //         minLength: 0 // Set to 0 to show suggestions immediately
                //     });
                //
                //     // Trigger the autocomplete suggestions on input focus (click)
                //     $("#warehouse").on('focus', function () {
                //         $(this).autocomplete('search', ''); // Force the dropdown to show on click/focus
                //     });
                //
                // });

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

                    var savedAuction = ""; // Saved auction value

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
                                    auction_id: auctionId // Send selected auction ID
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
                                    // $('#fromState').change(function () {
                                    //
                                    //     var fromStateId = $(this).val();
                                    //
                                    //     setTimeout(() => {
                                    //         fetchPortsForEdit(fromStateId,
                                    //             null); // Fetch ports but no preselected value on change
                                    //     }, 100)
                                    //
                                    // });
                                },
                                error: function (error) {
                                    alert("Error fetching from states");

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
                                    from_state_id: fromStateId
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
                                    from_state_id: fromStateId, // Send selected from_state ID
                                    auction_id: $('#auction').val(),
                                    location_name: $('#fromState option:selected').text()
                                },
                                success: function (response) {
                                    // Populate the to_port_id dropdown with the received ports
                                    $('#to_port_id').empty(); // Clear the existing options

                                    setTimeout( () => {
                                        $.each(response.ports, function (key, port) {
                                            console.log(key, port)
                                            $('#to_port_id').append(`<option value="${key}">${port}</option>`);
                                        });
                                        $('#to_port_id').append('<option value selected="">Select an option</option>');
                                    }, 100)
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
                                console.log(response.shipping_price)
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
                                    alert("Error calculation");
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
                    console.log(alpineComponent.balance_accounting);


                    // Find if "Shipping cost" already exists in the repeater
                    for (let i = 0; i < alpineComponent.balance_accounting.length; i++) {
                        if (alpineComponent.balance_accounting[i].name === 'Shipping cost') {
                            // Update the existing shipping cost
                            alpineComponent.balance_accounting[i].value = shippingCost;

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
                        'Shipping cost',
                        'Vehicle cost',
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
                    // $('.select2').select2();
                    $('.select2').select2({
                        matcher: function (params, data) {
                            if ($.trim(params.term) === '') {
                                return data;
                            }

                            if (!data.text) {
                                return null;
                            }

                            // Convert search term & option text to lowercase
                            let term = params.term.toLowerCase().replace(/\s+/g, ''); // Remove spaces
                            let text = data.text.toLowerCase().replace(/\s+/g, ''); // Remove spaces

                            // Allow partial substring matching (even in the middle of words)
                            if (text.includes(term)) {
                                return data;
                            }

                            // Allow matches where letters appear in the same order
                            let regex = new RegExp(term.split("").join(".*"), "i");
                            if (regex.test(text)) {
                                return data;
                            }

                            return null;
                        }
                    });
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
