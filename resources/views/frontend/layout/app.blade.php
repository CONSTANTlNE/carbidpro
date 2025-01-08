<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    @inject('settings', '\App\Services\SettingsService')

    <title>Carbidpro</title>

    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.css" />
    <!-- Add the slick-theme.css if you want default styling -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick-theme.css" />


    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> --}}
    <link rel="stylesheet" href="{{asset('frontendAssets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('frontendAssets/css/fontawesome-all.css')}}">
    <link rel="stylesheet" href="{{asset('frontendAssets/css/flaticon.css')}}">
    <link rel="stylesheet" href="{{asset('frontendAssets/css/animate.css')}}">
    <link rel="stylesheet" href="{{asset('frontendAssets/css/nice-select.css')}}">
    <link rel="stylesheet" href="{{asset('frontendAssets/css/video.min.css')}}">
    <link rel="stylesheet" href="{{asset('frontendAssets/css/jquery.mCustomScrollbar.min.css')}}">
    <link rel="stylesheet" href="{{asset('frontendAssets/css/slick.css')}}">
    <link rel="stylesheet" href="{{asset('frontendAssets/css/rs6.css')}}">
    <link rel="stylesheet" href="{{asset('frontendAssets/css/slick-theme.css')}}">
    <link rel="stylesheet" href="{{asset('frontendAssets/css/style.css?v=')}}{{ time() }}">
    <link rel="stylesheet" href="{{asset('frontendAssets/css/main.css?v=')}}{{ time() }}">
    @stack('style')

</head>

<body>
@include('frontend.partials.header')
<div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
    @if (Route::has('login'))
        <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
            @auth
                <a href="{{ url('/home') }}"
                   class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Home</a>
            @else
                <a href="{{ route('login') }}"
                   class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log
                    in</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                @endif
            @endauth
        </div>
    @endif

</div>
@yield('content')
@include('frontend.partials.footer')


<script src="{{asset('frontendAssets/js/jquery.min.js')}}"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
{{-- <script src="/assets/js/bootstrap.min.js"></script> --}}
<script src="{{asset('frontendAssets/js/popper.min.js')}}"></script>
<script src="{{asset('frontendAssets/js/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('frontendAssets/js/appear.js')}}"></script>
<script src="{{asset('frontendAssets/js/slick.js')}}"></script>
<script src="{{asset('frontendAssets/js/jquery.counterup.min.js')}}"></script>
<script src="{{asset('frontendAssets/js/waypoints.min.js')}}"></script>
<script src="{{asset('frontendAssets/js/imagesloaded.pkgd.min.js')}}"></script>
<script src="{{asset('frontendAssets/js/jquery.filterizr.js')}}"></script>
<script src="{{asset('frontendAssets/js/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<script src="{{asset('frontendAssets/js/wow.min.js')}}"></script>
<script src="{{asset('frontendAssets/js/jquery.cssslider.min.js')}}"></script>
<script src="{{asset('frontendAssets/js/rbtools.min.js')}}"></script>
<script src="{{asset('frontendAssets/js/rs6.min.js')}}"></script>
<script src="{{asset('frontendAssets/js/script.js?v=')}}{{ time() }}"></script>

@stack('scripts')

</body>

</html>
