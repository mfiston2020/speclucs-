@extends('manager.includes.app')

@section('title','Manager\'s Invoices')

@push('css')

@endpush
{{-- al business information must be kept private unless you want to see them all the dashboard itmes must be removed and add a company logo
    
    invoice 
    =======

    invoice must have all product all products
    and a detail of everything that was done on a particular invoice for a customer

    --}}
{{-- ==== Breadcumb ======== --}}
@section('current','All Invoices')
@section('page_name','All Invoices')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
   <div class="row">
    <a href="{{route('manager.cutomerInvoice')}}" class="col-lg-4 col-md-6 text-dark">
        <div class="">
            <div class="card border-right border-dark">
                <div class="card-body">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <span class="text-dark display-6"><i class="ti-layout-slider-alt"></i></span>
                        </div>
                        <div class="ml-auto">
                            <h3>Statement Invoice</h3>
                            {{-- <h6 class="text-dark">Device Variations</h6> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </a>
    <a href="{{route('manager.my.order.invoice')}}" class="col-lg-4 col-md-6 text-dark">
        <div class="">
            <div class="card border-right border-warning">
                <div class="card-body">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <span class="text-warning display-6"><i class="ti-layout-slider-alt"></i></span>
                        </div>
                        <div class="ml-auto">
                            <h3>Supplier Orders Invoices </h3>
                            {{-- <h6 class="text-warning">Net Income</h6> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </a>
    <a href="{{route('manager.received.order.invoice')}}" class="col-lg-4 col-md-6 text-dark">
        <div class="">
            <div class="card border-right border-info">
                <div class="card-body">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <span class="text-info display-6"><i class="ti-layout-slider-alt"></i></span>
                        </div>
                        <div class="ml-auto">
                            <h3>Clients Order Invoices</h3>
                            {{-- <h6 class="text-info">Net Income</h6> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>
</div>
@endsection

@push('scripts')

@endpush
