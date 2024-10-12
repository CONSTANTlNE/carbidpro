@foreach ($cars as $key => $cargroup)
    <h4 style="m-0"> GROUP: {{ $key + 1 }}</h2>

        <div class="table-responsive">
            <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
                <thead class="back_table_color">
                    <tr class="info">
                        <th>ID</th>
                        <th style="width: 20%;">CAR</th>
                        <th>Car type</th>
                        <th>Fuel type</th>
                        <th>Warehouse</th>
                        <th>Owner</th>
                        <th style="width: 10%;">Dispatch day count</th>
                        <th style="width: 10%;">Container #
                        </th>
                        <th style="width: 10%;">Container Cost
                        </th>
                        <th style="width: 10%;"> Photo of BOL
                        </th>
                        <th>Action</th>
                    </tr>
                </thead>
                <thead class="back_table_color">
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
                            <th style="width: 10%;"></th>
                            <th style="width: 10%;">
                                <input type="text" value="{{ $cargroup->container_id }}" placeholder="Container #"
                                    class="form-control" name="container" id="container">
                            </th>
                            <th style="width: 10%;">
                                <input type="text" value="{{ $cargroup->cost }}" placeholder="Container Cost $"
                                    class="form-control" name="container_cost" id="container_cost">
                            </th>
                            <th style="width: 10%;">
                                <input type="file" name="bol_photo" id="bol_photo"
                                    onchange="previewImage(event, 'preview_{{ $cargroup->id }}')" required>

                                @if (!empty($cargroup->photo))
                                    <a href="{{ Storage::url($cargroup->photo) }}" target="_blank">

                                        <img src="{{ Storage::url($cargroup->photo) }}"
                                            style="max-width: 100px; margin-top:10px" alt="Recipte">
                                    </a>
                                @endif

                                <img id="preview_{{ $cargroup->id }}" src="" alt="Image preview"
                                    style="display:none; max-width: 100px; margin-top:10px;">
                            </th>
                            <th>
                                <div class="d-flex" style="gap:10px">
                                    <button type="button" id="sendEmail" data-container-id="{{ $cargroup->id }}"
                                        class="sendEmail btn btn-primary btn-sm">
                                        Send email
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
                                <td>{{ $car->loadType->name }}</td>
                                <td>{{ $car->type_of_fuel }}</td>

                                <td>
                                    {{ $car->warehouse }}<br>
                                    <label for="">Destination Port:</label>
                                    <br>
                                    {{ $car->port->name }}<br>
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
                                <td>{{ $car->container_number }}</td>
                                <td>{{ $cargroup->cost }}</td>
                                <td></td>

                                <td>

                                </td>
                            </form>

                        </tr>
                    @endforeach




                </tbody>
            </table>
        </div>
@endforeach

@push('js')
    <script>
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
