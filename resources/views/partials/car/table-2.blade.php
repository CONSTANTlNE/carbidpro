<div class="table-responsive">
    <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
        <thead class="back_table_color">
            <tr class="info">
                <th>ID</th>
                <th>CAR</th>
                <th>From</th>
                <th>Warehouse</th>
                <th>Price</th>
                <th>Carrier Info</th>
                <th>
                    <a
                        href="{{ request()->fullUrlWithQuery(['sort' => 'customers.contact_name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                        Dealer
                    </a>
                </th>
                <th>Pickup & Delivery Dates</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cars as $car)
                <tr>

                    <td>
                        {{ $car->id }}</td>
                    <td class="car_info">                         @include('partials.car.table_content-parts.car-info')                     </td>
                    <form action="{{ route('car.listupdate', $car->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status"
                            value="{{ isset($_GET['status']) ? $_GET['status'] : 'for-Dispatch' }}">
                       <td>@include('partials.car.table_content-parts.field-from')</td>
                        <td>
                            {{ $car->warehouse }}
                        </td>
                        <td>
                            <label for="internal_shipping">Rate</label>
                            <input id="internal_shipping" value="{{ $car->internal_shipping }}" type="number"
                                class="form-control" name="internal_shipping" required>
                        </td>
                        <td>
                            <label for="company_name">Company name</label>
                            <input id="company_name" value="{{ $car->company_name }}" type="text"
                                class="form-control" name="company_name" required>

                            <label for="contact_info">Contact info</label>
                            <input id="contact_info" value="{{ $car->contact_info }}" type="text"
                                class="form-control" name="contact_info" required>
                        </td>
                        <td>
                            @if (!empty($car->customer))
                                {{ $car->customer->contact_name }}
                            @endif
                        </td>
                        <td>
                            <input type="text" name="pickup_dates" class="form-control daterange" />
                        </td>

                        <td>
                            <button type="submit" class="btn btn-success btn-sm">
                                Next
                            </button>

                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#deleteUserModal" data-user-id="{{ $car->id }}">
                                <i class="fa fa-trash-o"></i>
                            </button>
                            <br>
                            <br>
                            @if (isset($car->updated_at))
                                <span class="btn btn-dark">
                                    {{ $car->updated_at->format('d.m') }}</span>
                            @endif

                        </td>
                    </form>

                </tr>
            @endforeach

        </tbody>
    </table>
</div>

@push('js')
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script type="text/javascript">
        $(function() {
            $('.daterange').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'DD.MM' // Set the desired format
                }
            }, function(start, end, label) {
                console.log("A new date range was chosen: " + start.format('DD.MM') + ' to ' + end
                    .format('DD.MM'));
            });
        });
    </script>
@endpush
