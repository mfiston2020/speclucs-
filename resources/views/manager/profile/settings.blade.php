@extends('manager.includes.app')

@section('title','Manager\' Settings')

@push('css')
<style>
    .switch {
    cursor: pointer;
    }
    .switch input {
    display: none;
    }
    .switch input + span {
    width: 48px;
    height: 28px;
    border-radius: 14px;
    transition: all 0.3s ease;
    display: block;
    position: relative;
    background: #FF4651;
    box-shadow: 0 8px 16px -1px rgba(255, 70, 81, 0.2);
    }
    .switch input + span:before, .switch input + span:after {
    content: "";
    display: block;
    position: absolute;
    transition: all 0.3s ease;
    }
    .switch input + span:before {
    top: 5px;
    left: 5px;
    width: 18px;
    height: 18px;
    border-radius: 9px;
    border: 5px solid #fff;
    }
    .switch input + span:after {
    top: 5px;
    left: 32px;
    width: 6px;
    height: 18px;
    border-radius: 40%;
    transform-origin: 50% 50%;
    background: #fff;
    opacity: 0;
    }
    .switch input + span:active {
    transform: scale(0.92);
    }
    .switch input:checked + span {
    background: #48EA8B;
    box-shadow: 0 8px 16px -1px rgba(72, 234, 139, 0.2);
    }
    .switch input:checked + span:before {
    width: 0px;
    border-radius: 3px;
    margin-left: 27px;
    border-width: 3px;
    background: #fff;
    }
    .switch input:checked + span:after {
    -webkit-animation: blobChecked 0.35s linear forwards 0.2s;
            animation: blobChecked 0.35s linear forwards 0.2s;
    }
    .switch input:not(:checked) + span:before {
    -webkit-animation: blob 0.85s linear forwards 0.2s;
            animation: blob 0.85s linear forwards 0.2s;
    }

    @-webkit-keyframes blob {
    0%, 100% {
        transform: scale(1);
    }
    30% {
        transform: scale(1.12, 0.94);
    }
    60% {
        transform: scale(0.96, 1.06);
    }
    }

    @keyframes blob {
    0%, 100% {
        transform: scale(1);
    }
    30% {
        transform: scale(1.12, 0.94);
    }
    60% {
        transform: scale(0.96, 1.06);
    }
    }
    @-webkit-keyframes blobChecked {
    0% {
        opacity: 1;
        transform: scaleX(1);
    }
    30% {
        transform: scaleX(1.44);
    }
    70% {
        transform: scaleX(1.18);
    }
    50%, 99% {
        transform: scaleX(1);
        opacity: 1;
    }
    100% {
        transform: scaleX(1);
        opacity: 0;
    }
    }
    @keyframes blobChecked {
    0% {
        opacity: 1;
        transform: scaleX(1);
    }
    30% {
        transform: scaleX(1.44);
    }
    70% {
        transform: scaleX(1.18);
    }
    50%, 99% {
        transform: scaleX(1);
        opacity: 1;
    }
    100% {
        transform: scaleX(1);
        opacity: 0;
    }
    }
</style>

