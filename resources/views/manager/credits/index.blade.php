@extends('manager.includes.app')

@section('title','Manager Dashboard - Credits')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Credits')
@section('page_name','Credits List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- column -->
        <a href="{{route('manager.my.credit')}}" class="col-sm-12 col-lg-4 text-dark">
            <div >
                <div class="card card-hover">
                    <div class="card-body">
                        <h4 class="card-title">Credits</h4>
                        <div class="d-flex">
                            <h3>My Requests to supplier</h3>
                            <span class="ml-auto">my credits to supplier</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <!-- column -->
        <a href="{{route('manager.requestd.credit')}}" class="col-sm-12 col-lg-4 text-dark">
            <div >
                <div class="card card-hover">
                    <div class="card-body">
                        <h4 class="card-title">Credits</h4>
                        <div class="d-flex">
                            <h3>Requested Credits from clients</h3>
                            <span class="ml-auto">Credits from clients</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js')}}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js')}}"></script>
@endpush
