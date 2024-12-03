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
                <i class="fa fa-money"></i>
            </div>
            <div class="header-title">
                <h1>Payment Reports</h1>
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
                                    <a href="#" class="btn btn-add" data-toggle="modal" data-target="#addModal"><i
                                            class="fa fa-plus"></i> Add Report</a>
                                </div>
                            </div>

                            <!-- ./Plugin content:powerpoint,txt,pdf,png,word,xl -->
                            <div class="table-responsive">
                                <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
                                    <thead class="back_table_color">
                                        <tr class="info">
                                            <th>#</th>
                                            <th>CAR</th>
                                            <th>Customer</th>
                                            <th>Left amount</th>
                                            <th>Is Approved</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payment_reports as $key => $payment_report)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $payment_report->car->make_model_year }}</td>
                                                <td>{{ $payment_report->customer->contact_name }}</td>
                                                <td>${{ $payment_report->left_amount }}</td>
                                                <td><span
                                                        class="label-{{ $payment_report->is_approved == 1 ? 'custom' : 'danger' }} label label-default">{{ $payment_report->is_approved == 1 ? 'YES' : 'NO' }}</span>
                                                </td>
                                                <td>
                                                    {{ $payment_report->created_at }}
                                                </td>
                                                <td>
                                                    <button type="button" class="btn-edit-record btn btn-add btn-sm"
                                                        data-toggle="modal" data-record-id="{{ $payment_report->id }}"
                                                        data-target="#update"><i class="fa fa-pencil"></i></button>

                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-toggle="modal" data-target="#deleteModal"
                                                        data-record-id="{{ $payment_report->id }}">
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

            <!-- User Modal1 -->
            <!-- Single Update User Modal -->
            <div class="modal fade" id="update" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header modal-header-primary">
                            <h3><i class="fa fa-plus m-r-5"></i> Update Record</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="updateRecordForm" class="form-horizontal">
                                        @csrf
                                        <input type="hidden" id="recordId" name="id">
                                        <!-- Hidden field for user ID -->
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label class="control-label">Car</label>
                                                <select id="car_id" name="car_id" class="form-control" required>
                                                    <option value=""></option>
                                                    @foreach ($cars as $car)
                                                        <option value="{{ $car->id }}">{{ $car->make_model_year }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>

                                            <div class="col-md-6 form-group">
                                                <label class="control-label">Customer</label>
                                                <select id="customer_id" name="customer_id" class="form-control"
                                                    required>
                                                    <option value=""></option>
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}">
                                                            {{ $customer->contact_name }} -
                                                            {{ $customer->email }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6 form-group">
                                                <label class="control-label">Left amount</label>
                                                <input type="text" id="left_amount" name="left_amount"
                                                    placeholder="Left amount" class="form-control" required>
                                            </div>

                                            <div class="col-md-6 form-group">
                                                <label class="control-label">Is approved ?</label>
                                                <input type="checkbox" id="is_approved" name="is_approved">
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
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            <!-- /.modal -->

            <!-- Modal -->
            <!-- User Modal1 -->
            <!-- Add New -->
            <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header modal-header-primary">
                            <h3><i class="fa fa-plus m-r-5"></i> Add New</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <form id="addForm" class="form-horizontal" method="POST"
                                enctype="multipart/form-data">
                                @csrf <!-- CSRF token -->
                                <div class="row">

                                    <div class="col-md-6 form-group">
                                        <label class="control-label">Car</label>
                                        <select id="car_id" name="car_id" class="form-control" required>
                                            <option value=""></option>
                                            @foreach ($cars as $car)
                                                <option value="{{ $car->id }}">{{ $car->make_model_year }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="control-label">Customer</label>
                                        <select id="customer_id" name="customer_id" class="form-control" required>
                                            <option value=""></option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->contact_name }} -
                                                    {{ $customer->email }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="control-label">Left amount</label>
                                        <input type="text" id="left_amount" name="left_amount"
                                            placeholder="Left amount" class="form-control" required>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label class="control-label">Is approved ?</label>
                                        <input type="checkbox" id="is_approved" name="is_approved">
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
            <!-- Delete Record Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header modal-header-primary">
                            <h3> Delete Record</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this Record?</p>
                            <form id="deleteUserForm">
                                @csrf <!-- CSRF token for security -->
                                <input type="hidden" id="deleteRecordId" name="record_id">
                                <!-- Hidden field to hold the user ID -->
                                <div class="form-group user-form-group">
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
            // Function to toggle password visibility
            $(document).on('click', '.toggle-password', function() {
                const target = $(this).data('target'); // Get the target field to toggle
                const input = $(target);
                const icon = $(this).find('i');

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text'); // Show the password
                    icon.removeClass('fa-eye').addClass('fa-eye-slash'); // Change icon
                } else {
                    input.attr('type', 'password'); // Hide the password
                    icon.removeClass('fa-eye-slash').addClass('fa-eye'); // Change icon
                }
            });


            function openEditModal(recordId) {
                // Find the user data from the server or the existing data (you can also use AJAX here)
                const record = @json($payment_reports).find(u => u.id === recordId);
                // Populate the form fields in the modal
                $('#recordId').val(record.id);
                $('#car_id').val(record.car.id);
                $('#customer_id').val(record.customer.id);
                $('#left_amount').val(record.left_amount);
                $('#is_approved').val(record.is_approved);

                // Show the modal
                $('#update').modal('show');
            }

            // Attach click event to edit buttons
            $('.btn-edit-record').on('click', function() {
                const recordId = $(this).data('record-id'); // Get user ID from the button
                openEditModal(recordId); // Call function to open modal with user data
            });

            // Set up the CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Handle form submission (you can use AJAX to submit the form)
            $('#updateRecordForm').on('submit', function(e) {
                e.preventDefault();
                const recordId = $('#recordId').val();
                const formData = new FormData(this); // Get form data

                // Clear previous error messages
                $('.text-danger').remove();

                // Make an AJAX request to update the user (adjust the URL and method accordingly)
                $.ajax({
                    url: `/dashboard/payment-reports/${recordId}`, // Your update URL
                    method: 'POST', // or 'PUT' based on your route
                    data: formData,
                    dataType: "JSON",
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert('Record updated successfully');
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

            $('#addForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                const formData = new FormData(this); // Create FormData object from the form

                // Clear previous error messages
                $('.text-danger').remove();

                $.ajax({
                    url: '/dashboard/payment-reports', // The URL to add a new user (adjust as necessary)
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert(response.message); // Show success message
                        $('#addModal').modal('hide'); // Hide the modal
                        location.reload(); // Reload the page to show the new user
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

            // Open delete modal and set user ID
            $('#deleteModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var recordId = button.data('record-id'); // Extract user ID from data-* attribute
                var modal = $(this);

                // Set the user ID in the hidden input field in the form
                modal.find('#deleteRecordId').val(recordId);
            });

            // Handle the form submission for deleting the user
            $('#deleteUserForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                var recordId = $('#deleteRecordId').val(); // Get the user ID from the hidden input
                var formData = $(this).serialize(); // Serialize form data for submission

                $.ajax({
                    url: `/dashboard/payment-reports/${recordId}`, // URL for deleting the user
                    type: 'DELETE', // HTTP method for deletion
                    data: formData, // Send the serialized form data (including the CSRF token)
                    success: function(response) {
                        alert(response.message); // Show success message
                        $('#deleteModal').modal('hide'); // Hide the modal
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
