    {{ !empty($car->state) ? $car->state->name : '' }}
    <br>
    <strong>ZIP:</strong> {{ $car->zip_code }}
    <br>
    <strong>WAREHOUSE:</strong>
    <br>
    {{ $car->warehouse }}
    <br>
    <strong>Container:</strong>
    @if($car->groups->isNotEmpty())
    <br>
    {{ $car->groups[0]->container_id ? $car->groups[0]->container_id : 'N/A'}}
    @else
        <br>
        Not Grouped Yet
    @endif