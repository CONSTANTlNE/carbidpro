<div class="col-12 mt-1" style="padding: 0">
    @hasanyrole('Admin|Developer')
    <a href="{{ route('car.edit', $car->id) }}">
        <button type="button" class="btn green_btn btn-sm mt-1">
            Edit Car
        </button>
    </a>
    @endrole
    <button type="button" class="btn btn-warning btn-sm mt-1" data-toggle="modal"
            data-target="#editcarmodal{{$index}}" data-car-id="{{ $car->id }}">
        Edit
    </button>
    <div class="modal fade" id="editcarmodal{{$index}}" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <h3>Edit Car {{$car->vin}}</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <label for="contact_info">Change Status</label>
                    <select name="status" class="form-control mb-1" id="customer_id"
                            hx-post="{{route('car.change.status')}}"
                            hx-vals='{"car_id": "{{ $car->id }}", "_token": "{{ csrf_token() }}" }'
                            onchange="setTimeout(() => { window.location.reload() }, 200)">
                        <option value="">Status</option>
                        @foreach($statuses as $statusindex => $status)

                            @if(auth()->user()?->hasAnyRole('Broker') && $status->id===2)
                                <option {{ $car->car_status_id == $status->id ? 'selected' : ''}} value="{{$status->id}}"> {{ $status->name }}</option>
                            @endif
                            @hasanyrole('Admin|Developer')
                                 <option {{ $car->car_status_id == $status->id ? 'selected' : ''}} value="{{$status->id}}"> {{ $status->name }}</option>
                            @endhasanyrole
                        @endforeach
                    </select>

                    <form action="{{route('car.minor.updates')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="car_id" value="{{$car->id}}">
                        <h4 class="mt-2 mb-1 text-center">Company</h4>
                        <label for="contact_info">Company Name</label>
                        <input @required($car->company_name!=null)  value="{{$car->company_name}}" type="text"
                               class="form-control mb-1" name="company_name" pattern=".*\S.*"
                               title="This field cannot be empty or contain only spaces">

                        <label for="contact_info ">Contact info</label>
                        <input @required($car->contact_info!=null)  value="{{$car->contact_info}}" type="text"
                               class="form-control mb-1" name="contact_info" pattern=".*\S.*"
                               title="This field cannot be empty or contain only spaces">

                        <label for="internal_shipping mt-1">Internal Shipping Rate</label>
                        <input style="max-width: 100px"
                               @required($car->internal_shipping!=null)   value="{{$car->internal_shipping}}"
                               type="number" class="form-control mb-1" name="internal_shipping" pattern=".*\S.*"
                               title="This field cannot be empty or contain only spaces">
                        <h4 class="mt-2 mb-1 text-center">Payment</h4>

                        <select @required($car->payment_method!=null)  aria-placeholder="" name="payment_method"
                                id="payment_method" class="form-control">
                            <option value="">Payment method</option>
                            <option @selected($car->payment_method==='zelle') value="zelle" selected="">Zelle</option>
                            <option @selected($car->payment_method==='check') value="check">Check</option>
                            <option @selected($car->payment_method==='bank') value="bank">Banktransfer</option>
                        </select>

                        <input @required($car->payment_company!=null) type="text" value="Payment Info"
                               placeholder="Receiver Name" name="payment_company" class="mt-1 form-control"
                               pattern=".*\S.*" title="This field cannot be empty or contain only spaces">
                        <input @required($car->payment_address!=null) type="text" value="Payment Info"
                               placeholder="Payment Address" name="payment_address" class="form-control mt-1"
                               pattern=".*\S.*" title="This field cannot be empty or contain only spaces">
                        <label for="internal_shipping mt-1">Recipte</label>

                        <input type="file" name="payment_photo" accept="image/*">

                        <div class="d-flex justify-content-center mt-2">
                            <button style="max-width: 100px" class="btn green_btn btn-sm">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>