@if($car->ready_for_pickup===0)
    <div hx-get="{{route('car.readyforpickup')}}"
         hx-vals='{"carindex": "{{$carindex}}","car_id": "{{$car->id}}"}'
         hx-target="#target{{$car->vin}}"
         class="btn btn-danger"
         type="button">
        not ready
    </div>
@else
    <div hx-get="{{route('car.readyforpickup')}}"
         hx-vals='{"carindex": "{{$carindex}}","car_id": "{{$car->id}}"}'
         hx-target="#target{{$car->vin}}"
         class="btn btn-success"
         type="button">
        ready
    </div>
@endif