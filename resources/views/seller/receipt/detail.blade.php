@extends('seller.includes.app')

@section('title','Seller - Invoice Detail')

@push('css')
<style>
    @media print {
        .noprint 
        {
            visibility: hidden !important;
        }
    }
</style>
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Invoice Detail')
@section('page_name','Invoice Detail')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
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
                                    @foreach ($products as $key=> $product)
                                    <span
                                        hidden>{{$prod=\App\Models\Product::where(['id'=>$product->product_id])->select('*')->first()}}</span>
                                    <span 
                                        hidden>{{$power=\App\Models\Power::where(['product_id'=>$product->product_id])->where('company_id',Auth::user()->company_id)->select('*')->first()}}</span>
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$prod->product_name }} | {{ $prod->description}} 
                                            @if ($power)
                                            @if (initials($prod->product_name)=='SV')
                                                <span> {{$power->sphere}} / {{$power->sphere}}</span>
                                            @else
                                                <span>{{$power->sphere}} / {{$power->sphere}} *{{$power->axis}}  {{$power->add}}</span>
                                            @endif
                                            @endif
                                        </td>
                                        <td class="text-right">{{($product->quantity)}} </td>
                                        <td class="text-right"> {{format_money($product->unit_price)}} </td>
                                        <td class="text-right"> {{format_money($product->total_amount)}} </td>
                                    </tr>
                                    @endforeach

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
                        <hr class="noprint">
                        <div class="text-right noprint">
                            <button id="print" class="btn btn-default btn-outline" type="button"> <span><i
                                        class="fa fa-print"></i> Print</span> </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('dashboard/assets/dist/js/pages/samplepages/jquery.PrintArea.js')}}"></script>
<script>
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
