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
            @hasanyrole('Admin|Developer')
            <th>Change Status</th>
            @endhasanyrole
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
        @foreach ($cars as $car)
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
                    {{ $car->id }}</td>
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
                        <div style="display: flex;flex-direction: column;gap: 10px; justify-content: center;height: 100%!important">
                            <!-- Button to open the modal -->
                            <button type="button"
                                    class="btn btn-sm {{ !$car->getMedia('images')->isNotEmpty() ? 'btn-danger' : 'btn-success' }}"
                                    data-toggle="modal" data-target="#imageUploadModal-{{ $car->id }}"
                                    data-record-id="{{ $car->id }}">
                                Upload Car Photo
                            </button>

                            <button type="button"
                                    class="btn  btn-sm  {{ !$car->getMedia('bl_images')->isNotEmpty() ? 'btn-danger' : 'btn-success' }}"
                                    data-toggle="modal"
                                    data-target="#blimage{{ $car->id }}">
                                Upload BL Photo
                            </button>
                            <!-- Modal  BL Images -->

                            <div class="modal fade" id="blimage{{ $car->id }}" tabindex="-1"
                                 role="dialog" aria-labelledby="blimage" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">BL Images</h5>
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
                                            <input type="file" data-car_id="{{ $car->id }}" class="filepond2"
                                                   id="filepond" name="images[]" multiple>

                                            <!-- Section to display existing images -->
                                            @if ($car->getMedia('bl_images')->isNotEmpty())
                                                <div class="existing-images mt-4">
                                                    <h6>Existing Images</h6>
                                                    <div class="row mt-2">
                                                        @foreach ($car->getMedia('bl_images') as $image)
                                                            <div class="col-md-3">
                                                                <div class="image-thumbnail mb-2">
                                                                    <img src="{{ $image->getUrl() }}" class="img-fluid"
                                                                         style="max-height: 100px; object-fit: cover;"
                                                                         alt="Uploaded Image">
                                                                    <button style="margin-left: 20px"
                                                                            hx-post="{{route('car.image.delete')}}"
                                                                            hx-vals='{"image_id": "{{ $image->id }}", "_token": "{{ csrf_token() }}" }'
                                                                            class="btn btn-sm"
                                                                            type="button"
                                                                            onclick="setTimeout( ()=> { this.parentElement.remove() }, 200) ">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                             height="24" viewBox="0 0 24 24">
                                                                            <path fill="#ce0909"
                                                                                  d="M7 21q-.825 0-1.412-.587T5 19V6q-.425 0-.712-.288T4 5t.288-.712T5 4h4q0-.425.288-.712T10 3h4q.425 0 .713.288T15 4h4q.425 0 .713.288T20 5t-.288.713T19 6v13q0 .825-.587 1.413T17 21zM17 6H7v13h10zm-7 11q.425 0 .713-.288T11 16V9q0-.425-.288-.712T10 8t-.712.288T9 9v7q0 .425.288.713T10 17m4 0q.425 0 .713-.288T15 16V9q0-.425-.288-.712T14 8t-.712.288T13 9v7q0 .425.288.713T14 17M7 6v13z"/>
                                                                        </svg>
                                                                    </button>
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
                            <!-- Modal for FilePond upload -->
                            <div class="modal fade" id="imageUploadModal-{{ $car->id }}" tabindex="-1"
                                 role="dialog" aria-labelledby="imageUploadModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Car Images</h5>
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
                                            <input type="file" data-car_id="{{ $car->id }}" class="filepond"
                                                   id="filepond" name="images[]" multiple>


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
                                                                    <button style="margin-left: 20px"
                                                                            hx-post="{{route('car.image.delete')}}"
                                                                            hx-vals='{"image_id": "{{ $image->id }}", "_token": "{{ csrf_token() }}" }'
                                                                            class="btn btn-sm"
                                                                            type="button"
                                                                            onclick="setTimeout( ()=> { this.parentElement.remove() }, 200) ">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                             height="24" viewBox="0 0 24 24">
                                                                            <path fill="#ce0909"
                                                                                  d="M7 21q-.825 0-1.412-.587T5 19V6q-.425 0-.712-.288T4 5t.288-.712T5 4h4q0-.425.288-.712T10 3h4q.425 0 .713.288T15 4h4q.425 0 .713.288T20 5t-.288.713T19 6v13q0 .825-.587 1.413T17 21zM17 6H7v13h10zm-7 11q.425 0 .713-.288T11 16V9q0-.425-.288-.712T10 8t-.712.288T9 9v7q0 .425.288.713T10 17m4 0q.425 0 .713-.288T15 16V9q0-.425-.288-.712T14 8t-.712.288T13 9v7q0 .425.288.713T14 17M7 6v13z"/>
                                                                        </svg>
                                                                    </button>
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

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            // Register FilePond plugins
            FilePond.registerPlugin(
                FilePondPluginImagePreview, // for showing image preview
                FilePondPluginFileValidateType, // for validating file types
                FilePondPluginFileValidateSize // for validating file size
            );

            // Select all file input elements
            const inputElements = document.querySelectorAll('.filepond');

            // Iterate over each input element and initialize FilePond
            inputElements.forEach(function (inputElement) {
                // Get the car_id from each input element
                const carId = inputElement.getAttribute('data-car_id');
                const submitBtn = $('#submit-btn-' + carId);

                // Initialize FilePond for each input element
                const pond = FilePond.create(inputElement, {
                    allowMultiple: true, // Allow multiple file uploads
                    maxFiles: 15, // Limit to 15 files
                    acceptedFileTypes: ['image/*'], // Only accept image files
                    maxFileSize: '10MB', // Maximum file size of 10MB

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

                // Disable submit button initially

            });


            //  Upload Bl images
            const inputElements2 = document.querySelectorAll('.filepond2');
            // Iterate over each input element and initialize FilePond
            inputElements2.forEach(function (inputElement2) {
                // Get the car_id from each input element
                const carId = inputElement2.getAttribute('data-car_id');
                const submitBtn = $('#submit-btn-' + carId);

                // Initialize FilePond for each input element
                const pond = FilePond.create(inputElement2, {
                    allowMultiple: true, // Allow multiple file uploads
                    maxFiles: 15, // Limit to 15 files
                    acceptedFileTypes: ['image/*'], // Only accept image files
                    maxFileSize: '10MB', // Maximum file size of 10MB

                    // FilePond server configuration
                    server: {
                        process: {
                            url: '{{ route('upload.bl.images') }}',
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


                // Disable submit button initially

                // Enable submit button only when files are added
                // pond.on('addfile', (error, file) => {
                //     if (!error) {
                //         submitBtn.prop('disabled', false);
                //     }
                // });

                // // Disable submit button when no files are present
                // pond.on('removefile', () => {
                //     if (pond.getFiles().length === 0) {
                //         submitBtn.prop('disabled', true);
                //     }
                // });
            });


        });


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
