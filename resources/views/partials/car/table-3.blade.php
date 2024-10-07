<div class="table-responsive">
    <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
        <thead class="back_table_color">
            <tr class="info">
                <th>ID</th>
                <th>CAR</th>
                <th>From</th>
              
                <th>Price</th>
                <th>Carrier</th>
                
                <th>Pickup & Delivery Dates</th>
                <th>Title Status</th>
                <th>Storage</th>
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
                            ${{ $car->internal_shipping }}
                        </td>
                        <td>
                            <label for="company_name">Company name:</label><br>
                            {{ $car->company_name }}
                            <br>
                            <label for="contact_info">Contact info:</label><br>
                            {{ $car->contact_info }}
                        </td>
                        <td>
                            <input type="text" data-record-id="{{ $car->id }}" value="{{ $car->pickup_dates }}"
                                name="pickup_dates" class="form-control daterange" />
                        </td>
                        <td>{{ $car->title }}</td>
                        <td>
                            <label for="company_name">Storage:</label><br>
                            <input type="text" name="storage" class="form-control storage" id="storage" required>
                            <br>
                            <label for="contact_info">Cost:</label><br>
                            <input type="number" class="form-control" name="cost" required>
                        </td>
                        <td>
                            <button type="submit" id="submit-btn-{{ $car->id }}"
                                class="btn btn-success btn-sm">
                                Next
                            </button>

                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#deleteUserModal" data-user-id="{{ $car->id }}">
                                <i class="fa fa-trash-o"></i>
                            </button>
                            <br>
                            <br>
                            @if (isset($car->updated_at))
                                <span class="btn btn-dark">
                                    {{ $car->updated_at->format('d.m') }}</span>
                            @endif

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
                'No storage',
            ];

            // Initialize jQuery UI autocomplete
            function initializeAutocomplete() {
                $(".storage").autocomplete({
                    source: suggestedWords,
                    minLength: 0,
                    select: function(event, ui) {
                        let selectedWord = ui.item.value; // Get the selected word
                        let correspondingCost = 0

                        // Set the value of the .cost input
                        if (selectedWord == 'No storage') {
                            $(this).closest('td').find('input[name="cost"]').val(correspondingCost);
                        }else{
                            $(this).closest('td').find('input[name="cost"]').val('');
                        }

                    }
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

                });

            });
        });
    </script>
@endpush
