<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<form action="{{route('customer.upload')}}" method="post" enctype="multipart/form-data">
    @csrf
    <label for="customers">Customers</label>
    <input id="customers" type="file" name="file">

    <button>upload</button>
    <br><br><br>
</form>

<form action="{{route('user.upload')}}" method="post" enctype="multipart/form-data">
    @csrf
    <label for="users">Users</label>
    <input id="users" type="file" name="file">

    <button>upload</button>
    <br><br><br>
</form>


<p>Your CSRF token will expire in: <span id="countdown"></span></p>

<script>
    // CSRF token expiration time in seconds (passed from the controller)
    let csrfExpiration = {{ $csrfExpiration }};

    // Function to update the countdown
    function updateCountdown() {
        const minutes = Math.floor(csrfExpiration / 60);
        const seconds = csrfExpiration % 60;

        // Display the countdown
        document.getElementById('countdown').innerText = `${minutes}m ${seconds}s`;

        // Decrease the remaining time
        csrfExpiration--;

        // If the countdown reaches 0, stop the timer
        if (csrfExpiration < 0) {
            clearInterval(countdownInterval);
            document.getElementById('countdown').innerText = 'Expired!';
        }
    }

    // Update the countdown every second
    const countdownInterval = setInterval(updateCountdown, 1000);

    // Initial call to display the countdown immediately
    updateCountdown();
</script>
</body>
</html>