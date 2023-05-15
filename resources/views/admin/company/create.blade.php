@extends('admin.includes.app')

@section('title','Admin Dashboard - add company')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','New Company')
@section('current','Add Company')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <!-- Sales chart -->
    <div class="row">
        <div class="col-lg-8 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Create New Company</h4>
                    {{-- ====== input error message ========== --}}
                    @error('category_name')
                    <div class="alert alert-danger alert-rounded">
                        {{$message}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span
                                aria-hidden="true">×</span> </button>
                    </div>
                    @enderror
                    {{-- ========== inserting error message ========== --}}
                    @if(session('errorMsg'))
                    <div class="alert alert-danger alert-rounded">
                        <b>Error! </b>{{session('errorMsg')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span
                                aria-hidden="true">×</span> </button>
                    </div>
                    @enderror

                    {{-- ====== input error message ========== --}}
                    @include('admin.includes.layouts.message')
                    {{-- ====================================== --}}

                    <form class="form-horizontal p-t-20" method="POST" action="{{route('admin.company.save')}}">
                        @csrf
                        {{-- ==================================== --}}
                        <div class="form-group row">
                            <label for="company_name" class="col-sm-3 control-label">Company Name<span
                                    style="color: red">*</span></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="ti-tag"></i></span></div>
                                    <input type="text" class="form-control" id="company_name" placeholder="Company Name"
                                        name="company_name" value="{{old('company_name')}}">
                                </div>
                            </div>
                        </div>
                        {{-- ==================================== --}}
                        <div class="form-group row">
                            <label for="company_email" class="col-sm-3 control-label">Company Email<span
                                    style="color: red">*</span></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="ti-tag"></i></span></div>
                                    <input type="text" class="form-control" id="company_email"
                                        placeholder="Company Email" name="company_email"
                                        value="{{old('company_email')}}">
                                </div>
                            </div>
                        </div>
                        {{-- ==================================== --}}
                        <div class="form-group row">
                            <label for="company_phone" class="col-sm-3 control-label">Company Phone<span
                                    style="color: red">*</span></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="ti-tag"></i></span></div>
                                    <input type="text" class="form-control" id="company_phone"
                                        placeholder="Company Email" name="company_phone"
                                        value="{{old('company_phone')}}">
                                </div>
                            </div>
                        </div>
                        {{-- ==================================== --}}
                        <div class="form-group row">
                            <label for="company_street" class="col-sm-3 control-label">Company Street<span
                                    style="color: red">*</span></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="ti-tag"></i></span></div>
                                    <input type="text" class="form-control" id="company_street"
                                        placeholder="Company Street" name="company_street"
                                        value="{{old('company_street')}}">
                                </div>
                            </div>
                        </div>
                        {{-- ==================================== --}}
                        <div class="form-group row">
                            <label for="tin_number" class="col-sm-3 control-label">Company TIN Number<span
                                    style="color: red">*</span></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="ti-tag"></i></span></div>
                                    <input type="text" class="form-control" id="tin_number"
                                        placeholder="Company TIN Number" name="tin_number"
                                        value="{{old('tin_number')}}">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h4>Managing Director </h4>
                        <hr>
                        {{-- ==================================== --}}
                        <div class="form-group row">
                            <label for="director_name" class="col-sm-3 control-label">Name<span
                                    style="color: red">*</span></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="ti-tag"></i></span></div>
                                    <input type="text" class="form-control" id="director_name"
                                        placeholder="director name" name="director_name"
                                        value="{{old('director_name')}}">
                                </div>
                            </div>
                        </div>
                        {{-- ==================================== --}}
                        <div class="form-group row">
                            <label for="director_email" class="col-sm-3 control-label">email<span
                                    style="color: red">*</span></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="ti-tag"></i></span></div>
                                    <input type="text" class="form-control" id="director_email"
                                        placeholder="director email" name="director_email"
                                        value="{{old('director_email')}}">
                                </div>
                            </div>
                        </div>
                        {{-- ==================================== --}}
                        <div class="form-group row">
                            <label for="director_phone" class="col-sm-3 control-label">Phone<span
                                    style="color: red">*</span></label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="ti-tag"></i></span></div>
                                    <input type="text" class="form-control" id="director_phone"
                                        placeholder="director phone" name="director_phone"
                                        value="{{old('director_phone')}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row m-b-0">
                            <div class="offset-sm-3 col-sm-9">
                                <button type="submit" class="btn btn-info waves-effect waves-light">Save
                                    Company</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

@endpush
