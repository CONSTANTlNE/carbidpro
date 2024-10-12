    {{ !empty($car->state) ? $car->state->name : '' }} <br> ZIP: {{ $car->zip_code }}<br> WAREHOUSE:<br>
    {{ $car->warehouse }}
