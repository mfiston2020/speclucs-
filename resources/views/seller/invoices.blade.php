@extends('seller.includes.app')

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
    <a href="{{route('seller.cutomerInvoice')}}" class="col-lg-4 col-md-6 text-dark">
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
</div>
</div>
@endsection

@push('scripts')

@endpush
