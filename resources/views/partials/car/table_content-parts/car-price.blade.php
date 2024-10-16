
<div class="d-flex" style="gap: 5px"><strong>Price: </strong>
    ${{ $car->internal_shipping }}</div>
<div class="d-flex" style="flex-wrap: wrap;"><strong>Storage-{{ $car->storage }}: </strong><span>${{ $car->storage_cost }}</span>
</div>
@if ($car->storage != 'Owner')
    <div class="d-flex" style="gap: 5px"><strong>Sum: </strong>
        ${{ $car->internal_shipping + $car->storage_cost }}</div>
@else
    <div class="d-flex" style="gap: 5px"><strong>Sum: </strong>
        ${{ $car->internal_shipping }}</div>
@endif
