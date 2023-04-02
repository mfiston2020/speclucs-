@extends('manager.includes.app')

@section('title','Manager Dashboard - Add Credits')

@push('css')

@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','New Credits')
@section('current','Add Credits')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Add Credits</h4>

                </div>
                <hr>

                <form class="form-horizontal" action="{{route('manager.credit.save')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        {{-- ====== input error message ========== --}}
                        @include('manager.includes.layouts.message')
                        {{-- ====================================== --}}

                        <div class="form-group row">
                            <label for="invoice" class="col-sm-3 text-right control-label col-form-label">Invoice Number</label>
                            <div class="col-sm-9">
                                <input type="text" id="invoice" class="form-control" value="{{$invoice->id}}" name="invoice"
                                    readonly required>
                                    <input type="hidden" name="invoice_id" id="invoice_id" value="{{$invoice->id}}">
                                    <input type="hidden" name="order_id" id="order_id" value="{{$invoice->order_id}}">
                                    <input type="hidden" name="supplier_id" id="supplier_id" value="{{$invoice->supplier_id}}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="balance"
                                class="col-sm-3 text-right control-label col-form-label invalid">Invoice Amount</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{format_money($invoice->cost)}}" id="balance" name="balance" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="amount" class="col-sm-3 text-right control-label col-form-label">Credit Amount 
                                <span id="left" style="color: red"></span>
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="amount" placeholder="0"
                                    name="amount" min="1" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="form-group m-b-0 text-right">
                            <button type="submit" class="btn btn-info waves-effect waves-light">Request Credit</button>
                            <a href="{{url()->previous()}}" class="btn btn-dark waves-effect waves-light">Cancel</a>
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
