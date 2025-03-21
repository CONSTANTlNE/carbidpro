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

                <td> {{ $car->id }}</td>

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
                        <label for="company_name">Receiver Name:</label><br>
                        {{ $car->company_name }}
                        <br>
                        <label for="contact_info">Contact info:</label><br>
                        {{ $car->contact_info }}
                    </td>

                    <td>
                        <input type="text" data-record-id="{{ $car->id }}" value="{{ $car->pickup_dates }}"
                               name="pickup_dates" class="form-control daterange"/>
                    </td>
                    <td>
                        <label style="margin: 0;padding:0">T/status</label>
                        <br>
                        <span> {{ $car->title }}</span>

                        <label class="mt-2" for="title_delivery">T/delivery</label>
                        <select name="title_delivery"
                                class="form-control {{ $car->title == 'yes' && $car->title_delivery == 'no' ? 'error' : '' }}"
                                id="title_delivery" required>
                            <option value=""></option>
                            <option value="yes" {{ $car->title_delivery == 'yes' ? 'selected' : '' }}>YES
                            </option>
                            <option value="no" {{ $car->title_delivery == 'no' ? 'selected' : '' }}>NO</option>
                        </select>
                    </td>


                    <td>
                        {{-- upload car and bol images html--}}
                        @include('partials.car.table_content-parts.carAndBol_images')
                    </td>

                    <td>
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
                            <input type="file" id="payment_photo_1" name="payment_photo"
                                   {{empty($car->payment_photo) ? 'required' : ''}}
                                   accept="image/*" onchange="previewImage(event, 'preview_{{ $car->id }}')">
                            <br>
                            <br>

                            @if (!empty($car->payment_photo))
                                <a href="{{ Storage::url($car->payment_photo) }}" target="_blank">

                                    <img src="{{ Storage::url($car->payment_photo) }}" style="max-width: 150px;"
                                         alt="Recipte">
                                </a>
                            @endif

                            <img id="preview_{{ $car->id }}" src="" alt="Image preview"
                                 style="display:none; width: 150px;">


                        </div>


                    </td>

                    <td>

                        <button type="submit" id="submit-btn-{{ $car->id }}"
                                class="btn btn-success btn-sm">
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
    <script>
        function previewImage(event, previewId) {
            const input = event.target;
            const preview = document.getElementById(previewId);

            // Ensure a file was selected
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
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

            // console.log(isEmpty);
            // if (isEmpty) {
            //     $('.buttonexport .btn.btn-primary').addClass('btn-danger');
            // } else {
            //     $('.buttonexport .btn.btn-primary').removeClass('btn-danger');
            // }

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
