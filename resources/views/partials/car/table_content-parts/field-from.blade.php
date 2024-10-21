    {{ !empty($car->state) ? $car->state->name : '' }} <br> <strong>ZIP:</strong> {{ $car->zip_code }}<br> <strong>WAREHOUSE:</strong><br>
    {{ $car->warehouse }}
