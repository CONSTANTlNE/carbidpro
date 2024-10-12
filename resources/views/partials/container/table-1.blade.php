<div class="table-responsive mt-3">
    <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
        <thead class="back_table_color">
            <tr class="info">
                <th></th>
                <th>ID</th>
                <th>CAR</th>
                <th>Car type</th>
                <th>Fuel type</th>
                <th>Warehouse</th>
                <th>Title Status</th>
                <th>Owner</th>
                <th style="width: 6%;">Dispatch day count</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($cars as $car)
                <tr>
                    <td>
                        <input type="checkbox" class="car_ids" name="car_ids[]" value="{{ $car->id }}">
                        {{ $car->model }}<br>
                    </td>
                    <td>
                        {{ $car->id }}</td>
                    <td class="car_info"> @include('partials.car.table_content-parts.car-info') </td>
                    <form action="{{ route('container.listupdate', $car->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status"
                            value="{{ isset($_GET['status']) ? $_GET['status'] : 'for-Dispatch' }}">
                        <td>{{ $car->loadType->name }}</td>
                        <td>{{ $car->type_of_fuel }}</td>

                        <td>
                            {{ $car->warehouse }}<br>
                            <label for="">Destination Port:</label>
                            <br>
                            {{ $car->port->name }}<br>
                        </td>
                        <td>
                            <label class="mt-2" for="title">Title</label>
                            <select name="title" class="form-control" id="title" required>
                                <option value=""></option>
                                <option value="yes" {{ $car->title == 'yes' ? 'selected' : '' }}>YES
                                </option>
                                <option value="no" {{ $car->title == 'no' ? 'selected' : '' }}>NO</option>
                            </select>
                        </td>


                        <td>
                            {{ $car->vehicle_owner_name }}<br>
                            {{ $car->owner_id_number }}<br>
                            {{ $car->owner_phone_number }}<br>
                        </td>

                        <td>
                            @php
                                $updatedAt = $car->updated_at;
                                $daysGone = \Carbon\Carbon::parse($updatedAt)->diffInDays(\Carbon\Carbon::now());
                            @endphp

                            {{ $daysGone }} Day

                        </td>

                        <td>
                            @if (auth()->user()->hasRole('Admin'))
                                <button type="submit" class="btn btn-success btn-sm">
                                    SAVE
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
