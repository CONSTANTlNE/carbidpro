@push('css')
    <style>
        .htmx-indicator {
            opacity: 0;
            transition: opacity 500ms ease-in;
        }

        .htmx-request .htmx-indicator {
            opacity: 1;
        }

        .htmx-request.htmx-indicator {
            opacity: 1;
        }
    </style>
@endpush


@foreach ($cars as $key => $cargroup)

    <h4 class="mt-2">ID: {{$cargroup->id}} GROUP : {{ $key + 1 }} {{$cargroup->warehouse->name}}</h4>

    <div class="table-responsive">
        <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
            <thead class="back_table_color">
            <tr class="info">
                <th>ID</th>
                <th style="width: 20%;">CAR</th>
                <th style="width: 10%;">Car type</th>
                <th style="width: 10%;">Fuel type</th>
                <th>Warehouse</th>

                <th style="width: 10%;">Dispatch days</th>
                <th style="width: 10%;">Container Info</th>
                <th style="width: 10%;">Container Cost</th>

                <th style="width: 100px;">Action</th>
            </tr>
            </thead>
            <thead class="back_table_color" style="background-color: #576cff21">
            <form action="{{ route('container.updateGroup') }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="group_id" value="{{ $cargroup->id }}">
                @csrf
                <tr class="info">
                    <th></th>
                    <th style="width: 20%;"></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="width: 10%;">
                        <label for="booking_id">Booking #</label>
                        <input type="text" value="{{ $cargroup->booking_id }}" placeholder="Booking #"
                               class="form-control" name="booking_id" id="booking_id">
                        <label for="container">Container #</label>
                        <input type="text" value="{{ $cargroup->container_id }}" placeholder="Container #"
                               class="form-control" name="container" id="container" required>
                        <label for="arrival_time">Arrival Date</label>
                        <input type="date"
                               value="{{ \Carbon\Carbon::parse($cargroup->arrival_time)->format('Y-m-d') }}"
                               placeholder="Arrival Time" class="form-control" name="arrival_time"
                               id="arrival_time" {{ $cargroup->arrival_time ? '' : 'required' }}>
                    </th>
                    <th style="width: 10%;">
{{--                        <input type="text" placeholder="Shipping Line" value="{{ $cargroup->thc_agent }}"--}}
{{--                               name="thc_agent" class="form-control thc_agent" id="thc_agent" required>--}}

                        <select class="form-control" name="shipping_line_id" required>
                            <option value="">Shipping Line</option>
                            @foreach($shipping_lines as $line)
                                <option value="{{$line->id}}"> {{$line->name}}</option>
                            @endforeach
                        </select>

                        <br>

                        <input type="text" value="{{ $cargroup->cost }}" placeholder="Container Cost $"
                               class="form-control" name="container_cost" id="container_cost" required>
                        <br>
                        <label for="">Photo of BOL</label>
                        <input type="file" name="bol_photo" id="bol_photo" style="width: 200px;"
                                {{empty($cargroup->photo) ? 'reqiored' : ''}}>

                        @if (!empty($cargroup->photo))
                            <a href="{{ Storage::url($cargroup->photo) }}" target="_blank">

                                <img src="{{ Storage::url($cargroup->photo) }}"
                                     style="max-width: 100px; margin-top:10px" alt="Recipte">
                            </a>
                        @endif

                        <img id="preview_{{ $cargroup->id }}" src="" alt="Image preview"
                             style="display:none; max-width: 100px; margin-top:10px;">
                    </th>


                    <th style="width: 100px!important;">
                        <div class="d-flex" style="gap:10px;width: 100px;    flex-direction: column;">
                            <button
                                    hx-get="{{route('container.htmx.select.car')}}"
                                    hx-vals='{"warehouse_id": "{{ $cargroup->warehouse_id }}" , "key": "{{$key}}" , "cargroup_id": "{{$cargroup->id }}" }'
                                    hx-target="#addcartarget{{$key}}"
                                    data-toggle="modal"
                                    class="btn btn-dark"

                                    data-target="#addCarModal{{$key}}"
                                    type="button">
                                Add Car
                            </button>
                            {{-- add car modal--}}
                            <div class="modal fade" id="addCarModal{{$key}}" tabindex="-1"
                                 aria-labelledby="addCarModalLabel{{$key}}"
                                 aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addCarModalLabell{{$key}}">Add car</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        {{-- Content inserted via htmx  --}}
                                        <div id="addcartarget{{$key}}">

                                        </div>

                                    </div>
                                </div>
                            </div>
                            {{-- send email--}}
                            <button type="button" id="sendEmail{{$key}}"
                                    hx-indicator="#spinner{{$key}}"
                                    hx-post="{{ route('container.sendEmail') }}"
                                    hx-target="this" hx-swap="outerHTML"
                                    hx-vals='{"cargroup_id": "{{ $cargroup->id }}", "_token": "{{ csrf_token() }}" }'
                                    class=" btn {{ $cargroup->is_email_sent == 1 ? 'btn-warning' : 'btn-primary' }} btn-sm">
                                {{ $cargroup->is_email_sent == 1 ? 'Email sent ' . $cargroup->email_sent_date : 'Send email' }}
                            </button>
                            {{--Spinner for htmx request--}}
                            <div class="d-flex justify-content-center w-100">
                                <svg id="spinner{{$key}}" class="htmx-indicator" xmlns="http://www.w3.org/2000/svg"
                                     width="35" height="35" viewBox="0 0 24 24">
                                    <circle cx="12" cy="3" r="0" fill="#46d21e">
                                        <animate id="svgSpinners6DotsScaleMiddle0" attributeName="r"
                                                 begin="0;svgSpinners6DotsScaleMiddle2.end-0.5s" calcMode="spline"
                                                 dur="0.6s" keySplines=".27,.42,.37,.99;.53,0,.61,.73" values="0;2;0"/>
                                    </circle>
                                    <circle cx="16.5" cy="4.21" r="0" fill="#46d21e">
                                        <animate id="svgSpinners6DotsScaleMiddle1" attributeName="r"
                                                 begin="svgSpinners6DotsScaleMiddle0.begin+0.1s" calcMode="spline"
                                                 dur="0.6s" keySplines=".27,.42,.37,.99;.53,0,.61,.73" values="0;2;0"/>
                                    </circle>
                                    <circle cx="7.5" cy="4.21" r="0" fill="#46d21e">
                                        <animate id="svgSpinners6DotsScaleMiddle2" attributeName="r"
                                                 begin="svgSpinners6DotsScaleMiddle4.begin+0.1s" calcMode="spline"
                                                 dur="0.6s" keySplines=".27,.42,.37,.99;.53,0,.61,.73" values="0;2;0"/>
                                    </circle>
                                    <circle cx="19.79" cy="7.5" r="0" fill="#46d21e">
                                        <animate id="svgSpinners6DotsScaleMiddle3" attributeName="r"
                                                 begin="svgSpinners6DotsScaleMiddle1.begin+0.1s" calcMode="spline"
                                                 dur="0.6s" keySplines=".27,.42,.37,.99;.53,0,.61,.73" values="0;2;0"/>
                                    </circle>
                                    <circle cx="4.21" cy="7.5" r="0" fill="#46d21e">
                                        <animate id="svgSpinners6DotsScaleMiddle4" attributeName="r"
                                                 begin="svgSpinners6DotsScaleMiddle6.begin+0.1s" calcMode="spline"
                                                 dur="0.6s" keySplines=".27,.42,.37,.99;.53,0,.61,.73" values="0;2;0"/>
                                    </circle>
                                    <circle cx="21" cy="12" r="0" fill="#46d21e">
                                        <animate id="svgSpinners6DotsScaleMiddle5" attributeName="r"
                                                 begin="svgSpinners6DotsScaleMiddle3.begin+0.1s" calcMode="spline"
                                                 dur="0.6s" keySplines=".27,.42,.37,.99;.53,0,.61,.73" values="0;2;0"/>
                                    </circle>
                                    <circle cx="3" cy="12" r="0" fill="#46d21e">
                                        <animate id="svgSpinners6DotsScaleMiddle6" attributeName="r"
                                                 begin="svgSpinners6DotsScaleMiddle8.begin+0.1s" calcMode="spline"
                                                 dur="0.6s" keySplines=".27,.42,.37,.99;.53,0,.61,.73" values="0;2;0"/>
                                    </circle>
                                    <circle cx="19.79" cy="16.5" r="0" fill="#46d21e">
                                        <animate id="svgSpinners6DotsScaleMiddle7" attributeName="r"
                                                 begin="svgSpinners6DotsScaleMiddle5.begin+0.1s" calcMode="spline"
                                                 dur="0.6s" keySplines=".27,.42,.37,.99;.53,0,.61,.73" values="0;2;0"/>
                                    </circle>
                                    <circle cx="4.21" cy="16.5" r="0" fill="#46d21e">
                                        <animate id="svgSpinners6DotsScaleMiddle8" attributeName="r"
                                                 begin="svgSpinners6DotsScaleMiddlea.begin+0.1s" calcMode="spline"
                                                 dur="0.6s" keySplines=".27,.42,.37,.99;.53,0,.61,.73" values="0;2;0"/>
                                    </circle>
                                    <circle cx="16.5" cy="19.79" r="0" fill="#46d21e">
                                        <animate id="svgSpinners6DotsScaleMiddle9" attributeName="r"
                                                 begin="svgSpinners6DotsScaleMiddle7.begin+0.1s" calcMode="spline"
                                                 dur="0.6s" keySplines=".27,.42,.37,.99;.53,0,.61,.73" values="0;2;0"/>
                                    </circle>
                                    <circle cx="7.5" cy="19.79" r="0" fill="#46d21e">
                                        <animate id="svgSpinners6DotsScaleMiddlea" attributeName="r"
                                                 begin="svgSpinners6DotsScaleMiddleb.begin+0.1s" calcMode="spline"
                                                 dur="0.6s" keySplines=".27,.42,.37,.99;.53,0,.61,.73" values="0;2;0"/>
                                    </circle>
                                    <circle cx="12" cy="21" r="0" fill="#46d21e">
                                        <animate id="svgSpinners6DotsScaleMiddleb" attributeName="r"
                                                 begin="svgSpinners6DotsScaleMiddle9.begin+0.1s" calcMode="spline"
                                                 dur="0.6s" keySplines=".27,.42,.37,.99;.53,0,.61,.73" values="0;2;0"/>
                                    </circle>
                                </svg>
                            </div>
                            <button type="submit" class="btn btn-success btn-sm">
                                NEXT
                            </button>
                        </div>
                    </th>
                </tr>
            </form>
            </thead>
            <tbody>
            @foreach ($cargroup->cars as $cargroupindex => $car)
                <tr>
                    <td>
                        {{ $car->id }}</td>
                    <td class="car_info"> @include('partials.car.table_content-parts.car-info') </td>
                    <form action="{{ route('container.listupdate', $car->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status"
                               value="{{ isset($_GET['status']) ? $_GET['status'] : 'for-Dispatch' }}">
                        <td>{{ isset($car->loadType) ? $car->loadType->name : '' }}</td>
                        <td>@include('partials.container.table_content-parts.fuel-type')</td>
                        <td>
                            <label for="">Port:</label>
                            <br>{{$car->warehouse}}<br>
                            {{--                            <label for="">Port:</label>--}}
                            {{--                            {{ !empty($car->port) ? $car->port->name : '' }} <br>--}}
                            <label for="">Dest Port:</label>
                            <br>
                            POTI
                            <br>
                        </td>

                        <td>
                            @php
                                $updatedAt = $car->updated_at;
                                $daysGone = \Carbon\Carbon::parse($updatedAt->startOfDay())->diffInDays(
                                    \Carbon\Carbon::now()->startOfDay(),
                                );
                            @endphp
                            {{ $daysGone }} Day
                        </td>
                        <td></td>

                        <td>{{ $cargroup->cost }}</td>

                        {{--                     replace or remove cars--}}
                        <td>
                            <div style="display: flex;flex-direction: column; max-width: 100px">
                                <button
                                        hx-get="{{route('container.htmx.select.car2')}}"
                                        hx-vals='{"warehouse_id": "{{ $cargroup->warehouse_id }}" , "key": "{{$cargroupindex}}" , "container_id": "{{$cargroup->id }}", "oldcar_id": "{{ $car->id }}"  }'

                                        hx-target="#replacecartarget{{$cargroupindex}}"
                                        type="button" class="btn btn-dark" data-toggle="modal"
                                        data-target="#mymodals{{$cargroupindex}}">
                                    Replace
                                </button>
                                {{--replace car modal--}}
                                <div class="modal fade" id="mymodals{{$cargroupindex}}" tabindex="-1" role="dialog"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Replace car : {{$car->vin}}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div id="replacecartarget{{$cargroupindex}}" class="modal-body">

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{--remove car from container group--}}
                                <button data-car-id="{{ $car->id }}"
                                        data-container-id="{{ $cargroup->id }}" class="btn btn-danger removefromlist mt-2"
                                        type="button">
                                    Remove
                                </button>
                            </div>
                        </td>
                    </form>

                </tr>

            @endforeach
            </tbody>
        </table>
    </div>

