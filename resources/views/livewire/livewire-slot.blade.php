@extends('manager.includes.app')

@section('title', 'Admin Dashboard - Sales')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/extra-libs/prism/prism.css') }}">
    <style>
        .custom-border {
            border: 1px solid #F0F0F0;
            border-left: 2px solid #3BAFDA;
            border-radius: 4px;
        }
    </style>
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current', 'Order')
@section('page_name', 'New Order')
{{-- === End of breadcumb == --}}

@section('content')
    <div class="container-fluid row">

        {{ $slot }}

        {{-- @livewire('manager.add-sale-product', ['invoice_id' => $invoice_id]) --}}

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
@endpush
