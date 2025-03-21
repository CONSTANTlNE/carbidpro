<script>
    document.addEventListener("DOMContentLoaded", function () {


        // Register FilePond plugins
        FilePond.registerPlugin(
            FilePondPluginImagePreview, // for showing image preview
            FilePondPluginFileValidateType, // for validating file types
            FilePondPluginFileValidateSize // for validating file size
        );

        // Turn input element into a pond
        // Get the car_id from the input element  UPLOADS CAR IMAGES
        const inputElements = document.querySelectorAll('.filepond');
        // const carId = inputElement.getAttribute('data-car_id');

        inputElements.forEach((inputElement) => {
            // Get the car_id from the current input element
            const carId = inputElement.getAttribute('data-car_id');
            // Initialize FilePond with car_id passed in the process data
            let totalFiles = 0; // Track total files being uploaded
            let uploadedFiles = 0; // Track the number of files that have been uploaded

            FilePond.create(inputElement, {
                allowMultiple: true, // Allow multiple file uploads
                maxFiles: 15, // Limit to 15 files
                acceptedFileTypes: ['image/*'], // Only accept image files
                maxFileSize: '4MB', // Maximum file size of 10MB

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
                        onload: (response) => {
                            console.log('Upload successful!', response);
                        },
                        onerror: (response) => {
                            console.error('Upload error:', response);
                        }
                    },
                    revert: null // Revert uploaded image if necessary
                },

                // Event when each file finishes uploading
                onprocessfile: (file) => {
                    uploadedFiles++;


                    // If all files have been uploaded, refresh the page
                    if (uploadedFiles === totalFiles) {
                        window.location.reload(); // Refresh the page after all files are uploaded
                    }
                },

                // Event when a file is added
                onaddfile: (file) => {
                    totalFiles++; // Increment total files count when a new file is added
                }
            });

        });


        //  Upload Bl images
        const inputElements2 = document.querySelectorAll('.filepond2');

        // Iterate over each input element and initialize FilePond
        inputElements2.forEach(function (inputElement2) {
            // Get the car_id from each input element
            const carId = inputElement2.getAttribute('data-car_id');
            const submitBtn = $('#submit-btn-' + carId);
            let totalFiles = 0; // Track total files being uploaded
            let uploadedFiles = 0; // Track the number of files that have been uploaded

            // Initialize FilePond for each input element
            const pond = FilePond.create(inputElement2, {
                allowMultiple: true, // Allow multiple file uploads
                maxFiles: 15, // Limit to 15 files
                acceptedFileTypes: ['image/*'], // Only accept image files
                maxFileSize: '4MB', // Maximum file size of 10MB

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
                },

                // Event when each file finishes uploading
                onprocessfile: (file) => {
                    uploadedFiles++;


                    // If all files have been uploaded, refresh the page
                    if (uploadedFiles === totalFiles) {
                        window.location.reload(); // Refresh the page after all files are uploaded
                    }
                },

                // Event when a file is added
                onaddfile: (file) => {
                    totalFiles++; // Increment total files count when a new file is added
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
</script>