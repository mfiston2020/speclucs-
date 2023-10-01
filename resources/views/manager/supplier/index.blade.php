@extends('manager.includes.app')

@section('title','Admin Dashboard - Suppliers')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Suppliers')
@section('page_name','Supplier\'s List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">All Suppliers</h4><hr>
                        <a href="{{route('manager.supplier.create')}}" type="button" class="btn waves-effect waves-light btn-rounded btn-outline-primary" style="align-items: right;">
                            <i class="fa fa-plus"></i> New Supplier</a>
                    </div>
                        <hr>
                        {{-- ================================= --}}
                        @include('manager.includes.layouts.message')
                        {{-- ========================== --}}

                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered nowrap"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Email</th>
                                    <th>Telephone</th>
                                    <th>Payment Made</th>
                                    <th>Total Payment</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $key=> $supplier)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$supplier->name}}</td>
                                        <td>{{$supplier->description}}</td>
                                        <td>{{$supplier->email}}</td>
                                        <td>{{$supplier->phone}}</td>
                                        <td>0</td>
                                        <td>{{format_money(0)}}</td>
                                        <td>
                                            <a href="{{ route('manager.supplier.edit',Crypt::encrypt($supplier->id))}}">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js')}}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js')}}"></script>
@endpush
