<div class="d-flex" style="gap: 5px"><strong>Price: </strong>
    ${{ $car->internal_shipping }}</div>
@php
    // Calculate the sum for "Storage" keys
    $data = json_decode($car->balance_accounting, true);
    $storageSum = array_reduce(
        $data,
        function ($sum, $item) {
            if (strtolower($item['name']) === 'storage') {
                $sum += $item['value'];
            }
            return $sum;
        },
        0,
    );

@endphp
<div class="d-flex" style="flex-wrap: wrap;"><strong>Strg-{{ $car->storage }}: 
    </strong><span> ${{ $car->storage_cost }}</span>
</div>
@if ($car->storage != 'Owner')
    <div class="d-flex" style="gap: 5px"><strong>Sum: </strong>
        ${{ $car->internal_shipping + $car->storage_cost }}</div>
@else
    <div class="d-flex" style="gap: 5px"><strong>Sum: </strong>
        ${{ $car->internal_shipping }}</div>
@endif
