
    <div class="form-group">
        <label for="car-list">
            Select a new car:
            <br>
            <br>
            <select name="new_car_id{{$key}}"
                    required
                    class="js-example-responsive js-states form-control"
                    >
                <option value="">Select Car</option>
                @foreach($carstoadd as $cartoadd)
                    <option value="{{$cartoadd->id}}" class="p-2">
                        <p> VIN : {{$cartoadd->vin}}
                            Model: {{$cartoadd->make_model_year}}
                        </p>
                    </option>
                @endforeach
            </select>
        </label>
    </div>


<div class="modal-footer">
    <button
            hx-post="{{route('container.replaceCar')}}"
            hx-include="[name='new_car_id{{$key}}']"
            hx-vals='{ "key": "{{$key}}" , "container_id": "{{$containerid}}","_token": "{{csrf_token()}}","oldcar_id":"{{$oldcar_id}}" }'
            hx-on::after-request="window.location.reload()"
            type="button" class="btn btn-primary"
             >
            Save changes
    </button>
</div>
