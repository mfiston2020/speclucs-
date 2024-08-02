@extends('seller.includes.app')

@section('title','Manager Dashboard - Order Invoice Summary')

@push('css')
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Order Invoice Summary')
@section('page_name','Order Invoice Summary')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title"> <a href="{{url()->previous()}}"><i
                                    class="fa fa-arrow-alt-circle-left"></i></a> Order Invoice Summary</h4>
                    </div>
                </div>
            </div>
            <div class="card">

                <div class="card-body printableArea" id="printableArea">
                    {{-- ================================= --}}
                    @include('seller.includes.layouts.message')
                    {{-- ========================== --}}

                    <div class="col-md-12 d-flex justify-content-between">
                        <div class="pull-left">
                                <span hidden>{{$company=\App\Models\CompanyInformation::where(['id'=>Auth::user()->company_id])->select('*')->first()}}</span>
                            <img src="{{ asset('documents/logos/'.$company->logo)}}" style="height: 150px">
                            <address>
                                @if (Auth::user()->company_id!=3)
                                    <h3> &nbsp;<b class="text-danger">{{$company->company_name}}</b></h3>
                                @endif
                                <p class="text-muted m-l-5"><strong class="text-black-50">TIN:</strong> {{$company->company_tin_number}}
                                    {{-- <br /><span></span> {{$company->company_street}} --}}
                                    <br /><strong class="text-black-50">Phone Number:</strong> {{$company->company_phone}}
                                    <br /><strong class="text-black-50">Email:</strong> {{$company->company_email}}</p>
                            </address>
                        </div>
                        <div>
                            <h3>Statement Invoice List</h3>
                        </div>
                        <div class="pull-right text-right">
                            <address>
                                <p class="m-t-30"><b>Name :</b> {{$customer->name}}</p>
                                    <p class="m-t-30"><b>Phone :</b> {{$customer->phone}}</p>
                                        {{-- <p class="m-t-30"><b>TIN Number :</b> {{$customer->tin_number}}</p> --}}
                                <p><b>Due Date :</b> <i class="fa fa-calendar"></i>
                                    {{date('Y-m-d H:m:s',strtotime($customer->updated_at))}}</p>
                            </address>
                        </div>
                    </div>

                    <form action="{{route('seller.statementInvoice.pay')}}" method="post" id="payment-form">
                        @csrf
                        <div class="table-responsive">
                            <table id="zero_config" class="table table-striped table-bordered nowrap"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check form-check-inline align-content-center">
                                                <input class="form-check-input" type="checkbox" id="checkall"
                                                    value="option1">
                                            </div>
                                        </th>
                                        <th>Statement Date</th>
                                        <th>Statement #</th>
                                        <th>Paid</th>
                                        <th>Unpaid</th>
                                        <th>Total Amount</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <span hidden>{{$total=0}}</span>
                                    <span hidden>{{$total_u=0}}</span>
                                    <span hidden>{{$total_p=0}}</span>

                                    <span hidden>{{$paid=0}}</span>
                                    <span hidden>{{$unpaid=0}}</span>

                                    @foreach ($invoices as $invoice)

                                        <tr>
                                            <td>
                                                <div class="form-check form-check-inline align-content-center">
                                                    <input class="form-check-input allCheckbox" type="checkbox"
                                                        name="invoice[]" id="inlineCheckbox1" value="{{$invoice->id}}"
                                                        {{($invoice->status=='paid')?'disabled':''}}>
                                                </div>
                                            </td>
                                            <td>{{date('Y-m-d',strtotime($invoice->created_at))}}</td>
                                            <td>
                                                <a href="{{ route('seller.statementInvoice.details',Crypt::encrypt($invoice->id))}}">
                                                    Statement  #{{sprintf('%04d',$invoice->id)}}
                                                </a>
                                            </td>
                                            <span hidden>{{$total=\App\Models\Invoice::where('statement_id',$invoice->id)->select('*')->sum('total_amount')}}</span>

                                            @if ($invoice->status!='invoiced')
                                                <span>{{$paid+=$total}}</span>
                                            @else
                                                <span>{{$unpaid+=$total}}</span>
                                            @endif


                                            <td>
                                                @if ($invoice->status=='invoiced')
                                                    {{format_money($total_p=0)}}
                                                @else
                                                    {{format_money($total)}}
                                                @endif

                                            </td>
                                            <td>
                                                @if ($invoice->status=='invoiced')
                                                    {{format_money($total)}}
                                                @else
                                                    {{format_money($total_u=0)}}
                                                @endif
                                            </td>
                                            <td>{{format_money($total)}}</td>
                                            <td>
                                                <a href="{{ route('seller.statementInvoice.details',Crypt::encrypt($invoice->id))}}">More Details >></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <div class="pull-right m-t-30 text-right">
                                <h4><b>Paid:</b> {{format_money($paid)}}</h4>
                            </div>
                            <div class="pull-right m-t-30 text-right">
                                <h4><b>Unpaid:</b> {{format_money($unpaid)}}</h4>
                            </div>
                            <div class="clearfix"></div>
                            <div class="pull-right m-t-30 text-right">
                                <h3><b>Total :</b> {{format_money($paid+$unpaid)}}</h3>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                        <div class="form-group m-b-0 text-center">
                            {{-- <button onclick="exportAll('xlsx')" class="btn btn-success waves-effect waves-light text-white">
                                <i class="fa fa-download"></i> Export To Excel</button> --}}
                            <button id="print" class="btn btn-info waves-effect waves-light"><i class="fa fa-print"></i> Print</button>
                            <button id="pay-button" class="btn btn-secondary waves-effect waves-light text-white">
                                <i class="fa fa-money-bill-alt"></i> Pay Invoice</button>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- <script src="{{ asset('dashboard/assets/dist/js/export.js')}}"></script> --}}
<script src="{{ asset('dashboard/assets/dist/js/pages/samplepages/jquery.PrintArea.js')}}"></script>
<script>

    $('#pay-button').on('click',function(){
        $('#payment-form').submit();
        $(this).html('Loading....');
        $(this).prop('disabled',true);
    })
    $(function () {
        $("#print").click(function () {
            var mode = 'iframe'; //popup
            var close = mode == "popup";
            var options = {
                mode: mode,
                popClose: close
            };
            $("div.printableArea").printArea(options);
        });
    });
</script>
@endpush