@endforeach

@push('js')
    <script>
        $(document).ready(function () {

            const suggestedWords = [
                'MAERSK',
                'Hapag-Eisa',
                'Turkon-DTS',
                'One net-Wilhelmsen',
            ];

            // Initialize jQuery UI autocomplete
            // function initializeAutocomplete() {
            //     $(".thc_agent").autocomplete({
            //         source: suggestedWords,
            //         minLength: 0,
            //         select: function (event, ui) {
            //             let selectedWord = ui.item.value; // Get the selected word
            //             $(this).closest('td').find('input[name="cost"]').val('');
            //         }
            //     }).focus(function () {
            //         // Force the dropdown to show when the input gains focus
            //         $(this).autocomplete("search", "");
            //     });
            // }
            //
            // initializeAutocomplete();


            $('.removefromlist').on('click', function () {
                var carId = $(this).data('car-id');
                var cargroup_id = $(this).data('container-id');

                // Send an AJAX request to replace the car
                $.ajax({
                    url: '{{ route('container.removeFromList') }}', // Laravel route for replacing cars
                    method: 'POST',
                    data: {
                        carId: carId,
                        cargroup_id: cargroup_id,
                        _token: '{{ csrf_token() }}' // Include CSRF token for security
                    },
                    success: function (response) {
                        alert('Car Removed From List!');
                        location.reload(); // Optionally, reload the page to reflect changes
                    },
                    error: function (xhr) {
                        alert('Failed to Remove car.');
                    }
                });
            });

        });

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

    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script type="text/javascript">
        $(function () {
            $('.daterange').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'DD.MM' // Set the desired format
                }
            }, function (start, end, label) {
                console.log("A new date range was chosen: " + start.format('DD.MM') + ' to ' + end
                    .format('DD.MM'));
            });
        });
    </script>
@endpush
