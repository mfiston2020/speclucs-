@extends('manager.includes.app')

@section('title', 'Dashboard - Invoice Detail')

@push('css')
    <style>
        @media print {
            .noprint {
                visibility: hidden !important;
            }
        }
    </style>
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current', 'Invoice Detail')
@section('page_name', 'Invoice Detail')
{{-- === End of breadcumb == --}}

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-body printableArea">
                    <h3><b>INVOICE</b> <span class="pull-right">#{{ sprintf('%04d', $invoice->reference_number) }}</span>

                        @if ($invoice->hospital_name!=null)
                            | [{{$invoice->cloud_id}}] {{$invoice->hospital_name}}
                        @else
                            | {{$invoice->client_name}}
                        @endif
                    </h3>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                <address>

                                    <img src="{{ asset('documents/logos/' . $companyInfo->logo) }}" alt=""
                                        height="100px">
                                    {{-- @if (Auth::user()->company_id != 3) --}}
                                    <h3> &nbsp;<b class="text-danger">{{ $companyInfo->company_name }}</b></h3>
                                    {{-- @endif --}}
                                    <p class="text-muted m-l-5"><strong class="text-black-50">TIN Number:</strong>
                                        {{ $companyInfo->company_tin_number }}
                                        {{-- <br /><span></span> {{$companyInfo->company_street}} --}}
                                        <br /><strong class="text-black-50">Phone Number:</strong>
                                        {{ $companyInfo->company_phone }}
                                        <br /><strong class="text-black-50">Email:</strong>
                                        {{ $companyInfo->company_email }}
                                    </p>
                                </address>
                            </div>
                            <div class="pull-right text-right">
                                @if ($invoice->client_id)
                                    <address>
                                        <p class="m-t-30"><b>Name :</b> {{ $clients_information->name }}</p>
                                        <p class="m-t-30"><b>Phone :</b> {{ $clients_information->phone }}</p>
                                        <p class="m-t-30"><b>Email :</b> {{ $clients_information->email }}</p>
                                        <p><b>Invoice Date :</b> <i class="fa fa-calendar"></i>
                                            {{ date('Y-m-d H:m:s', strtotime($invoice->updated_at)) }}</p>
                                    </address>
                                @else
                                    <address>
                                        <p class="m-t-30"><b>Name :</b>
                                            @if ($invoice->hospital_name)
                                                {{ $invoice->hospital_name }}
                                            @else
                                                {{ $invoice->client_name }}
                                            @endif
                                        </p>
                                        <p class="m-t-30"><b>Phone :</b> {{ $invoice->phone??'-' }}</p>
                                        <p class="m-t-30"><b>TIN Number :</b> {{ $invoice->tin_number??'-' }}</p>
                                        <p><b>Invoice Date:</b> <i class="fa fa-calendar"></i>
                                            {{ date('Y-m-d H:m:s', strtotime($invoice->updated_at)) }}</p>
                                    </address>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive m-t-40" style="clear: both;">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Product Name</th>
                                            <th class="text-right">Seg H</th>
                                            <th class="text-right">Mono PD</th>
                                            <th class="text-right">Quantity</th>
                                            <th class="text-right">Unit Cost</th>
                                            <th class="text-right">Total</th>
                                        </tr>
                                    </thead>
                                    @php
                                        $total_=0;
                                        $Instotal_=0;
                                        $Pttotal_=0;
                                    @endphp
                                    <tbody>
                                        @foreach ($invoice->unavailableProducts as $key => $product)
                                            @php
                                                $type = \App\Models\LensType::where('id', $product->type_id)
                                                    ->pluck('name')
                                                    ->first();
                                                $coating = \App\Models\PhotoCoating::where('id', $product->coating_id)
                                                    ->pluck('name')
                                                    ->first();
                                                $index = \App\Models\PhotoIndex::where('id', $product->index_id)
                                                    ->pluck('name')
                                                    ->first();
                                                $chromatics = \App\Models\PhotoChromatics::where('id', $product->chromatic_id)
                                                    ->pluck('name')
                                                    ->first();
                                            @endphp
                                            <tr>
                                                <td class="text-center"><center>{{ OneInitials($product->eye) }}</center></td>
                                                <td>
                                                    {{ initials($type)=='BT'?'Bifocal Round Top':lensDescription(initials($type)) . ' ' . $index . ' ' . $chromatics . ' ' . $coating }}
                                                    @if (initials($type) == 'SV' && is_null($product->cylinder))
                                                        <span> {{ format_values($product->sphere) }} /
                                                            {{ format_values($product->cylinder) }}</span>
                                                    @else
                                                        <span>{{ format_values($product->sphere) }} /
                                                            {{ format_values($product->cylinder) }}
                                                            *{{ format_values($product->axis) }}
                                                            {{ $product->addition }}</span>
                                                    @endif
                                                </td>
                                                @php
                                                    $total_+=$product->price;
                                                @endphp
                                                <td class="text-right">{{ is_null($product->segment_h)?'-':$product->segment_h }}</td>
                                                <td class="text-right">{{ is_null($product->mono_pd)?'-': $product->mono_pd}}</td>
                                                <td class="text-right">{{ $product->quantity }} </td>
                                                <td class="text-right"> {{ format_money($product->price) }} </td>
                                                <td class="text-right"> {{ format_money($product->price * $product->quantity) }} </td>
                                            </tr>
                                        @endforeach
                                        @foreach ($invoice->soldproduct as $key => $product)
                                            @php
                                                $prod = $products->where('id', $product->product_id)->first();
                                            @endphp
                                            <tr>
                                                <td class="text-center">
                                                    @if ($product->eye!=null)
                                                        <center>{{ OneInitials($product->eye) }}</center>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $prod->product_name }} | {{ lensDescription($prod->description) }}
                                                    @if ($prod->power)
                                                        @if (initials($prod->product_name) == 'SV' && is_null($prod->power->cylinder))
                                                            <span> {{ $prod->power->sphere }} /
                                                                {{ $prod->power->cylinder }}</span>
                                                        @else
                                                            <span>{{ $prod->power->sphere }} /
                                                                {{ $prod->power->cylinder }}
                                                                *{{ $prod->power->axis }}
                                                                {{ $prod->power->add }}</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                @php
                                                    $total_+=$product->quantity*$product->unit_price;
                                                    if ($invoice->insurance_id) {
                                                        $percentage     =   ($total_*$product->percentage)/100;
                                                        $Pttotal_       =   $total_-$percentage;
                                                        $Instotal_      =   $percentage;
                                                    }
                                                @endphp
                                                <td class="text-right">{{ is_null($product->segment_h)?'-':$product->segment_h }}</td>
                                                <td class="text-right">{{ is_null($product->mono_pd)?'-': $product->mono_pd}}</td>
                                                <td class="text-right">{{ $product->quantity }} </td>
                                                <td class="text-right"> {{ format_money($product->unit_price) }} </td>
                                                <td class="text-right"> {{ format_money($product->total_amount) }} </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="pull-right m-t-30 text-right">
                                @php
                                    $total_invoice_amount = $total_;
                                @endphp
                                <p>Total amount: {{ format_money($total_invoice_amount) }}</p>
                                @if ($invoice->insurance_id)
                                    <p>Ins amount: {{ format_money($Instotal_) }}</p>
                                    <p>Pt amount: {{ format_money($Pttotal_) }}</p>
                                @endif
                                <p>Total paid: {{ format_money($total_invoice_amount - $invoice->due) }}</p>
                                <p>Due: {{ format_money($invoice->due) }}</p>
                                <p>vat (18%) : {{ format_money(0) }} </p>
                                <hr>
                                <h3><b>Total :</b>
                                    @if ($invoice->insurance_id)
                                        {{ format_money($Pttotal_) }}
                                    @else
                                        {{ format_money($total_invoice_amount) }}
                                    @endif
                                </h3>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                                    @php
                                        $total_=0;
                                        $Instotal_=0;
                                        $Pttotal_=0;
                                    @endphp
                    </div>
                </div>
                <hr class="noprint">
                <div class="text-right noprint">
                    <button id="print" class="btn btn-default btn-outline" type="button">
                        <span><i class="fa fa-print"></i> Print</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('dashboard/assets/dist/js/pages/samplepages/jquery.PrintArea.js') }}"></script>
    <script>
        $(function() {
            $("#print").click(function() {
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
