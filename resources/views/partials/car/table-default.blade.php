<div class="table-responsive">
    <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
        <thead class="back_table_color">
            <tr class="info">
                <th>ID</th>
                <th>CAR INFO</th>
                <th>FROM-TO</th>
                <th>
                    <a
                        href="{{ request()->fullUrlWithQuery(['sort' => 'dispatcher.name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                        Broker
                    </a>
                </th>
                <th>
                    <a
                        href="{{ request()->fullUrlWithQuery(['sort' => 'customers.contact_name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                        Dealer
                    </a>
                </th>
                <th><a
                        href="{{ request()->fullUrlWithQuery(['sort' => 'car_statuses.name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                        Status
                    </a></th>
                <th>Balance</th>
                 
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cars as $car)
                <tr>

                    <td>
                        {{ $car->id }}</td>
                    <td class="car_info">
                        @include('partials.car.table_content-parts.car-info')
                    </td>
                    <form action="{{ route('car.listupdate', $car->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status"
                            value="{{ isset($_GET['status']) ? $_GET['status'] : 'for-Dispatch' }}">
                        <td>@include('partials.car.table_content-parts.field-from')</td>
                        <td>
                            @if (!empty($car->dispatch))
                                {{ $car->dispatch->name }}
                            @endif
                        </td>
                        <td>
                            @if (!empty($car->customer))
                                {{ $car->customer->contact_name }}
                            @endif
                        </td>
                        <td>
                            @if (!empty($car->CarStatus))
                                <a
                                    href="{{ route('car.showStatus', $car->CarStatus->slug) }}">{{ $car->CarStatus->name }}</a>
                            @endif

                        </td>
                        <td>
                            <strong>All Cost:</strong><br>
                            {{ $car->total_cost }}<br>
                            <strong>Amount due:</strong><br>
                            {{ $car->amount_due }}

                        </td>

                        <td>
                            <a href="{{ route('car.edit', $car->id) }}">
                                <button type="button" class="btn btn-success btn-sm">
                                    Edit
                                </button>
                            </a>

                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#deleteCarModal" data-car-id="{{ $car->id }}">
                                <i class="fa fa-trash-o"></i>
                            </button>
                            <br>
                            <br>
                            <strong>Create:</strong> {{ $car->created_at->format('d.m.y') }}
                            <br>
                            <strong>Update:</strong> {{ $car->updated_at->format('d.m.y') }} <br>

                        </td>
                    </form>

                </tr>
            @endforeach

        </tbody>
    </table>
</div>
