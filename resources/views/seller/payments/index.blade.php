@extends('seller.includes.app')

@section('title','Seller - Payments')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Payments')
@section('page_name','Payments List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">All Payments</h4><hr>
                        <a href="{{route('seller.payment.add')}}" type="button" class="btn waves-effect waves-light btn-rounded btn-outline-primary" style="align-items: right;">
                            <i class="fa fa-plus"></i> New Payment</a>
                    </div>
                        <hr>
                        {{-- ================================= --}}
                        @include('seller.includes.layouts.message')
                        {{-- ========================== --}}

                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered nowrap"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Supplier</th>
                                    <th>Reciept Number</th>
                                    <th>Title</th>
                                    <th>Payment Method</th>
                                    <th>Total Amount</th>
                                    <th>Due</th>
                                    {{-- <th></th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $key=> $payment)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{\App\Models\Supplier::where(['id'=>$payment->supplier_id])->where('company_id',Auth::user()->company_id)->pluck('name')->first()}}</td>
                                        <td><a href="{{route('seller.receipt.detail',Crypt::encrypt($payment->receipt_id))}};">
                                            Receipt #{{sprintf('%04d',\App\Models\Receipt::where(['id'=>$payment->receipt_id])->where('company_id',Auth::user()->company_id)->pluck('id')->first())}}
                                            </a>
                                        </td>
                                        <td>{{\App\Models\Receipt::where(['id'=>$payment->receipt_id])->where('company_id',Auth::user()->company_id)->pluck('title')->first()}}</td>
                                        <td>{{\App\Models\PaymentMethod::where(['id'=>$payment->payment_method_id])->where('company_id',Auth::user()->company_id)->pluck('name')->first()}}</td>
                                        <td>{{\App\Models\Receipt::where(['id'=>$payment->receipt_id])->where('company_id',Auth::user()->company_id)->pluck('total_cost')->first()}}</td>
                                        <td>{{$payment->due}}</td>
                                        {{-- <td>
                                            <a href="{{route('seller.edit.receipt',Crypt::encrypt($payment->id))}}" style="color: rgb(0, 38, 255)">Edit</a>
                                            <a href="#" class="pl-2" data-toggle="modal" data-target="#myModal-{{$key}}" style="color: red">Delete</a>
                                        </td> --}}
                                    </tr>
                                    <div id="myModal-{{$key}}" class="modal fade" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel"><i
                                                        class="fa fa-exclamation-triangle"></i> Warning</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                <h4>delete???</h4>

                                            </div>
                                            <div class="modal-footer">
                                                <a href="{{route('seller.sales.remove.product',Crypt::encrypt($payment->id))}}"
                                                    class="btn btn-info waves-effect">Yes</a>
                                                <button type="button" class="btn btn-danger waves-effect"
                                                    data-dismiss="modal">No</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
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
