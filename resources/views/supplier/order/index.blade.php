@extends('supplier.includes.app')

@section('title','Supplier Dashboard - Orders')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Orders')
@section('page_name','Orders List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-xl-8 col-sm-12">
            <div class="card card-hover">
                <div class="card-body" style="background:url(assets/images/background/active-bg.png) no-repeat top center;">
                    <div class="p-t-20 text-center">
                        <i class="mdi mdi-file-chart display-4 text-orange d-block"></i>
                        <span class="display-4 d-block font-medium">{{count($orders)}}</span>
                        <span>All Orders Made</span>
                        <!-- Progress -->
                        <div class="progress m-t-40" style="height:4px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <!-- Progress -->
                        <!-- row -->
                        <div class="row m-t-30 m-b-20">
                            <!-- column -->
                            <div class="col-md-3 col-sm-6 col-xs-12 border-right text-left">
                                <h3 class="m-b-0 font-medium">0<br></h3>NEW</div>
                            <!-- column -->
                            <div class="col-md-3 col-sm-6 col-xs-12 border-right text-left">
                                <h3 class="m-b-0 font-medium">0<br></h3>IN PRODUCTION</div>
                            <!-- column -->
                            <div class="col-md-3 col-sm-6 col-xs-12 text-right">
                                <h3 class="m-b-0 font-medium">0<br></h3>COMPLETED</div>
                            <!-- column -->
                            <div class="col-md-3 col-sm-6 col-xs-12 text-right">
                                <h3 class="m-b-0 font-medium">0<br></h3>DELIVERED</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================== --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">All Orders</h4>
                    </div>
                        <hr>
                        {{-- ================================= --}}
                        @include('manager.includes.layouts.message')
                        {{-- ========================== --}}

                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered nowrap"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order Number</th>
                                    <th>User </th>
                                    <th>Supplier</th>
                                    <th>Status</th>
                                    <th>Amount Paid</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
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
