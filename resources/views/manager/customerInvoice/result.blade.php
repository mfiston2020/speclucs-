@extends('manager.includes.app')

@section('title','Manager Dashboard - Order List')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Order')
@section('page_name','Order List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">

        <form action="{{route('manager.statementInvoice.create')}}" method="post" class="col-12">
            @csrf
            <div class="col-12">
                <div class="card">

                    <div class="card-body">
                        <div class="row">
                            <h4 class="card-title">
                                <a href="{{url()->previous()}}"><i class="fa fa-arrow-alt-circle-left"></i></a>
                                All Customer Orders
                            </h4>
                        </div>
                        <hr>
                        {{-- ================================= --}}
                        @include('manager.includes.layouts.message')
                        {{-- ========================== --}}

                        <span hidden>{{$total=0}}</span>
                        <span hidden>{{$total1=0}}</span>
                        <div class="table-responsive">
                            <table id="" class="table table-striped table-bordered nowrap"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check form-check-inline align-content-center">
                                                <input class="form-check-input" type="checkbox" id="checkall"
                                                    value="option1">
                                            </div>
                                        </th>
                                        <th>Order Date</th>
                                        <th>Delivery Date</th>
                                        <th>Order Number</th>
                                        <th>Lens Total</th>
                                        <th>Frame Total</th>
                                        <th>Accessories Total</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $customerInvoice as $key=> $invoice )
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-inline align-content-center">
                                                <input class="form-check-input allCheckbox" type="checkbox"
                                                    name="invoice[]" id="inlineCheckbox1" value="{{$invoice->id}}">
                                            </div>
                                        </td>
                                        <td>{{ date('Y-m-d',strtotime($invoice->created_at)) }}</td>
                                        <td>{{ date('Y-m-d',strtotime($invoice->orderrecord[0]->created_at)) }}</td>
                                        <td>
                                            <a href="{{route('manager.sales.edit',Crypt::encrypt($invoice->id))}}">
                                                ORDER #{{ sprintf('%04d',$invoice->id) }}
                                            </a>
                                        </td>
                                        <td>{{ format_money($invoice->sumOfCategorizedproduct()['lens']) }}</td>
                                        <td>{{format_money($invoice->sumOfCategorizedproduct()['frame'])}}</td>
                                        <td>{{format_money($invoice->sumOfCategorizedproduct()['accessories'])}}</td>
                                        <td>{{format_money($invoice->totalAmount())}}</td>
                                        <td><span class="text-success">{{$invoice->status}}</span></td>
                                        <span hidden>{{$total=$total+$invoice->total_amount}}</span>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <input type="hidden" name="client_id" value="{{$client_id}}">
                        <div class="form-group m-b-0 text-center">
                            {{-- <a href="#" id="print" class="btn btn-default btn-outline"> <span><i
                                class="fa fa-save"></i> Save Invoice</span> </a> --}}
                            {{-- <a href="#" id="save" class="btn btn-default btn-outline"> <span><i
                                class="fa fa-save"></i> Save Invoice</span> </a> --}}
                            <button type="submit" class="btn btn-success waves-effect waves-light text-white">
                                <i class="fa fa-save"></i> Save Invoice
                            </button>
                            {{-- <a id="sendEmail" href="#" class="btn btn-secondary waves-effect waves-light text-white">Send as email</a>
                            <img src="{{ asset('dashboard/assets/images/loading.gif')}}" alt="" height="50px"
                            width="50px"
                            id="loading"> --}}
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-12" hidden>
                <div class="card card-body printableArea">
                    <h3><b>INVOICE</b> <span class="pull-right">#{{sprintf('%04d',$invoice->reference_number)}}</span>
            </h3>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-left">
                        <address>
                            <span
                                hidden>{{$company=\App\Models\CompanyInformation::where(['id'=>Auth::user()->company_id])->select('*')->first()}}</span>
                            <h3> &nbsp;<b class="text-danger">{{$company->company_name}}</b></h3>
                            <p class="text-muted m-l-5">{{$company->company_tin_number}}
                                <br /> {{$company->company_street}}
                                <br /> {{$company->company_phone}}
                                <br /> {{$company->company_email}}</p>
                        </address>
                    </div>
                    <div class="pull-right text-right">
                        <address>
                            <span
                                hidden>{{$client=\App\Models\Customer::where(['id'=>$invoice->client_id])->select('name','company_id','phone')->first()}}</span>
                            {{-- <span hidden>{{$company=\App\Models\CompanyInformation::where(['id'=>$client->company_id])->select('*')->first()}}</span>
                            --}
                            <p class="m-t-30"><b>Client Name :</b> {{$client->name}}</p>
                            <p class="m-t-30"><b>Company Name :</b> {{$client->name}}</p>
                            <p class="m-t-30"><b>Phone :</b> {{$client->phone}}</p>
                            {{-- <p class="m-t-30"><b>TIN Number :</b> {{$company->company_tin_number}}</p> --}
                            <p><b>Invoice Date :</b> <i class="fa fa-calendar"></i>
                                {{\Carbon\Carbon::parse($invoice->updated_at)}}</p>
                        </address>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive m-t-40" style="clear: both;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Invoice Date</th>
                                    <th>Invoice Number</th>
                                    <th class="text-right">product Quantity</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customerInvoice as $key=> $invoice)
                                <tr>
                                    <span
                                        hidden>{{$client=\App\Models\Customer::where(['id'=>$invoice->client_id])->where('company_id',Auth::user()->company_id)->pluck('name')->first()}}</span>
                                    <span
                                        hidden>{{$products=\App\Models\SoldProduct::where(['invoice_id'=>$invoice->id])->where('company_id',Auth::user()->company_id)->select('product_id')->get()}}</span>
                                    @foreach ($products as $item)
                                    <span hidden>{{$allProduct[]=$item}}</span>
                                    @endforeach
                                    <td>{{$key+1}}</td>
                                    <td>{{date('Y-m-d',strtotime($invoice->created_at))}}</td>
                                    <td>INVOICE #{{sprintf('%04d',$invoice->reference_number)}} </td>
                                    <td class="text-right">
                                        {{\App\Models\SoldProduct::where(['invoice_id'=>$invoice->id])->where('company_id',Auth::user()->company_id)->select('*')->sum('quantity')}}
                                    </td>
                                    <td class="text-right">{{format_money($invoice->total_amount)}}</td>
                                    <span hidden>{{$total1=$total1+$invoice->total_amount}}</span>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="pull-right m-t-30 text-right">
                        {{-- <span hidden>{{$vat=(($invoice->total_amount*18)/100)}}</span> --}
                        <p>Sub - Total amount: {{format_money($total1)}}</p>
                        <p>vat (0%) : {{format_money(0)}} </p>
                        <hr>
                        <h3><b>Total :</b> {{format_money($total1)}}</h3>
                    </div>
                </div>
            </div>
    </div>
</div> --}}
</form>
{{--
        <form action="{{route('manager.cutomerInvoice.email',Crypt::encrypt($invoice->id))}};" id="sendCustomerEmail"
method="POST" hidden>
@csrf
<input type="text" id="hello" name="invoices">
<input type="text" id="hello" name="start_date" value="{{$from}}">
<input type="text" id="hello" name="end_date" value="{{$to}}">
</form> --}}
</div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js')}}"></script>
<script src="{{ asset('dashboard/assets/dist/js/pages/samplepages/jquery.PrintArea.js')}}"></script>
<script>
    $('#loading').hide();
    $("#sendEmail").on('click', function () {
        checkbox();
    });

    $(function () {
        $("#print").click(function () {
            var mode = 'iframe'; //popup
            var close = mode == "popup";
            var options = {
                mode: mode,
                popClose: close
            };
            $("div.printableArea").printArea(options);
            manager.invoice.pay
        });
    });

    $("#checkall").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    function checkbox() {
        var arr = [];
        var text = '';
        $('input.allCheckbox:checkbox:checked').each(function () {
            arr.push($(this).val());
        });
        text = arr.join();
        $('#hello').val(text);
        $('#sendCustomerEmail').submit();

        console.log(text);
    }
</script>
@endpush
