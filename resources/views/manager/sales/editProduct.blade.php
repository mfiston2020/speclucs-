@extends('manager.includes.app')

@section('title','Admin Dashboard - Add Product')

@push('css')
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','New Product')
@section('current','Add Product')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Update <strong>{{\App\Models\Product::where(['id'=>$product->product_id])->where('company_id',Auth::user()->company_id)->pluck('product_name')->first()}}</strong></h4>

                </div>
                <hr>

                <form class="form-horizontal" action="{{route('manager.sales.update.product',Crypt::encrypt($product->id))}}" method="POST">
                    @csrf
                    <input type="hidden" value="{{$product->invoice_id}}" name="invoice_id">
                    <div class="card-body">
                        {{-- ====== input error message ========== --}}
                        @include('manager.includes.layouts.message')
                        {{-- ====================================== --}}

                        <div class="form-group row">
                            <label for="stock" class="col-sm-3 text-right control-label col-form-label">Unit Price</label>
                            <div class="col-sm-9">
                                <input type="text" id="unit_price" class="form-control" placeholder="0" name="unit_price"
                                    readonly required value="{{$product->unit_price}}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fstock" class="col-sm-3 text-right control-label col-form-label">Quantity 
                                <span id="left" style="color: red">| {{\App\Models\Product::where(['id'=>$product->product_id])->where('company_id',Auth::user()->company_id)->pluck('stock')->first()}} Left</span>
                            </label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="quantity" placeholder="quantity"
                                    name="quantity" value="{{$product->quantity}}" min="1">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cost"
                                class="col-sm-3 text-right control-label col-form-label invalid">Total Amount</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="total_amount" placeholder="0" name="total_amount"
                                    value="{{$product->total_amount}}" readonly>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="form-group m-b-0 text-right">
                            <button type="submit" class="btn btn-info waves-effect waves-light">Update</button>
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

<script>
    
    $('#quantity').on('input',function(){
        var qty =   $(this).val();
        var up  =   $('#unit_price').val();

        var total   =   $("#total_amount").val(qty*up);
    });
</script>
@endpush
