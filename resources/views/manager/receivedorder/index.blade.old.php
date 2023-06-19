@extends('manager.includes.app')

@section('title','Manager Dashboard - Received Orders')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Income')
@section('page_name','Income List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

                    {{-- ================================= --}}
                    @include('manager.includes.layouts.message')
                    {{-- ========================== --}}

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">
                            <a href="{{ route('manager.received.order.new')}}" class="btn btn-primary btn-rounded"><i class="mdi mdi-cart-plus"></i> New Orders</a>
                            <a href="{{ route('manager.received.order.inProduction')}}" class="btn btn-primary btn-rounded"><i class="mdi mdi-cart-plus"></i> In Production</a>
                            <a href="{{ route('manager.received.order.incomplete')}}" class="btn btn-primary btn-rounded"><i class="mdi mdi-cart-plus"></i> Completed Orders</a>
                            <a href="{{ route('manager.received.order.indelivery')}}" class="btn btn-primary btn-rounded"><i class="mdi mdi-cart-plus"></i> delivery Orders</a>
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Column -->
        <div class="col-sm-12 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Orders</h4>
                    <h5 class="card-subtitle">Total orders received</h5>
                    <h2 class="font-medium">{{$orders_count}}</h2>
                </div>
                <hr>
                <div class="card-body">
                    <h4 class="card-title">New Orders</h4>
                    <h2 class="font-medium">{{$orders_new}}</h2>
                </div>
            </div>
        </div>
        
        <!-- Column -->
        <div class="col-sm-12 col-lg-8">
            <div class="card">
                <div class="card-body border-bottom">
                    <h4 class="card-title">Orders Overview</h4>
                    <h5 class="card-subtitle">Overview Of all orders</h5>
                </div>
                <div class="card-body">
                    <div class="row m-t-10">
                        <!-- col -->
                        <div class="col-md-6 col-sm-12 col-lg-3">
                            <div class="d-flex align-items-center">
                                <div class="m-r-10"><span class="text-orange display-5"><i class="mdi mdi-factory"></i></span></div>
                                <div><span class="text-muted">In Production</span>
                                    <h3 class="font-medium m-b-0">{{$orders_production}}</h3></div>
                            </div>
                        </div>
                        <!-- col -->
                        <!-- col -->
                        <div class="col-md-6 col-sm-12 col-lg-3">
                            <div class="d-flex align-items-center">
                                <div class="m-r-10"><span class="text-primary display-5"><i class="mdi mdi-basket"></i></span></div>
                                <div><span class="text-muted">Completed Orders</span>
                                    <h3 class="font-medium m-b-0">{{$orders_completed}}</h3></div>
                            </div>
                        </div>
                        <!-- col -->
                        <!-- col -->
                        <div class="col-md-6 col-sm-12 col-lg-3">
                            <div class="d-flex align-items-center">
                                <div class="m-r-10"><span class="display-5"><i class="mdi mdi-truck-delivery"></i></span></div>
                                <div><span class="text-muted">In Delivery</span>
                                    <h3 class="font-medium m-b-0">{{$orders_delivery}}</h3></div>
                            </div>
                        </div>
                        <!-- col -->
                        <!-- col -->
                        <div class="col-md-6 col-sm-12 col-lg-3">
                            <div class="d-flex align-items-center">
                                <div class="m-r-10"><span class="display-5"><i class="mdi mdi-package-variant"></i></span></div>
                                <div><span class="text-muted">Received</span>
                                    <h3 class="font-medium m-b-0">{{$orders_received}}</h3></div>
                            </div>
                        </div>
                        <!-- col -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js')}}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js')}}"></script>
@endpush
