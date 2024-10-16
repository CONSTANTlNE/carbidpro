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
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://rawgit.com/LeshikJanz/libraries/master/Bootstrap/baguetteBox.min.css">
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
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
                    <div class="col-sm-12 col-md-2">
                        <a class="lightbox" href="{{ $image->getUrl() }}">
                            <img src="{{ $image->getUrl() }}" alt="Bridge">
                        </a>
                    </div>
                @endforeach


            </div>

        </div>

    </div>


    @include('partials.footer')

    @push('js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.8.1/baguetteBox.min.js"></script>
        <script>
            baguetteBox.run('.tz-gallery');
        </script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const carCheckboxes = document.querySelectorAll('.car_ids');
                const hiddenInput = document.getElementById('carids');

                carCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        // Collect all selected car IDs
                        const selectedCars = Array.from(carCheckboxes)
                            .filter(checkbox => checkbox.checked)
                            .map(checkbox => checkbox.value);

                        // Update the hidden input value with selected IDs (comma-separated)
                        hiddenInput.value = selectedCars.join(',');
                    });
                });
            });

            $('.title').on('change', function(e) {
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
                    success: function(response) {},
                    error: function(xhr) {
                        alert('Something went wrong.'); // Handle errors
                    }
                });
            });
        </script>



        <script>
            $('.copy').on('click', function() {
                const text = $(this).prev('strong').find('span').text().trim();
                navigator.clipboard.writeText(text).then(function() {
                    console.log('Copied to clipboard:', text);
                }).catch(function(error) {
                    console.error('Failed to copy:', error);
                });
            });
        </script>


        <script>
            $(function() {
                var availableWarehouse = [
                    "MTL- New jersey",
                    "TRT - New Jersey"
                ];

                $("#warehouse").autocomplete({
                    source: availableWarehouse,
                    minLength: 0 // Set to 0 to show suggestions immediately
                });

                // Trigger the autocomplete suggestions on input focus (click)
                $("#warehouse").on('focus', function() {
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
            $('#deleteUserModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var userId = button.data('user-id'); // Extract user ID from data-* attribute
                var modal = $(this);

                // Set the user ID in the hidden input field in the form
                modal.find('#deleteUserId').val(userId);
            });

            // Handle the form submission for deleting the user
            $('#deleteUserForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                var userId = $('#deleteUserId').val(); // Get the user ID from the hidden input
                var formData = $(this).serialize(); // Serialize form data for submission

                $.ajax({
                    url: `/dashboard/users/${userId}`, // URL for deleting the user
                    type: 'DELETE', // HTTP method for deletion
                    data: formData, // Send the serialized form data (including the CSRF token)
                    success: function(response) {
                        alert(response.message); // Show success message
                        $('#deleteUserModal').modal('hide'); // Hide the modal
                        location.reload(); // Optionally reload the page to update the user list
                    },
                    error: function(xhr) {
                        alert('Something went wrong.'); // Handle errors
                    }
                });
            });
        </script>
    @endpush
</div>
@endsection
