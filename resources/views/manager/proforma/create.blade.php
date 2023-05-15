@extends('manager.includes.app')

@section('title','Manager Dashboard - Create Proforma')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('dashboard/assets/dist/css/style.css')}}">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','New Proforma')
@section('current','Add Proforma')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    @livewire('proforma-customer')
</div>
</div>
@endsection

@push('scripts')

@endpush
