@extends('layouts.app')
@section('content')
    @push('css')
        <style>
            .table-responsive td {
                align-content: center;
            }

            .buttonexport {
                padding: 5px 15px;
            }
        </style>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css"/>
        <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
    @endpush
    @section('body-class', 'hold-transition sidebar-mini sidebar-collapse')

    <!--preloader-->
    <div id="preloader">
        <div id="status"></div>
    </div>

    <!-- Site wrapper -->
    <div class="wrapper">
        @include('partials.header')
        <!-- =============================================== -->
        <!-- Left side column. contains the sidebar -->
        @include('partials.aside')
        <!-- =============================================== -->

        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="header-icon">
                    <i class="fa fa-car"></i>
                </div>
                <div class="header-title">
                    <h1>Car Gallery</h1>
                </div>
            </section>
            <div class="tz-gallery">
                <div class="row">
                    @foreach ($images as $image)
                        <div class=" col-sm-12 col-md-2 ">
                                <a style="display: block; width: 250px; height: 250px; overflow: hidden;" class="glightbox3 d-flex justify-content-center" href="{{ $image->getUrl() }}">
                                    <img style="width: 100%; height: 100%; object-fit: cover;" src="{{ $image->getUrl() }}" alt="Bridge">
                                </a>

                            <form style="display: flex;justify-content: center!important;width:250px" action="{{route('arrived.image.delete')}}" method="post">
                                @csrf
                                <input type="hidden" name="image_id" value="{{ $image->id }}">
                                <button style="all:unset;cursor: pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24">
                                        <path fill="#b72525"
                                              d="M7 21q-.825 0-1.412-.587T5 19V6q-.425 0-.712-.288T4 5t.288-.712T5 4h4q0-.425.288-.712T10 3h4q.425 0 .713.288T15 4h4q.425 0 .713.288T20 5t-.288.713T19 6v13q0 .825-.587 1.413T17 21zM17 6H7v13h10zm-7 11q.425 0 .713-.288T11 16V9q0-.425-.288-.712T10 8t-.712.288T9 9v7q0 .425.288.713T10 17m4 0q.425 0 .713-.288T15 16V9q0-.425-.288-.712T14 8t-.712.288T13 9v7q0 .425.288.713T14 17M7 6v13z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>


        @include('partials.footer')

        @push('js')
            <script>
                var lightboxVideo = GLightbox({
                selector: '.glightbox3'
                });
            </script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const carCheckboxes = document.querySelectorAll('.car_ids');
                    const hiddenInput = document.getElementById('carids');

                    carCheckboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', function () {
                            // Collect all selected car IDs
                            const selectedCars = Array.from(carCheckboxes)
                                .filter(checkbox => checkbox.checked)
                                .map(checkbox => checkbox.value);

                            // Update the hidden input value with selected IDs (comma-separated)
                            hiddenInput.value = selectedCars.join(',');
                        });
                    });
                });

                $('.title').on('change', function (e) {
                    e.preventDefault(); // Prevent the default form submission

                    var title = $(this).val(); // Get the user ID from the hidden input
                    var carid = $(this).data('car-id'); // Get the user ID from the hidden input

                    $.ajax({
                        url: '{{ route('container.listupdate') }}',
                        type: 'POST', // HTTP method for deletion
                        data: {
                            carid: carid,
                            title: title
                        }, // Send the serialized form data (including the CSRF token)
                        success: function (response) {
                        },
                        error: function (xhr) {
                            alert('Something went wrong.'); // Handle errors
                        }
                    });
                });
            </script>



            <script>
                $('.copy').on('click', function () {
                    const text = $(this).prev('strong').find('span').text().trim();
                    navigator.clipboard.writeText(text).then(function () {
                        console.log('Copied to clipboard:', text);
                    }).catch(function (error) {
                        console.error('Failed to copy:', error);
                    });
                });
            </script>


            <script>
                $(function () {
                    var availableWarehouse = [
                        "MTL- New jersey",
                        "TRT - New Jersey"
                    ];

                    $("#warehouse").autocomplete({
                        source: availableWarehouse,
                        minLength: 0 // Set to 0 to show suggestions immediately
                    });

                    // Trigger the autocomplete suggestions on input focus (click)
                    $("#warehouse").on('focus', function () {
                        $(this).autocomplete('search', ''); // Force the dropdown to show on click/focus
                    });

                });
            </script>


            <script>
                // Set up the CSRF token for all AJAX requests
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });


                // Open delete modal and set user ID
                $('#deleteUserModal').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var userId = button.data('user-id'); // Extract user ID from data-* attribute
                    var modal = $(this);

                    // Set the user ID in the hidden input field in the form
                    modal.find('#deleteUserId').val(userId);
                });

                // Handle the form submission for deleting the user
                $('#deleteUserForm').on('submit', function (e) {
                    e.preventDefault(); // Prevent the default form submission

                    var userId = $('#deleteUserId').val(); // Get the user ID from the hidden input
                    var formData = $(this).serialize(); // Serialize form data for submission

                    $.ajax({
                        url: `/dashboard/users/${userId}`, // URL for deleting the user
                        type: 'DELETE', // HTTP method for deletion
                        data: formData, // Send the serialized form data (including the CSRF token)
                        success: function (response) {
                            alert(response.message); // Show success message
                            $('#deleteUserModal').modal('hide'); // Hide the modal
                            location.reload(); // Optionally reload the page to update the user list
                        },
                        error: function (xhr) {
                            alert('Something went wrong.'); // Handle errors
                        }
                    });
                });
            </script>
        @endpush
    </div>
@endsection
