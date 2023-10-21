@extends('manager.includes.app')

@section('title', 'Manager\'s Manager')

@push('css')
@endpush
{{-- ==== Breadcumb ======== --}}
@section('current', 'Manager Report')
@section('page_name', 'Manager Report')
{{-- === End of breadcumb == --}}

@section('content')
    <div class="container-fluid">
        <div class="row">


            {{-- closing report --}}
            <a href="{{ route('manager.closing.report') }}" class="col-lg-4 col-md-6 text-warning">
                <div class="">
                    <div class="card border-right border-warning">
                        <div class="card-body">
                            <div class="d-flex no-block align-items-center">
                                <div>
                                    <span class="text-warning display-6"><i class="ti-layout-slider-alt"></i></span>
                                </div>
                                <div class="ml-auto">
                                    <h3>Closing Stock</h3>
                                    <small class="text-dark">Get reporting from closing stock</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>


            {{-- Stock Adjustment report --}}
            <a href="{{ route('manager.product.adjustment.report')}}" class="col-lg-4 col-md-6 text-secondary">
                <div class="">
                    <div class="card border-right border-secondary">
                        <div class="card-body">
                            <div class="d-flex no-block align-items-center">
                                <div>
                                    <span class="text-secondary display-6"><i class="ti-layout-slider-alt"></i></span>
                                </div>
                                <div class="ml-auto">
                                    <h3>Stock Adjustment</h3>
                                    <small class="text-dark">Get Stock History on adjustments</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>


            {{-- Stock History report --}}
            <a href="{{ route('manager.product.stock.report')}}" class="col-lg-4 col-md-6 text-primary">
                <div class="">
                    <div class="card border-right border-primary">
                        <div class="card-body">
                            <div class="d-flex no-block align-items-center">
                                <div>
                                    <span class="text-primary display-6"><i class="ti-layout-slider-alt"></i></span>
                                </div>
                                <div class="ml-auto">
                                    <h3>Stock History</h3>
                                    <small class="text-dark">Get Stock History regarding in/out</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            {{-- fifo --}}
            {{-- <a href="{{ route('manager.product.report') }}" class="col-lg-4 col-md-6 text-success">
                <div class="">
                    <div class="card border-right border-success">
                        <div class="card-body">
                            <div class="d-flex no-block align-items-center">
                                <div>
                                    <span class="text-success display-6"><i class="ti-layout-slider-alt"></i></span>
                                </div>
                                <div class="ml-auto">
                                    <h3>FIFO Report</h3>
                                    <small class="text-dark">Get reporting from stock</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a> --}}

            {{-- Insurance report --}}
            {{-- <a href="{{ route('manager.insurance.proforma') }}" class="col-lg-4 col-md-6 text-primary">
                <div class="">
                    <div class="card border-right border-primary">
                        <div class="card-body">
                            <div class="d-flex no-block align-items-center">
                                <div>
                                    <span class="text-primary display-6"><i class="ti-layout-slider-alt"></i></span>
                                </div>
                                <div class="ml-auto">
                                    <h3>Insurance Invoice</h3>
                                    <small class="text-dark">Create monthly Insurance Invoices</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a> --}}

        </div>
    </div>
@endsection

@push('scripts')
@endpush
