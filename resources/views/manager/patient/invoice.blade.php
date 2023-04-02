@extends('manager.includes.app')

@section('title','Admin Dashboard - Invoice Detail')

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
                <h3><b>INV</b> <span class="pull-right">#{{date('Ymd',strtotime($invoice->created_at))}}-{{sprintf('%04d',$invoice->invoice_number)}}</span></h3>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-left">
                                <span hidden>{{$company=\App\Models\CompanyInformation::where(['id'=>Auth::user()->company_id])->select('*')->first()}}</span>
                            <img src="{{ asset('documents/logos/'.$company->logo)}}" style="height: 150px">
                            <address>
                                @if (Auth::user()->company_id!=3)
                                    <h3> &nbsp;<b class="text-danger">{{$company->company_name}}</b></h3>
                                @endif
                                <p class="text-muted m-l-5"><strong class="text-black-50">TIN Number:</strong> {{$company->company_tin_number}}
                                    {{-- <br /><span></span> {{$company->company_street}} --}}
                                    <br /><strong class="text-black-50">Phone Number:</strong> {{$company->company_phone}}
                                    <br /><strong class="text-black-50">Email:</strong> {{$company->company_email}}</p>
                            </address>
                        </div>
                        <div class="pull-right text-right">
                            <address>
                                <p class="m-t-30"><b>Name :</b> {{$patient->firstname}} {{$patient->lastname}}</p>
                                    <p class="m-t-30"><b>Phone :</b> {{$patient->phone}}</p>
                                        <p class="m-t-30"><b>TIN Number :</b> {{$invoice->tin_number}}</p>
                                <p><b>Due Date :</b> <i class="fa fa-calendar"></i>
                                    {{date('Y-m-d H:m:s',strtotime($invoice->updated_at))}}</p>
                            </address>
                        </div>
                    </div>
                    <span hidden>{{$grandTotal=0}}</span>
                    <div class="col-md-12">
                        <div class="table-responsive m-t-40" style="clear: both;">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Exam Name</th>
                                        <th class="text-right">Insurance Percentage</th>
                                        <th class="text-right">Customer Percentage</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($exams as $key=> $exam)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td>{{$exam['exam_name']}}</td>
                                        <td class="text-right">{{$exam['percentage']}} %</td>
                                        <td class="text-right"> {{100-$exam['percentage']}} %</td>
                                        <td class="text-right"> {{format_money($exam['amount']-$exam['total'])}} </td>
                                        <span hidden>{{$grandTotal=$grandTotal+($exam['amount']-$exam['total'])}}</span>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="pull-right m-t-30 text-right">
                            <span hidden>{{$vat=(($invoice->total_amount*18)/100)}}</span>
                            <p>Client Total amount: {{format_money($grandTotal)}}</p>
                            <p>Insurance Total amount: {{format_money($grandT-$grandTotal)}}</p>
                            {{-- <p>Total paid: {{format_money($invoice->total_amount-$invoice->due)}}</p> --}}
                            <p>Due: {{format_money($invoice->due)}}</p>
                            {{-- <p>vat (18%) : {{format_money($vat*0)}} </p> --}}
                            <hr>
                            <h3><b>Total :</b> {{format_money($grandTotal)}}</h3>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center noprint">
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
