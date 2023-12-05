@extends('seller.includes.app')

@section('title','Manager Dashboard - Order Invoice Detail')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Order Invoice Detail')
@section('page_name','Order Invoice Detail')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title"> <a href="{{url()->previous()}}"><i
                                    class="fa fa-arrow-alt-circle-left"></i></a> Order Invoice Detail</h4>
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
                                    <th>Customer Name</th>
                                    <th>Order Date</th>
                                    <th>Invoice Date</th>
                                    <th>Invoice #</th>
                                    <th>Order #</th>
                                    <th>Category</th>
                                    <th>Product</th>
                                    <th>Description</th>
                                    <th>Power</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <span hidden>{{$total=0}}</span>
                                @foreach ($invoice_products as $item)
                                    <tr>
                                        <span hidden>{{$order=\App\Models\Invoice::where(['id'=>$item->invoice_id])->select('*')->first()}}</span>
                                        <span hidden>{{$statement=\App\Models\InvoiceStatement::where('id',$order->statement_id)->select('*')->first()}}</span>
                                        <span hidden>{{$product=\App\Models\Product::where('id',$item->product_id)->where('company_id',Auth::user()->company_id)->select('*')->first()}}</span>
                                        <span hidden>{{$power=\App\Models\Power::where('product_id',$item->product_id)->where('company_id',Auth::user()->company_id)->select('*')->first()}}</span>
                                        <span hidden>{{$customer=\App\Models\Customer::where('id',$order->client_id)->where('company_id',Auth::user()->company_id)->select('*')->first()}}</span>
                                        {{-- ============================================================== --}}

                                        <td>{{$customer->name}}</td>
                                        {{-- <td>{{\App\Models\Customer::where('id',$client_id)->pluck('name')->first()}}</td> --}}
                                        <td>{{date('Y-m-d',strtotime($order->created_at))}}</td>
                                        <td>{{date('Y-m-d',strtotime($statement->created_at))}}</td>
                                        <td>INVOICE # {{sprintf('%04d',$statement->invoice_number)}}</td>
                                        <td>ORDER # {{sprintf('%04d',$order->reference_number)}}</td>
                                        <td>{{\App\Models\Category::where('id',$product->category_id)->pluck('name')->first()}}</td>
                                        <td>{{$product->product_name}}</td>
                                        <td>{{lensDescription($product->description)}}</td>
                                        <td>
                                            @if ($power)
                                                @if (initials($product->product_name)=='SV')
                                                    <span>{{$power->sphere}} / {{$power->cylinder}}</span>
                                                @else
                                                    <span>{{$power->sphere}} / {{$power->cylinder}} *{{$power->axis}}  {{$power->add}}</span>
                                                @endif
                                            @else
                                                <center><span>-</span></center>
                                            @endif
                                        </td>
                                        <td>{{$item->quantity}}</td>
                                        <td>{{format_money($item->unit_price)}}</td>
                                        <td>{{format_money($item->total_amount)}}</td>
                                        <td>
                                            <span class="text-{{($statement->status=='invoiced')?'warning':'success'}}">{{$statement->status}}</span>
                                        </td>
                                        <span hidden>{{$total=$total+$item->total_amount}}</span>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="col-md-12">
                        <div class="pull-right m-t-30 text-right">
                            <h3><b>Total :</b> {{format_money($total)}}</h3>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <hr>
                    <div class="form-group m-b-0 text-center">
                        <button onclick="exportAll('xls')" class="btn btn-success waves-effect waves-light text-white">
                            <i class="fa fa-download"></i> Export To Excel</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light text-white">
                                <i class="fa fa-print"></i> Print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- </div> --}}
{{-- </div> --}}
@endsection

@push('scripts')
<script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js')}}"></script>
<script src="{{ asset('dashboard/assets/dist/js/export.js')}}"></script>
<script>
     function exportAll(type) {

        $('#zero_config').tableExport({
            filename: 'table_%DD%-%MM%-%YY%-month(%MM%)',
            format: type
        });
        }
</script>
@endpush
