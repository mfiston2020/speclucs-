@extends('manager.includes.app')

@section('title','Manager Dashboard - Quotations')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Quotation')
@section('page_name','Quotation List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">All Quotations</h4><hr>
                        {{-- <a href="{{route('manager.supplier.create')}}" type="button" class="btn waves-effect waves-light btn-rounded btn-outline-primary" style="align-items: right;">
                            <i class="fa fa-plus"></i> New Supplier</a> --}}
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
                                    <th>Quotation Number</th>
                                    <th>Supplier</th>
                                    <th>products</th>
                                    <th>status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quotations as $key=> $quotation)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$quotation->quotation_number}}</td>
                                        <td>{{\App\Models\Supplier::where('id',$quotation->supplier_id)->pluck('name')->first()}}</td>
                                        <td>{{count(\App\Models\PurchaseOrder::where('quotation_number',$quotation->quotation_number)->select('*')->get())}}</td>
                                        <td>
                                            <span class="text-{{($quotation->status=='request')?'warning':'success'}}">{{$quotation->status}}</span>
                                        </td>
                                        <td>
                                            <a href="{{route('manager.quation.detail',Crypt::encrypt($quotation->quotation_number))}}" style="color: blue">Detail</a>
                                            {{-- <a href="#" data-toggle="modal" data-target="#delete-{{$key}}" style="color: red; padding-left: 30px;">delete</a> --}}
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
