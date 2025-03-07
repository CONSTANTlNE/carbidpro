<div>
    <strong>Owner:<span>{{ !empty($car->vehicle_owner_name) ? $car->vehicle_owner_name : 'N/A' }}</span></strong>
    <img src="/assets/dist/img/copy.svg" alt="copy" class="copy">
</div>
<div>
    <strong>Owner ID:<span>{{ !empty($car->owner_id_number) ? $car->owner_id_number : 'N/A' }}</span></strong>
    <img src="/assets/dist/img/copy.svg" alt="copy" class="copy">
</div>
<div>
    <strong>Owner Mob:<span>{{ !empty($car->owner_phone_number) ? $car->owner_phone_number : 'N/A' }}</span></strong>
    <img src="/assets/dist/img/copy.svg" alt="copy" class="copy">
</div>
<div>
    <strong>LOT:<span>{{ $car->lot }}</span></strong>
    <img src="/assets/dist/img/copy.svg" alt="copy" class="copy">
</div>
<div>
    <strong>{{ !empty($car->Auction) ?  $car->Auction->name  : 'Gate/Iaai'}}:<span>{{ $car->gate_or_member }}</span></strong>
    <img src="/assets/dist/img/copy.svg" alt="copy" class="copy">
</div>
<div>
    <strong>Model:<span>{{ $car->make_model_year }}</span></strong>
    <img src="/assets/dist/img/copy.svg" alt="copy" class="copy">
</div>
<div>
    <strong>VIN:<span>{{ $car->vin }}</span></strong>
    <img src="/assets/dist/img/copy.svg" alt="copy" class="copy">
</div>

