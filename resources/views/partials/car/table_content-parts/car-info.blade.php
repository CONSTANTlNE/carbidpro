<div>
    <strong>VIN:<span>{{ $car->vin }}</span></strong>
    <img src="/assets/dist/img/copy.svg" alt="copy" class="copy">
</div>
<div>
    <strong>Model:<span>{{ $car->make_model_year }}</span></strong>
    <img src="/assets/dist/img/copy.svg" alt="copy" class="copy">
</div>
@if(request()->routeIs('car.showStatus'))
    <div>
        <strong>LOT:<span>{{ $car->lot }}</span></strong>
        <img src="/assets/dist/img/copy.svg" alt="copy" class="copy">
    </div>
@endif
<div>
    <strong>{{ !empty($car->Auction) ?  $car->Auction->name  : 'Gate/Iaai'}}
        :<span>{{ $car->gate_or_member }}</span></strong>
    <img src="/assets/dist/img/copy.svg" alt="copy" class="copy">
</div>


@if(!request()->routeIs('car.showStatus'))
<div>
    <strong>Owner:<span>{{ !empty($car->vehicle_owner_name) ? $car->vehicle_owner_name .' ' . $car->owner_id_number : 'N/A' }}</span></strong>
    <img src="/assets/dist/img/copy.svg" alt="copy" class="copy">
</div>
@endif
@if(request()->routeIs('car.showStatus'))

    <div>
        <strong>Dealer:<span>{{ $car->customer->contact_person }}</span></strong>
        <img src="/assets/dist/img/copy.svg" alt="copy" class="copy">
    </div>
@endif


{{--<div>--}}
{{--    <strong>Owner ID:<span>{{ !empty($car->owner_id_number) ? $car->owner_id_number : 'N/A' }}</span></strong>--}}
{{--    <img src="/assets/dist/img/copy.svg" alt="copy" class="copy">--}}
{{--</div>--}}
{{--<div>--}}
{{--    <strong>Owner Mob:<span>{{ !empty($car->owner_phone_number) ? $car->owner_phone_number : 'N/A' }}</span></strong>--}}
{{--    <img src="/assets/dist/img/copy.svg" alt="copy" class="copy">--}}
{{--</div>--}}

