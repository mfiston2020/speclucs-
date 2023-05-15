@extends('manager.includes.app')

@section('title','Admin Dashboard - Add Product')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('dashboard/assets/libs/bootstrap-duallistbox/dist/bootstrap-duallistbox.min.css')}}">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','New Product')
@section('current','Add Product')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <form class="form-horizontal" action="{{route('manager.patient.diagnose.save')}}" method="POST">
        @csrf
        <div class="row">
            {{-- ====== input error message ========== --}}
            @include('manager.includes.layouts.message')
            {{-- ====================================== --}}

            {{-- =========== patient information =============== --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Patient's Personal information</h4>
                        <hr>
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="hidden" name="patient_id" value="{{$patient->id}}">
                                        <label>Patient #</label>
                                        <input type="text" class="form-control" readonly name="firstname"
                                            value="Patient #{{sprintf('%04d',$patient->patient_number)}}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Firstname</label>
                                        <input type="text" class="form-control" readonly name="firstname"
                                            value="{{$patient->firstname}}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Lastname</label>
                                        <input type="text" class="form-control" readonly name="lastname"
                                            value="{{$patient->lastname}}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Birthdate</label>
                                        <input type="text" class="form-control" readonly name="birthdate"
                                            value="{{$patient->birthdate}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Province</label>
                                        <input type="text" class="form-control" readonly name="firstname"
                                            value="{{\App\Models\Province::where('id',$patient->province)->pluck('name')->first()}}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>District</label>
                                        <input type="text" class="form-control" readonly name="firstname"
                                            value="{{\App\Models\District::where('id',$patient->district)->pluck('name')->first()}}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Sector</label>
                                        <input type="text" class="form-control" readonly name="lastname"
                                            value="{{\App\Models\Sector::where('id',$patient->sector)->pluck('name')->first()}}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Cell</label>
                                        <input type="text" class="form-control" readonly name="birthdate"
                                            value="{{\App\Models\Cell::where('id',$patient->cell)->pluck('name')->first()}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" class="form-control" readonly name="birthdate"
                                            value="{{$patient->phone}}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>INSURANCE</label>
                                        <select class="form-control custom-select" name="insurance"
                                            required>
                                            <option value="">-- Select --</option>
                                            <option value="0"> Private </option>
                                            @foreach ($insurance as $insurance)
                                            <option value="{{$insurance->id}}"
                                                {{(old('insurance')==$insurance->id)?'selected':''}}>
                                                {{$insurance->insurance_name}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- =========== Complaints =============== --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Complaint</h4>
                        <hr>
                        @foreach ($complaint as $item)
                        <div class="input-group mt-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    {{$item->name}}
                                </span>
                            </div>
                            <input type="text" class="form-control" name="complaint[]"
                                value="{{old('complaint[]')}}">
                            <input type="hidden" value="{{$item->id}}" name="complaint_id[]">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- =========== History =============== --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">History</h4>
                        <hr>
                        @foreach ($history as $item)
                        <div class="input-group mt-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    {{$item->name}}
                                </span>
                            </div>
                            <input type="text" class="form-control" name="history[]">
                            <input type="hidden" value="{{$item->id}}" name="history_id[]">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- =========== UNAIDED =============== --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Unaided</h4>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <div class="input-group mt-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">RIGHT</span>
                                        <span class="input-group-text">6 / </span>
                                    </div>
                                    <input type="number" step="any" class="form-control" name="unaided_right"
                                        value="{{old('unaided_right')}}">
                                    @error('unaided_right')
                                    <div>
                                        <span style="color: red">{{$message}}</span>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group mt-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">LEFT</span>
                                        <span class="input-group-text">6 / </span>
                                    </div>
                                    <input type="number" step="any" class="form-control" name="unaided_left"
                                        value="{{old('unaided_left')}}">
                                    @error('unaided_left')
                                    <div>
                                        <span style="color: red">{{$message}}</span>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- =========== PINHOLE =============== --}}
            <div class="col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Pinhole</h4>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <div class="input-group mt-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">RIGHT</span>
                                        <span class="input-group-text">6 / </span>
                                    </div>
                                    <input type="number" step="any" class="form-control" name="pinhole_right"
                                        value="{{old('pinhole_right')}}">
                                    @error('pinhole_right')
                                    <div>
                                        <span style="color: red">{{$message}}</span>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group mt-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">LEFT</span>
                                        <span class="input-group-text">6 / </span>
                                    </div>
                                    <input type="number" step="any" class="form-control" name="pinhole_left"
                                        value="{{old('pinhole_left')}}">
                                    @error('pinhole_left')
                                    <div>
                                        <span style="color: red">{{$message}}</span>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <span hidden>{{$ind=$index}}</span>
            <span hidden>{{$chro=$chromatics}}</span>
            <span hidden>{{$ct=$coatings}}</span>
            {{-- =========== Current =============== --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Current Eye Wear</h4>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-body">
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Lens Type</label>
                                                <select class="form-control custom-select" name="current_lens_type">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($lens_types as $lens_type)
                                                    <option value="{{$lens_type->id}}"
                                                        {{(old('current_lens_type')==$lens_type->id)?'selected':''}}>
                                                        {{$lens_type->name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Index</label>
                                                <select class="form-control custom-select" name="current_index">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($index as $index)
                                                    <option value="{{$index->id}}"
                                                        {{(old('current_index')==$index->id)?'selected':''}}>
                                                        {{$index->name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Chromatics Aspects</label>
                                                <select class="form-control custom-select" name="current_chromatics">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($chromatics as $chromatics)
                                                    <option value="{{$chromatics->id}}"
                                                        {{(old('current_chromatics')==$chromatics->id)?'selected':''}}>
                                                        {{$chromatics->name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Coating</label>
                                                <select class="form-control custom-select" name="current_coating">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($coatings as $coatings)
                                                    <option value="{{$coatings->id}}"
                                                        {{(old('current_coating')==$coatings->id)?'selected':''}}>
                                                        {{$coatings->name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="col-12">
                                <hr>
                                <h4 class="card-title">Right</h4>
                            </div>
                            <div class="col-12 row">
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Sphere</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="current_sphere_right"
                                            value="{{old('current_sphere_right')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Cylinder</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="current_cylinder_right"
                                            value="{{old('current_cylinder_right')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Axis</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="current_axis_right"
                                            value="{{old('current_axis_right')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Addition</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="current_addition_right"
                                            value="{{old('current_addition_right')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">6 /</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="current_6_right"
                                            value="{{old('current_6_right')}}">
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="col-12">
                                <hr>
                                <h4 class="card-title">Left</h4>
                            </div>
                            <div class="col-12 row">
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Sphere</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="current_sphere_left" value="{{old('current_sphere_left')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Cylinder</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="current_cylinder_left" value="{{old('current_cylinder_left')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Axis</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="current_axis_left" value="{{old('current_axis_left')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Addition</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="current_addition_left" value="{{old('current_addition_left')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">6 /</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="current_6_left" value="{{old('current_6_left')}}">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- =========== subjective refraction =============== --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Auto Refraction</h4>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-body">
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Lens Type</label>
                                                <select class="form-control custom-select" name="subjective_lens_type"
                                                   >
                                                    <option value="">-- Select --</option>
                                                    @foreach ($lens_types as $lens_type)
                                                    <option value="{{$lens_type->id}}"
                                                        {{(old('subjective_lens_type')==$lens_type->id)?'selected':''}}>
                                                        {{$lens_type->name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Index</label>
                                                <select class="form-control custom-select" name="subjective_index"
                                                   >
                                                    <option value="">-- Select --</option>
                                                    @foreach ($ind as $index_)
                                                    <option value="{{$index_->id}}"
                                                        {{(old('subjective_index')==$index_->id)?'selected':''}}>
                                                        {{$index_->name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Chromatics Aspects</label>
                                                <select class="form-control custom-select" name="subjective_chromatics"
                                                   >
                                                    <option value="">-- Select --</option>
                                                    @foreach ($chro as $chromatics)
                                                    <option value="{{$chromatics->id}}"
                                                        {{(old('subjective_chromatics')==$chromatics->id)?'selected':''}}>
                                                        {{$chromatics->name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Coating</label>
                                                <select class="form-control custom-select" name="subjective_coating"
                                                   >
                                                    <option value="">-- Select --</option>
                                                    @foreach ($ct as $coatings)
                                                    <option value="{{$coatings->id}}"
                                                        {{(old('subjective_coating')==$coatings->id)?'selected':''}}>
                                                        {{$coatings->name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="col-12">
                                <hr>
                                <h4 class="card-title">Right</h4>
                            </div>
                            <div class="col-12 row">
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Sphere</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="subjective_sphere_right"
                                            value="{{old('subjective_sphere_right')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Cylinder</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="subjective_cylinder_right"
                                            value="{{old('subjective_cylinder_right')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Axis</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="subjective_axis_right"
                                            value="{{old('subjective_axis_right')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Addition</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="subjective_addition_right"
                                            value="{{old('subjective_addition_right')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">6 /</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="subjective_6_right"
                                            value="{{old('subjective_6_right')}}">
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="col-12">
                                <hr>
                                <h4 class="card-title">Left</h4>
                            </div>
                            <div class="col-12 row">
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Sphere</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="subjective_sphere_left"
                                            value="{{old('subjective_sphere_left')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Cylinder</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="subjective_cylinder_left"
                                            value="{{old('subjective_cylinder_left')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Axis</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="subjective_axis_left"
                                            value="{{old('subjective_axis_left')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Addition</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="subjective_addition_left"
                                            value="{{old('subjective_addition_left')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">6 /</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="subjective_6"
                                            value="{{old('subjective_6')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- =========== Final prescription =============== --}}

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Final Prescription</h4>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-body">
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Lens Type</label>
                                                <select class="form-control custom-select" name="final_lens_type"
                                                   >
                                                    <option value="">-- Select --</option>
                                                    @foreach ($lens_types as $lens_type)
                                                    <option value="{{$lens_type->id}}"
                                                        {{(old('final_lens_type')==$lens_type->id)?'selected':''}}>
                                                        {{$lens_type->name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Index</label>
                                                <select class="form-control custom-select" name="final_index">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($ind as $index_)
                                                    <option value="{{$index_->id}}"
                                                        {{(old('final_index')==$index_->id)?'selected':''}}>
                                                        {{$index_->name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Chromatics Aspects</label>
                                                <select class="form-control custom-select" name="final_chromatics"
                                                   >
                                                    <option value="">-- Select --</option>
                                                    @foreach ($chro as $chromatics)
                                                    <option value="{{$chromatics->id}}"
                                                        {{(old('final_chromatics')==$chromatics->id)?'selected':''}}>
                                                        {{$chromatics->name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Coating</label>
                                                <select class="form-control custom-select" name="final_coating"
                                                   >
                                                    <option value="">-- Select --</option>
                                                    @foreach ($ct as $coatings)
                                                    <option value="{{$coatings->id}}"
                                                        {{(old('final_coating')==$coatings->id)?'selected':''}}>
                                                        {{$coatings->name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="col-12">
                                <hr>
                                <h4 class="card-title">Right</h4>
                            </div>
                            <div class="col-12 row">
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Sphere</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="final_sphere_right"
                                            value="{{old('final_sphere_right')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Cylinder</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="final_cylinder_right"
                                            value="{{old('final_cylinder_right')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Axis</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="final_axis_right"
                                            value="{{old('final_axis_right')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Addition</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="final_addition_right"
                                            value="{{old('final_addition_right')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">6 /</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="final_6_right"
                                            value="{{old('final_6_right')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">PD</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="final_pd_right"
                                            value="{{old('final_pd_right')}}">
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="col-12">
                                <hr>
                                <h4 class="card-title">Left</h4>
                            </div>
                            <div class="col-12 row">
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Sphere</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="final_sphere_left"
                                            value="{{old('final_sphere_left')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Cylinder</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="final_cylinder_left"
                                            value="{{old('final_cylinder_left')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Axis</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="final_axis_left"
                                            value="{{old('final_axis_left')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Addition</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="final_addition_left"
                                            value="{{old('final_addition_left')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">6 /</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="final_6_left"
                                            value="{{old('final_6_left')}}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group mt-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">PD</span>
                                        </div>
                                        <input type="number" step="any" class="form-control" name="final_pd_left"
                                            value="{{old('final_pd_left')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- =========== Final prescription =============== --}}


            <div class="col-12 d-flex justify-content-center">
                <h4 class="card-title mt-2">Eye health examination</h4>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-body">
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="h4">Right</label>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <center>
                                                    <label class="h4">Slit Lap</label>
                                                </center>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="h4">Left</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr style="margin-top: -15px !important">

                        <div class="row">
                            <div class="col-12">
                                <div class="form-body">
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="lids_right" value="{{old('lids_right')}}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <center>
                                                    <span class="text-center h5">Lids</span>
                                                </center>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="lids_left" name="lids_left" value="{{old('lids_left')}}"
                                                   >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-body">
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="conjuctiva_right" name="conjuctiva_right"
                                                    value="{{old('conjuctiva_right')}}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <center>
                                                    <span class="text-center h5">Conjuctiva</span>
                                                </center>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="conjuctiva_left" name="conjuctiva_left"
                                                    value="{{old('conjuctiva_left')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-body">
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="cornea_right" name="cornea_right"
                                                    value="{{old('cornea_right')}}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <center>
                                                    <span class="text-center h5">Cornea</span>
                                                </center>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="cornea_left" name="cornea_left" value="{{old('cornea_left')}}"
                                                   >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-body">
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="a_c_right" name="a_c_right" value="{{old('a_c_right')}}"
                                                   >
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <center>
                                                    <span class="text-center h5">A/C</span>
                                                </center>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="a_c_left" name="a_c_left" value="{{old('a_c_left')}}"
                                                   >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-body">
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="iris_right" name="iris_right" value="{{old('iris_right')}}"
                                                   >
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <center>
                                                    <span class="text-center h5">Iris</span>
                                                </center>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="iris_left" name="iris_left" value="{{old('iris_left')}}"
                                                   >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-body">
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="pupil_right" name="pupil_right" value="{{old('pupil_right')}}"
                                                   >
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <center>
                                                    <span class="text-center h5">Pupil</span>
                                                </center>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="pupil_left" name="pupil_left" value="{{old('pupil_left')}}"
                                                   >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-body">
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="lens_right" name="lens_right" value="{{old('lens_right')}}"
                                                   >
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <center>
                                                    <span class="text-center h5">Lens</span>
                                                </center>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="lens_left" name="lens_left" value="{{old('lens_left')}}"
                                                   >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-body">
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="vitreous_right" name="vitreous_right"
                                                    value="{{old('vitreous_right')}}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <center>
                                                    <span class="text-center h5">Vitreous</span>
                                                </center>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="vitreous_left" name="vitreous_left"
                                                    value="{{old('vitreous_left')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-6">

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-body">
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="h4">Right</label>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <center>
                                                    <label class="h4">Fundoscopy</label>
                                                </center>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="h4">Left</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr style="margin-top: -15px !important">

                        <div class="row">
                            <div class="col-12">
                                <div class="form-body">
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="c_d_right" name="c_d_right" value="{{old('c_d_right')}}"
                                                   >
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <center>
                                                    <span class="text-center h5">C/D</span>
                                                </center>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="c_d_left" name="c_d_left" value="{{old('c_d_left')}}"
                                                   >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-body">
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="a_v_right" name="a_v_right" value="{{old('a_v_right')}}"
                                                   >
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <center>
                                                    <span class="text-center h5">A/V</span>
                                                </center>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="a_v_left" name="a_v_left" value="{{old('a_v_left')}}"
                                                   >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-body">
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="macula_right" name="macula_right"
                                                    value="{{old('macula_right')}}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <center>
                                                    <span class="text-center h5">Macula</span>
                                                </center>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="macula_left" name="macula_left" value="{{old('macula_left')}}"
                                                   >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-body">
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="periphery_right" name="periphery_right"
                                                    value="{{old('periphery_right')}}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <center>
                                                    <span class="text-center h5">Periphery</span></span>
                                                </center>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="periphery_left" name="periphery_left"
                                                    value="{{old('periphery_left')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-body">
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="iop_right" name="iop_right" value="{{old('iop_right')}}"
                                                   >
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <center>
                                                    <span class="text-center h5">iOP</span>
                                                </center>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control border-top-0 border-left-0 border-right-0"
                                                    name="iop_left" name="iop_left" value="{{old('iop_left')}}"
                                                   >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card" data-select2-id="447">
                    <div class="card-body">
                        <div class="card-body">
                            <h4 class="card-title">List of all exams</h4>
                            <select multiple="multiple" size="10" class="duallistbox" style="display: none;" name="exams[]">
                                @foreach ($exams as $item)
                                    <option value="{{$item->id}}">{{$item->exam_name}}</option>
                                @endforeach

                            </select>
                            <p class="mt-1"><code>exam clicked will go in the different box</code></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow rounded-sm">
                    <div class="card-body">

                        <div class="form-group row">
                            <label for="assessment_diagnosis" class="col-sm-3 text-right control-label col-form-label">
                                Assessment / Diagnosis
                                <span id="left" style="color: red"></span>
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{old('assessment_diagnosis')}}"
                                    name="assessment_diagnosis" autocomplete="off" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 text-right control-label col-form-label">
                                Management / Treatment
                                <span id="left" style="color: red"></span>
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{old('management_treatment')}}"
                                    name="management_treatment" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 d-flex justify-content-center">
                <h4 class="card-title mt-2">Medical Prescription</h4>
            </div>

            <div class="col-12">
                <div class="card shadow rounded-sm">
                    <div class="card-body">

                        <div class="form-group row">
                            <div class="col-4">
                                <div class="input-group mt-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Medication</span>
                                    </div>
                                    <input type="text" step="any" class="form-control" name="medication[]"
                                        value="{{old('medication')}}">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-group mt-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Strength</span>
                                    </div>
                                    <input type="text" step="any" class="form-control" name="medication_strength[]"
                                        value="{{old('medication_strength')}}">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-group mt-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Route</span>
                                    </div>
                                    <input type="text" step="any" class="form-control" name="medication_route[]"
                                        value="{{old('medication_route')}}">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="input-group mt-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Dosage</span>
                                    </div>
                                    <input type="text" step="any" class="form-control" name="medication_dosage[]"
                                        value="{{old('medication_dosage')}}">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="input-group mt-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Frequency</span>
                                    </div>
                                    <input type="text" step="any" class="form-control" name="medication_frequency[]"
                                        value="{{old('medication_frequency')}}">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="input-group mt-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">T.Dosage</span>
                                    </div>
                                    <input type="text" step="any" class="form-control" name="medication_total_dosage[]"
                                        value="{{old('medication_total_dosage')}}">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="input-group mt-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Duration</span>
                                    </div>
                                    <input type="text" step="any" class="form-control" name="medication_duration[]"
                                        value="{{old('medication_duration')}}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group m-b-0 text-right">
                            <button type="button" class="btn btn-success waves-effect waves-light" onclick="add_medication();">
                                <i class="fas fa-plus-circle"></i>
                            </button>
                        </div>
                        <hr>
                        <div id="add_meddication" class=" m-t-20"></div>

                    </div>
                </div>
            </div>

            {{-- ===========  =============== --}}
            <div class="col-md-12" id="action_buttons">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="form-group m-b-0 text-center">
                            <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                            <a href="{{url()->previous()}}" class="btn btn-dark waves-effect waves-light">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
            {{-- =========== Powe division for addition =============== --}}
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('dashboard/assets/libs/bootstrap-duallistbox/dist/jquery.bootstrap-duallistbox.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/dist/js/pages/forms/dual-listbox/dual-listbox.js')}}"></script>
<script src="{{ asset('dashboard/assets/extra-libs/jquery.repeater/add-medication.js') }}"></script>
@endpush
