@extends('seller.includes.app')

@section('title','Seller - Edit Receipt')

@push('css')
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','Edit Receipt')
@section('current','Edit Receipt')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Update <strong>{{$product_=\App\Models\Product::where(['id'=>$product->product_id])->where('company_id',Auth::user()->company_id)->pluck('product_name')->first()}}
                        <span hidden>{{$power=\App\Models\Power::where(['product_id'=>$product->product_id])->where('company_id',Auth::user()->company_id)->select('*')->first()}}</span>
                        @if ($power)
                        @if (initials($product_)=='SV')
                            <span>{{$power->sphere}} / {{$power->sphere}}</span>
                        @else
                            <span>{{$power->sphere}} / {{$power->sphere}} *{{$power->axis}} {{$power->add}}</span>
                        @endif
                    @else
                        <span>
                            {{\App\Models\Category::where(['id'=>$prod->category_id])->where('company_id',Auth::user()->company_id)->pluck('name')->first()}}
                        </span>
                    @endif
                    </strong></h4>
                    
                </div>
                <hr>

                <form class="form-horizontal" action="{{route('seller.save.receipt',Crypt::encrypt($product->id))}}" method="POST">
                    @csrf
                    <input type="hidden" value="{{$product->receipt_id}}" name="invoice_id">
                    <div class="card-body">
                        {{-- ====== input error message ========== --}}
                        @include('seller.includes.layouts.message')
                        {{-- ====================================== --}}

                        <div class="form-group row">
                            <label for="stock" class="col-sm-3 text-right control-label col-form-label">Stock</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="stock Here" name="stock"
                                    value="{{$product->stock}}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cost" class="col-sm-3 text-right control-label col-form-label">Cost</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="cost" id="cost" value="{{$product->cost}}"
                                    readonly>
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

@endpush
