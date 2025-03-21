<div class="table-responsive">
    <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
        <thead class="back_table_color">
        <tr class="info">
            <th>ID</th>
            <th>CAR INFO</th>
            <th>FROM-TO</th>

            <th>Price</th>
            <th>Carrier Info</th>

            <th>T/status</th>
            <th>Pickup & Delivery Dates</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($cars as $index =>  $car)
            <tr>

                <td>
                    {{ $car->id }}
                </td>

                <td class="car_info"> @include('partials.car.table_content-parts.car-info') </td>
                <form action="{{ route('car.listupdate', $car->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status"
                           value="{{ isset($_GET['status']) ? $_GET['status'] : 'for-Dispatch' }}">
                    <td>@include('partials.car.table_content-parts.field-from')</td>

                    <td>
                        <label for="internal_shipping">Internal Shipping Rate</label>
                        <input id="internal_shipping" value="{{ $car->internal_shipping }}" type="number"
                               class="form-control" name="internal_shipping" required
                               pattern=".*\S.*" title="This field cannot be empty or contain only spaces"
                        >
                    </td>
                    <td>
                        <label for="company_name">Company name</label>
                        <input id="company_name" value="{{ $car->company_name }}" type="text"
                               class="form-control" name="company_name" required
                               pattern=".*\S.*" title="This field cannot be empty or contain only spaces"
                        >

                        <label for="contact_info">Contact info</label>
                        <input id="contact_info" value="{{ $car->contact_info }}" type="text"
                               class="form-control" name="contact_info" required
                               pattern=".*\S.*" title="This field cannot be empty or contain only spaces"
                        >
                    </td>

                    <td>{{ $car->title }}</td>
                    <td>
                        <input type="text" name="pickup_dates" class="form-control daterange"
                               value="{{ $car->pickup_dates }}"
                        >
                    </td>

                    <td>
                        <button data-target="#shipping{{$car->id}}" data-toggle="modal" type="button" class="btn btn-success btn-sm">
                            Next
                        </button>
{{--                        @include('partials.car.table_content-parts.edit-modal')--}}
{{--                        internal shipping confirmation modal--}}
                        <div class="modal fade" id="shipping{{$car->id}}" tabindex="-1" role="dialog"  aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
{{--                                        <h5 class="modal-title" >Modal title</h5>--}}
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>
                                            Want to leave internal shipping unchanged?
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                                        <button type="submit" class="btn green_btn custom_grreen2">Yes / Next</button>
                                    </div>
                                </div>
                            </div>
                        </div>

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
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script type="text/javascript">
        $(function () {
            $('.daterange').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'DD.MM.YYYY' // Set the desired format
                }
            }, function (start, end, label) {
                console.log("A new date range was chosen: " + start.format('DD.MM.YYYY') + ' to ' + end
                    .format('DD.MM.YYYY'));
            });
        });
    </script>
@endpush
