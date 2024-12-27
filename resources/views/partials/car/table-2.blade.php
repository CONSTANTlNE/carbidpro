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
            @foreach ($cars as $car)
                <tr>

                    <td>
                        {{ $car->id }}</td>
                    <td class="car_info"> @include('partials.car.table_content-parts.car-info') </td>
                    <form action="{{ route('car.listupdate', $car->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status"
                            value="{{ isset($_GET['status']) ? $_GET['status'] : 'for-Dispatch' }}">
                        <td>@include('partials.car.table_content-parts.field-from')</td>

                        <td>
                            <label for="internal_shipping">Internal Shipping Rate</label>
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
                    
                        <td>{{ $car->title }}</td>
                        <td>
                            <input type="text" name="pickup_dates" class="form-control daterange" />
                        </td>

                        <td>
                            <button type="submit" class="btn btn-success btn-sm">
                                Next
                            </button>

                            <br>
                            <br>
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
