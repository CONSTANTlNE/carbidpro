<input type="hidden"
       name="car_id"
       id="car_id_{{$key}}"
       value="{{$car->id}}">
<input type="hidden"
       name="key"
       id="key{{$key}}"
       value="{{$key}}">
@if($car->ready_for_pickup===0)
    <div hx-get="{{route('car.readyforpickup')}}"
         hx-include="#car_id_{{$key}},#key{{$key}}"
         hx-target="#target{{$key}}"
         class="btn btn-danger"
         type="button">
        not
        ready
    </div>
@else
    <div hx-get="{{route('car.readyforpickup')}}"
         hx-include="#car_id_{{$key}},#key{{$key}}"
         hx-target="#target{{$key}}"
         class="btn btn-success"
         type="button">
        ready
    </div>
@endif