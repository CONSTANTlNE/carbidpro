@foreach ($cars as $key => $cargroup)
    <h4 class="mt-2"> GROUP: {{ $key + 1 }}</h4>

        <div class="table-responsive">
            <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
                <thead class="back_table_color">
                    <tr class="info">
                        <th>ID</th>
                        <th style="width: 20%;">CAR</th>
                        <th style="width: 10%;">Car type</th>
                        <th style="width: 10%;">Fuel type</th>
                        <th>Warehouse</th>
                        <th>Owner</th>
                        <th style="width: 10%;">Dispatch days</th>
                        <th style="width: 10%;">Container Info</th>
                        <th style="width: 10%;">Container Cost
                        </th>
                        <th style="width: 10%;"> Photo of BOL
                        </th>
                        <th style="width: 50%;">Action</th>
                    </tr>
                </thead>
                <thead class="back_table_color" style="background-color: #576cff21">
                    <form action="{{ route('container.updateGroup') }}" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="group_id" value="{{ $cargroup->id }}">
                        @csrf
                        <tr class="info">
                            <th></th>
                            <th style="width: 20%;"></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="width: 10%;">
                                <label for="booking_id">Booking #</label>
                                <input type="text" value="{{ $cargroup->booking_id }}" placeholder="Booking #"
                                    class="form-control" name="booking_id" id="booking_id" required>
                                <label for="container">Container #</label>
                                <input type="text" value="{{ $cargroup->container_id }}" placeholder="Container #"
                                    class="form-control" name="container" id="container" required>
                                <label for="arrival_time">Arrival Date</label>
                                <input type="date"
                                    value="{{ \Carbon\Carbon::parse($cargroup->arrival_time)->format('Y-m-d') }}"
                                    placeholder="Arrival Time" class="form-control" name="arrival_time"
                                    id="arrival_time" {{ $cargroup->arrival_time ? '' : 'required' }}>
                            </th>
                            <th style="width: 10%;">
                                <input type="text" placeholder="Shipping Line" value="{{ $cargroup->thc_agent }}"
                                    name="thc_agent" class="form-control thc_agent" id="thc_agent" required>
                                <br>
                                <br>
                                <input type="text" value="{{ $cargroup->cost }}" placeholder="Container Cost $"
                                    class="form-control" name="container_cost" id="container_cost" required>
                            </th>
                            <th style="width: 10%;">
                                <input type="file" name="bol_photo" id="bol_photo" style="width: 200px;" required>

                                @if (!empty($cargroup->photo))
                                    <a href="{{ Storage::url($cargroup->photo) }}" target="_blank">

                                        <img src="{{ Storage::url($cargroup->photo) }}"
                                            style="max-width: 100px; margin-top:10px" alt="Recipte">
                                    </a>
                                @endif

                                <img id="preview_{{ $cargroup->id }}" src="" alt="Image preview"
                                    style="display:none; max-width: 100px; margin-top:10px;">
                            </th>
                            <th style="width: 50%;">
                                <div class="d-flex" style="gap:10px;    flex-direction: column;">

                                    <button data-container-id="{{ $cargroup->id }}"
                                        data-to_port_id='{{ $cargroup->to_port_id }}' data-toggle="modal"
                                        class="btn btn-dark open-car-modal" data-target="#addCarModal" type="button">
                                        Add Car
                                    </button>

                                    <button type="button" id="sendEmail" data-container-id="{{ $cargroup->id }}"
                                        class="sendEmail btn {{ $cargroup->is_email_sent == 1 ? 'btn-warning' : 'btn-primary' }} btn-sm">
                                        {{ $cargroup->is_email_sent == 1 ? 'Email sent ' . $cargroup->email_sent_date : 'Send email' }}
                                    </button>

                                    <button type="submit" class="btn btn-success btn-sm">
                                        NEXT
                                    </button>


                                </div>


                            </th>
                        </tr>
                    </form>

                </thead>


                <tbody>


                    @foreach ($cargroup->cars as $car)
                        <tr>

                            <td>
                                {{ $car->id }}</td>
                            <td class="car_info"> @include('partials.car.table_content-parts.car-info') </td>
                            <form action="{{ route('container.listupdate', $car->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status"
                                    value="{{ isset($_GET['status']) ? $_GET['status'] : 'for-Dispatch' }}">
                                <td>{{ isset($car->loadType) ? $car->loadType->name : '' }}</td>
                                <td> @include('partials.container.table_content-parts.fuel-type')
                                </td>

                                <td>
                                    {{ !empty($car->port) ? $car->port->name : '' }} <br>
                                    <label for="">Dest Port:</label>
                                    <br>
                                    POTI<br>
                                </td>

                                <td>
                                    {{ $car->vehicle_owner_name }}<br>
                                    {{ $car->owner_id_number }}<br>
                                    {{ $car->owner_phone_number }}<br>
                                </td>

                                <td>
                                    @php
                                        $updatedAt = $car->updated_at;
                                        $daysGone = \Carbon\Carbon::parse($updatedAt)->diffInDays(
                                            \Carbon\Carbon::now(),
                                        );
                                    @endphp

                                    {{ $daysGone }} Day

                                </td>
                                <td>

                                </td>
                                <td>{{ $cargroup->cost }}</td>
                                <td></td>

                                <td>
                                    <!-- Button to trigger the modal -->
                                    <button data-car-id="{{ $car->id }}" to_port_id="{{ $car->to_port_id }}"
                                        data-container-id="{{ $cargroup->id }}" data-toggle="modal"
                                        class="btn btn-dark open-modal" data-target="#replaceCarModal" type="button">
                                        Replace
                                    </button>
                                    <button data-car-id="{{ $car->id }}"
                                        data-container-id="{{ $cargroup->id }}" class="btn btn-danger removefromlist"
                                        type="button">
                                        Remove
                                    </button>
                                </td>
                            </form>

                        </tr>
                    @endforeach




                </tbody>
            </table>
        </div>

        <!-- Modal Replace -->
        <div class="modal fade" id="replaceCarModal" tabindex="-1" aria-labelledby="replaceCarModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="replaceCarModalLabel">Replace Car</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="replaceCarForm">
                            <input type="hidden" name="original_car_id" id="original_car_id">

                            <div class="form-group">
                                <label for="new_car_id">Select a new car:</label>
                                <select name="new_car_id" id="new_car_id" class="form-control">
                                    <!-- Options will be populated dynamically -->
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-container-id='0' class="btn btn-primary"
                            id="saveCarReplacement">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Add car -->
        <div class="modal fade" id="addCarModal" tabindex="-1" aria-labelledby="addCarModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCarModalLabel">Add car</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('container.addCarToGroup') }}" method="POST" id="addCarForm">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="container_id" id="container_id">

                            <div class="form-group">
                                <label for="car-list">
                                    Select a new car:
                                    <br>
                                    <br>

                                    <select name="car_id" class="js-example-responsive js-states form-control"
                                        id="car-list"></select>
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
@endforeach

@push('js')
    <script>
        $(document).ready(function() {

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
                        $('.select2-results__options').empty()

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
                        $('#car-list').empty()
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

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
