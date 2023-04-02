@extends('manager.includes.app')

@section('title','Manager Dashboard - Medical Prescription')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/select2/dist/css/select2.min.css')}}">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','File')
@section('current','Medical Prescription')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="invoiceing-box" style="display: block;">
                    <div class="card card-body printableArea" id="printableArea">
                        <div class="invoice-header d-flex align-items-center border-bottom pb-3">
                            <h3 class="font-medium text-uppercase mb-0">Medical prescription</h3>
                            <div class="ml-auto">
                                <h4 class="invoice-number">
                                    PSR # {{date('Ymd',strtotime($file->created_at))}}-{{sprintf('%04d',$file->file_number)}}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="form-group m-b-0 text-center">
                        <button id="print" class="btn btn-info waves-effect waves-light">Print</button>
                        {{-- <a href="{{route('manager.patient.final.prescription',Crypt::encrypt($file->id))}}" class="btn btn-success waves-effect waves-light">Final Prescription</a>
                        <a href="{{route('manager.patient.file.invoice',Crypt::encrypt($file->id))}}" class="btn btn-danger waves-effect waves-light text-right">Invoice</a> --}}
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
