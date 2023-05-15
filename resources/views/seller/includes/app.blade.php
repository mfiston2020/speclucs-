<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title')</title>
    <!-- Custom CSS -->
    <link href="{{ asset('dashboard/assets/libs/chartist/dist/chartist.min.css')}}" rel="stylesheet">
    <link href="{{ asset('dashboard/assets/extra-libs/c3/c3.min.css')}}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('dashboard/assets/dist/css/style.min.css')}}" rel="stylesheet">
    @stack('css')
    @livewireStyles
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-MPHB4250YR"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-MPHB4250YR');
</script>
</head>

<body>
    <!-- Preloader - style you can find in spinners.css -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <div id="main-wrapper">
        
        @include('seller.includes.layouts.header')

        <div class="page-wrapper">
            <!-- Bread crumb and right sidebar toggle -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-5 align-self-center">
                        <h4 class="page-title">@yield('page_name')</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">@yield('current')</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Container fluid  -->
            
            @yield('content')

            <!-- End Container fluid  -->
            
            @include('seller.includes.layouts.footer')
        </div>
    </div>
    
    <!-- All Jquery -->
    <script src="{{ asset('dashboard/assets/libs/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('dashboard/assets/libs/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{ asset('dashboard/assets/libs/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- apps -->
    <script src="{{ asset('dashboard/assets/dist/js/app.min.js')}}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/app.init.light-sidebar.js')}}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/app-style-switcher.js')}}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('dashboard/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js')}}"></script>
    <script src="{{ asset('dashboard/assets/extra-libs/sparkline/sparkline.js')}}"></script>
    <!--Wave Effects -->
    <script src="{{ asset('dashboard/assets/dist/js/waves.js')}}"></script>
    <!--Menu sidebar -->
    <script src="{{ asset('dashboard/assets/dist/js/sidebarmenu.js')}}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('dashboard/assets/dist/js/custom.min.js')}}"></script>
    <!--This page JavaScript -->
    @stack('scripts')
    @livewireScripts
</body>
</html>
