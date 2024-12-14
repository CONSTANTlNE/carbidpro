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
    <h2>New Car</h2>
    <p>Dealer: {{ $dealer->contact_name }} - {{ $dealer->email }}</p>
    <p>Model: {{ $model }}</p>
    <p>VIN: {{ $vin }}</p>
    @if (!empty($balance_accounting))
        @foreach ($balance_accounting as $cost)
            <p>{{ $cost['name'] }}: ${{ $cost['value'] }}</p>
        @endforeach
    @endif
    <p>Total: {{ $total_cost }}</p>

</body>

</html>
