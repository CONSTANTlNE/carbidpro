{{ !empty($car->state) ? $car->state->name : '' }}
<br>
<strong>ZIP:</strong>
<span style="cursor: pointer"
      onclick="copyToClipboard(this)">
       {{ $car->zip_code }}
</span>
<br>
<strong>WAREHOUSE:</strong>
<br>
<span style="cursor: pointer"
      onclick="copyToClipboard(this)">
    {{ $car->warehouse }}
    </span>
<br>
<strong>Container:</strong>
@if($car->groups->isNotEmpty())
    <br>
    <span style="cursor: pointer"
          onclick="copyToClipboard(this)">
    {{ $car->groups[0]->container_id ?: 'N/A'}}
    </span>
@else
{{--    <br>--}}
{{--    Not Grouped Yet--}}
@endif