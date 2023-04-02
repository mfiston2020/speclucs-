@extends('manager.includes.app')

@section('title','Manager Dashboard - Add Proforma Product')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('dashboard/assets/dist/css/style.css')}}">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','New Proforma Product')
@section('current','Add Proforma Product')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    @livewire('add-proforma-product', ['proforma_id' => $proforma])
</div>
</div>
@endsection

@push('scripts')

@endpush
