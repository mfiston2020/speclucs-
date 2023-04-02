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
        <form action="{{route('manager.search.my.invoice.pay')}}" method="post" class="col-12">
            @csrf
            @if ($status!='paid')
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group m-b-0 text-left  " id='actionButtons'>
                                <button type="submit" class="btn btn-info waves-effect waves-light">Pay Selected Invoices</button>
                                <a href="{{url()->previous()}}" class="btn btn-dark waves-effect waves-light">Go Back</a>
                            </div>
                            <div class="form-group m-b-0 text-center" id="loading">
                                <h5>Processing Please wait <img src="{{ asset('dashboard/assets/images/loading.gif')}}" height="60px">
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h4 class="card-title">All Invoices</h4><hr>
                            <a href="{{url()->previous()}}" type="button" class="ml-2 btn waves-effect waves-light btn-rounded btn-outline-success" style="align-items: right;">
                                <i class="fa fa-file-excel"></i> Export To Excel</a>
                        </div> <hr>
                        {{-- ============================== --}}
                        @include('manager.includes.layouts.message')
                        {{-- =============================== --}}
                        
                        <div class="table-responsive">
                            <table id="zero_config" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        @if ($status!='paid')
                                        <th>
                                            <div class="form-check form-check-inline align-content-center">
                                                <input class="form-check-input" type="checkbox" id="checkall"
                                                    value="option1">
                                            </div>
                                        </th>
                                        @else
                                            <th>
                                                #
                                            </th>
                                        @endif
                                        <th>Supplier</th>
                                        <th>Order Number</th>
                                        <th>Order Date</th>
                                        <th>Cost</th>
                                        <th>Invoice Date</th>
                                        <th>status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <span hidden>{{$total=0}}</span>
                                <tbody>
                                    @foreach ($invoices as $key=> $invoice)
                                        <tr>
                                            <span hidden>{{$order  =   \App\Models\Order::where('id',$invoice->order_id)->select('*')->first()}}</span>
                                            @if ($status!='paid')
                                                <td>
                                                    <div class="form-check form-check-inline align-content-center">
                                                        <input class="form-check-input allCheckbox" type="checkbox"
                                                            name="invoice[]" id="inlineCheckbox1" value="{{$invoice->id}}">
                                                    </div>
                                                </td>
                                            @else
                                                <td>
                                                    {{$key+1}}
                                                </td>
                                            @endif
                                            
                                            <td>{{\App\Models\CompanyInformation::where('id',$invoice->supplier_id)->pluck('company_name')->first()}}</td>
                                            <td>{{$invoice->invoice_number}}</td>
                                            <td>{{date('Y-m-d',strtotime($order->created_at))}}</td>
                                            <td>{{format_money($invoice->cost)}}</td>
                                            <td>{{date('Y-m-d',strtotime($invoice->created_at))}}</td>
                                            <td><span class="text-warning">{{$invoice->status}}</span></td>
                                            <td>
                                                <span hidden>{{$found=\App\Models\InvoiceCredits::where('invoice_id',$invoice->id)->pluck('id')->first()}}</span>
                                                @if ($invoice->status=='paid')
                                                @if ($invoice->invoice_number!='CREDIT')
                                                    @if (!$found)
                                                        
                                                            <a href="#" data-toggle="modal" data-target="#credit-{{$key}}" class="btn btn-primary btn-sm btn-rounded">Order Credit</a>
                                                        @endif
                                                    @else
                                                        <span class="text-primary">Credit Requested</span>
                                                    @endif
                                                    
                                                @else
                                                    <span class="text-primary">No Action needed</span>
                                                @endif
                                            </td>
                                            
                                        </tr>
    
                                        <div id="credit-{{$key}}" class="modal fade" tabindex="-1" role="dialog"
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
                                                    <h4>Do you want to Request Credit for {{$invoice->invoice_number}}  ??</h4>
    
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="{{route('manager.credit.request',Crypt::encrypt($invoice->id))}}"
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
                                        <th>Order Number</th>
                                        <th>Order Date</th>
                                        <th>Cost</th>
                                        <th>Invoice Date</th>
                                        <th>status</th>
                                        <th></th>
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
