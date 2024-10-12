<!DOCTYPE html>
<html>

<head>
    <title>Car Group Information</title>
</head>

<body>
    <h1>Car Group Details</h1>

    <p>Hello,</p>

    <p>List of Cars in this Container:</p>
    <ul>
        @foreach ($carGroup as $car)
            <li>VIN: {{ $car->vin }}</li>
            <li>Owner Name: {{ $car->vehicle_owner_name }}</li>
            <li>Owner ID Number: {{ $car->owner_id_number }}</li>
            <li>Owner Phone: {{ $car->owner_phone_number }}</li>
            <li>Container: {{ $car->container_number }}</li>
        @endforeach
    </ul>

    <p>Thank you!</p>
</body>

</html>
