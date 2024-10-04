@if (!empty($car->state))
    {{ $car->state->name }} <br> ZIP: {{ $car->zip_code }}<br> WAREHOUSE: {{ $car->warehouse }}
@endif
