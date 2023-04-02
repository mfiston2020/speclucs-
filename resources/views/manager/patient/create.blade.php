@extends('manager.includes.app')

@section('title','Manager Dashboard - Create Patient')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/select2/dist/css/select2.min.css')}}">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','New Patient')
@section('current','Add Patient')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Patient Information</h4>

                </div>

                <form class="form-horizontal" action="{{route('manager.patient.save')}}" method="POST">
                    @csrf
                    <div class="card-body">
                {{-- ====== input error message ========== --}}
                @include('manager.includes.layouts.message')
                {{-- ====================================== --}}
                        <div class="form-group row">
                            <label for="pname"
                                class="col-sm-3 text-right control-label col-form-label">Firstname</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" required
                                    placeholder="Firstname" name="firstname" value="{{old('firstname')}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pname" class="col-sm-3 text-right control-label col-form-label">Lastname</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" required
                                    placeholder="Lastname" name="lastname" value="{{old('lastname')}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pname" class="col-sm-3 text-right control-label col-form-label">Birthdate</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" max="{{date('Y-m-d')}}" required
                                    placeholder="Birthdate" name="birthdate" value="{{old('birthdate')}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pname" class="col-sm-3 text-right control-label col-form-label">ID Number</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" required
                                    placeholder="ID Number" name="id_number" value="{{old('id_number')}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pname" class="col-sm-3 text-right control-label col-form-label">Phone Number</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control"
                                    placeholder="+2507" name="phone" value="{{old('phone')}}">
                            <span class="text-info">
                                Start with country code
                            </span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pname" class="col-sm-3 text-right control-label col-form-label">Martial Status</label>
                            <div class="col-sm-9">
                                <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                    name="status" id="status">
                                    <option value="">Select status</option>
                                    <option value="single" {{(old('status')=='single')?'selected':''}}>
                                        Sinlge
                                    </option>
                                    <option value="married" {{(old('status')=='married')?'selected':''}}>
                                        Married
                                    </option>
                                    <option value="divorced" {{(old('status')=='divorced')?'selected':''}}>
                                        Divorced
                                    </option>
                                    <option value="widowed" {{(old('status')=='widowed')?'selected':''}}>
                                        Widowed
                                    </option>
                                </option>

                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pname" class="col-sm-3 text-right control-label col-form-label">Father Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control"
                                    placeholder="Father Name" name="father_name" value="{{old('father_name')}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pname" class="col-sm-3 text-right control-label col-form-label">Mother Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control"
                                    placeholder="Mother Name" name="mother_name" value="{{old('mother_name')}}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pname" class="col-sm-3 text-right control-label col-form-label">Referral</label>
                            <div class="col-sm-9">
                                <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                    name="referral" id="referral">
                                    <option value="">Select referral</option>
                                    <option value="radio" {{(old('referral')=='radio')?'selected':''}}>
                                        Radio
                                    </option>
                                    <option value="billboard" {{(old('referral')=='billboard')?'selected':''}}>
                                        billboard
                                    </option>
                                    <option value="word of mouth" {{(old('referral')=='word of mouth')?'selected':''}}>
                                        word of mouth
                                    </option>
                                    <option value="other" {{(old('referral')=='other')?'selected':''}}>
                                        other
                                    </option>
                                </option>

                                </select>
                            </div>
                        </div>

                        @livewire('address-form')

                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="form-group m-b-0 text-right">
                            <button type="submit"
                                class="btn btn-info waves-effect waves-light">Save</button>
                            <button type="reset"
                                class="btn btn-dark waves-effect waves-light">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

@endpush
