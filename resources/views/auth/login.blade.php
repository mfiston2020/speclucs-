@extends('layouts.app')

@push('css')

@endpush

@section('title','Login')

@section('content')
    <div class="auth-wrapper d-flex no-block justify-content-center align-items-center"
        style="background:url({{ asset('dashboard/assets/images/bg.jpg')}}) no-repeat center/cover;">
        <div class="auth-box">
            <div id="loginform">
                <div class="logo">
                    <span class="db"><img src="{{ asset('dashboard/assets/images/logo-icon.png')}}" alt="logo" /></span>
                    <h5 class="font-medium m-b-20 mt-2">Sign In</h5>
                </div>
                <!-- Form -->
                <div class="row">
                    <div class="col-12">
                        <form class="form-horizontal m-t-20" id="loginform" action="{{route('login')}}" method="POST">
                            @csrf
                            @if ($errors->has('email'))
                                @foreach ($errors->all() as $message)
                                <div class="alert alert-danger alert-rounded">
                                    {{$message}}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span
                                            aria-hidden="true">×</span> </button>
                                </div>
                                @endforeach
                            @endif
                            {{-- ================ reset password message =============== --}}
                            @if (session('status'))
                                <div class="alert alert-success alert-rounded">
                                    {{ session('status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span
                                            aria-hidden="true">×</span> </button>
                                </div>
                            @endif
                            <div class="input-group mb-3 status-error">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                                </div>
                                <input type="email" class="form-control form-control-lg" placeholder="email"
                                    aria-label="Username" aria-describedby="basic-addon1" name="email" value="{{old('email')}}">

                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon2"><i class="ti-pencil"></i></span>
                                </div>
                                <input type="password" class="form-control form-control-lg" placeholder="Password"
                                    aria-describedby="basic-addon1" name="password">
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" name="remember"
                                        {{old('remember')?'checked':''}}>
                                        <label class="custom-control-label" for="customCheck1">Remember me</label>
                                        <a href="javascript:void(0)" id="to-recover" class="text-dark float-right"><i
                                                class="fa fa-lock m-r-5"></i> Forgot pwd?</a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <div class="col-xs-12 p-b-20">
                                    <button class="btn btn-block btn-lg btn-info" type="submit">Log In</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div id="recoverform">
                <div class="logo">
                    <a href="/">
                        <span class="db"><img src="{{ asset('dashboard/assets/images/logo-icon.png')}}" alt="logo" /></span>
                    </a>
                    <h5 class="font-medium m-b-20">Recover Password</h5>
                    <span>Enter your Email and instructions will be sent to you!</span>
                </div>
                <div class="row m-t-20">
                    <!-- Form -->
                    <form class="col-12" action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <!-- email -->
                        <div class="form-group row">
                            <div class="col-12">
                                <input class="form-control form-control-lg" name="email" type="email" required="" placeholder="Your Email">
                            </div>
                        </div>
                        <!-- pwd -->
                        <div class="row m-t-20">
                            <div class="col-12">
                                <button class="btn btn-block btn-lg btn-danger" type="submit" name="action">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $('[data-toggle="tooltip"]').tooltip();
    $(".preloader").fadeOut();
    // ==============================================================
    // Login and Recover Password
    // ==============================================================
    $('#to-recover').on("click", function () {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });

</script>
@endpush
