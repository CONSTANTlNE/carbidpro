<!DOCTYPE html>
<html>

<head>
    <title></title>
</head>

<body>
    <style>
       p {
            margin: 0 !important;
            margin-block-start: 0 !important;
            margin-block-end: 0 !important;
        }
    </style>
    <h2>Payment Report</h2>
    <p>Dealer Name: {{ $dealer_name }}</p>
    <p>Dealer Email: {{ $dealer_email }}</p>
    <p>Car: {{ $car_model }}</p>
    <p>VIN: {{ $car_vin }}</p>
    <p>Payed: ${{ $payed }}</p>
</body>

</html>
