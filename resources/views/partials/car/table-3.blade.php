<div class="table-responsive">
    <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
        <thead class="back_table_color">
        <tr class="info">
            <th>ID</th>
            @hasanyrole('Admin|Developer')
            <th>Change Status</th>
            @endhasanyrole
            <th>CAR INFO</th>
            <th>FROM-TO</th>

            <th>Price</th>
            <th>Carrier</th>

            <th>Pickup & Delivery Dates</th>
            <th>T/status</th>
            <th>Storage</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($cars as $car)

            <form action="{{ route('car.listupdate', $car->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status"
                       value="{{ isset($_GET['status']) ? $_GET['status'] : 'for-Dispatch' }}">
                <tr>

                    <td>
                        {{ $car->id }}
                    </td>
                    @hasanyrole('Admin|Developer')
                    <td>
                        <div class="col-12">
                            <label>CAR Status</label>
                            <select name="status" class="form-control" id="customer_id"
                                    hx-post="{{route('car.change.status')}}"
                                    hx-vals='{"car_id": "{{ $car->id }}", "_token": "{{ csrf_token() }}" }'
                                    onchange="setTimeout(() => { window.location.reload() }, 200)"
                            >
                                @foreach($statuses as $statusindex => $status)
                                    <option {{ $car->car_status_id == $status->id ? 'selected' : ''}} value="{{$status->id}}"> {{ $status->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                    @endhasanyrole
                    <td class="car_info"> @include('partials.car.table_content-parts.car-info') </td>


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
                               name="pickup_dates" class="form-control daterange"/>
                    </td>
                    <td>{{ $car->title }}</td>
                    <td>
                        <label for="company_name">Storage:</label><br>
                        <input type="text" name="storage" class="form-control storage" id="storage" required pattern=".*\S.*" title="This field cannot be empty or contain only spaces">
                        <br>
                        <label for="contact_info">Cost:</label><br>
                        <input type="number" class="form-control" name="cost" required pattern=".*\S.*" title="This field cannot be empty or contain only spaces">
                    </td>
                    <td>
                        <button type="submit" id="submit-btn-{{ $car->id }}"
                                class="btn btn-success btn-sm">
                            Next
                        </button>


                        <br>
                        <br>
                        <strong>Create:</strong> {{ $car->created_at->format('d.m.y') }} <br>
                        <strong>Update:</strong> {{ $car->updated_at->format('d.m.y') }} <br>

                    </td>

                </tr>
            </form>
        @endforeach

        </tbody>
    </table>
</div>

@push('js')
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script type="text/javascript">
        $(function () {

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
                    select: function (event, ui) {
                        let selectedWord = ui.item.value; // Get the selected word
                        let correspondingCost = 0

                        // Set the value of the .cost input
                        if (selectedWord == 'No storage') {
                            $(this).closest('td').find('input[name="cost"]').val(correspondingCost);
                        } else {
                            $(this).closest('td').find('input[name="cost"]').val('');
                        }

                    }
                }).focus(function () {
                    // Force the dropdown to show when the input gains focus
                    $(this).autocomplete("search", "");
                });
            }

            // Initialize autocomplete on page load
            $(document).ready(function () {
                initializeAutocomplete();
            });


            // Initialize date range picker for each .daterange input
            $('.daterange').each(function () {
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
                }, function (start, end, label) {
                    let today = moment().startOf('day'); // Get today's date at 00:00:00
                    // Get the corresponding submit button for the current daterange
                    let submitBtn = $('#submit-btn-' + recordId);

                });

            });
        });
    </script>
@endpush
