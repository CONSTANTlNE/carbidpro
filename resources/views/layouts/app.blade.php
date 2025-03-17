<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon and touch icons -->
    <link rel="shortcut icon" href="/assets/dist/img/ico/favicon.png" type="image/x-icon">
    <!-- Start Global Mandatory Style
      =====================================================================-->

    <!-- lobicard tather css -->
    <link rel="stylesheet" href="/assets/plugins/lobipanel/css/tether.min.css"/>
    <!-- Bootstrap -->
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
    <!-- lobicard tather css -->
    <link rel="stylesheet" href="/assets/plugins/lobipanel/css/jquery-ui.min.css"/>
    <!-- lobicard min css -->
    <link href="/assets/plugins/lobipanel/css/lobicard.min.css" rel="stylesheet"/>
    <!-- lobicard github css -->
    <link href="/assets/plugins/lobipanel/css/github.css" rel="stylesheet"/>
    <!-- Pace css -->
    <link href="/assets/plugins/pace/flash.css" rel="stylesheet"/>
    <!-- Font Awesome -->
    <link href="/assets/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
    <!-- Pe-icon -->
    <link href="/assets/pe-icon-7-stroke/css/pe-icon-7-stroke.css" rel="stylesheet"/>
    <!-- Themify icons -->
    <link href="/assets/themify-icons/themify-icons.css" rel="stylesheet"/>
    <!-- End Global Mandatory Style
      =====================================================================-->
    <!-- Start page Label Plugins
      =====================================================================-->
    <!-- Emojionearea -->
    <link href="/assets/plugins/emojionearea/emojionearea.min.css" rel="stylesheet"/>
    <!-- Monthly css -->
    <link href="/assets/plugins/monthly/monthly.css" rel="stylesheet"/>
    <!-- End page Label Plugins
      =====================================================================-->
    <!-- Start Theme Layout Style
      =====================================================================-->
    <!-- Theme style -->
    <link href="/assets/dist/css/stylecrm.css?v={{time()}}" rel="stylesheet"/>
    <!-- Theme style rtl -->
    <!--<link href="assets/dist/css/stylecrm-rtl.css" rel="stylesheet" />-->
    <!-- End Theme Layout Style

      =====================================================================-->
    <link href="{{asset('assets/plugins/modals/component.css')}}" rel="stylesheet"/>
    <script src="{{asset('assets/htmx203.min.js')}}"
            integrity="sha384-0895/pl2MU10Hqc6jd4RvrthNlDiE9U1tWmX7WRESftEDRosgxNsQG/Ze9YMRzHq"
            crossorigin="anonymous"></script>
    @stack('css')
</head>

{{--<body class="@yield('body-class', '')">--}}
<body class="sidebar-mini  pace-done">

<div id="htmxerrors"></div>

@yield('content')
@yield('auctions')
@yield('locations')
@yield('load-types')
@yield('shipping-prices')
@yield('ports')
@yield('customers')
@yield('customers.extra')
@yield('payment_request')
@yield('allsms')
@yield('smsdrafts')
@yield('selectedsms')
@yield('roles')
@yield('states')
@yield('titles')
@yield('shipping_lines')

{{-- Site Settings --}}
@yield('sliders')
@yield('announces')
@yield('settings')
@yield('services')



<!-- jQuery -->
<script src="/assets/plugins/jQuery/jquery-1.12.4.min.js"></script>
<!-- Bootstrap proper -->
<script src="/assets/bootstrap/js/popper.min.js"></script>
<!-- lobicard ui min js -->
<script src="/assets/plugins/lobipanel/js/jquery-ui.min.js"></script>
<!-- lobicard ui touch-punch-improved js -->
<script src="/assets/plugins/lobipanel/js/jquery.ui.touch-punch-improved.js"></script>
<!-- lobicard tether min js -->
<script src="/assets/plugins/lobipanel/js/tether.min.js"></script>
<!-- Bootstrap -->
<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
<!-- lobicard js -->
<script src="/assets/plugins/lobipanel/js/lobicard.js"></script>
<!-- lobicard highlight js -->
<script src="/assets/plugins/lobipanel/js/highlight.js"></script>
<!-- Pace js -->
<script src="/assets/plugins/pace/pace.min.js"></script>
<!-- NIceScroll -->
<script src="/assets/plugins/slimScroll/jquery.nicescroll.min.js"></script>
<!-- FastClick -->
<script src="/assets/plugins/fastclick/fastclick.min.js"></script>
<!-- CRMadmin frame -->
<script src="/assets/dist/js/custom.js"></script>

<!-- End Core Plugins
     =====================================================================-->
<!-- Start Page Lavel Plugins
     =====================================================================-->
<!-- ChartJs JavaScript -->
<script src="/assets/plugins/chartJs/Chart.min.js"></script>
<!-- Counter js -->
<script src="/assets/plugins/counterup/waypoints.js"></script>
<script src="/assets/plugins/counterup/jquery.counterup.min.js"></script>
<!-- Monthly js -->
<script src="/assets/plugins/monthly/monthly.js"></script>
<!-- End Page Lavel Plugins
     =====================================================================-->
<!-- Start Theme label Script
     =====================================================================-->
<!-- Dashboard js -->
<script src="/assets/dist/js/dashboard.js"></script>
<!-- End Theme label Script
     =====================================================================-->
<!-- Modal js -->
<script src="{{asset('assets/plugins/modals/classie.js')}}"></script>
<script src="{{asset('assets/js/custom-htmx.js')}}"></script>
<script src="{{asset('assets/plugins/modals/modalEffects.js')}}"></script>

<script>
    function dash() {
        // single bar chart
        var ctx = document.getElementById("singelBarChart");
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Sun", "Mon", "Tu", "Wed", "Th", "Fri", "Sat"],
                datasets: [{
                    label: "My First dataset",
                    data: [40, 55, 75, 81, 56, 55, 40],
                    borderColor: "rgba(0, 150, 136, 0.8)",
                    width: "1",
                    borderWidth: "0",
                    backgroundColor: "rgba(0, 150, 136, 0.8)"
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });


    }
</script>

{{--<script>--}}
{{--    let sessionLifetime = 120; // Minutes (from Laravel)--}}
{{--    let refreshTime = (sessionLifetime - 5) * 60 * 1000; // Convert to ms--}}
{{--    let countdownSeconds = refreshTime / 1000; // Convert to seconds--}}

{{--    function updateCountdown() {--}}
{{--        let minutes = Math.floor(countdownSeconds / 60);--}}
{{--        let seconds = countdownSeconds % 60;--}}
{{--        document.getElementById("countdown").innerText = `Refreshing in ${minutes}m ${seconds}s`;--}}

{{--        if (countdownSeconds > 0) {--}}
{{--            countdownSeconds--;--}}
{{--            setTimeout(updateCountdown, 1000); // Update every second--}}
{{--        } else {--}}
{{--            location.reload(); // Refresh page when countdown reaches zero--}}
{{--        }--}}
{{--    }--}}

{{--    updateCountdown(); // Start countdown--}}
{{--</script>--}}

@stack('js')
</body>

</html>
