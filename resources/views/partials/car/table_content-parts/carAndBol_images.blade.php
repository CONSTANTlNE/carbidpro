<div style="display: flex;flex-direction: column;gap: 10px; justify-content: center;height: 100%!important">
    <!-- Button to open the modal -->
    <button type="button"
            class="btn {{ !$car->getMedia('images')->isNotEmpty() ? 'btn-danger' : 'btn-success' }}"
            data-toggle="modal" data-target="#imageUploadModal-{{ $car->id }}"
            data-record-id="{{ $car->id }}">
        Car Images
    </button>

    <button type="button"
            class="btn  {{ !$car->getMedia('bl_images')->isNotEmpty() ? 'btn-danger' : 'btn-success' }}"
            data-toggle="modal"
            data-target="#blimage{{ $car->id }}">
        BOL Images
    </button>

    <div class="modal fade" id="blimage{{ $car->id }}" tabindex="-1"
         role="dialog" aria-labelledby="blimage" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">BOL Images </h5>

                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <h6 class="mt-2 text-center"> Max Files: 15 -  Total Max Size: 60mb</h6>
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
                            <h6>BOL Images</h6>
                            <div class="row mt-2">
                                @foreach ($car->getMedia('bl_images') as $image)
                                    <div class="col-md-3">
                                        <div class="image-thumbnail mb-2">
                                            <img src="{{ $image->getUrl() }}" class="img-fluid"
                                                 style="max-height: 100px; object-fit: cover;"
                                                 alt="Uploaded Image">
                                        </div>
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
                <h6 class="mt-2 text-center"> Max Files: 15 -  Total Max Size: 60mb</h6>
                <div class="modal-body">
                    <!-- Hidden input to pass the car ID -->
                    <input type="hidden" value="{{ $car->id }}" name="car_id"
                           id="recordIdInput">

                    <!-- FilePond input for multiple image uploads -->
                    <input type="file" style="display: none"
                           data-car_id="{{ $car->id }}" class="filepond" id="filepond"
                           name="images[]" multiple>



                    @if ($car->getMedia('images')->isNotEmpty())
                        <div class="existing-images mt-4">
                            <h6>Car Images</h6>
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
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     width="24"
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