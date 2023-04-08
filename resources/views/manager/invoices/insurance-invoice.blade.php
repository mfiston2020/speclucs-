@extends('manager.includes.app')

@section('title','Manager\'s Invoices')

@push('css')
    <link rel="stylesheet" href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current',__('manager/finance/finance.all_invoices'))
@section('page_name',__('manager/finance/finance.all_invoices'))
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
            @livewire('manager.invoice.insurance-invoice')
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('dashboard/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.js') }}"></script>
@endpush
