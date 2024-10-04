@push('css')
    <!-- FilePond CSS -->
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />

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
                <th>CAR</th>
                <th>From</th>
                
                <th>Price</th>
                <th>Carrier</th>
                <th>
                    <a
                        href="{{ request()->fullUrlWithQuery(['sort' => 'customers.contact_name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                        Dealer
                    </a>
                </th>
                <th>Pickup & Delivery Dates</th>
                <th>Title delivery</th>
                <th>Photos</th>
                <th>Payment Information</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cars as $car)
                <tr>

                    <td>
                        {{ $car->id }}</td>
                    <td class="car_info">                         @include('partials.car.table_content-parts.car-info')                     </td>
                    <form action="{{ route('car.listupdate', $car->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="status"
                            value="{{ isset($_GET['status']) ? $_GET['status'] : 'for-Dispatch' }}">
                       <td>@include('partials.car.table_content-parts.field-from')</td>
                        
                        <td>
                            <div class="d-flex" style="gap: 5px"><strong>Price: </strong>
                                ${{ $car->internal_shipping }}</div>
                            <div class="d-flex" style="gap: 5px"><strong>Storage: </strong> ${{ $car->storage_cost }}
                            </div>
                            <div class="d-flex" style="gap: 5px"><strong>Sum: </strong>
                                ${{ $car->internal_shipping + $car->storage_cost }}</div>
                        </td>
                        <td>
                            <label for="company_name">Company name:</label><br>
                            {{ $car->company_name }}
                            <br>
                            <label for="contact_info">Contact info:</label><br>
                            {{ $car->contact_info }}
                        </td>
                        <td>
                            @if (!empty($car->customer))
                                {{ $car->customer->contact_name }}
                            @endif
                        </td>
                        <td>
                            {{ $car->pickup_dates }}
                        </td>
                        <td>
                            {{ $car->title_delivery }}
                        </td>
                        <td>
                            <!-- Button to open the modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#imageUploadModal" data-record-id="{{ $car->id }}">
                                Images
                            </button>

                            <!-- Modal for FilePond upload -->
                            <div class="modal fade" id="imageUploadModal" tabindex="-1" role="dialog"
                                aria-labelledby="imageUploadModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Upload Images</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Hidden input to pass the car ID -->
                                            <input type="hidden" value="{{ $car->id }}" name="car_id"
                                                id="recordIdInput">

                                            <!-- FilePond input for multiple image uploads -->
                                            <input type="file" style="display: none"
                                                data-car_id="{{ $car->id }}" id="filepond" name="images[]"
                                                multiple>

                                            <!-- Section to display existing images -->
                                            @if ($car->getMedia('images')->isNotEmpty())
                                                <div class="existing-images mt-4">
                                                    <h6>Existing Images</h6>
                                                    <div class="row mt-2">
                                                        @foreach ($car->getMedia('images') as $image)
                                                            <div class="col-md-3">
                                                                <div class="image-thumbnail mb-2">
                                                                    <img src="{{ $image->getUrl() }}" class="img-fluid"
                                                                        style="max-height: 100px; object-fit: cover;"
                                                                        alt="Uploaded Image">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

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
                                    transfer</option>
                            </select>

                            <label for="payment_address">Payment info</label>
                            <input type="text" value="{{ $car->payment_address }}" name="payment_address"
                                id="payment_address" class="form-control" required>

                            <div class="record-row">
                                <label for="payment_photo_1">Recipte</label>
                                <input type="file" id="payment_photo_1" name="payment_photo" accept="image/*"
                                    onchange="previewImage(event, 'preview_{{ $car->id }}')">
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

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            // Register FilePond plugins
            FilePond.registerPlugin(
                FilePondPluginImagePreview, // for showing image preview
                FilePondPluginFileValidateType, // for validating file types
                FilePondPluginFileValidateSize // for validating file size
            );

            // Turn input element into a pond
            // Get the car_id from the input element
            const inputElement = document.querySelector('input[type="file"]');
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

        });





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


                });


            });
        });
    </script>
@endpush
