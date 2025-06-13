<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Blueberry - Multi Purpose eCommerce Template.">
    <meta name="keywords"
        content="eCommerce, mart, apparel, bootstrap 5, catalog, fashion, html, multipurpose, online store, shop, store, template">
    <title>{{config('app.name')}} - @yield('title')</title>

    <!-- Site Favicon -->
    <link rel="icon" href="assets/img/favicon/favicon.png')}}" type="image/x-icon">

    <!-- Css All Plugins Files -->
    <link rel="stylesheet" href="{{ asset('website/assets/css/vendor/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/vendor/remixicon.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/vendor/aos.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/vendor/swiper-bundle.min.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/vendor/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/vendor/slick.min.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/vendor/animate.min.css')}}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/vendor/jquery-range-ui.css')}}">

    <!-- Main Style -->
    <link rel="stylesheet" href="{{ asset('website/assets/css/style.css')}}">

    @viteReactRefresh
    @vite('resources/js/app.jsx')
    @inertiaHead
</head>

<body>

    @inertia

    <!-- Plugins -->
    <script src="{{ asset('website/assets/js/vendor/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('website/assets/js/vendor/jquery.min.js')}}"></script>
    <script src="{{ asset('website/assets/js/vendor/jquery.zoom.min.js')}}"></script>
    <script src="{{ asset('website/assets/js/vendor/aos.js')}}"></script>
    <script src="{{ asset('website/assets/js/vendor/swiper-bundle.min.js')}}"></script>
    <script src="{{ asset('website/assets/js/vendor/smoothscroll.min.js')}}"></script>
    {{-- <script src="{{ asset('website/assets/js/vendor/countdownTimer.js')}}"></script> --}}
    <script src="{{ asset('website/assets/js/vendor/owl.carousel.min.js')}}"></script>
    <script src="{{ asset('website/assets/js/vendor/slick.min.js')}}"></script>
    <script src="{{ asset('website/assets/js/vendor/jquery-range-ui.min.js')}}"></script>
    <script src="{{ asset('website/assets/js/vendor/tilt.jquery.min.js')}}"></script>

    <!-- main-js -->
    <script src="{{ asset('website/assets/js/main.js')}}"></script>
</body>
</html>