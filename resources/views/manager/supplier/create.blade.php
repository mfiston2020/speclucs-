@extends('manager.includes.app')

@section('title','Admin Dashboard - Add Suppliers')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/select2/dist/css/select2.min.css')}}">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','New Suppliers')
@section('current','Add Suppliers')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Suppliers Information</h4>

                </div>
                <hr>

                <form class="form-horizontal" action="{{route('manager.supplier.save')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        {{-- ====== input error message ========== --}}
                        @include('manager.includes.layouts.message')
                        {{-- ====================================== --}}
                        <div class="form-group row">
                            <label for="" class="col-sm-3 text-right control-label col-form-label">Suppliers
                                Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="sname" placeholder="Suppliers Name"
                                    name="suppliers_name" value="{{old('suppliers_name')}}">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="" class="col-sm-3 text-right control-label col-form-label">Suppliers
                                description</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="sdescription" placeholder="Suppliers description"
                                    name="suppliers_description" value="{{old('suppliers_description')}}">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="" class="col-sm-3 text-right control-label col-form-label">Suppliers
                                Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="semail" placeholder="Suppliers email"
                                    name="suppliers_email" value="{{old('suppliers_email')}}">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="" class="col-sm-3 text-right control-label col-form-label">Suppliers
                                phone</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="sphone" placeholder="Suppliers phone"
                                    name="suppliers_phone" value="{{old('suppliers_phone')}}">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="form-group m-b-0 text-right">
                            <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                            <button type="reset" class="btn btn-dark waves-effect waves-light">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('dashboard/assets/libs/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/libs/select2/dist/js/select2.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/dist/js/pages/forms/select2/select2.init.js')}}"></script>
@endpush
