@extends('manager.includes.app')

@section('title','Manager Dashboard - Product')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','My Invoices')
@section('page_name','Invoice\'s List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <!-- Sales chart -->
    <div class="row">
        <form action="" method="post" class="col-12">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group m-b-0 text-left  " id='actionButtons'>
                            {{-- <button type="submit" class="btn btn-info waves-effect waves-light">Place Order</button> --}}
                            <a href="{{url()->previous()}}" class="btn btn-dark waves-effect waves-light">Go Back</a>
                            <a href="#" class="btn btn-warning waves-effect waves-light"><i class="fa fa-print"></i> Print Invoice</a>
                        </div>
                        <div class="form-group m-b-0 text-center" id="loading">
                            <h5>Processing Please wait <img src="{{ asset('dashboard/assets/images/loading.gif')}}" height="60px">
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h4 class="card-title">All Invoices</h4><hr>
                            <a href="{{url()->previous()}}" type="button" class="ml-2 btn waves-effect waves-light btn-rounded btn-outline-secondary" style="align-items: right;">
                                <i class="fa fa-arrow-left"></i> Back</a>
                        </div> <hr>
                        {{-- ============================== --}}
                        @include('manager.includes.layouts.message')
                        {{-- =============================== --}}
                        
                        <div class="table-responsive">
                            <table id="zero_config" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            #
                                            {{-- <div class="form-check form-check-inline align-content-center">
                                                <input class="form-check-input" type="checkbox" id="checkall"
                                                    value="option1">
                                            </div> --}}
                                        </th>
                                        <th>Supplier</th>
                                        <th>Order NUmber</th>
                                        <th>Order Date</th>
                                        <th>Cost</th>
                                        <th>Invoice Date</th>
                                        <th>status</th>
                                    </tr>
                                </thead>
                                <span hidden>{{$total=0}}</span>
                                <tbody>
                                    @foreach ($invoices as $key=> $invoice)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <span hidden>{{$order  =   \App\Models\Order::where('id',$invoice->order_id)->select('*')->first()}}</span>
                                            <td>{{\App\Models\CompanyInformation::where('id',$invoice->supplier_id)->pluck('company_name')->first()}}</td>
                                            <td>{{$invoice->invoice_number}}</td>
                                            <td>{{date('Y-m-d',strtotime($order->created_at))}}</td>
                                            <td>{{format_money($invoice->cost)}}</td>
                                            <td>{{date('Y-m-d',strtotime($invoice->created_at))}}</td>
                                            <td><span class="text-warning">{{$invoice->status}}</span></td>
                                        </tr>
    
                                        <div id="delete-{{$key}}" class="modal fade" tabindex="-1" role="dialog"
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
                                                    <h4>Do you want to delete  ??</h4>
    
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="#"
                                                        class="btn btn-info waves-effect">Yes</a>
                                                    <button type="button" class="btn btn-danger waves-effect"
                                                        data-dismiss="modal">No</button>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                    </div>

                                    <span hidden>{{$total=$total+$invoice->cost}}</span>  
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Supplier</th>
                                        <th>Order NUmber</th>
                                        <th>Order Date</th>
                                        <th>Cost</th>
                                        <th>Invoice Date</th>
                                        <th>status</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <div class="pull-right m-t-30 text-right">
                                <h3><b>Total :</b> {{format_money($total)}}</h3>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-body printableArea">
                <h3><b>INVOICE</b> <span class="pull-right">#{{sprintf('%04d',$invoice->reference_number)}}</span></h3>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-left">
                            <address>
                                <span hidden>{{$company=\App\Models\CompanyInformation::where(['id'=>Auth::user()->company_id])->select('*')->first()}}</span>
                                <h3> &nbsp;<b class="text-danger">{{$company->company_name}}</b></h3>
                                <p class="text-muted m-l-5">{{$company->company_tin_number}}
                                    <br /> {{$company->company_street}}
                                    <br /> {{$company->company_phone}}
                                    <br /> {{$company->company_email}}</p>
                            </address>
                        </div>
                        <div class="pull-right text-right">
                            <address>
                                <p class="m-t-30"><b>Name :</b> {{$invoice->client_name}}</p>
                                    <p class="m-t-30"><b>Phone :</b> {{$invoice->phone}}</p>
                                        <p class="m-t-30"><b>TIN Number :</b> {{$invoice->tin_number}}</p>
                                <p><b>Due Date :</b> <i class="fa fa-calendar"></i>
                                    {{date('Y-m-d H:m:s',strtotime($invoice->updated_at))}}</p>
                            </address>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive m-t-40" style="clear: both;">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Product Name</th>
                                        <th class="text-right">Quantity</th>
                                        <th class="text-right">Unit Cost</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        {{-- @foreach ($products as $key=> $product)
                                        <span
                                            hidden>{{$prod=\App\Models\Product::where(['id'=>$product->product_id])->select('*')->first()}}</span>
                                        <span 
                                            hidden>{{$power=\App\Models\Power::where(['product_id'=>$product->product_id])->where('company_id',Auth::user()->company_id)->select('*')->first()}}</span>
                                        <tr>
                                            <td class="text-center">{{$key+1}}</td>
                                            <td>{{$prod->product_name }} | {{ $prod->description}} 
                                                @if ($power)
                                                @if (initials($prod->product_name)=='SV')
                                                    <span> {{$power->sphere}} / {{$power->cylinder}}</span>
                                                @else
                                                    <span>{{$power->sphere}} / {{$power->cylinder}} *{{$power->axis}}  {{$power->add}}</span>
                                                @endif
                                                @endif
                                            </td>
                                            <td class="text-right">{{($product->quantity)}} </td>
                                            <td class="text-right"> {{format_money($product->unit_price)}} </td>
                                            <td class="text-right"> {{format_money($product->total_amount)}} </td>
                                        </tr>
                                        @endforeach --}}

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="pull-right m-t-30 text-right">
                            <span hidden>{{$vat=(($invoice->total_amount*18)/100)}}</span>
                            <p>Total amount: {{format_money($invoice->total_amount)}}</p>
                            <p>Total paid: {{format_money($invoice->total_amount-$invoice->due)}}</p>
                            <p>Due: {{format_money($invoice->due)}}</p>
                            <p>vat (18%) : {{format_money($vat*0)}} </p>
                            <hr>
                            <h3><b>Total :</b> {{format_money($invoice->total_amount)}}</h3>
                        </div>
                        <div class="clearfix"></div>
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
    <script>
        $('#loading').hide();

        $("#checkall").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        function hideME() {
            $('#loading').show();
            $('#actionButtons').hide();
        }
    </script>
@endpush
