@extends('layouts.app')
@section('content')
    @push('css')
    @endpush
@section('body-class', 'hold-transition sidebar-mini')

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
                <i class="fa fa-envelope"></i>
            </div>
            <div class="header-title">
                <h1>Port Emails</h1>
                <small>List of emails</small>
            </div>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-lg-12 pinpin">
                    <div class="card lobicard" id="lobicard-custom-control" data-sortable="true">

                        <div class="card-body">
                            <!-- Plugin content:powerpoint,txt,pdf,png,word,xl -->
                            <div class="btn-group d-flex" role="group">
                                <div class="buttonexport">
                                    <a href="#" class="btn btn-add" data-toggle="modal"
                                        data-target="#addportemailModal"><i class="fa fa-plus"></i> Add Port Email</a>
                                </div>
                            </div>

                            <!-- ./Plugin content:powerpoint,txt,pdf,png,word,xl -->
                            <div class="table-responsive">
                                <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
                                    <thead class="back_table_color">
                                        <tr class="info">
                                            <th>Email</th>
                                            <th>Port</th>
                                            <th>Created at</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($port_emails as $portemail)
                                            <tr>
                                                <td>{{ $portemail->email }}</td>
                                                <td><span
                                                        class="label-custom label label-default">{{ $portemail->port->name }}</span>
                                                </td>
                                                <td>
                                                    {{ $portemail->created_at }}
                                                </td>
                                                <td>
                                                    <button type="button" class="btn-edit-portemail btn btn-add btn-sm"
                                                        data-toggle="modal" data-portemail-id="{{ $portemail->id }}"
                                                        data-target="#update"><i class="fa fa-pencil"></i></button>

                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-toggle="modal" data-target="#deleteportemailModal"
                                                        data-portemail-id="{{ $portemail->id }}">
                                                        <i class="fa fa-trash-o"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- portemail Modal1 -->
            <!-- Single Update portemail Modal -->
            <div class="modal fade" id="update" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header modal-header-primary">
                            <h3><i class="fa fa-plus m-r-5"></i> Update portemail</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="updateportemailForm" class="form-horizontal">
                                        @csrf
                                        <input type="hidden" id="portemailId" name="id">
                                        <!-- Hidden field for portemail ID -->
                                        <div class="row">
                                            <!-- Text input for portemail Name -->
                                            <div class="col-md-6 form-group">
                                                <label class="control-label">Name</label>
                                                <input type="email" id="email" name="email" placeholder="Email"
                                                    class="form-control" required>
                                            </div>



                                            <!-- Text input for Role/Type -->
                                            <div class="col-md-6 form-group">
                                                <label class="control-label">Port</label>
                                                <select id="port_email_id" name="port_email_id" class="form-control">
                                                    <option value=""></option>
                                                    @foreach ($ports as $port)
                                                        <option value="{{ $port->id }}">{{ $port->name }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                            <div class="col-md-12 form-group portemail-form-group">
                                                <div class="float-right">
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-add btn-sm">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            <!-- /.modal -->

            <!-- Modal -->
            <!-- portemail Modal1 -->
            <!-- Add New portemail Modal -->
            <div class="modal fade" id="addportemailModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header modal-header-primary">
                            <h3><i class="fa fa-plus m-r-5"></i> Add New Email</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <form id="addportemailForm" class="form-horizontal" method="POST"
                                enctype="multipart/form-data">
                                @csrf <!-- CSRF token -->
                                <!-- portemail Name -->
                                <div class="row">
                                    <!-- Text input for portemail Name -->
                                    <div class="col-md-6 form-group">
                                        <label class="control-label">Email</label>
                                        <input type="email" id="email" name="email" placeholder="Email"
                                            class="form-control" required>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="control-label">Port</label>
                                        <select id="port_email_id" name="port_email_id" class="form-control"
                                            required>
                                            <option value=""></option>
                                            @foreach ($ports as $port)
                                                <option value="{{ $port->id }}">{{ $port->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-md-12 form-group">
                                        <button type="submit" class="btn btn-add">Add</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->
            <!-- Modal -->
            <!-- Delete portemail Modal -->
            <div class="modal fade" id="deleteportemailModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header modal-header-primary">
                            <h3><i class="fa fa-portemail m-r-5"></i> Delete portemail</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this portemail?</p>
                            <form id="deleteportemailForm">
                                @csrf <!-- CSRF token for security -->
                                <input type="hidden" id="deleteportemailId" name="portemail_id">
                                <!-- Hidden field to hold the portemail ID -->
                                <div class="form-group portemail-form-group">
                                    <div class="float-right">
                                        <button type="button" class="btn btn-danger btn-sm"
                                            data-dismiss="modal">NO</button>
                                        <button type="submit" class="btn btn-add btn-sm">YES</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger float-left"
                                data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->
        </section>
        <!-- /.content -->
    </div>


    @include('partials.footer')

    @push('js')
        <script>
            function openEditModal(portemailId) {
                // Find the portemail data from the server or the existing data (you can also use AJAX here)
                const portemail = @json($port_emails).find(u => u.id === portemailId);

                // Populate the form fields in the modal
                $('#portemailId').val(portemail.id);
                $('#email').val(portemail.email);
                $('#port_email_id').val(portemail.port);

                // Show the modal
                $('#update').modal('show');
            }

            // Attach click event to edit buttons
            $('.btn-edit-portemail').on('click', function() {
                const portemailId = $(this).data('portemail-id'); // Get portemail ID from the button
                openEditModal(portemailId); // Call function to open modal with portemail data
            });

            // Set up the CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Handle form submission (you can use AJAX to submit the form)
            $('#updateportemailForm').on('submit', function(e) {
                e.preventDefault();
                const portemailId = $('#portemailId').val();
                const formData = new FormData(this); // Get form data

                // Clear previous error messages
                $('.text-danger').remove();

                // Make an AJAX request to update the portemail (adjust the URL and method accordingly)
                $.ajax({
                    url: `/dashboard/portemails/${portemailId}`, // Your update URL
                    method: 'POST', // or 'PUT' based on your route
                    data: formData,
                    dataType: "JSON",
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert('portemail updated successfully');
                        $('#update').modal('hide');
                        location.reload(); // Optionally reload the page to see the changes
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) { // Validation errors
                            let errors = xhr.responseJSON.errors;
                            // Loop through errors and display them above the inputs
                            $.each(errors, function(key, value) {
                                console.log(key)

                                let inputField = $(
                                    `#${key}`); // Find the input field with the error by ID
                                inputField.after(
                                    `<span class="text-danger">${value[0]}</span>`
                                ); // Display the error
                            });
                        } else {
                            alert('Something went wrong.');
                        }
                    }

                });
            });

            $('#addportemailForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                const formData = new FormData(this); // Create FormData object from the form

                // Clear previous error messages
                $('.text-danger').remove();

                $.ajax({
                    url: '/dashboard/portemails', // The URL to add a new portemail (adjust as necessary)
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert(response.message); // Show success message
                        $('#addportemailModal').modal('hide'); // Hide the modal
                        location.reload(); // Reload the page to show the new portemail
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) { // Validation error
                            let errors = xhr.responseJSON.errors;

                            // Loop through errors and display them above the inputs
                            $.each(errors, function(key, value) {
                                let inputField = $(
                                    `#add_${key}`); // Find the input field with the error
                                inputField.before(
                                    `<span class="text-danger">${value[0]}</span>`
                                ); // Display the error
                            });
                        } else {
                            alert('Something went wrong.');
                        }
                    }
                });
            });

            // Open delete modal and set portemail ID
            $('#deleteportemailModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var portemailId = button.data('portemail-id'); // Extract portemail ID from data-* attribute
                var modal = $(this);

                // Set the portemail ID in the hidden input field in the form
                modal.find('#deleteportemailId').val(portemailId);
            });

            // Handle the form submission for deleting the portemail
            $('#deleteportemailForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                var portemailId = $('#deleteportemailId').val(); // Get the portemail ID from the hidden input
                var formData = $(this).serialize(); // Serialize form data for submission

                $.ajax({
                    url: `/dashboard/portemails/${portemailId}`, // URL for deleting the portemail
                    type: 'DELETE', // HTTP method for deletion
                    data: formData, // Send the serialized form data (including the CSRF token)
                    success: function(response) {
                        alert(response.message); // Show success message
                        $('#deleteportemailModal').modal('hide'); // Hide the modal
                        location.reload(); // Optionally reload the page to update the portemail list
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
