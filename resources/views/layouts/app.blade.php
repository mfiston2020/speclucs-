<!DOCTYPE html>
<html dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>Stock</title>
    <!-- Custom CSS -->
    <link href="{{ asset('dashboard/assets/dist/css/style.min.css')}}" rel="stylesheet">

    @stack('css')

</head>

<body>
    <div class="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        
        @yield('content')
        
    </div>
    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="{{ asset('dashboard/assets/libs/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('dashboard/assets/libs/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{ asset('dashboard/assets/libs/bootstrap/dist/js/bootstrap.min.js')}}"></script>

    @stack('scripts')

</body>
</html>