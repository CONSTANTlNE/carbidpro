<div>
    <strong>VIN:<span style="cursor: pointer;"  onclick="customCopy(this)">{{ $car->vin }}</span></strong>
{{--    <img src="/assets/dist/img/copy.svg" alt="copy" class="copy">--}}
</div>
<div>
    <strong>Model:<span style="cursor: pointer;"  onclick="customCopy(this)">{{ $car->make_model_year }}</span></strong>
{{--    <img src="/assets/dist/img/copy.svg" alt="copy" class="copy">--}}
</div>
@if(request()->routeIs('car.showStatus'))
    <div>
        <strong>LOT:<span style="cursor: pointer;"  onclick="customCopy(this)">{{ $car->lot }}</span></strong>
{{--        <img src="/assets/dist/img/copy.svg" alt="copy" class="copy">--}}
    </div>
@endif
<div>
    <strong>{{ !empty($car->Auction) ?  $car->Auction->name  : 'Gate/Iaai'}}
        :<span style="cursor: pointer;"  onclick="customCopy(this)">{{ $car->gate_or_member }}</span></strong>
{{--    <img src="/assets/dist/img/copy.svg" alt="copy" class="copy">--}}
</div>


@if(!request()->routeIs('car.showStatus') )
<div>
    <strong>Owner:<span style="cursor: pointer;"  onclick="customCopy(this)">{{ !empty($car->vehicle_owner_name) ? $car->vehicle_owner_name .' ' . $car->owner_id_number : 'N/A' }}</span></strong>
{{--    <img src="/assets/dist/img/copy.svg" alt="copy" class="copy">--}}
</div>
@endif
@if(request()->routeIs('car.showStatus') || request()->routeIs('container.showStatus') )

    <div>
        <strong>Dealer:<span style="cursor: pointer;"  onclick="customCopy(this)">{{ $car->customer->contact_name }}</span></strong>
{{--        <img src="/assets/dist/img/copy.svg" alt="copy" class="copy">--}}
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

