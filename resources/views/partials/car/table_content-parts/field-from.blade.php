<span style="cursor: pointer" onclick="customCopy(this)">
{{ !empty($car->state) ? $car->state->name : '' }}
</span>
<br>
<div style="max-width: 200px" class="mb-1">
    <strong>ZIP:</strong>
    <span style="cursor: pointer"
          onclick="customCopy(this)">
       {{ $car->zip_code }}
</span>
</div>
<div style="max-width: 200px" class="mb-1">
    <strong>WAREHOUSE:</strong>
    <span style="cursor: pointer"
          onclick="customCopy(this)">
    {{ $car->warehouse }}
    </span>

</div>
@if(request()->routeIs('cars.index') || (request()->routeIs('car.showStatus') && request()->route('slug') === 'dispatched'))
    <strong>Container:</strong>
    @if($car->groups->isNotEmpty())
        <br>
        <span style="cursor: pointer"
              onclick="customCopy(this)">
    {{ $car->groups[0]->container_id ?: 'N/A'}}
    </span>
        {{--    <br>--}}
        {{--    Not Grouped Yet--}}
    @endif
@endif
