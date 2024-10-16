<form method="GET" action="">
    <div class="row">
        <!-- To Port -->
        <div class="col">
            <label for="to_port_id">To Port</label>
            <select name="to_port_id" id="to_port_id" class="form-control">
                <option value="">Select Port</option>
                @foreach($ports as $port)
                    <option value="{{ $port->id }}" {{ request('to_port_id') == $port->id ? 'selected' : '' }}>
                        {{ $port->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Load Type -->
        <div class="col">
            <label for="load_type">Load Type</label>
            <select name="load_type" id="load_type" class="form-control">
                <option value="">Select Load Type</option>
                @foreach($loadtypes as $load_type)
                    <option value="{{ $load_type->id }}" {{ request('load_type') == $load_type->id ? 'selected' : '' }}>
                        {{ $load_type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Type of Fuel -->
        <div class="col">
            <label for="type_of_fuel">Type of Fuel</label>
            <select name="type_of_fuel" id="type_of_fuel" class="form-control">
                <option value="">Select Fuel Type</option>
                <option value="Petrol" {{ request('type_of_fuel') == 'Petrol' ? 'selected' : '' }}>Petrol</option>
                <option value="Hybrid" {{ request('type_of_fuel') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
            </select>
        </div>

        <!-- Title (Yes/No) -->
        <div class="col">
            <label for="title">Title</label>
            <select name="title" id="title" class="form-control">
                <option value="">Select Yes/No</option>
                <option value="yes" {{ request('title') == 'yes' ? 'selected' : '' }}>Yes</option>
                <option value="no" {{ request('title') == 'no' ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <!-- Submit Button -->
        <div class="col">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-success btn-block">Search</button>
        </div>
    </div>
</form>

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

                    <input type="hidden" name="status"
                        value="{{ isset($_GET['status']) ? $_GET['status'] : 'for-Dispatch' }}">
                    <td>{{ $car->loadType->name }}</td>
                    <td>{{ $car->type_of_fuel }}</td>

                    <td>
                        {{ !empty($car->port) ? $car->port->name : '' }} <br>
                        <label for="">Destination Port:</label>
                        <br>
                        POTI<br>
                    </td>
                    <td>
                        <label class="mt-2" for="title">Title</label>
                        <select name="title" class="form-control title" data-car-id="{{ $car->id}}"
                            id="title" required>
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
                        <br>
                        @if (isset($car->updated_at))
                            <span class="btn btn-dark">
                                {{ $car->updated_at->format('d.m') }}</span>
                        @endif

                    </td>


                </tr>
            @endforeach

        </tbody>
    </table>
</div>
