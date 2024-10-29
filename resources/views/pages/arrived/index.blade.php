@extends('layouts.app')
@section('content')
    @push('css')
        <style>
            .table-responsive td {
                align-content: center;
            }

            .buttonexport {
                padding: 5px 15px;
            }
        </style>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush
@section('body-class', 'hold-transition sidebar-mini sidebar-collapse')

<!--preloader-->
<div id="preloader">
    <div id="status"></div>
</div>

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
                <i class="fa fa-anchor"></i>
            </div>
            <div class="header-title">
                <h1>Arrived</h1>
                <small>List of Containers</small>
            </div>
        </section>
        <div class="container">
            @if (session('message'))
                <style>
                    .alert {
                        text-align: center;
                        font-size: 20px;
                        text-transform: uppercase;
                        font-weight: bold;
                        max-width: 600px;
                        margin: 1rem auto 0;
                    }
                </style>
                @if (session('alert-type') == 'success')
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @elseif (session('alert-type') == 'error')
                    <div class="alert alert-danger">
                        {{ session('message') }}
                    </div>
                @endif
            @endif
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-lg-12 pinpin">
                    <div class="card lobicard" id="lobicard-custom-control" data-sortable="true">

                        <div class="card-body">
                            <!-- Plugin content:powerpoint,txt,pdf,png,word,xl -->
                            <div class="btn-group d-flex" role="group">
                                <div class="row" style="justify-content: space-between;width: 100%;">

                                    <div class="buttonexport">
                                        @php
                                            $currentStatus = request()->segment(count(request()->segments()));

                                        @endphp



                                    </div>


                                </div>

                            </div>


                            @foreach ($groups as $key => $cargroup)
                                <div class="table-responsive">
                                    <table id="dataTableExample1"
                                        class="table table-bordered table-striped table-hover">
                                        <thead class="back_table_color">
                                            <tr class="info">
                                                <th>ID</th>
                                                <th>Container #</th>
                                                <th>Line + Agent THC</th>
                                                <th>Estimated Arrival</th>
                                                <th>Terminal</th>
                                                <th>TRT/THC Payed</th>
                                                <th>Ask Green</th>
                                                <th>CONT Status</th>
                                                <th>Estimated Open Date</th>
                                                <th>Opend</th>
                                                <th>Open Payd</th>
                                                <th>Remark</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>



                                        <tbody>


                                            <form action="{{ route('arrived.update', $cargroup->id) }}" method="POST"
                                                enctype="multipart/form-data" novalidate>
                                                @csrf
                                                <tr class="info">
                                                    <td>{{ $key + 1 }}</td>

                                                    <td>
                                                        <a href="javascript:void()" data-toggle="modal"
                                                            data-target="#containerGroup-{{ $cargroup->id }}"
                                                            data-record-id="{{ $cargroup->id }}">{{ $cargroup->container_id }}</a>

                                                        <!-- Modal for FilePond upload -->
                                                        <div class="modal fade" id="containerGroup-{{ $cargroup->id }}"
                                                            tabindex="-1" role="dialog"
                                                            aria-labelledby="containerGroupLabel" aria-hidden="true">
                                                            <div class="modal-dialog" style="max-width:1150px"
                                                                role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Car List</h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">


                                                                        <div class="table-responsive">
                                                                            <table id="dataTableExample1"
                                                                                class="table table-bordered table-striped table-hover">
                                                                                <thead class="back_table_color">
                                                                                    <tr class="info">
                                                                                        <th>ID</th>
                                                                                        <th>CAR INFO</th>
                                                                                        <th>Owner</th>
                                                                                        <th>Car Photo</th>
                                                                                        <th>Bll OF Loading</th>
                                                                                        <th>Remark</th>
                                                                                        <th>Action</th>
                                                                                    </tr>
                                                                                </thead>



                                                                                <tbody>

                                                                                    @foreach ($cargroup->cars as $car)
                                                                                        <div class="car-wrapper">
                                                                                            <tr class="info">
                                                                                                <td>{{ $car->id }}
                                                                                                </td>
                                                                                                <td>
                                                                                                    
                                                                                                    <div>
                                                                                                        <strong>Model:<span>{{ $car->make_model_year }}</span></strong>
                                                                                                        <img src="/assets/dist/img/copy.svg"
                                                                                                            alt="copy"
                                                                                                            class="copy">
                                                                                                    </div>
                                                                                                    <div>
                                                                                                        <strong>VIN:<span>{{ $car->vin }}</span></strong>
                                                                                                        <img src="/assets/dist/img/copy.svg"
                                                                                                            alt="copy"
                                                                                                            class="copy">
                                                                                                    </div>
                                                                                                </td>
                                                                                                <td>
                                                                                                    {{ $car->vehicle_owner_name }}<br>
                                                                                                    {{ $car->owner_id_number }}<br>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <div
                                                                                                        class="upload__box">
                                                                                                        <div
                                                                                                            class="upload__btn-box">
                                                                                                            <label
                                                                                                                class="upload__btn">
                                                                                                                <p>Car Photo 
                                                                                                                </p>
                                                                                                                <input
                                                                                                                    type="file"
                                                                                                                    multiple=""
                                                                                                                    data-max_length="20"
                                                                                                                    class="upload__inputfile"
                                                                                                                    id="upload__inputfile_{{ $car->id }}"
                                                                                                                    name="container_images">
                                                                                                            </label>
                                                                                                        </div>
                                                                                                        <div
                                                                                                            class="upload__img-wrap">
                                                                                                        </div>
                                                                                                        @if ($car->getMedia('container_images')->isNotEmpty())
                                                                                                            <a href="{{ route('arrived.showImages', $car->id) }}"
                                                                                                                target="_blank">
                                                                                                                <img src="https://cdn-icons-png.flaticon.com/512/1375/1375106.png"
                                                                                                                    style="max-width: 25px; margin-top:10px"
                                                                                                                    alt="">
                                                                                                            </a>
                                                                                                        @endif

                                                                                                    </div>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <input
                                                                                                        type="file"
                                                                                                        name="bill_of_loading"
                                                                                                        id="bill_of_loading_{{ $car->id }}"
                                                                                                        style="width: 200px;"
                                                                                                        {{ !empty($car->bill_of_loading) ? '' : 'required' }}>
                                                                                                    <br>
                                                                                                    @if (!empty($car->bill_of_loading))
                                                                                                        <a href="{{ Storage::url($car->bill_of_loading) }}"
                                                                                                            target="_blank">
                                                                                                            <img src="https://cdn-icons-png.freepik.com/256/10106/10106255.png?semt=ais_hybrid"
                                                                                                                style="max-width: 25px; margin-top:10px"
                                                                                                                alt="Receipt">
                                                                                                        </a>
                                                                                                    @endif
                                                                                                </td>
                                                                                                <td>

                                                                                                    <textarea class="form-control" name="remark" id="remark_{{ $car->id }}" cols="30" rows="2">{{ $car->remark ?? '' }}</textarea>

                                                                                                </td>

                                                                                                <td>
                                                                                                    <div class="d-flex"
                                                                                                        style="gap:10px">
                                                                                                        <button
                                                                                                            type="button"
                                                                                                            class="btn btn-success btn-sm"
                                                                                                            onclick="saveCarData({{ $car->id }})">SAVE</button>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </div>
                                                                                    @endforeach







                                                                                </tbody>
                                                                            </table>
                                                                        </div>


                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $cargroup->thc_agent }}</td>
                                                    <td>
                                                        <input type="date"
                                                            value="{{ $cargroup->arrival_time ? \Carbon\Carbon::parse($cargroup->arrival_time)->format('Y-m-d') : '' }}"
                                                            placeholder="Arrival Time" class="form-control"
                                                            name="arrival_time" id="arrival_time"
                                                            {{ $cargroup->arrival_time ? '' : 'required' }}>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="terminal" id="terminal">
                                                            <option value=""></option>
                                                            <option value="APM"
                                                                {{ $cargroup->terminal == 'APM' ? 'selected' : '' }}>
                                                                APM</option>
                                                            <option value="Espe"
                                                                {{ $cargroup->terminal == 'Espe' ? 'selected' : '' }}>
                                                                Espe</option>
                                                            <option value="GTI"
                                                                {{ $cargroup->terminal == 'GTI' ? 'selected' : '' }}>
                                                                GTI</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <label for="trt_payed">TRT Payed</label>

                                                        <select class="form-control" name="trt_payed" id="trt_payed">
                                                            <option value=""></option>
                                                            <option value="yes"
                                                                {{ $cargroup->trt_payed == 'yes' ? 'selected' : '' }}>
                                                                YES</option>
                                                            <option value="no"
                                                                {{ $cargroup->trt_payed == 'no' ? 'selected' : '' }}>NO
                                                            </option>
                                                        </select>

                                                        <label for="thc_payed">THC Payed</label>

                                                        <select class="form-control" name="thc_payed" id="thc_payed">
                                                            <option value=""></option>
                                                            <option value="yes"
                                                                {{ $cargroup->thc_payed == 'yes' ? 'selected' : '' }}>
                                                                YES</option>
                                                            <option value="no"
                                                                {{ $cargroup->thc_payed == 'no' ? 'selected' : '' }}>NO
                                                            </option>
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <select class="form-control" name="is_green" id="is_green">
                                                            <option value=""></option>
                                                            <option value="yes"
                                                                {{ $cargroup->is_green == 'yes' ? 'selected' : '' }}>
                                                                YES</option>
                                                            <option value="no"
                                                                {{ $cargroup->is_green == 'no' ? 'selected' : '' }}>NO
                                                            </option>
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <select class="form-control" name="cont_status"
                                                            id="cont_status"
                                                            style="{{ $cargroup->cont_status == 'Green' ? 'background:#28a745;color:#fff;' : '' }} {{ $cargroup->cont_status == 'Red' ? 'background:#dc3545;color:#fff;' : '' }}">
                                                            <option value=""></option>


                                                            <option value="Green"
                                                                {{ $cargroup->cont_status == 'Green' ? 'selected' : '' }}>
                                                                Green</option>
                                                            <option value="Red"
                                                                {{ $cargroup->cont_status == 'Red' ? 'selected' : '' }}>
                                                                Red</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="date"
                                                            value="{{ $cargroup->est_open_date ? \Carbon\Carbon::parse($cargroup->est_open_date)->format('Y-m-d') : '' }}"
                                                            placeholder="Est Open Date" class="form-control"
                                                            name="est_open_date" id="est_open_date"
                                                            {{ $cargroup->est_open_date ? '' : 'required' }}>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="opened" id="opened">
                                                            <option value=""></option>
                                                            <option value="YES"
                                                                {{ $cargroup->opened == 'YES' ? 'selected' : '' }}>YES
                                                            </option>
                                                            <option value="NO"
                                                                {{ $cargroup->opened == 'NO' ? 'selected' : '' }}>NO
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="open_payed"
                                                            id="open_payed">
                                                            <option value=""></option>
                                                            <option value="YES"
                                                                {{ $cargroup->open_payed == 'YES' ? 'selected' : '' }}>
                                                                YES</option>
                                                            <option value="NO"
                                                                {{ $cargroup->open_payed == 'NO' ? 'selected' : '' }}>
                                                                NO</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <textarea class="form-control" name="remark" id="remark" cols="30" rows="2">{{ $cargroup->remark ?? '' }}</textarea>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex" style="gap:10px">
                                                            <button type="submit"
                                                                class="btn btn-success btn-sm">SAVE</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </form>
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach

                            @push('js')
                                <script>
                                    function saveCarData(carId) {
                                        // Prepare the form data
                                        var formData = new FormData();
                                        formData.append('_token', '{{ csrf_token() }}'); // Laravel CSRF token
                                        formData.append('bill_of_loading', document.querySelector('#bill_of_loading_' + carId).files[0]);
                                        formData.append('remark', document.querySelector('#remark_' + carId).value);
                                        formData.append('title_take', document.querySelector('#title_take_' + carId).value);
                                        formData.append('car_id', carId);

                                        // Add any additional form fields, such as the uploaded files
                                        let containerImages = document.querySelector('#upload__inputfile_' + carId).files;
                                        for (let i = 0; i < containerImages.length; i++) {
                                            formData.append('container_images[]', containerImages[i]);
                                        }

                                        // Send the AJAX request
                                        $.ajax({
                                            url: '/dashboard/arrived/car/' + carId + '/update', // Adjust route as needed
                                            method: 'POST',
                                            data: formData,
                                            processData: false, // Required for FormData
                                            contentType: false, // Required for FormData
                                            success: function(response) {
                                                // Handle success response (e.g., show a success message or update the UI)
                                                alert('Car data saved successfully!');
                                                location.reload();
                                            },
                                            error: function(response) {
                                                // Handle validation errors or any other errors
                                                if (response.status === 422) {
                                                    var errors = response.responseJSON.errors;
                                                    // Display the validation errors in the UI
                                                    alert('Validation error: ' + JSON.stringify(errors));
                                                } else {
                                                    alert('An error occurred while saving the data.');
                                                }
                                            }
                                        });
                                    }



                                    jQuery(document).ready(function() {
                                        ImgUpload();
                                    });

                                    function ImgUpload() {
                                        var imgWrap = "";
                                        var imgArray = [];

                                        $('.upload__inputfile').each(function() {
                                            $(this).on('change', function(e) {
                                                imgWrap = $(this).closest('.upload__box').find('.upload__img-wrap');
                                                var maxLength = $(this).attr('data-max_length');

                                                var files = e.target.files;
                                                var filesArr = Array.prototype.slice.call(files);
                                                var iterator = 0;
                                                filesArr.forEach(function(f, index) {

                                                    if (!f.type.match('image.*')) {
                                                        return;
                                                    }

                                                    if (imgArray.length > maxLength) {
                                                        return false
                                                    } else {
                                                        var len = 0;
                                                        for (var i = 0; i < imgArray.length; i++) {
                                                            if (imgArray[i] !== undefined) {
                                                                len++;
                                                            }
                                                        }
                                                        if (len > maxLength) {
                                                            return false;
                                                        } else {
                                                            imgArray.push(f);

                                                            var reader = new FileReader();
                                                            reader.onload = function(e) {
                                                                var html =
                                                                    "<div class='upload__img-box'><div style='background-image: url(" +
                                                                    e.target.result + ")' data-number='" + $(
                                                                        ".upload__img-close").length + "' data-file='" + f
                                                                    .name +
                                                                    "' class='img-bg'><div class='upload__img-close'></div></div></div>";
                                                                imgWrap.append(html);
                                                                iterator++;
                                                            }
                                                            reader.readAsDataURL(f);
                                                        }
                                                    }
                                                });
                                            });
                                        });

                                        $('body').on('click', ".upload__img-close", function(e) {
                                            var file = $(this).parent().data("file");
                                            for (var i = 0; i < imgArray.length; i++) {
                                                if (imgArray[i].name === file) {
                                                    imgArray.splice(i, 1);
                                                    break;
                                                }
                                            }
                                            $(this).parent().parent().remove();
                                        });
                                    }
                                </script>
                                <script>
                                    $(document).ready(function() {

                                        // Example words for autocomplete
                                        const suggestedWords = [
                                            'MAERSK',
                                            'Hapag-Eisa',
                                            'Turkon-DTS',
                                            'One net-Wilhelmsen',
                                        ];

                                        // Initialize jQuery UI autocomplete
                                        function initializeAutocomplete() {
                                            $(".thc_agent").autocomplete({
                                                source: suggestedWords,
                                                minLength: 0,
                                                select: function(event, ui) {
                                                    let selectedWord = ui.item.value; // Get the selected word
                                                    $(this).closest('td').find('input[name="cost"]').val('');
                                                }
                                            }).focus(function() {
                                                // Force the dropdown to show when the input gains focus
                                                $(this).autocomplete("search", "");
                                            });
                                        }
                                        initializeAutocomplete();


                                        $('#car-list').select2({
                                            placeholder: 'Select Car',
                                            dropdownParent: $('#addCarModal'),
                                            matcher: function(params, data) {
                                                // If there are no search terms, return all of the data
                                                if ($.trim(params.term) === '') {
                                                    return data;
                                                }

                                                // Do not display the item if there is no 'text' property
                                                if (typeof data.text === 'undefined') {
                                                    return null;
                                                }

                                                // `params.term` is the user's search term
                                                // `data.id` should be checked against
                                                // `data.text` should be checked against
                                                var q = params.term.toLowerCase();
                                                if (data.text.toLowerCase().indexOf(q) > -1 || data.id.toLowerCase().indexOf(q) > -
                                                    1) {
                                                    return $.extend({}, data, true);
                                                }

                                                // Return `null` if the term should not be displayed
                                                return null;
                                            }

                                        });

                                        $('#new_car_id').select2({
                                            placeholder: 'Select Car',
                                            dropdownParent: $('#replaceCarModal'),
                                        });

                                        // Handle opening the modal and fetching available cars
                                        $('.open-modal').on('click', function() {
                                            var carId = $(this).data('car-id');
                                            var container_id = $(this).data('container-id');
                                            $('#original_car_id').val(carId); // Set the original car ID in the modal form

                                            // Clear any existing options in the select dropdown
                                            $('#new_car_id').empty();

                                            console.log(container_id)
                                            $('#saveCarReplacement').attr('data-container-id', container_id);


                                            // Fetch available cars via AJAX
                                            $.ajax({
                                                url: '{{ route('container.availableCars') }}', // Your endpoint to fetch available cars
                                                method: 'POST',
                                                data: {
                                                    carId: carId,
                                                    container_id: container_id,
                                                },
                                                success: function(response) {
                                                    // Loop through the response and populate the select dropdown
                                                    $('#new_car_id').append(
                                                        $('<option>', {
                                                            value: '',
                                                            text: ''
                                                        })
                                                    );
                                                    response.cars.forEach(function(car) {
                                                        $('#new_car_id').append(
                                                            $('<option>', {
                                                                value: car.id,
                                                                text: car.make_model_year + ' - ' + car
                                                                    .vin + ' | ' + car.load_type.name +
                                                                    ' | ' + car.type_of_fuel
                                                            })
                                                        );
                                                    });
                                                    // Show the modal after cars are loaded
                                                    $('#replaceCarModal').modal('show');

                                                },
                                                error: function(xhr) {
                                                    alert('Failed to load available cars.');
                                                }
                                            });
                                        });

                                        // Handle opening the modal and fetching available cars
                                        $('.open-car-modal').on('click', function() {
                                            var carId = $(this).data('car-id');
                                            var container_id = $(this).data('container-id');
                                            var to_port_id = $(this).data('to_port_id');

                                            $('#container_id').val(container_id);


                                            // Fetch available cars via AJAX
                                            $.ajax({
                                                url: '{{ route('container.availableCars') }}', // Your endpoint to fetch available cars
                                                method: 'POST',
                                                data: {
                                                    carId: carId,
                                                    container_id: container_id,
                                                    to_port_id: to_port_id
                                                },
                                                success: function(response) {
                                                    // Loop through the response and populate the select dropdown
                                                    $('#car-list').append(
                                                        $('<option>', {
                                                            value: '',
                                                            text: ''
                                                        })
                                                    );
                                                    response.cars.forEach(function(car) {
                                                        $('#car-list').append(
                                                            $('<option>', {
                                                                value: car.id,
                                                                text: car.make_model_year + ' - ' + car
                                                                    .vin + ' | ' + car.load_type.name +
                                                                    ' | ' + car.type_of_fuel
                                                            })
                                                        );
                                                    });
                                                },
                                                error: function(xhr) {
                                                    alert('Failed to load available cars.');
                                                }
                                            });
                                        });




                                        $('.removefromlist').on('click', function() {
                                            var carId = $(this).data('car-id');
                                            var container_id = $(this).data('container-id');

                                            // Send an AJAX request to replace the car
                                            $.ajax({
                                                url: '{{ route('container.removeFromList') }}', // Laravel route for replacing cars
                                                method: 'POST',
                                                data: {
                                                    carId: carId,
                                                    container_id: container_id,
                                                    _token: '{{ csrf_token() }}' // Include CSRF token for security
                                                },
                                                success: function(response) {
                                                    alert('Car Removed From List!');
                                                    location.reload(); // Optionally, reload the page to reflect changes
                                                },
                                                error: function(xhr) {
                                                    alert('Failed to Remove car.');
                                                }
                                            });
                                        });

                                        // Handle saving the replacement
                                        $('#saveCarReplacement').on('click', function() {
                                            var originalCarId = $('#original_car_id').val();
                                            var newCarId = $('#new_car_id').val();
                                            var container_id = $(this).data('container-id');

                                            // Send an AJAX request to replace the car
                                            $.ajax({
                                                url: '{{ route('container.replaceCar') }}', // Laravel route for replacing cars
                                                method: 'POST',
                                                data: {
                                                    original_car_id: originalCarId,
                                                    new_car_id: newCarId,
                                                    container_id: container_id,
                                                    _token: '{{ csrf_token() }}' // Include CSRF token for security
                                                },
                                                success: function(response) {
                                                    alert('Car replaced successfully!');
                                                    $('#replaceCarModal').modal('hide'); // Close the modal on success
                                                    location.reload(); // Optionally, reload the page to reflect changes
                                                },
                                                error: function(xhr) {
                                                    alert('Failed to replace car.');
                                                }
                                            });
                                        });
                                    });



                                    $(document).on('click', '.sendEmail', function() {
                                        var container_id = $(this).data('container-id');
                                        sendEmail(container_id);
                                    });


                                    function sendEmail(container_id) {
                                        $.ajax({
                                            url: '{{ route('container.sendEmail') }}', // The route where the request is sent
                                            method: 'POST',
                                            data: {
                                                container_id: container_id, // Send the container ID
                                                _token: '{{ csrf_token() }}' // Include the CSRF token for security
                                            },
                                            success: function(response) {
                                                alert(response.message); // Show a success message
                                            },
                                            error: function(xhr) {
                                                alert('Error sending email.');
                                            }
                                        });
                                    }

                                    function previewImage(event, previewId) {
                                        const input = event.target;
                                        const preview = document.getElementById(previewId);

                                        // Ensure a file was selected
                                        if (input.files && input.files[0]) {
                                            const reader = new FileReader();

                                            reader.onload = function(e) {
                                                // Set the src of the preview image to the file's content
                                                preview.src = e.target.result;
                                                preview.style.display = 'block'; // Show the preview image
                                            };

                                            // Read the file
                                            reader.readAsDataURL(input.files[0]);
                                        } else {
                                            // Hide preview if no image is selected
                                            preview.src = '';
                                            preview.style.display = 'none';
                                        }
                                    }
                                </script>
                                <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

                                <link rel="stylesheet" type="text/css"
                                    href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
                                <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

                                <script type="text/javascript">
                                    $(function() {
                                        $('.daterange').daterangepicker({
                                            opens: 'left',
                                            locale: {
                                                format: 'DD.MM' // Set the desired format
                                            }
                                        }, function(start, end, label) {
                                            console.log("A new date range was chosen: " + start.format('DD.MM') + ' to ' + end
                                                .format('DD.MM'));
                                        });
                                    });
                                </script>
                            @endpush


                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <!-- Delete User Modal -->
            <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header modal-header-primary">
                            <h3><i class="fa fa-user m-r-5"></i> Delete User</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this user?</p>
                            <form id="deleteUserForm">
                                @csrf <!-- CSRF token for security -->
                                <input type="hidden" id="deleteUserId" name="user_id">
                                <!-- Hidden field to hold the user ID -->
                                <div class="form-group user-form-group">
                                    <div class="float-right">
                                        <button type="button" class="btn btn-danger btn-sm"
                                            data-dismiss="modal">NO</button>
                                        <button type="submit" class="btn btn-add btn-sm">YES</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger float-left"
                                data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->
        </section>
        <!-- /.content -->
    </div>


    @include('partials.footer')

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const carCheckboxes = document.querySelectorAll('.car_ids');
                const hiddenInput = document.getElementById('carids');

                carCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        // Collect all selected car IDs
                        const selectedCars = Array.from(carCheckboxes)
                            .filter(checkbox => checkbox.checked)
                            .map(checkbox => checkbox.value);

                        // Update the hidden input value with selected IDs (comma-separated)
                        hiddenInput.value = selectedCars.join(',');
                    });
                });
            });

            $('.title').on('change', function(e) {
                e.preventDefault(); // Prevent the default form submission

                var title = $(this).val(); // Get the user ID from the hidden input
                var carid = $(this).data('car-id'); // Get the user ID from the hidden input

                $.ajax({
                    url: '{{ route('container.listupdate') }}',
                    type: 'POST', // HTTP method for deletion
                    data: {
                        carid: carid,
                        title: title
                    }, // Send the serialized form data (including the CSRF token)
                    success: function(response) {},
                    error: function(xhr) {
                        alert('Something went wrong.'); // Handle errors
                    }
                });
            });
        </script>



        <script>
            $('.copy').on('click', function() {
                const text = $(this).prev('strong').find('span').text().trim();
                navigator.clipboard.writeText(text).then(function() {
                    console.log('Copied to clipboard:', text);
                }).catch(function(error) {
                    console.error('Failed to copy:', error);
                });
            });
        </script>


        <script>
            $(function() {
                var availableWarehouse = [
                    "MTL- New jersey",
                    "TRT - New Jersey"
                ];

                $("#warehouse").autocomplete({
                    source: availableWarehouse,
                    minLength: 0 // Set to 0 to show suggestions immediately
                });

                // Trigger the autocomplete suggestions on input focus (click)
                $("#warehouse").on('focus', function() {
                    $(this).autocomplete('search', ''); // Force the dropdown to show on click/focus
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


            // Open delete modal and set user ID
            $('#deleteUserModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var userId = button.data('user-id'); // Extract user ID from data-* attribute
                var modal = $(this);

                // Set the user ID in the hidden input field in the form
                modal.find('#deleteUserId').val(userId);
            });

            // Handle the form submission for deleting the user
            $('#deleteUserForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                var userId = $('#deleteUserId').val(); // Get the user ID from the hidden input
                var formData = $(this).serialize(); // Serialize form data for submission

                $.ajax({
                    url: `/dashboard/users/${userId}`, // URL for deleting the user
                    type: 'DELETE', // HTTP method for deletion
                    data: formData, // Send the serialized form data (including the CSRF token)
                    success: function(response) {
                        alert(response.message); // Show success message
                        $('#deleteUserModal').modal('hide'); // Hide the modal
                        location.reload(); // Optionally reload the page to update the user list
                    },
                    error: function(xhr) {
                        alert('Something went wrong.'); // Handle errors
                    }
                });
            });
        </script>
    @endpush
</div>
@endsection
