@extends('manager.includes.app')

@section('title','Manager Dashboard - print Proforma')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('dashboard/assets/dist/css/style.css')}}">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','print Proforma')
@section('current','print Proforma')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-md-12">
            <div class="card card-body printableArea">
                <div class="d-flex justify-content-between">
                    <h3>
                        <b>PROFORMA INVOICE No</b> <span class="pull-right">#{{sprintf('%04d',$proforma->id)}}</span>
                    </h3>
                    {{-- @if ($proforma->status=='pending' || $proforma->status=='finalized')
                        <h3> &nbsp;<b class="text-danger text-capitalize">{{$proforma->status}}</b></h3>
                    @endif --}}
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-left">
                            <address>
                                <span hidden>{{$company=\App\Models\CompanyInformation::where(['id'=>Auth::user()->company_id])->select('*')->first()}}</span>
                                <img src="{{ asset('documents/logos/'.$company->logo)}}" alt="" height="100px">
                                @if (Auth::user()->company_id!=3)
                                    <h3> &nbsp;<b class="text-danger">{{$company->company_name}}</b></h3>
                                @endif

                                <p class="text-muted m-l-5"><strong class="text-black-50">TIN:</strong> {{$company->company_tin_number}}
                                    {{-- <br /><span></span> {{$company->company_street}} --}}
                                    <br /><strong class="text-black-50">Bank Name:</strong> {{$company->bank_name}}
                                    <br /><strong class="text-black-50">Account Name:</strong> {{$company->account_name}}
                                    <br /><strong class="text-black-50">Account No:</strong> {{$company->account_number}}
                                    <br /><strong class="text-black-50">Swift Code:</strong> {{$company->swift_code}}</p>
                            </address>
                        </div>
                        <div class="pull-right text-right">
                            <address>
                                <h3>To,</h3>
                                @if ($proforma->patient_id==null)
                                    <h4 class="font-bold">{{$proforma->patient_firstname}} {{$proforma->patient_lastname}}</h4>
                                    <p class="text-muted ml-4">{{$proforma->patient_phone}}
                                        <br> {{$proforma->patient_email}} </p>
                                        <span class="text-muted ml-4">
                                            {{\App\Models\Insurance::where('id',$proforma->insurance_id)->pluck('insurance_name')->first()}}
                                        </span>
                                        <br>
                                        <strong>Insurance Card Number: </strong>{{$proforma->patient_number}}
                                @else
                                    <?php $client=\App\Models\Patient::find($proforma->patient_id); ?>
                                    <h4 class="font-bold">{{$client->firstname}} {{$client->lastname}}</h4>
                                    <p class="text-muted ml-4">{{$client->phone}}
                                        <br>
                                        {{\App\Models\Insurance::where('id',$proforma->insurance_id)->pluck('insurance_name')->first()}}
                                        <br>
                                        <strong>Insurance Card Number: </strong>{{$proforma->patient_number}}
                                @endif
                            </address>
                        </div>
                    </div>

                    <span hidden>{{$total_insurance=0}}</span>
                    <span hidden>{{$total_Patient=0}}</span>

                    <div class="col-md-12">
                        <div class="table-responsive mt-5" style="clear: both;">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Description</th>
                                        <th class="text-right">Quantity</th>
                                        <th class="text-right">Unit Cost</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-right">Patient Payment</th>
                                        <th class="text-right">Insurance Payment</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($proforma_detail as $product)
                                    <span hidden>{{$client=0}}</span>
                                    <span hidden>{{$insurance=0}}</span>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td>
                                                {{$product->product_name }} | {{ $product->description}}
                                                <br>
                                                @if ($product->type_id!=null)
                                                    @if (initials($product->product_name)=='SV')
                                                        <span> {{$product->sphere}} / {{$product->cylinder}}</span>
                                                    @else
                                                        <span>{{$product->sphere}} / {{$product->cylinder}} *{{$product->axis}}  {{$product->add}}</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-right">{{$product->quantity}} </td>
                                            <td class="text-right"> {{format_money($product->unit_price)}} </td>
                                            <td class="text-right"> {{format_money($product->total_amount)}} </td>
                                            <span hidden>{{$insurance=($product->total_amount*$product->insurance_percentage)/100 }}</span>
                                            <span hidden>{{$client=$product->total_amount-$insurance}}</span>

                                            <span hidden>{{$total_insurance+=$insurance}}</span>
                                            <span hidden>{{$total_Patient+=$product->total_amount-$insurance}}</span>
                                            <td class="text-right"> {{format_money($product->total_amount-$insurance)}} </td>
                                            <td class="text-right"> {{format_money($insurance)}} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="pull-right mt-4 text-right">
                            <p>Patient - Total amount: {{format_money($total_Patient)}}</p>
                            <p>Insurance - Total amount: {{format_money($total_insurance)}}</p>
                            <p>vat (18%) : {{format_money(0)}} </p>
                            <hr>
                            <h3><b>Total :</b> {{format_money($total_Patient+$total_insurance)}}</h3>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="col-md-12 d-flex justify-content-between">
                            <div class="pull-right text-right">
                                <address>
                                    <h5><b>for {{$company->company_name}}</b></h5>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                </address>
                            </div>
                        </div>
                        <h5>&nbsp; Authorised Signatory</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <button id="print" class="btn btn-default btn-outline" type="button">
                                <span><i class="fa fa-print"></i> Print</span> </button>
                        </div>
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
