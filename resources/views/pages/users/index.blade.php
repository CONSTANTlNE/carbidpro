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
                <i class="fa fa-user-plus"></i>
            </div>
            <div class="header-title">
                <h1>Users</h1>
                <small>List of User</small>
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
                                        data-target="#addUserModal"><i class="fa fa-plus"></i> Add Users</a>
                                </div>
                            </div>

                            <!-- ./Plugin content:powerpoint,txt,pdf,png,word,xl -->
                            <div class="table-responsive">
                                <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
                                    <thead class="back_table_color">
                                        <tr class="info">
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Created at</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td><span
                                                        class="label-custom label label-default">{{ $user->role }}</span>
                                                </td>
                                                <td>
                                                    {{ $user->created_at }}
                                                </td>
                                                <td>
                                                    <button type="button" class="btn-edit-user btn btn-add btn-sm"
                                                        data-toggle="modal" data-user-id="{{ $user->id }}"
                                                        data-target="#update"><i class="fa fa-pencil"></i></button>

                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        data-toggle="modal" data-target="#deleteUserModal"
                                                        data-user-id="{{ $user->id }}">
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
                            <h3><i class="fa fa-plus m-r-5"></i> Update User</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="updateUserForm" class="form-horizontal">
                                        @csrf
                                        <input type="hidden" id="userId" name="id">
                                        <!-- Hidden field for user ID -->
                                        <div class="row">
                                            <!-- Text input for User Name -->
                                            <div class="col-md-6 form-group">
                                                <label class="control-label">Name</label>
                                                <input type="text" id="name" name="name"
                                                    placeholder="User Name" class="form-control" required>
                                            </div>
                                            <!-- Text input for Status -->
                                            <div class="col-md-6 form-group">
                                                <label class="control-label">Email</label>
                                                <input type="email" id="email" name="email" placeholder="email"
                                                    class="form-control">
                                            </div>

                                            <!-- Password Field -->
                                            <div class="col-md-6 form-group">
                                                <label class="control-label">Password</label>
                                                <div class="input-group">
                                                    <input type="password" id="password" name="password"
                                                        placeholder="Password" class="form-control">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-secondary toggle-password" type="button"
                                                            data-target="#password"><i class="fa fa-eye"></i></button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Confirm Password Field -->
                                            <div class="col-md-6 form-group">
                                                <label class="control-label">Confirm Password</label>
                                                <div class="input-group">
                                                    <input type="password" id="password_confirmation"
                                                        name="password_confirmation" placeholder="Confirm Password"
                                                        class="form-control">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-secondary toggle-password"
                                                            type="button" data-target="#password_confirmation"><i
                                                                class="fa fa-eye"></i></button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Text input for Role/Type -->
                                            <div class="col-md-6 form-group">
                                                <label class="control-label">Role</label>
                                                <select id="userRole" name="role" class="form-control">
                                                    <option value="Admin">Admin</option>
                                                    <option value="Editor">Editor</option>
                                                    <option value="Dispatch">Dispatch</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12 form-group user-form-group">
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
            <!-- User Modal1 -->
            <!-- Add New User Modal -->
            <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header modal-header-primary">
                            <h3><i class="fa fa-plus m-r-5"></i> Add New User</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <form id="addUserForm" class="form-horizontal" method="POST"
                                enctype="multipart/form-data">
                                @csrf <!-- CSRF token -->
                                <!-- User Name -->
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="control-label">Name</label>
                                        <input type="text" id="add_name" name="name" placeholder="User Name"
                                            class="form-control" required>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6 form-group">
                                        <label class="control-label">Email</label>
                                        <input type="email" id="add_email" name="email" placeholder="Email"
                                            class="form-control" required>
                                    </div>

                                    <!-- Password -->
                                    <div class="col-md-6 form-group">
                                        <label class="control-label">Password</label>
                                        <input type="password" id="add_password" name="password"
                                            placeholder="Password" class="form-control" required>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="col-md-6 form-group">
                                        <label class="control-label">Confirm Password</label>
                                        <input type="password" id="add_password_confirmation"
                                            name="password_confirmation" placeholder="Confirm Password"
                                            class="form-control" required>
                                    </div>

                                    <!-- Role -->
                                    <div class="col-md-6 form-group">
                                        <label class="control-label">Role</label>
                                        <select id="add_role" name="role" class="form-control" required>
                                            <option value="Admin">Admin</option>
                                            <option value="Editor">Editor</option>
                                            <option value="Dispatch">Dispatch</option>
                                        </select>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-md-12 form-group">
                                        <button type="submit" class="btn btn-add">Add User</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->
            <!-- Modal -->
            <!-- Delete User Modal -->
            <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header modal-header-primary">
                            <h3><i class="fa fa-user m-r-5"></i> Delete User</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this user?</p>
                            <form id="deleteUserForm">
                                @csrf <!-- CSRF token for security -->
                                <input type="hidden" id="deleteUserId" name="user_id">
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


            function openEditModal(userId) {
                // Find the user data from the server or the existing data (you can also use AJAX here)
                const user = @json($users).find(u => u.id === userId);

                // Populate the form fields in the modal
                $('#userId').val(user.id);
                $('#name').val(user.name);
                $('#email').val(user.email);
                $('#userRole').val(user.role);

                // Show the modal
                $('#update').modal('show');
            }

            // Attach click event to edit buttons
            $('.btn-edit-user').on('click', function() {
                const userId = $(this).data('user-id'); // Get user ID from the button
                openEditModal(userId); // Call function to open modal with user data
            });

            // Set up the CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Handle form submission (you can use AJAX to submit the form)
            $('#updateUserForm').on('submit', function(e) {
                e.preventDefault();
                const userId = $('#userId').val();
                const formData = new FormData(this); // Get form data

                // Clear previous error messages
                $('.text-danger').remove();

                // Make an AJAX request to update the user (adjust the URL and method accordingly)
                $.ajax({
                    url: `/dashboard/users/${userId}`, // Your update URL
                    method: 'POST', // or 'PUT' based on your route
                    data: formData,
                    dataType: "JSON",
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert('User updated successfully');
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

            $('#addUserForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                const formData = new FormData(this); // Create FormData object from the form

                // Clear previous error messages
                $('.text-danger').remove();

                $.ajax({
                    url: '/dashboard/users', // The URL to add a new user (adjust as necessary)
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert(response.message); // Show success message
                        $('#addUserModal').modal('hide'); // Hide the modal
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
