<div class="table-responsive">
    <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
        <thead class="back_table_color">
            <tr class="info">
                <th>ID</th>
                @hasanyrole('Admin|Developer')
                <th>Change Status</th>
                @endhasanyrole
                <th>CAR INFO</th>
                <th>FROM-TO</th>
                <th style="width:10%">Price (Internal Shipping)</th>

                <th>T/status</th>
                <th>Created at</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cars as $car)
                <tr>
                    <td>
                        {{ $car->id }}
                    </td>
                    @hasanyrole('Admin|Developer')
                    <td>
                        <div class="col-12">
                            <label>CAR Status</label>
                            <select name="status" class="form-control" id="customer_id"
                                    hx-post="{{route('car.change.status')}}"
                                    hx-vals='{"car_id": "{{ $car->id }}", "_token": "{{ csrf_token() }}" }'
                                    onchange="setTimeout(() => { window.location.reload() }, 200)"
                            >
                                @foreach($statuses as $statusindex => $status)
                                    <option {{ $car->car_status_id == $status->id ? 'selected' : ''}} value="{{$status->id}}"> {{ $status->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                    @endhasanyrole
                    <td class="car_info"> @include('partials.car.table_content-parts.car-info') </td>
                    <form action="{{ route('car.listupdate', $car->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status"
                            value="{{ isset($_GET['status']) ? $_GET['status'] : 'for-Dispatch' }}">
                        <td>@include('partials.car.table_content-parts.field-from')</td>

                        <td>
                            <input id="internal_shipping" type="number" class="form-control" name="internal_shipping"
                                required pattern=".*\S.*" title="This field cannot be empty or contain only spaces" >

                        </td>

                        <td>{{ $car->title }}</td>
                        <td>

                        </td>

                        <td>
                            
                            <button type="submit" class="btn btn-success btn-sm"
                                {{ $car->is_dispatch == 'no' ? 'disabled' : '' }}>
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