@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Profile')
@section('page_name','Profile')
{{-- === End of breadcumb == --}}

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-lg-4 col-xlg-3 col-md-5">
                <div class="card">
                    <div class="card-body">
                        <center class="m-t-30"> <img src="{{ asset('dashboard/assets/images/users/1.jpg')}}"
                                class="rounded-circle" width="150" />
                            <h4 class="card-title m-t-10">{{$user_info->name}}</h4>
                            <h6 class="card-subtitle" style="text-transform: uppercase">{{Auth::user()->role}}</h6>
                        </center>
                    </div>
                    <div>
                        <hr>
                    </div>
                    <div class="card-body">
                        <h4>About The Company</h4><br>
                        <center class="m-t-30"> <img src="{{ asset('documents/logos/'.$company->logo)}}"
                            class="" width="150" /> <br>
                        <small class="text-muted">Name </small>
                        <h6>{{$company->company_name}}</h6>
                        <small class="text-muted">Email address </small>
                        <h6>{{$company->company_email}}</h6>
                        <small class="text-muted p-t-30 db">Phone</small>
                        <h6>{{$company->company_phone}}</h6>
                        <small class="text-muted p-t-30 db">Address</small>
                        <h6>{{$company->company_street}}</h6>
                        <small class="text-muted p-t-30 db">TIN</small>
                        <h6>{{$company->company_tin_number}}</h6>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="col-lg-8 col-xlg-9 col-md-7">
                <div class="card">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="current-month" role="tabpanel"
                            aria-labelledby="pills-timeline-tab">
                            <div class="card-body">
                                {{-- <hr> --}}
                                <h5>Account Settings</h5>
                                <hr>

                                <div class="alert alert-rounded col-lg-12 col-md-12 col-sm-12" id="alert-show">
                                    <b id="setting-error-name">Error! </b>
                                    <span id="setting-error-message"></span>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span>
                                    </button>
                                </div>

                                <form class="form-horizontal form-material" action="{{route('manager.profile.password')}}" method="POST">
                                    @csrf

                                    <div class="m-b-30 bt-switch">
                                        <div class="form-group">
                                            <label for="option1">Can Supply</label><br>
                                            <label class="switch">
                                                <input type="checkbox" id="supplier" {{(getuserCompanyInfo()->can_supply=='1')?'checked':''}}>
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                @if (auth()->user()->permissions=='manager')
                <div class="card">
                    {{-- ============================== --}}
                    @include('manager.includes.layouts.message')
                    {{-- =============================== --}}

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="current-month" role="tabpanel"
                            aria-labelledby="pills-timeline-tab">
                            <div class="card-body">
                                {{-- <hr> --}}
                                <h5>Client SMS</h5>

                                <form class="form-horizontal form-material" action="{{route('manager.profile.sms.message')}}"
                                    method="POST">
                                    @csrf

                                    <div class="m-b-30 bt-switch">
                                        <div class="form-group">
                                            <textarea name="message" class="form-control" cols="30" rows="10">{{$company->sms_message}}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button class="btn btn-primary">Change SMS</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <!-- Column -->
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $("#alert-show").hide();

        // ================= changing the state of supply visibility =============
        $('#supplier').on('change',function()
        {
            $.ajax({
                url: "{{ route('manager.supplier.confirm') }}",
                method: 'POST',
                data: {
                        "_token": "{{ csrf_token() }}",
                    },

                success: function (data) {
                    console.log(data);

                    if (data == 'error') {
                        $("#alert-show").show();
                        document.getElementById("alert-show").classList.add('alert-success');
                        document.getElementById("alert-show").classList.add('alert-danger');
                        $('#setting-error-name').text('Error');
                        $('#setting-error-message').text('Something Went Wrong!');
                    }

                    if (data == 'success') {
                        $("#alert-show").show();
                        document.getElementById("alert-show").classList.remove('alert-danger');
                        document.getElementById("alert-show").classList.add('alert-success');
                        $('#setting-error-name').text('Success! ');
                        $('#setting-error-message').text('Status Successfully changed!');
                    }else{

                        $("#alert-show").hide();
                    }
                },
                error: function (data) {
                    console.log(data);
                }
            });
        });
        // ================= changing the state of stock visibility =============
        $('#stock').on('change',function()
        {
            $.ajax({
                url: "{{ route('manager.supplier.stock.state') }}",
                method: 'POST',
                data: {
                        "_token": "{{ csrf_token() }}",
                    },

                success: function (data) {
                    console.log(data);

                    if (data == 'error') {
                        $("#alert-show").show();
                        document.getElementById("alert-show").classList.add('alert-danger');
                        $('#setting-error-name').text('Error');
                        $('#setting-error-message').text('Something Went Wrong!');
                    }

                    if (data == 'success') {
                        $("#alert-show").show();
                        document.getElementById("alert-show").classList.add('alert-success');
                        $('#setting-error-name').text('Success! ');
                        $('#setting-error-message').text('Status Successfully changed!');
                    }
                },
                error: function (data) {
                    console.log(data);
                }
            });
        });
    </script>
@endpush
