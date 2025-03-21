@push('css')
    <!-- FilePond CSS -->
    <link href="{{asset('assets/filepond/filepond.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/filepond/filepond-plugin-image-preview.css')}}" rel="stylesheet"/>

    <style>
        .existing-images img {
            max-width: 100%;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            margin-bottom: 10px;
        }
    </style>
@endpush
<div class="table-responsive">
    <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
        <thead class="back_table_color">
        <tr class="info">
            <th>ID</th>
            <th>CAR INFO</th>
            <th>FROM-TO</th>
            <th>Price</th>
            <th>Carrier</th>
            <th>Pickup & Delivery Dates</th>
            <th>Title</th>
            <th>Photos</th>
            <th>Payment Info</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($cars as $index => $car)
            <tr>
                <td>
                    {{ $car->id }}
                </td>

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
                    <td>
                        <input type="text" data-record-id="{{ $car->id }}" value="{{ $car->pickup_dates }}"
                               name="pickup_dates" class="form-control daterange"
                               style="width: 185px;display:inline-block"/>
                    </td>
                    <td>
                        <label style="margin: 0;padding:0">T/status</label>
                        <br>
                        <span> {{ $car->title }}</span>
                        <br>

                        <label class="mt-2" for="title_delivery">T/delivery</label>
                        <select name="title_delivery" style="padding-left: 5px"
                                class="form-control {{ $car->title == 'yes' && $car->title_delivery == 'no' ? 'error' : '' }}"
                                id="title_delivery" required pattern=".*\S.*"
                                title="This field cannot be empty or contain only spaces">
                            <option value=""></option>
                            <option  value="yes" {{ $car->title_delivery == 'yes' ? 'selected' : '' }}>YES</option>
                            <option value="no" {{ $car->title_delivery == 'no' ? 'selected' : '' }}>NO</option>
                        </select>
                    </td>
                    <td style="display: flex;flex-direction: column;gap: 10px">
                        {{--                        upload car and bol images html--}}
                        @include('partials.car.table_content-parts.carAndBol_images')
                    </td>

                    <td>
                        <select aria-placeholder="" name="payment_method" id="payment_method" class="form-control"
                                required>
                            <option selected value="">Payment method</option>

                            <option value="zelle" {{ $car->payment_method == 'zelle' ? 'selected' : '' }}>Zelle
                            </option>
                            <option value="check" {{ $car->payment_method == 'check' ? 'selected' : '' }}>Check
                            </option>
                            <option value="bank" {{ $car->payment_method == 'bank' ? 'selected' : '' }}>Bank
                                transfer
                            </option>
                        </select>
                        <input type="text" value="{{ $car->payment_company }}" placeholder="Receiver Name"
                               name="payment_company" id="payment_company" class="mt-1 form-control" required
                               pattern=".*\S.*" title="This field cannot be empty or contain only spaces">
                        <input type="text" value="{{ $car->payment_address }}" placeholder="Payment Address"
                               name="payment_address" id="payment_address" class="form-control mt-1" required
                               pattern=".*\S.*" title="This field cannot be empty or contain only spaces">

                    </td>

                    <td>
                        <button type="submit" id="submit-btn-{{ $car->id }}" class="btn btn-success btn-sm"

                                @if(!$car->getMedia('bl_images')->isNotEmpty())
                                    onclick="
                                    event.preventDefault();
                                    alert('Please upload BOL Images')
                                  "
                                @endif
                        >
                                Next
                        </button>

                        @include('partials.car.table_content-parts.edit-modal')

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

    <!-- FilePond JS -->
    <script src="{{asset('assets/filepond/filepond.js')}}"></script>

    <!-- Plugins for image preview and file type validation -->
    <script src="{{asset('assets/filepond/filepond-plugin-image-preview.js')}}"></script>
    <script src="{{asset('assets/filepond/filepond-plugin-file-validate-type.js')}}"></script>
    <script src="{{asset('assets/filepond/filepond-plugin-file-validate-size.js')}}"></script>
    <script src="{{asset('assets/filepond/filepond-plugin-file-encode.js')}}"></script>
    <script src="{{asset('assets/filepond/filepond-plugin-image-exif-orientation.js')}}">
    </script>
    <script src="{{asset('assets/filepond/filepond-plugin-image-edit.js')}}"></script>


    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


    {{--    upload script for car images and bl images--}}
    @include('partials.car.table_content-parts.filepond-script')

    <script type="text/javascript">


        $(function () {

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
                    let endDate = end.startOf(
                        'day'); // Ensure the selected end date is also at 00:00:00

                    // Get the corresponding submit button for the current daterange
                    let submitBtn = $('#submit-btn-' + recordId);

                    console.log(today);
                    console.log(endDate.isBefore(today)); // Log if the end date is before today
                    console.log(endDate);

                    // Disable the button if the selected delivery date is in the future (endDate is after today)
                    if (endDate.isAfter(today)) {
                        submitBtn.attr('disabled',
                            true
                        ); // Disable the submit button because delivery date is in the future
                    } else {
                        submitBtn.attr('disabled',
                            false
                        ); // Enable the submit button if the delivery date is today or earlier
                    }
                });

                // On page load, check the current value of the date range picker
                let initialEndDate = daterangeInput.data('daterangepicker').endDate.startOf(
                    'day'); // Get the initial end date and reset time to 00:00:00
                let today = moment().startOf('day'); // Get today's date at 00:00:00


                // Get the corresponding submit button for the current daterange
                let submitBtn = $('#submit-btn-' + recordId);

                // Compare the initial start date with today's date
                if (initialEndDate.isAfter(today)) {
                    // If the start date is before today, disable the submit button
                    submitBtn.attr('disabled', true);
                    // $('.buttonexport .btn.btn-primary').addClass('btn-danger');
                    $('.buttonexport .btn.btn-primary');

                } else {
                    // If the start date is today or after today, enable the submit button
                    submitBtn.attr('disabled', false);
                    $('.buttonexport .btn.btn-primary').removeClass('btn-danger');

                }
            });
        });
    </script>
@endpush
