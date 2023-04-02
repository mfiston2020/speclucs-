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
                <div class="card-body">
                    <div class="form-group m-b-0 text-center">
                        <a href="{{ route('manager.patient.medical.prescription',Crypt::encrypt($file->id)) }}" class="btn btn-info waves-effect waves-light">Medical Prescription</a>
                        <a href="{{route('manager.patient.final.prescription',Crypt::encrypt($file->id))}}" class="btn btn-success waves-effect waves-light">Final Prescription</a>
                        {{-- <button id="print" class="btn btn-warning waves-effect waves-light">Print</button> --}}
                        <a href="{{route('manager.patient.file.invoice',Crypt::encrypt($file->id))}}" class="btn btn-danger waves-effect waves-light text-right">Invoice</a>
                    </div>
                </div>
            </div>


            <div class="card">

                <div class="invoiceing-box" style="display: block;">
                    <div class="card card-body printableArea" id="printableArea">
                        <div class="invoice-header d-flex align-items-center border-bottom pb-3">
                            <h3 class="font-medium text-uppercase mb-0">file number</h3>
                            <div class="ml-auto">
                                <h4 class="invoice-number">
                                    File # {{date('Ymd',strtotime($file->created_at))}}-{{sprintf('%04d',$file->file_number)}}
                                </h4>
                            </div>
                        </div>
                        <div class="" id="custom-invoice" style="display: block;">
                            <!-- (1) -->
                            <div class="invoice-123" style="">
                                <div class="row pt-3">
                                        <div class="col-md-12 d-flex justify-content-between">
                                            {{-- <div class="pull-left">
                                                <address>
                                                    {{-- <h4 class="mb-0 font-weight-bold">&nbsp;Steve Jobs</h4> --}
                                                    <div class="mb-2">
                                                        <span class="font-weight-bold ml-1">Republic Of Rwanda</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class="font-weight-bold ml-1">Ministry of Health</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class="font-weight-bold ml-1">P.O Box 84 Kigali</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <a href="http://moh.gov.rw" class="font-weight-bold ml-1">www.moh.gov.rw</a>
                                                    </div>
                                                </address>
                                            </div> --}}
                                            <div class="pull-right text-left">
                                                    <span hidden>{{$company=\App\Models\CompanyInformation::where(['id'=>Auth::user()->company_id])->select('*')->first()}}</span>
                                                <img src="{{ asset('documents/logos/'.$company->logo)}}" style="height: 100px">


                                                <address>
                                                    <br>
                                                    {{-- <h3> &nbsp;<b>Name:</b> {{$company->company_name}}</h3> --}}
                                                    <p class="text-muted m-l-5"><strong class="text-black-50">TIN Number: </strong>{{$company->company_tin_number}}
                                                        {{-- <br /> <strong class="text-black-50">Phone Number:</strong> {{$company->company_street}} --}}
                                                        <br /> <strong class="text-black-50">Phone Number: </strong>{{$company->company_phone}}
                                                        <br /> <strong class="text-black-50">E-mail Address: </strong> {{$company->company_email}}</p>
                                                </address>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="table-responsive mt-5">
                                                <table class="table table-hover">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan='6' class="text-center">
                                                                <h4>Patient Record Card</h4>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left">
                                                                <span class="font-weight-bold ml-1">Date:</span>
                                                                <span class="invoice-number ml-2">{{date('Y-m-d',strtotime($file->created_at))}}</span>
                                                            </td>
                                                            <td class="text-left">
                                                                <span class="font-weight-bold ml-1">Examiner Name:</span>
                                                                <span class="invoice-number ml-2">{{\App\Models\User::where('id',$file->user_id)->pluck('name')->first()}}</span>
                                                            </td>
                                                            <td class="text-left">
                                                                <span class="font-weight-bold ml-1">Patient Code:</span>
                                                                <span class="invoice-number ml-2">Patient # {{sprintf('%04d',$patient->patient_number)}}</span>
                                                            </td>
                                                            <td class="text-left">
                                                                <span class="font-weight-bold ml-1">Rwandan:</span>
                                                                <span class="invoice-number ml-2">-</span>
                                                            </td>
                                                            <td class="text-left">
                                                                <span class="font-weight-bold ml-1">Patient Number:</span>
                                                                <span class="invoice-number ml-2">Patient# {{date('Ymd',strtotime($file->created_at))}}-{{sprintf('%04d',$patient->patient_number)}}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left" colspan="2">
                                                                <span class="font-weight-bold ml-1">Firstname:</span>
                                                                <span class="invoice-number ml-2">{{$patient->firstname}}</span>
                                                            </td>
                                                            <td class="text-left" colspan="2">
                                                                <span class="font-weight-bold ml-1">Lastname:</span>
                                                                <span class="invoice-number ml-2">{{$patient->lastname}}</span>
                                                            </td>
                                                            <td class="text-left" colspan="2">
                                                                <span class="font-weight-bold ml-1">Birthdate:</span>
                                                                <span class="invoice-number ml-2">{{$patient->birthdate}}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left" colspan="2">
                                                                <span class="font-weight-bold ml-1">Province:</span>
                                                                <span class="invoice-number ml-2">{{\App\Models\Province::where('id',$patient->province)->pluck('name')->first()}}</span>
                                                            </td>
                                                            <td class="text-left" colspan="2">
                                                                <span class="font-weight-bold ml-1">District:</span>
                                                                <span class="invoice-number ml-2">{{\App\Models\District::where('id',$patient->district)->pluck('name')->first()}}</span>
                                                            </td>
                                                            <td class="text-left" colspan="2">
                                                                <span class="font-weight-bold ml-1">Sector:</span>
                                                                <span class="invoice-number ml-2">{{\App\Models\Sector::where('id',$patient->sector)->pluck('name')->first()}}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left" colspan="6">
                                                                <span class="font-weight-bold ml-1">Phone Number:</span>
                                                                <span class="invoice-number ml-2">{{$patient->phone}}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left" colspan="2">
                                                                <span class="font-weight-bold ml-1">Martial Status:</span>
                                                                <span class="invoice-number ml-2">{{$patient->status}}</span>
                                                            </td>
                                                            <td class="text-left" colspan="2">
                                                                <span class="font-weight-bold ml-1">Insurance:</span>
                                                                <span class="invoice-number ml-2">
                                                                    @if ($file->insurance_id==0)
                                                                        Private
                                                                    @else
                                                                        {{\App\Models\Insurance::where('id',$file->insurance_id)->pluck('insurance_name')->first()}}
                                                                    @endif
                                                                </span>
                                                            </td>
                                                            <td class="text-left" colspan="2">
                                                                <span class="font-weight-bold ml-1">Father:</span>
                                                                <span class="invoice-number ml-2">{{$patient->father_name}}</span>
                                                                <br>
                                                                <span class="font-weight-bold ml-1">Mother:</span>
                                                                <span class="invoice-number ml-2">{{$patient->mother_name}}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left" colspan="3">
                                                                <span class="font-weight-bold ml-1">Chief Complaint</span>
                                                                <br>
                                                                <ul>
                                                                    @foreach ($complaint_data as $key=> $complaint)
                                                                    <li>
                                                                        {{\App\Models\Complaint::where('id',$complaint->complaint_id)->pluck('name')->first()}}
                                                                        <strong class="text-primary">: {{$complaint->value}}</strong>
                                                                    </li>
                                                                    @endforeach
                                                                </ul>
                                                            </td>
                                                            <td class="text-left" colspan="3">
                                                                <span class="font-weight-bold ml-1">History</span>
                                                                <br>
                                                                <ul>
                                                                    @foreach ($history_data as $key=> $history)
                                                                    <li>
                                                                        <strong class="text-primary">
                                                                            {{$history->value}}</strong>
                                                                            {{\App\Models\History::where('id',$history->history_id)->pluck('name')->first()}}
                                                                    </li>
                                                                    @endforeach
                                                                </ul>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left" colspan="2">
                                                                <span class="font-weight-bold ml-1">Unaided:</span><br>
                                                                <strong class="text-primary mr-1">R</strong> 6/{{$file->unaided_right}}
                                                                <br>
                                                                <strong class="text-primary mr-1">L</strong> 6/{{$file->unaided_left}}<br>
                                                                NEAR
                                                            </td>
                                                            <td class="text-left" colspan="2">
                                                                <span class="font-weight-bold ml-1">Pinhole:</span><br>
                                                                <strong class="text-primary mr-1">R</strong> 6/{{$file->pinhole_right}}
                                                                <br>
                                                                <strong class="text-primary mr-1">L</strong> 6/{{$file->pinhole_left}}
                                                            </td>
                                                            <td class="text-left" colspan="2">
                                                                <span class="font-weight-bold ml-1">Current Eyewear:</span><br>
                                                                <strong class="text-primary mr-2">Lens Type:</strong> {{\App\Models\LensType::where('id',$file->current_lens_type)->pluck('name')->first()}}
                                                                <br>
                                                                <strong class="text-primary mr-1">Index:</strong> {{\App\Models\PhotoIndex::where('id',$file->current_index)->pluck('name')->first()}}
                                                                <strong class="text-primary mr-1">Chromatics:</strong> {{\App\Models\PhotoChromatics::where('id',$file->current_chromatics)->pluck('name')->first()}}
                                                                <strong class="text-primary mr-1">Coating:</strong> {{\App\Models\PhotoCoating::where('id',$file->current_coating)->pluck('name')->first()}}
                                                                <br>
                                                                <strong class="text-primary mr-1">Sphere:</strong> {{$file->current_sphere}}
                                                                <strong class="text-primary mr-1">Cylinder:</strong> {{$file->current_cylinder}}
                                                                <strong class="text-primary mr-1">Axis:</strong> {{$file->current_axis}}
                                                                <strong class="text-primary mr-1">Addition:</strong> {{$file->current_addition}}
                                                                <strong class="text-primary mr-1">6/:</strong> {{$file->current_6}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" class="text-left" >
                                                                <span class="font-weight-bold ml-1">Auto / Refraction:</span>
                                                            </td>
                                                            <td colspan="3" class="text-left" >
                                                                <span class="font-weight-bold ml-1">Final Prescription:</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left" colspan="3">
                                                                <strong class="text-primary mr-2">Lens Type:</strong> {{\App\Models\LensType::where('id',$file->subjective_lens_type)->pluck('name')->first()}} |
                                                                {{-- <br> --}}
                                                                <strong class="text-primary mr-1">Index:</strong> {{\App\Models\PhotoIndex::where('id',$file->subjective_index)->pluck('name')->first()}} |
                                                                <strong class="text-primary mr-1">Chromatics:</strong> {{\App\Models\PhotoChromatics::where('id',$file->subjective_chromatics)->pluck('name')->first()}} |
                                                                <strong class="text-primary mr-1">Coating:</strong> {{\App\Models\PhotoCoating::where('id',$file->subjective_coating)->pluck('name')->first()}}
                                                                <br>
                                                                <br>
                                                                <h5>Right</h5>
                                                                <strong class="text-primary mr-1">Sphere:</strong> {{$file->subjective_sphere_right}} |
                                                                <strong class="text-primary mr-1">Cylinder:</strong> {{$file->subjective_cylinder_right}} |
                                                                <strong class="text-primary mr-1">Axis:</strong> {{$file->subjective_axis_right}} |
                                                                <strong class="text-primary mr-1">Addition:</strong> {{$file->subjective_addition_right}} |
                                                                <strong class="text-primary mr-1">6/:</strong> {{$file->subjective_6_right}}
                                                                <br>
                                                                <br>
                                                                <h5>Left</h5>
                                                                <strong class="text-primary mr-1">Sphere:</strong> {{$file->subjective_sphere_left}} |
                                                                <strong class="text-primary mr-1">Cylinder:</strong> {{$file->subjective_cylinder_left}} |
                                                                <strong class="text-primary mr-1">Axis:</strong> {{$file->subjective_axis_left}} |
                                                                <strong class="text-primary mr-1">Addition:</strong> {{$file->subjective_addition_left}} |
                                                                <strong class="text-primary mr-1">6/:</strong> {{$file->subjective_6}}
                                                            </td>
                                                            <td class="text-left" colspan="3">

                                                                <strong class="text-primary mr-2">Lens Type:</strong> {{\App\Models\LensType::where('id',$file->final_lens_type)->pluck('name')->first()}} |
                                                                {{-- <br> --}}
                                                                <strong class="text-primary mr-1">Index:</strong> {{\App\Models\PhotoIndex::where('id',$file->final_index)->pluck('name')->first()}} |
                                                                <strong class="text-primary mr-1">Chromatics:</strong> {{\App\Models\PhotoChromatics::where('id',$file->final_chromatics)->pluck('name')->first()}} |
                                                                <strong class="text-primary mr-1">Coating:</strong> {{\App\Models\PhotoCoating::where('id',$file->final_coating)->pluck('name')->first()}}
                                                                <br>
                                                                <br>
                                                                <h5>Right</h5>
                                                                <strong class="text-primary mr-1">Sphere:</strong> {{$file->final_sphere_right}} |
                                                                <strong class="text-primary mr-1">Cylinder:</strong> {{$file->final_cylinder_right}} |
                                                                <strong class="text-primary mr-1">Axis:</strong> {{$file->final_axis_right}} |
                                                                <strong class="text-primary mr-1">Addition:</strong> {{$file->final_addition_right}} |
                                                                <strong class="text-primary mr-1">6/:</strong> {{$file->final_6_right}}
                                                                <br>
                                                                <br>
                                                                <h5>Left</h5>
                                                                <strong class="text-primary mr-1">Sphere:</strong> {{$file->final_sphere_left}} |
                                                                <strong class="text-primary mr-1">Cylinder:</strong> {{$file->final_cylinder_left}} |
                                                                <strong class="text-primary mr-1">Axis:</strong> {{$file->final_axis_left}} |
                                                                <strong class="text-primary mr-1">Addition:</strong> {{$file->final_addition_left}} |
                                                                <strong class="text-primary mr-1">6/:</strong> {{$file->final_6_left}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" class="text-left" >
                                                                <span class="font-weight-bold ml-1">Auto / Refraction:</span>
                                                            </td>
                                                            <td colspan="3" class="text-left" >
                                                                <span class="font-weight-bold ml-1">Final Prescription:</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="6" class="text-center" >
                                                                <span class="font-weight-bold ml-1">Eye Health Examination</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" class="text-center" >
                                                                <span class="font-weight-bold ml-1">Slit Lamp</span>
                                                            </td>
                                                            <td colspan="3" class="text-center" >
                                                                <span class="font-weight-bold ml-1">Fundoscopy</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center" >
                                                                <span class="font-weight-bold">Right</span>
                                                                <br>
                                                            </td>
                                                            <td class="text-center" >

                                                            </td>
                                                            <td class="text-center" >
                                                                <span class="font-weight-bold">Left</span>
                                                                <br>
                                                            </td>
                                                            {{-- fundoscopy --}}
                                                            <td class="text-center" >
                                                                <span class="font-weight-bold">Right</span>
                                                                <br>
                                                            </td>
                                                            <td class="text-center" >

                                                            </td>
                                                            <td class="text-center" >
                                                                <span class="font-weight-bold">Left</span>
                                                                <br>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center" >
                                                                <span>{{$file->lids_right}}</span>
                                                                <br>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span class="font-weight-bold">Lid</span>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span>{{$file->lids_left}}</span>
                                                                <br>
                                                            </td>
                                                            {{-- fundoscopy --}}
                                                            <td class="text-center" >
                                                                <span>{{$file->c_d_right}}</span>
                                                                <br>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span class="font-weight-bold">C / D</span>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span>{{$file->c_d_left}}</span>
                                                                <br>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center" >
                                                                <span>{{$file->conjuctiva_right}}</span>
                                                                <br>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span class="font-weight-bold">Conjuctiva</span>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span>{{$file->conjuctiva_left}}</span>
                                                                <br>
                                                            </td>
                                                            {{-- fundoscopy --}}
                                                            <td class="text-center" >
                                                                <span>{{$file->macula_right}}</span>
                                                                <br>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span class="font-weight-bold">Macula</span>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span>{{$file->macula_left}}</span>
                                                                <br>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center" >
                                                                <span>{{$file->cornea_right}}</span>
                                                                <br>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span class="font-weight-bold">cornea</span>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span>{{$file->cornea_left}}</span>
                                                                <br>
                                                            </td>
                                                            {{-- fundoscopy --}}
                                                            <td class="text-center" >
                                                                <span>{{$file->periphery_right}}</span>
                                                                <br>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span class="font-weight-bold">Periphery</span>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span>{{$file->periphery_left}}</span>
                                                                <br>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center" >
                                                                <span>{{$file->a_c_right}}</span>
                                                                <br>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span class="font-weight-bold">A / C</span>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span>{{$file->a_c_left}}</span>
                                                                <br>
                                                            </td>
                                                            {{-- fundoscopy --}}
                                                            <td class="text-center" >
                                                                <span>{{$file->iop_right}}</span>
                                                                <br>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span class="font-weight-bold">iop</span>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span>{{$file->iop_left}}</span>
                                                                <br>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center" >
                                                                <span>{{$file->iris_right}}</span>
                                                                <br>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span class="font-weight-bold">Iris</span>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span>{{$file->iris_left}}</span>
                                                                <br>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center" >
                                                                <span>{{$file->pupil_right}}</span>
                                                                <br>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span class="font-weight-bold">Pupil</span>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span>{{$file->pupil_left}}</span>
                                                                <br>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center" >
                                                                <span>{{$file->lens_right}}</span>
                                                                <br>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span class="font-weight-bold">Lens</span>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span>{{$file->lens_left}}</span>
                                                                <br>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-center" >
                                                                <span>{{$file->vitreous_right}}</span>
                                                                <br>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span class="font-weight-bold">Vitreous</span>
                                                            </td>
                                                            <td class="text-center" >
                                                                <span>{{$file->vitreous_left}}</span>
                                                                <br>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left" colspan="2">
                                                                <span class="font-weight-bold ml-1">Assessment / Diagnosis</span>
                                                            </td>
                                                            <td class="text-left" colspan="4">
                                                                <span>{{$file->assessment_diagnosis}}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left" colspan="2">
                                                                <span class="font-weight-bold ml-1">Management / Treatment</span>
                                                            </td>
                                                            <td class="text-left" colspan="4">
                                                                <span>{{$file->management_treatment}}</span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                @if ($medical_prescription)
                                                    <table class="table table-hover">
                                                        <tbody>
                                                                <tr>
                                                                    <td colspan='7' class="text-center border-0">

                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan='7' class="text-center">
                                                                        <h4>Medical Prescription</h4>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-left">
                                                                        <span class="font-weight-bold">Medicatio</span>
                                                                    </td>
                                                                    <td class="text-left">
                                                                        <span class="font-weight-bold">Strength</span>
                                                                    </td>
                                                                    <td class="text-left">
                                                                        <span class="font-weight-bold">Route</span>
                                                                    </td>
                                                                    <td class="text-left">
                                                                        <span class="font-weight-bold">Strength</span>
                                                                    </td>
                                                                    <td class="text-left">
                                                                        <span class="font-weight-bold">Frequency</span>
                                                                    </td>
                                                                    <td class="text-left">
                                                                        <span class="font-weight-bold">Total Dosage</span>
                                                                    </td>
                                                                    <td class="text-left">
                                                                        <span class="font-weight-bold">Duration</span>
                                                                    </td>
                                                                </tr>
                                                                @foreach ($medical_prescription as $item)
                                                                    <tr>
                                                                        <td class="text-left">
                                                                            <span class="invoice-number">{{$item->medication}}</span>
                                                                        </td>
                                                                        <td class="text-left">
                                                                            <span class="invoice-number">{{$item->strength}}</span>
                                                                        </td>
                                                                        <td class="text-left">
                                                                            <span class="invoice-number">{{$item->route}}</span>
                                                                        </td>
                                                                        <td class="text-left">
                                                                            <span class="invoice-number">{{$item->dosage}}</span>
                                                                        </td>
                                                                        <td class="text-left">
                                                                            <span class="invoice-number">{{$item->frequency}}</span>
                                                                        </td>
                                                                        <td class="text-left">
                                                                            <span class="invoice-number">{{$item->t_dosage}}</span>
                                                                        </td>
                                                                        <td class="text-left">
                                                                            <span class="invoice-number">{{$item->duration}}</span>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                        </tbody>
                                                    </table>
                                                @endif
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-12">
                                            <div class="pull-right mt-4 text-right">
                                                <p>Sub - Total amount: $13,848</p>
                                                <p>vat (10%) : $138 </p>
                                                <hr>
                                                <h3><b>Total :</b> $13,986</h3>
                                            </div>
                                            <div class="clearfix"></div>
                                            <hr>
                                            <div class="text-right">
                                                <button class="btn btn-danger" type="submit"> Proceed to payment </button>
                                                <button class="btn btn-default print-page" type="button"> <span><i class="fa fa-print"></i> Print</span> </button>
                                            </div>
                                        </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="form-group m-b-0 text-center">
                        <a href="{{ route('manager.patient.medical.prescription',Crypt::encrypt($file->id)) }}" class="btn btn-info waves-effect waves-light">Medical Prescription</a>
                        <a href="{{route('manager.patient.final.prescription',Crypt::encrypt($file->id))}}" class="btn btn-success waves-effect waves-light">Final Prescription</a>
                        {{-- <button id="print" class="btn btn-warning waves-effect waves-light">Print</button> --}}
                        <a href="{{route('manager.patient.file.invoice',Crypt::encrypt($file->id))}}" class="btn btn-danger waves-effect waves-light text-right">Invoice</a>
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
