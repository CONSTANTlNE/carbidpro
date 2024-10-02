@if (!empty($car->state))
    {{ $car->state->name }} <br> ZIP: {{ $car->zip_code }}
@endif
