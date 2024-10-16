<!DOCTYPE html>
<html>

<head>
    <title>Car Group Information</title>
</head>

<body>
    <h1>Load Vehicles in container</h1>

    <p>Hello</p>

    <p>Please Load this Vehicles:</p>
    <ul>
        @foreach ($carGroup as $car)
            <li style="color:rgb(47,85,151)">Vin# {{ $car->vin }} / {{ $car->make_model_year }}
                ({{ $car->vehicle_owner_name }} {{ $car->owner_id_number }})
            </li>
        @endforeach
    </ul>

    <p>Thank you!</p>
</body>

</html>
