@extends('manager.includes.app')

@section('title','Manager Dashboard - File Details')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/select2/dist/css/select2.min.css')}}">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','File')
@section('current','File Detail')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="invoiceing-box" style="display: block;">
                    <div class="card card-body printableArea" id="printableArea">
                        <div class="invoice-header d-flex align-items-center border-bottom pb-3">
                            <h3 class="font-medium text-uppercase mb-0">prescription</h3>
                            <div class="ml-auto">
                                <h4 class="invoice-number">
                                    PSR # {{date('Ymd',strtotime($file->created_at))}}-{{sprintf('%04d',$file->file_number)}}
                                </h4>
                            </div>
                        </div>
                        <div class="" id="custom-invoice" style="display: block;">
                            <!-- (1) -->
                            <div class="invoice-123" style="">
                                <div class="row pt-3">
                                        <div class="col-md-12 d-flex justify-content-between">
                                            <div class="pull-left text-left">
                                                    <span hidden>{{$company=\App\Models\CompanyInformation::where(['id'=>Auth::user()->company_id])->select('*')->first()}}</span>
                                                {{-- <img src="{{ asset('documents/logos/'.$company->logo)}}" style="height: 150px"> --}}
                                                {{-- <address>
                                                    <h3> &nbsp;<b>{{$company->company_name}}</b></h3>
                                                    <p class="text-muted m-l-5">{{$company->company_tin_number}}
                                                        <br/> {{$company->company_street}}
                                                        <br/> {{$company->company_phone}}
                                                        <br/> {{$company->company_email}}</p>
                                                        <span class="font-weight-bold ml-1">Date:</span>
                                                        <span class="invoice-number ml-2">{{date('Y-m-d',strtotime($file->created_at))}}</span>
                                                </address> --}}
                                                <img src="{{ asset('documents/logos/'.$company->logo)}}" style="height: 100px">


                                                <address>
                                                    <br>
                                                    {{-- <h3> &nbsp;<b>Name:</b> {{$company->company_name}}</h3> --}}
                                                    <p class="text-muted m-l-5"><strong class="text-black-50">TIN Number:</strong>{{$company->company_tin_number}}
                                                        {{-- <br /> <strong class="text-black-50">Phone Number:</strong> {{$company->company_street}} --}}
                                                        <br /> <strong class="text-black-50">Phone Number:</strong>{{$company->company_phone}}
                                                        <br /> <strong class="text-black-50">E-mail Address:</strong> {{$company->company_email}}</p>
                                                </address>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="table-responsive mt-2">
                                                <table class="table table-hover">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan='6' class="text-center">
                                                                <h4>Final Prescription</h4>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left">
                                                                <span class="font-weight-bold ml-1">Firstname:</span>
                                                                <span class="invoice-number ml-2">{{$patient->firstname}}</span>
                                                            </td>
                                                            <td class="text-left" colspan="2">
                                                                <span class="font-weight-bold ml-1">Lastname:</span>
                                                                <span class="invoice-number ml-2">{{$patient->lastname}}</span>
                                                            </td>
                                                            <td class="text-left" colspan="2">
                                                                {{-- <span class="font-weight-bold ml-1">Birthdate:</span>
                                                                <span class="invoice-number ml-2">{{$patient->birthdate}}</span> --}}
                                                            </td>
                                                            <td class="text-left">
                                                                <span class="font-weight-bold ml-1">Date:</span>
                                                                <span class="invoice-number ml-2">{{date('Y-m-d',strtotime($file->created_at))}}</span>
                                                            </td>
                                                        </tr>
                                                        {{-- <tr>
                                                            <td class="text-left">
                                                                <span class="font-weight-bold ml-1">Lens Type:</span>
                                                                <span class="invoice-number ml-2">{{\App\Models\LensType::where('id',$file->final_lens_type)->pluck('name')->first()}}</span>
                                                            </td>
                                                            <td class="text-left" colspan="2">
                                                                <span class="font-weight-bold ml-1">Index:</span>
                                                                <span class="invoice-number ml-2">{{\App\Models\PhotoIndex::where('id',$file->final_index)->pluck('name')->first()}}</span>
                                                            </td>
                                                            <td class="text-left" colspan="2">
                                                                <span class="font-weight-bold ml-1">Chromatics::</span>
                                                                <span class="invoice-number ml-2">{{\App\Models\PhotoChromatics::where('id',$file->final_chromatics)->pluck('name')->first()}}</span>
                                                            </td>
                                                            <td class="text-left">
                                                                <span class="font-weight-bold ml-1">Coating:</span>
                                                                <span class="invoice-number ml-2">{{\App\Models\PhotoCoating::where('id',$file->final_coating)->pluck('name')->first()}}</span>
                                                            </td>
                                                        </tr> --}}
                                                    </tbody>
                                                </table>
                                                <hr>
                                            </div>

                                            <div class="table-responsive">
                                                <table id="zero_config" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="border-top-0 font-weight-bold border-left-0 border-right-0"></th>
                                                            <th class="border-top-0 font-weight-bold border-left-0 border-right-0">SPHERE</th>
                                                            <th class="border-top-0 font-weight-bold border-left-0 border-right-0">CYLINDER</th>
                                                            <th class="border-top-0 font-weight-bold border-left-0 border-right-0">AXIS</th>
                                                            <th class="border-top-0 font-weight-bold border-left-0 border-right-0">ADD</th>
                                                            <th class="border-top-0 font-weight-bold border-left-0 border-right-0">MONO PD</th>
                                                            <th class="border-top-0 font-weight-bold border-left-0 border-right-0">SEG HEIGHT</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="font-weight-bold">Right</td>
                                                            <td>{{($file->final_sphere_right>0)?'+':''}}{{format_values($file->final_sphere_right)}}</td>
                                                            <td>{{($file->final_cylinder_right>0)?'+':''}}{{format_values($file->final_cylinder_right)}}</td>
                                                            <td>{{format_values($file->final_axis_right)}}</td>
                                                            <td>
                                                                {{($file->final_add_right>0)?'+':''}}{{format_values($file->final_addition_right)}}
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control border-0">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control border-0">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold">Left</td>
                                                            <td>{{($file->final_sphere_left>0)?'+':''}}{{format_values($file->final_sphere_left)}}</td>
                                                            <td>{{($file->final_cylinder_left>0)?'+':''}}{{format_values($file->final_cylinder_left)}}</td>
                                                            <td>{{format_values($file->final_axis_left)}}</td>
                                                            <td>{{($file->final_add_left>0)?'+':''}}{{format_values($file->final_addition_left)}}</td>
                                                            <td>
                                                                <input type="text" class="form-control border-0">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control border-0">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="table-responsive mt-2">
                                                <table class="table table-hover">
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-left border-0">
                                                                <span class="font-weight-bold ml-1">Lens Type:</span>
                                                                <span class="invoice-number ml-2">{{\App\Models\LensType::where('id',$file->final_lens_type)->pluck('name')->first()}}</span>
                                                            </td>
                                                            <td class="text-left border-0" colspan="2">
                                                                <span class="font-weight-bold ml-1">Index:</span>
                                                                <span class="invoice-number ml-2">{{\App\Models\PhotoIndex::where('id',$file->final_index)->pluck('name')->first()}}</span>
                                                            </td>
                                                            <td class="text-left border-0" colspan="2">
                                                                <span class="font-weight-bold ml-1">Chromatics::</span>
                                                                <span class="invoice-number ml-2">{{\App\Models\PhotoChromatics::where('id',$file->final_chromatics)->pluck('name')->first()}}</span>
                                                            </td>
                                                            <td class="text-left border-0">
                                                                <span class="font-weight-bold ml-1">Coating:</span>
                                                                <span class="invoice-number ml-2">{{\App\Models\PhotoCoating::where('id',$file->final_coating)->pluck('name')->first()}}</span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <hr>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <div class="col-md-12 d-flex justify-content-between">
                                                <div class="pull-right text-right">
                                                    <address>
                                                        <h5> &nbsp;<b>Signature:</b></h5>
                                                    </address>
                                                </div>
                                            </div>
                                        </div>
                                </div>
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
