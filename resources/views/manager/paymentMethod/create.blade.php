@extends('manager.includes.app')

@section('title','Admin Dashboard - Add Payment Method')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/select2/dist/css/select2.min.css')}}">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','New Payment Method')
@section('current','Add Payment Method')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Payment Method Information</h4>

                </div>
                <hr>

                <form class="form-horizontal" action="{{route('manager.paymentMethods.save')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        {{-- ====== input error message ========== --}}
                        @include('manager.includes.layouts.message')
                        {{-- ====================================== --}}
                        <div class="form-group row">
                            <label for="" class="col-sm-3 text-right control-label col-form-label">
                                Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="" placeholder=" Name"
                                    name="name" value="{{old('name')}}">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="" class="col-sm-3 text-right control-label col-form-label">
                                description</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="sdescription" placeholder="description"
                                    name="description" value="{{old('description')}}">
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
