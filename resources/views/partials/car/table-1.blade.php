<div class="table-responsive">
    <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
        <thead class="back_table_color">
            <tr class="info">
                <th>ID</th>
                <th>CAR</th>
                <th>From</th>
                <th>Warehouse</th>
                <th>Price</th>
                <th>
                    <a
                        href="{{ request()->fullUrlWithQuery(['sort' => 'customers.contact_name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                        Dealer
                    </a>
                </th>
                <th>Created at</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cars as $car)
                <tr>

                    <td>
                        {{ $car->id }}</td>
                    <td class="car_info">
                        <div>LOT: <span>{{ $car->lot }}</span></div>
                        <div>Gate/Iaai: <span>{{ $car->gate_or_member }}</span></div>
                        <div>Model: <span>{{ $car->make_model_year }}</span></div>
                        <div>VIN: <span>{{ $car->vin }}</span></div>
                    </td>
                    <form action="{{ route('car.listupdate', $car->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status"
                            value="{{ isset($_GET['status']) ? $_GET['status'] : 'for-Dispatch' }}">
                       <td>@include('partials.car.table_content-parts.field-from')</td>
                        <td>
                            <input id="warehouse" class="form-control" name="warehouse" required>
                        </td>
                        <td>
                            <input id="internal_shipping" type="number" class="form-control" name="internal_shipping"
                                required>

                        </td>
                        <td>
                            @if (!empty($car->customer))
                                {{ $car->customer->contact_name }}
                            @endif
                        </td>
                        <td>{{ $car->created_at }}</td>

                        <td>
                            <button type="submit" class="btn btn-success btn-sm">
                                Next
                            </button>
                            @if (auth()->user()->hasRole('Admin'))
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                    data-target="#deleteUserModal" data-user-id="{{ $car->id }}">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            @endif

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
