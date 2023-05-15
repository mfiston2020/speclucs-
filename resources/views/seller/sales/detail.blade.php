@extends('seller.includes.app')

@section('title','Seller - Order Detail')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Order Detail')
@section('page_name','Order Detail')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Products: <strong>{{count($products)}}</strong></h4>
                        <hr>
                        @if ($invoice->status=='completed')
                            @if ($invoice->payment=='paid')
                                <a href="{{route('seller.invoice.receipt',Crypt::encrypt($invoice->id))}}"
                                class="pull-right btn btn-outline-warning"><i class="fa fa-print"></i> Print Order</a>
                            @else
                            @if ($invoice->client_id!=null)
                                {{-- <a href="{{route('seller.cutomerInvoice')}}"
                                    class="pull-right btn btn-outline-secondary" style="margin-right: 10px">create Invoice</a> --}}
                            @endif
                                <a href="{{route('seller.invoice.pay',Crypt::encrypt($invoice->id))}}"
                                class="pull-right btn btn-outline-primary">Proceed Payment</a>
                            @endif
                            
                        @else
                            <a href="{{route('seller.sales.add',Crypt::encrypt($invoice->id))}}"
                            class="pull-right btn btn-outline-secondary">Add Product</a>
                        @endif
                    </div>
                    <div class="table-responsive m-t-40">
                        {{-- ====== input error message ========== --}}
                        @include('seller.includes.layouts.message')
                        {{-- ====================================== --}}
                        <table class="table stylish-table">
                            <thead>
                                <tr>
                                    <th colspan="2">Product</th>
                                    <th>quantity</th>
                                    <th>Price / unit</th>
                                    <th>Total</th>
                                    @if ($invoice->status=='completed')
                                        
                                    @else
                                        <th>Operation</th>
                                    @endif
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $key=> $product)
                                <tr>
                                    <td style="width:50px;"><span class="round">P</span></td>
                                    <span
                                        hidden>{{$prod=\App\Models\Product::where(['id'=>$product->product_id])->where('company_id',Auth::user()->company_id)->select('*')->first()}}</span>
                                        <span 
                                            hidden>{{$power=\App\Models\Power::where(['product_id'=>$product->product_id])->where('company_id',Auth::user()->company_id)->select('*')->first()}}</span>
                                    <td>
                                        <h6>{{$prod->product_name}}</h6>
                                        <small class="text-muted">
                                            @if ($power)
                                            @if (initials($prod->product_name)=='SV')
                                                <span> {{$power->sphere}} / {{$power->cylinder}}</span>
                                            @else
                                                <span>{{$power->sphere}} / {{$power->cylinder}} *{{$power->axis}}  {{$power->add}}</span>
                                            @endif
                                            @endif
                                        </small>
                                    </td>
                                    <td>{{$product->quantity}}</td>
                                    <td>{{format_money($product->unit_price)}}</td>
                                    <td>{{format_money($product->total_amount)}}</td>
                                    @if ($invoice->status=='completed')
                                        
                                    @else
                                    <td>
                                        <a href="{{route('seller.sales.edit.product',Crypt::encrypt($product->id))}}" style="color: rgb(0, 38, 255)">Edit</a>
                                            <a href="#" class="pl-2" data-toggle="modal" data-target="#myModal-{{$key}}"
                                                style="color: red">Delete</a>
                                    </td>
                                    @endif
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
                                                <h4>{{$prod->product_name}} have {{$product->quantity}} quantity!
                                                    continue??</h4>

                                            </div>
                                            <div class="modal-footer">
                                                <a href="{{route('seller.sales.remove.product',Crypt::encrypt($product->id))}}"
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
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <span hidden>{{$client=\App\Models\Customer::where(['id'=>$invoice->client_id])->where('company_id',Auth::user()->company_id)->pluck('name')->first()}}</span>
                    <h4 class="card-title">Order Number #{{sprintf('%04d',$invoice->id)}} 
                    @if ($client)
                        for: {{$client}}
                    @else
                        
                    @endif
                </h4>

                </div>
                <div class="card-body bg-light">
                    <div class="row text-center">
                        <div class="col-6 m-t-10 m-b-10">
                            <span class="label label-{{(($invoice->status)=='completed')?'success':'warning'}}">{{$invoice->status}}</span>
                        </div>
                        <div class="col-6 m-t-10 m-b-10">
                            {{\Carbon\Carbon::parse($invoice->created_at)->diffForHumans()}}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                        <h5 class="p-t-20">Products</h5>
                        <span>{{count($products)}}</span>
                        <h5 class="m-t-30">Total Stock</h5>
                        <span>{{\App\Models\SoldProduct::where(['invoice_id'=>$invoice->id])->where('company_id',Auth::user()->company_id)->select('*')->sum('quantity')}}</span>
                        @if ($invoice->due!=0)
                        <hr>
                        <h5 class="m-t-30">Due Amount</h5>
                        <span>{{format_money($invoice->due)}}
                        </span>
                        @endif
                        <h5 class="m-t-30">Total Amount</h5>
                        <span>{{format_money(\App\Models\SoldProduct::where(['invoice_id'=>$invoice->id])->where('company_id',Auth::user()->company_id)->select('*')->sum('total_amount'))}}
                        </span>
                        <br />
                        @if ($invoice->status=='pending')
                        <a href="" data-toggle="modal" data-target="#myModal" type="button" class="m-t-20 btn waves-effect waves-light btn-success">Finalize Order</a>
                        @else
                        @endif

                    <div id="myModal" class="modal fade" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{route('seller.sales.finalize',Crypt::encrypt($invoice->id))}}" method="post">
                                @csrf
                            <div class="modal-content">
                                <input type="hidden" name="total"
                            value={{\App\Models\SoldProduct::where(['invoice_id'=>$invoice->id])->where('company_id',Auth::user()->company_id)->select('*')->sum('total_amount')}}>
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle"></i>
                                        Warning</h4>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                    @if ($invoice->client_id)
                                        <h4>Do you want to finalize this??</h4>
                                    {{-- <hr> --}}
                                    @else
                                     <h4>client Information</h4>
                                    <hr>
                                    <div class="form-group row">
                                        <label for="cname" class="col-sm-3 text-right control-label col-form-label">Client Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="cname" class="form-control" placeholder="" name="name"
                                                >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="cphone" class="col-sm-3 text-right control-label col-form-label">Client Phone Number</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="cphone" class="form-control" placeholder="" name="phone"
                                                >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tin_number" class="col-sm-3 text-right control-label col-form-label">Client TIN Number</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="tin_number" class="form-control" placeholder="" name="tin_number"
                                                >
                                        </div>
                                    </div>   
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="submit"
                                        class="btn btn-info waves-effect">Finalize</button>
                                    <button type="button" class="btn btn-danger waves-effect"
                                        data-dismiss="modal">No</button>
                                </div>
                            </div>
                            </form>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
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
