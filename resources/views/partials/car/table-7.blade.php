@push('css')
    <!-- FilePond CSS -->
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet"/>
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
          rel="stylesheet"/>

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
            <th style="max-width: 200px">Payment Info</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($cars as $index => $car)
            @if ($car->title == 'yes' && $car->title_delivery == 'no')
                <script>
                    var isEmpty = true;
                </script>
            @elseif(!$car->getMedia('images')->isNotEmpty())
                <script>
                    var isEmpty = true;
                </script>
            @else
                <script>
                    var isEmpty = false;
                </script>
            @endif
            <tr>

                <td>
                    {{ $car->id }}
                </td>

                <td class="car_info"> @include('partials.car.table_content-parts.car-info') </td>
                <form action="{{ route('car.listupdate', $car->id) }}" method="POST" enctype="multipart/form-data">
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
                        {{ $car->pickup_dates }}
                    </td>
                    <td>
                        <label style="margin: 0;padding:0">T/status</label>
                        <br>
                        <span> {{ $car->title }}</span>

                        <label class="mt-2" for="title_delivery">T/delivery</label>
                        <select name="title_delivery" data-car-id="{{ $car->id }}"
                                class="form-control title_delivery {{ $car->title == 'yes' && $car->title_delivery == 'no' ? 'error' : '' }}"
                                id="title_delivery" required>
                            <option value=""></option>
                            <option value="yes" {{ $car->title_delivery == 'yes' ? 'selected' : '' }}>YES
                            </option>
                            <option value="no" {{ $car->title_delivery == 'no' ? 'selected' : '' }}>NO</option>
                        </select>
                    </td>

                    <td>
{{--                        upload car and bol images html--}}
                        @include('partials.car.table_content-parts.carAndBol_images')
                    </td>

                    <td style="max-width: 200px">
                        <label for="payment_method">Payment method</label>
                        <select name="payment_method" id="payment_method" class="form-control" required>
                            <option value=""></option>
                            <option value="zelle" {{ $car->payment_method == 'zelle' ? 'selected' : '' }}>Zelle
                            </option>
                            <option value="check" {{ $car->payment_method == 'check' ? 'selected' : '' }}>Check
                            </option>
                            <option value="bank" {{ $car->payment_method == 'bank' ? 'selected' : '' }}>Bank
                                transfer
                            </option>
                        </select>

                        <label for="payment_company">Receiver Name</label>
                        <input type="text" value="{{ $car->payment_company }}" placeholder="Receiver Name"
                               name="payment_company" id="payment_company" class="mt-1 form-control" required>

                        <label for="payment_address">Payment Address</label>
                        <input type="text" value="{{ $car->payment_address }}" name="payment_address"
                               id="payment_address" class="form-control" required>

                        <div class="record-row">
                            <label for="payment_photo_1">Recipte</label>

                            @if (!empty($car->payment_photo))
                                <a href="{{ Storage::url($car->payment_photo) }}" target="_blank" class="mr-3">

                                    <img src="{{ Storage::url($car->payment_photo) }}"
                                         style="max-width: 50px;max-height: 50px"
                                         alt="Recipte">
                                </a>
                                <a href="{{route('car.paymentImage.delete', $car->id)}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path fill="#ce0909"
                                              d="M7 21q-.825 0-1.412-.587T5 19V6q-.425 0-.712-.288T4 5t.288-.712T5 4h4q0-.425.288-.712T10 3h4q.425 0 .713.288T15 4h4q.425 0 .713.288T20 5t-.288.713T19 6v13q0 .825-.587 1.413T17 21zM17 6H7v13h10zm-7 11q.425 0 .713-.288T11 16V9q0-.425-.288-.712T10 8t-.712.288T9 9v7q0 .425.288.713T10 17m4 0q.425 0 .713-.288T15 16V9q0-.425-.288-.712T14 8t-.712.288T13 9v7q0 .425.288.713T14 17M7 6v13z"/>
                                    </svg>
                                </a>
                            @endif

                            <img id="preview_{{ $car->id }}" src="" alt="Image preview"
                                 style="display:none; width: 150px;">


                        </div>

                    </td>
                </form>
                    <td>
                        @include('partials.car.table_content-parts.edit-modal')

                        <strong>Create:</strong> {{ $car->created_at->format('d.m.y') }} <br>
                        <strong>Update:</strong> {{ $car->updated_at->format('d.m.y') }} <br>

                    </td>


            </tr>
        @endforeach

        </tbody>
    </table>
</div>

@push('js')
    <!-- FilePond JS -->
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>

    <!-- Plugins for image preview and file type validation -->
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.min.js"></script>
    <script
            src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js">
    </script>
    <script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.js"></script>



    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


{{--    upload script for car images and bl images--}}
    @include('partials.car.table_content-parts.filepond-script')

    <script type="text/javascript">
        $('.title_delivery').on('change', function (e) {

            var title = $(this).val(); // Get the user ID from the hidden input
            var carid = $(this).data('car-id'); // Get the user ID from the hidden input
            console.log($(this).data('car-id'));
            $.ajax({
                url: '{{ route('car.updateByid') }}',
                type: 'POST', // HTTP method for deletion
                data: {
                    id: carid,
                    title_delivery: title,
                    onlytitl_delivery: true
                }, // Send the serialized form data (including the CSRF token)
                success: function (response) {
                    location.reload();

                },
                error: function (xhr) {
                    location.reload();
                }
            });
        });





        $(function () {

            if (isEmpty) {
                // $('.buttonexport .btn.btn-primary').addClass('btn-danger');
                $('.buttonexport .btn.btn-primary');
            } else {
                $('.buttonexport .btn.btn-primary').removeClass('btn-danger');
            }

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

                    // Get the corresponding submit button for the current daterange
                    let submitBtn = $('#submit-btn-' + recordId);


                });


            });
        });
    </script>
@endpush
