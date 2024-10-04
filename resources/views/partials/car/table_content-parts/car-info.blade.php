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

