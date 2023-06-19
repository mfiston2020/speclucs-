@extends('manager.includes.app')

@section('title','Manager\'s Manager')

@push('css')

@endpush
{{-- ==== Breadcumb ======== --}}
@section('current','Manager Report')
@section('page_name','Manager Report')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">

     <a href="{{route('manager.insurance.proforma')}}" class="col-lg-4 col-md-6 text-primary">
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
     </a>

 </div>
 </div>
@endsection

@push('scripts')

@endpush
