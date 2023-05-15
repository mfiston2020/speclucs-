<div>
    {{-- <form wire:submit.prevent method="post">
        @csrf --}}
        <div class="row"></div>

        <div class="col-lg-12">

            <div class="alert alert-danger alert-rounded  col-lg-7 col-md-9 col-sm-12" wire:offline>
                You Lost your internet connection
            </div>
            <div class="card">
                <input type="hidden" name="proforma_id" value="{{$proforma}}">

                <div class="card-body">
                    {{-- ====== input error message ========== --}}
                    @include('manager.includes.layouts.message')
                    {{-- ====================================== --}}
                    <div class="form-group row">
                        <div class="col-12 row">
                            <label for="pname" class="col-sm-3 text-right control-label col-form-label">Product
                                category</label>
                            <div class="col-sm-9">
                                <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                    name="category" wire:model='category' required>
                                    <option value=""> ** Select category **</option>
                                    <option value="lens" {{(old('category')=='lens')?'selected':''}}>
                                        Lens
                                    </option>
                                    <option value="others" {{(old('category')=='others')?'selected':''}}>
                                        Other Products
                                    </option>
                                </select>
                                <img src="{{ asset('dashboard/assets/images/loading2.gif')}}" alt="" height="30px"
                                    wire:loading class="mt-2">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            @if ($category=='lens')
            <div class="card" id="lens">
                <div class="card-header bg-info">
                    <h4 class="m-b-0 text-white">Lens Information</h4>
                </div>
                <form class="form-horizontal" wire:submit.prevent>
                    @csrf
                    <div class="form-body">
                        <br>
                        <div class="card-body">
                            <!--/row-->
                            <div class="row">
                                <!--/span-->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Lens Type</label>
                                        <select class="form-control custom-select" name="lens_type" id="lens_type"
                                            wire:model.lazy="lensTpype" required>
                                            <option value="">-- Select --</option>
                                            @foreach ($lens_types as $lens_type)
                                            <option value="{{$lens_type->id}}"
                                                {{(old('lens_type')==$lens_type->id)?'selected':''}}>
                                                {{$lens_type->name}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Index</label>
                                        <select class="form-control custom-select" name="findex" id="index" required
                                            wire:model.lazyx="lensindex">
                                            <option value="">-- Select --</option>
                                            @foreach ($index as $index)
                                            <option value="{{$index->id}}" {{(old('index')==$index->id)?'selected':''}}>
                                                {{$index->name}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Chromatics Aspects</label>
                                        <select class="form-control custom-select" name="fchromatics" id="chromatics"
                                            required wire:model="lenschromatics">
                                            <option value="">-- Select --</option>
                                            @foreach ($chromatics as $chromatics)
                                            <option value="{{$chromatics->id}}"
                                                {{(old('chromatics')==$chromatics->id)?'selected':''}}>
                                                {{$chromatics->name}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Coating</label>
                                        <select class="form-control custom-select" name="fcoating" id="coating" required
                                            wire:model="lenscoating">
                                            <option value="">-- Select --</option>
                                            @foreach ($coatings as $coatings)
                                            <option value="{{$coatings->id}}"
                                                {{(old('coating')==$coatings->id)?'selected':''}}>
                                                {{$coatings->name}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>SPHERE</label>
                                        <input type="text" class="form-control" name="fsphere"
                                            value="{{ old('sphere')}}" id="sphere" required wire:model.lazy="lensphere">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>CYLINDER</label>
                                        <input type="text" class="form-control" name="fcylinder"
                                            value="{{ old('cylinder')}}" id="cylinder" required
                                            wire:model="lencylinder">
                                    </div>
                                </div>
                                @if ($tpype!='SINGLE VISION' && $tpype!=null)
                                <div class="col-md-3">
                                    <div class="form-group" id="axiss">
                                        <label>AXIS</label>
                                        <input type="text" class="form-control" name="faxis" value="{{ old('axis')}}"
                                            id="axis" required wire:model="lensaxis">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" id="adds">
                                        <label>ADD</label>
                                        <input type="text" class="form-control" name="fadd" value="{{ old('add')}}"
                                            id="add" required wire:model="lensadd">
                                    </div>
                                </div>
                                @endif

                            </div>

                            @if ($tpype!='SINGLE VISION' && $tpype!=null)
                            <hr>
                            <div class="col-12 row">
                                <div class="col-md-6" id="eyes">
                                    <div class="form-group row">
                                        <div class="wrapper">
                                            <input type="radio" name="eye" id="option-1" value="right"
                                                wire:model="lenseye">
                                            <input type="radio" name="eye" id="option-2" value="left"
                                                wire:model="lenseye">
                                            <label for="option-1" class="option option-1">
                                                <div class="dot"></div>
                                                <span>Right</span>
                                            </label>
                                            <label for="option-2" class="option option-2">
                                                <div class="dot"></div>
                                                <span>Left</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <hr>
                </form>
                {{-- ======================================================================= --}}
                <div class="card-body">
                    <div class="form-group m-b-0 text-center">

                        <button wire:click="searchLens()"
                            class="btn btn-info waves-effect waves-light text-white">Search Product</button>
                    </div>
                </div>
            </div>
            @endif


            @if ($product!=null && $category=='lens')
            <div class="card">

                <form action="{{route('manager.proforma.save.product')}}" method="POST">
                    @csrf
                    <input type="text" name="proforma_id" value="{{$proforma}}" hidden>
                    <input type="text" name="product_id" value="{{$product[0]->id}}" hidden>
                    <input type="text" name="unit_price" value="{{$product[0]->price}}" hidden>

                    <div class="card-body">
                        <h4 class="card-title">Insurance Information</h4>

                        <div class="form-group row">
                            <label for="pname"
                                class="col-sm-3 text-right control-label col-form-label">insurance</label>
                            <div class="col-sm-9">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="insurance"
                                        value="{{$insurance}}" aria-label="insurance" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pname"
                                class="col-sm-3 text-right control-label col-form-label">Insurance Percentage</label>
                            <div class="col-sm-9">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="percentage" name="percentage"
                                        aria-label="percentage" aria-describedby="basic-addon1">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pname" class="col-sm-3 text-right control-label col-form-label">Quantity</label>
                            <div class="col-sm-9">
                                <div class="input-group mb-3">
                                    <input type="number" min="1" class="form-control" name="quantity"
                                        placeholder="quantity" aria-label="quantity" aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="card-body">
                            <div class="form-group m-b-0 text-center">
                                <button type="submit" class="btn btn-info waves-effect waves-light">Add Product</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @endif

            @if ($category=='others')
                <div class="card" id="non-lens">
                    <div class="card-header bg-info">
                        <h4 class="m-b-0 text-white">Product Information</h4>
                    </div>
                    <br>
                    <form action="{{route('manager.proforma.save.product')}}" method="POST" id="nonLensForm">
                        @csrf

                        <input type="hidden" value="{{$proforma}}" name="proforma_id">

                        <div class="form-group row">
                            <label for="pname" class="col-sm-3 text-right control-label col-form-label">Product
                            </label>
                            <div class="col-sm-9">
                                <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                    name="product_id" wire:model="nonLensProduct" required>
                                    <option value="">Select</option>
                                    @foreach ($non_lens_product as $product)
                                    <option value="{{$product->id}}" {{(old('product')==$product->id)?'selected':''}}>
                                        {{$product->product_name}} | {{$product->description}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="stock" class="col-sm-3 text-right control-label col-form-label">Unit Price</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="0" name="unit_price"
                                    readonly required
                                    @if ($productNonLens!=null)
                                        value="{{$productNonLens->price}}"
                                    @else
                                        value="0"
                                    @endif>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="fstock" class="col-sm-3 text-right control-label col-form-label">Quantity
                                <span id="left2" style="color: red"></span>
                            </label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="quantity2" placeholder="quantity" name="quantity"
                                    value="{{old('quantity')}}" min="0" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pname"
                                class="col-sm-3 text-right control-label col-form-label">insurance</label>
                            <div class="col-sm-9">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="insurance" value="{{$insurance}}"
                                        aria-label="insurance" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="percentage" class="col-sm-3 text-right control-label col-form-label">Insurance Percentage
                                <span id="left2" style="color: red"></span>
                            </label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="percentage2" placeholder="percentage" name="percentage"
                                    value="{{old('percentage')}}" min="0" required>
                            </div>
                        </div>
                        <hr>
                        <div class="card-body">
                            <div class="form-group m-b-0 text-center">
                                <button type="button" class="btn btn-info waves-effect waves-light" onclick="document.getElementById('nonLensForm').submit()">Add Product</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    {{-- </form> --}}
</div>


@push('css')
<style>
    .wrapper {
        display: inline-flex;
        background: #fff;
        height: 100px;
        width: 400px;
        align-items: center;
        justify-content: space-evenly;
        border-radius: 5px;
        padding: 20px 15px;
    }

    .wrapper .option {
        background: #fff;
        height: 100%;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-evenly;
        margin: 0 10px;
        border-radius: 5px;
        cursor: pointer;
        padding: 0 10px;
        border: 2px solid lightgrey;
        transition: all 0.3s ease;
    }

    .wrapper .option .dot {
        height: 20px;
        width: 20px;
        background: #d9d9d9;
        border-radius: 50%;
        position: relative;
    }

    .wrapper .option .dot::before {
        position: absolute;
        content: "";
        top: 4px;
        left: 4px;
        width: 12px;
        height: 12px;
        background: #0069d9;
        border-radius: 50%;
        opacity: 0;
        transform: scale(1.5);
        transition: all 0.3s ease;
    }

    input[type="radio"] {
        display: none;
    }

    #option-1:checked:checked~.option-1,
    #option-2:checked:checked~.option-2 {
        border-color: #0069d9;
        background: #0069d9;
    }

    #option-1:checked:checked~.option-1 .dot,
    #option-2:checked:checked~.option-2 .dot {
        background: #fff;
    }

    #option-1:checked:checked~.option-1 .dot::before,
    #option-2:checked:checked~.option-2 .dot::before {
        opacity: 1;
        transform: scale(1);
    }

    .wrapper .option span {
        font-size: 20px;
        color: #808080;
    }

    #option-1:checked:checked~.option-1 span,
    #option-2:checked:checked~.option-2 span {
        color: #fff;
    }
</style>
@endpush
