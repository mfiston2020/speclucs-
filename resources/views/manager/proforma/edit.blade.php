@extends('manager.includes.app')

@section('title','Manager Dashboard - My Orders')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Orders')
@section('page_name','Orders List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">

    </div>

    {{-- ================================== --}}
    <div class="row">

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Editing {{$product->product_name}}</h4>
                </div>
                <hr>
                <form class="form-horizontal" method="POST" action="{{route('manager.proforma.update.product')}}">
                    @csrf
                    <div class="card-body">
                        <input type="hidden" name="proforma_id" value="{{$product->id}}">
                        <input type="hidden" name="proforma" value="{{$product->proforma_id}}">
                        <div class="form-group row">
                            <label for="product_name" class="col-sm-3 text-right control-label col-form-label">Product Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="product_name" value="{{$product->product_name}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="quantity" class="col-sm-3 text-right control-label col-form-label">Quantity</label>
                            <div class="col-sm-9">
                                <input type="number" value="{{$product->quantity}}" name="quantity" class="form-control" id="quantity" placeholder="Quantity Here">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email1" class="col-sm-3 text-right control-label col-form-label">Unit Price</label>
                            <div class="col-sm-9">
                                <input type="text" readonly value="{{$product->unit_price}}" class="form-control" name="unit_price" id="unit_price">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="insurance_percentage" class="col-sm-3 text-right control-label col-form-label">Insurance Percentage</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="percentage" value="{{$product->insurance_percentage}}" id="insurance_percentage" placeholder="Insurance Percentage Here">
                            </div>
                        </div>
                    </div>  
                    <hr>
                    <div class="card-body">
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                            <button type="submit" class="btn btn-dark waves-effect waves-light">Cancel</button>
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