@extends('manager.includes.app')

@section('title','Manager\'s Profile')

@push('css')

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
                        <h6 class="card-subtitle" style="text-transform: uppercase">{{Auth::user()->permissions}}</h6>
                        {{-- <div class="row text-center justify-content-md-center">
                            <div class="col-4"><a href="javascript:void(0)" class="link"><i
                                        class="fas fa-shopping-cart"></i>
                                    <font class="font-medium">254</font>
                                </a></div>
                            <div class="col-4"><a href="javascript:void(0)" class="link"><i
                                        class="fas fa-money-bill-alt"></i>
                                    <font class="font-medium">{{format_money(54)}}</font>
                                </a></div>
                        </div> --}}
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
                {{-- ============================== --}}
                @include('manager.includes.layouts.message')
                {{-- =============================== --}}
                <!-- Tabs -->
                <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-timeline-tab" data-toggle="pill" href="#current-month"
                            role="tab" aria-controls="pills-timeline" aria-selected="true">Profile Settings</a>
                    </li>
                    @if (userInfo()->permissions=='manager')
                        <li class="nav-item">
                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#last-month" role="tab"
                                aria-controls="pills-profile" aria-selected="false">About Company</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-bank-tab" data-toggle="pill" href="#bank" role="tab"
                                aria-controls="pills-bank" aria-selected="false">Bank Detail</a>
                        </li>
                    @endif
                </ul>
                <!-- Tabs -->
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="current-month" role="tabpanel"
                        aria-labelledby="pills-timeline-tab">
                        <div class="card-body">
                            <form class="form-horizontal form-material" method="POST"
                                action="{{route('manager.profile.username')}}">
                                @csrf
                                <div class="form-group">
                                    <label class="col-md-12">Full Name</label>
                                    <div class="col-md-12">
                                        <input type="text" placeholder="Full Name"
                                            class="form-control form-control-line" name="name"
                                            value="{{$user_info->name}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="example-email" class="col-md-12">Email</label>
                                    <div class="col-md-12">
                                        <input type="email" placeholder="eamil address"
                                            class="form-control form-control-line" name="email"
                                            value="{{$user_info->email}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-success">Update Profile</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <hr>
                            <h5>Account Password Settings</h5>
                            <hr>
                            <form class="form-horizontal form-material" action="{{route('manager.profile.password')}}"
                                method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="col-md-12">Current Password</label>
                                    <div class="col-md-12">
                                        <input type="password" name="current_password"
                                            class="form-control form-control-line">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="example-email" class="col-md-12">New Password</label>
                                    <div class="col-md-12">
                                        <input type="password" class="form-control form-control-line" name="password"
                                            id="example-email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="example-email" class="col-md-12">Confirm Password</label>
                                    <div class="col-md-12">
                                        <input type="password" class="form-control form-control-line"
                                            name="password_confirmation" id="example-email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button class="btn btn-success">Update Password</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @if (userInfo()->permissions=='manager')
                        <div class="tab-pane fade" id="last-month" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <div class="card-body">
                                <form class="form-horizontal form-material" method="POST"
                                    action="{{route('manager.profile.company')}}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label class="col-md-12">Company Name</label>
                                        <div class="col-md-12">
                                            <input type="text" placeholder="Company Name" name="company_name"
                                                class="form-control form-control-line" value="{{$company->company_name}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Company Email</label>
                                        <div class="col-md-12">
                                            <input type="text" placeholder="Company Email Address" name="company_email"
                                                class="form-control form-control-line" value="{{$company->company_email}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Company Phone</label>
                                        <div class="col-md-12">
                                            <input type="text" placeholder="Company Phone Number" name="company_number"
                                                class="form-control form-control-line" value="{{$company->company_phone}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Company Street</label>
                                        <div class="col-md-12">
                                            <input type="text" placeholder="Company street" name="company_street"
                                                class="form-control form-control-line" value="{{$company->company_street}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Company TIN Number</label>
                                        <div class="col-md-12">
                                            <input type="text" placeholder="Company street" name="company_tin_number"
                                                class="form-control form-control-line"
                                                value="{{$company->company_tin_number}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Company Logo</label>
                                        <div class="col-md-12">
                                            <input type="file" name="company_logo"
                                                class="form-control form-control-line"
                                                value="{{$company->company_logo}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button class="btn btn-success">Update Company Information</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="bank" role="tabpanel" aria-labelledby="pills-bank-tab">
                            <div class="card-body">
                                <form class="form-horizontal form-material" method="POST"
                                    action="{{route('manager.bank.company')}}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label class="col-md-12">Bank Name</label>
                                        <div class="col-md-12">
                                            <input type="text" placeholder="Bank Name" name="bank_name"
                                                class="form-control form-control-line" value="{{$company->bank_name}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Account Name</label>
                                        <div class="col-md-12">
                                            <input type="text" placeholder="Account Name" name="account_name"
                                                class="form-control form-control-line" value="{{$company->account_name}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Account Number</label>
                                        <div class="col-md-12">
                                            <input type="text" placeholder="Account Number" name="account_number"
                                                class="form-control form-control-line" value="{{$company->account_number}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Swift Code</label>
                                        <div class="col-md-12">
                                            <input type="text" placeholder="Swift Code" name="swift_code"
                                                class="form-control form-control-line" value="{{$company->swift_code}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button class="btn btn-success">Update Bank Details</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Column -->
    </div>
</div>
@endsection

@push('scripts')

@endpush
