<form method="GET" action="">
    <div class="row">
        <!-- To Port -->
        <div class="col">
            <label for="to_port_id">To Port</label>
            <select name="to_port_id" id="to_port_id" class="form-control">
                <option value="">Select Port</option>
                @foreach ($ports as $port)
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
                @foreach ($loadtypes as $load_type)
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


<div style="margin-top:30px;display: flex;flex-direction: column;gap: 30px;align-items: flex-end;">

    @if ($currentStatus == 'for-load')
        {{--   Creates ContainerGroup--}}
        <form action="{{ route('container.selected') }}"
              class="form-inline my-2 my-lg-0 mt-5 mb-3" method="post">
            @csrf
            <input type="hidden" class="carids" id="carids" name="car_ids[]"
                   value="">
            <button class="btn btn-primary my-2 my-sm-0 mb-3"
                    type="submit">Next
            </button>
        </form>
    @endif


</div>

<div class="table-responsive mt-3">
    <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
        <thead class="back_table_color">
        <tr class="info">

            <th>ID</th>
            <th>CAR INFO</th>
            <th>Container Group</th>
            <th>Car type</th>
            <th>Fuel type</th>
            <th>Warehouse</th>
            <th>T/status</th>
            <th style="width: 6%;">Dispatch days</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($cars as $car)
            <tr>
                <td>
                    <label for="{{$car->vin}}" style="cursor:pointer">
                        <input id="{{$car->vin}}" type="checkbox" class="car_ids" name="car_ids[]"
                               value="{{ $car->id }}">
                        {{ $car->id }}
                    </label>
                </td>
                <td class="car_info"> @include('partials.car.table_content-parts.car-info') </td>
                <td>

                    <select style="width: 250px" name="cargroup_id" class="form-control"
                            hx-post="{{route('container.addCarToGroup2')}}"
                            hx-vals='{"car_id": "{{ $car->id }}", "_token": "{{ csrf_token() }}" }'
                            hx-on::after-request="window.location.reload()"
                    >
                        <option value="">Add To Container Group</option>
                        @foreach($groups2 as $cargroup)
                            @if($cargroup->warehouse_id == $car->warehouse_id)
                            <option value="{{$cargroup->id}}">{{$cargroup->port->name}} -- {{$cargroup->warehouse->name}}</option>
                            @endif
                        @endforeach
                    </select>

                </td>
                <input type="hidden" name="status"
                       value="{{ isset($_GET['status']) ? $_GET['status'] : 'for-Dispatch' }}">
                <td>{{ isset($car->loadType) ? $car->loadType->name  : ''}}</td>
                <td>
                    @include('partials.container.table_content-parts.fuel-type')
                </td>
                <td>
                    <label for="">Warehouse:</label>
                    <br>{{$car->warehouse}}<br>
                    {{--                        <label for="">Port:</label>--}}
                    {{--                        {{ !empty($car->port) ? $car->port->name : '' }}--}}

                    <label for="">Dest Port:</label>
                    <br>
                    POTI
                    <br>
                </td>
                <td>
                    <label class="mt-2" for="title">Title</label>
                    <select name="title" class="form-control title" data-car-id="{{ $car->id }}"
                            id="title" required>
                        <option value=""></option>
                        <option value="yes" {{ $car->title == 'yes' ? 'selected' : '' }}>YES
                        </option>
                        <option value="no" {{ $car->title == 'no' ? 'selected' : '' }}>NO</option>
                    </select>
                </td>
                <td>
                    @php
                        $updatedAt = $car->updated_at;
                        $daysGone = \Carbon\Carbon::parse($updatedAt->startOfDay())->startOfDay()->diffInDays(\Carbon\Carbon::now()->startOfDay());
                    @endphp

                    {{ $daysGone }} Day
                    <br>
                    <strong>Create:</strong> {{ $car->created_at->format('d.m.y') }}
                    <br>
                    <strong>Update:</strong> {{ $car->updated_at->format('d.m.y') }} <br>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>
