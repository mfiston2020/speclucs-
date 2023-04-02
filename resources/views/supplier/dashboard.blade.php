@extends('supplier.includes.app')

@section('title','Supplier\'s Dashboard')

@push('css')

@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Dashboard')
@section('page_name','Dashboard')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <!-- Row -->
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="card border-bottom border-info">
                        <div class="card-body">
                            <div class="d-flex no-block align-items-center">
                                <div>
                                    <h2>0</h2>
                                    <h6 class="text-info">New Orders</h6>
                                </div>
                                <div class="ml-auto">
                                    <span class="text-info display-6"><i class="ti-notepad"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card border-bottom border-cyan">
                        <div class="card-body">
                            <div class="d-flex no-block align-items-center">
                                <div>
                                    <h2>0</h2>
                                    <h6 class="text-cyan">Order's in production</h6>
                                </div>
                                <div class="ml-auto">
                                    <span class="text-cyan display-6"><i class="ti-clipboard"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card border-bottom border-success">
                        <div class="card-body">
                            <div class="d-flex no-block align-items-center">
                                <div>
                                    <h2>0</h2>
                                    <h6 class="text-success">Completed Orders</h6>
                                </div>
                                <div class="ml-auto">
                                    <span class="text-success display-6"><i class="ti-wallet"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card border-bottom border-orange">
                        <div class="card-body">
                            <div class="d-flex no-block align-items-center">
                                <div>
                                    <h2>0</h2>
                                    <h6 class="text-orange">Payments</h6>
                                </div>
                                <div class="ml-auto">
                                    <span class="text-orange display-6"><i class="ti-stats-down"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row -->
        </div>
    </div>

</div>
@endsection

@push('scripts')

@endpush
