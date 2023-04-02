@extends('manager.includes.app')

@section('title','Admin Dashboard - Suppliers')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Receipt')
@section('page_name','Receipt List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">All Receipt</h4><hr>
                        <a href="{{route('manager.receipt.add')}}" type="button" class="btn waves-effect waves-light btn-rounded btn-outline-primary" style="align-items: right;">
                            <i class="fa fa-plus"></i> Register Receipt</a>
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
                                    <th>Date</th>
                                    <th>Title</th>
                                    <th>Provider</th>
                                    <th>Done by</th>
                                    {{-- <th>Product</th> --}}
                                    <th>Stock</th>
                                    <th>Faulty Stock</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($receipts as $key=> $receipt)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{date('Y-m-d',strtotime($receipt->created_at))}}</td>
                                        <td><a href="{{route('manager.receipt.detail',Crypt::encrypt($receipt->id))}}">{{$receipt->title}}</a> </td>
                                        <td><a href="#">{{\App\Models\Supplier::where(['id'=>$receipt->supplier_id])->where('company_id',Auth::user()->company_id)->pluck('name')->first()}}</a></td>
                                        <td><a href="#">{{\App\Models\User::where(['id'=>$receipt->user_id])->where('company_id',Auth::user()->company_id)->pluck('name')->first()}}</a></td>
                                        {{-- <td>0</td> --}}
                                        <td>
                                            {{count(\App\Models\ReceivedProduct::where(['receipt_id'=>$receipt->id])->where('company_id',Auth::user()->company_id)->select('*')->get())}}
                                        </td>
                                        <td>
                                            {{count(\App\Models\ReceivedProduct::where(['receipt_id'=>$receipt->id])->where('faulty_stock','>','0')->where('company_id',Auth::user()->company_id)->select('*')->get())}}
                                        </td>
                                        <td><span class="label label-pill label-{{($receipt->status=='pending')?'danger':'success'}}">{{$receipt->status}}</span></td>
                                        <td>
                                            <a href="{{route('manager.receipt.detail',Crypt::encrypt($receipt->id))}}" style="color: blue">edit</a>
                                            <a href="#" style="color: red; padding-left: 30px;">delete</a>
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
