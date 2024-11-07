<div class="table-responsive">
    <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
        <thead class="back_table_color">
            <tr class="info">
                <th>ID</th>
                <th>CAR INFO</th>
                <th>FROM-TO</th>

                <th>Price</th>
                <th>Carrier</th>
                <th>T/status</th>
                <th>Pickup & Delivery Dates</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cars as $car)
                <tr>

                    <td>
                        {{ $car->id }}</td>
                    <td class="car_info"> @include('partials.car.table_content-parts.car-info') </td>
                    <form action="{{ route('car.listupdate', $car->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status"
                            value="{{ isset($_GET['status']) ? $_GET['status'] : 'for-Dispatch' }}">
                        <td>@include('partials.car.table_content-parts.field-from')</td>

                        <td>
                            @include('partials.car.table_content-parts.car-price')
                        </td>
                        <td>
                            <label for="company_name">Company name:</label><br>
                            {{ $car->company_name }}
                            <br>
                            <label for="contact_info">Contact info:</label><br>
                            {{ $car->contact_info }}
                        </td>
                        <td>{{ $car->title }}</td>
                        <td>
                            @php
                                $pickupDates = $car->pickup_dates;
                                $dateParts = explode(' - ', $pickupDates);
                                $endDate = isset($dateParts[1])
                                    ? \Carbon\Carbon::createFromFormat('d.m.Y', trim($dateParts[1]))
                                    : null;
                                $isExpired = $endDate && $endDate->isPast();
                            @endphp

                            <input type="text" data-record-id="{{ $car->id }}" value="{{ $car->pickup_dates }}"
                                name="pickup_dates" class="form-control daterange"
                                style="background-color: {{ $isExpired ? 'red' : 'white' }};" />

                        </td>

                        <td>
                            <button type="submit" id="submit-btn-{{ $car->id }}" disabled
                                class="btn btn-success btn-sm">
                                Next
                            </button>
                            <br>
                            <br>
                            <strong>Create:</strong> {{ $car->created_at->format('d.m.y') }} <br>
                            <strong>Update:</strong> {{ $car->updated_at->format('d.m.y') }} <br>

                        </td>
                    </form>

                </tr>
            @endforeach

        </tbody>
    </table>
</div>

@push('js')
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script type="text/javascript">
        $(function() {

            // Example words for autocomplete
            const suggestedWords = [
                'Driver',
                'Owner',
            ];

            // Initialize jQuery UI autocomplete
            function initializeAutocomplete() {
                $(".storage").autocomplete({
                    source: suggestedWords,
                    minLength: 0 // Show suggestions even when no input is typed
                }).focus(function() {
                    // Force the dropdown to show when the input gains focus
                    $(this).autocomplete("search", "");
                });
            }

            // Initialize autocomplete on page load
            $(document).ready(function() {
                initializeAutocomplete();
            });


            // Initialize date range picker for each .daterange input
            $('.daterange').each(function() {
                let daterangeInput = $(this);
                let recordId = daterangeInput.data(
                    'record-id'); // Get the record ID from the data attribute

                // Initialize the date range picker
                daterangeInput.daterangepicker({
                    opens: 'left',
                    locale: {
                        format: 'DD.MM.YYYY'
                    },
                    autoUpdateInput: true // Automatically updates input with default value
                }, function(start, end, label) {
                    let today = moment().startOf('day'); // Get today's date at 00:00:00

                    // Get the corresponding submit button for the current daterange
                    let submitBtn = $('#submit-btn-' + recordId);

                    // Compare the start date with today's date
                    if (start.isAfter(today)) {
                        // If the start date is before today, disable the submit button
                        submitBtn.attr('disabled', true);
                    } else {
                        // If the start date is today or after today, enable the submit button
                        submitBtn.attr('disabled', false);
                    }
                });

                // On page load, check the current value of the date range picker
                let initialStartDate = daterangeInput.data('daterangepicker').startDate;
                let today = moment().startOf('day'); // Get today's date at 00:00:00

                // Get the corresponding submit button for the current daterange
                let submitBtn = $('#submit-btn-' + recordId);

                // Compare the initial start date with today's date
                if (initialStartDate.isAfter(today)) {
                    // If the start date is before today, disable the submit button
                    submitBtn.attr('disabled', true);
                } else {
                    // If the start date is today or after today, enable the submit button
                    submitBtn.attr('disabled', false);
                }
            });
        });
    </script>
@endpush
