<div class="table-responsive">
    <table id="dataTableExample1" class="table table-bordered table-striped table-hover">
        <thead class="back_table_color">
            <tr class="info">
                <th>ID</th>
                <th>CAR INFO</th>
                <th>FROM-TO</th>
                <th style="width:10%">Price</th>

                <th>Title Status</th>
                <th>Created at</th>
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
                            <input id="internal_shipping" type="number" class="form-control" name="internal_shipping"
                                required>

                        </td>

                        <td>{{ $car->title }}</td>
                        <td>

                        </td>

                        <td>

                            <button type="submit" class="btn btn-success btn-sm"
                                {{ $car->is_dispatch == 'no' ? 'disabled' : '' }}>
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
                            <strong>Create:</strong> {{ $car->created_at->format('d.m.y') }} <br>
                            <strong>Update:</strong> {{ $car->updated_at->format('d.m.y') }} <br>

                        </td>
                    </form>

                </tr>
            @endforeach

        </tbody>
    </table>
</div>
