@extends('manager.includes.app')

@section('title','Seller - Add Product')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/select2/dist/css/select2.min.css')}}">
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
                    <h4 class="card-title">Product Information</h4>

                </div>
                <hr>

                <form class="form-horizontal" action="{{route('manager.product.save')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        {{-- ====== input error message ========== --}}
                        @include('manager.includes.layouts.message')
                        {{-- ====================================== --}}
                        <div class="form-group row">
                            <label for="pname" class="col-sm-3 text-right control-label col-form-label">Product
                                Category</label>
                            <div class="col-sm-9">
                                <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                    name="category" id="category">
                                    <option value="">Select</option>
                                    @foreach ($categories as $category)
                                    <option value="{{$category->id}}">
                                        {{$category->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="lens">
                            <div class="form-body">
                                <div class="card-body">
                                    <!--/row-->
                                    <h4 class="card-title m-t-40">Lens Information</h4>
                                </div>
                                <hr>
                                <div class="card-body">
                                    <!--/row-->
                                    <div class="row">
                                        <!--/span-->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Lens Type</label>
                                                <select class="form-control custom-select" name="lens_type" id="type">
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
                                                <select class="form-control custom-select" name="index">
                                                    <option value="">-- Select --</option>
                                                    @foreach ($index as $index)
                                                        <option value="{{$index->id}}"
                                                            {{(old('index')==$index->id)?'selected':''}}>
                                                            {{$index->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Chromatics Aspects</label>
                                                <select class="form-control custom-select" name="chromatics">
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
                                                <select class="form-control custom-select" name="coating">
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
                                                <input type="text" class="form-control" name="sphere" value="{{ old('sphere')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>CYLINDER</label>
                                                <input type="text" class="form-control" name="cylinder" value="{{ old('cylinder')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group" id="axis">
                                                <label>AXIS</label>
                                                <input type="text" class="form-control" name="axis" value="{{ old('axis')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group" id="add">
                                                <label>ADD</label>
                                                <input type="text" class="form-control" name="add" value="{{ old('add')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="eyes">
                                            <div class="form-group">
                                                <label class="control-label">Eye</label>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="customRadio11" name="eye" value="left" class="custom-control-input">
                                                    <label class="custom-control-label" for="customRadio11">Left</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="customRadio22" name="eye" value="right" class="custom-control-input">
                                                    <label class="custom-control-label" for="customRadio22">Right</label>
                                                </div>
                                                {{-- <div class="custom-control custom-radio">
                                                    <input type="radio" id="customRadio33" name="eye" value="both" class="custom-control-input">
                                                    <label class="custom-control-label" for="customRadio33">Both</label>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Stock</label>
                                                <input type="text" class="form-control" name="lens_stock" value="{{old('lens_stock')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Cost</label>
                                                <input type="text" class="form-control" name="lens_cost" value="{{old('lens_cost')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Price</label>
                                                <input type="text" class="form-control" name="lens_price" value="{{old('lens_price')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="non-lens">
                            <div class="form-group row">
                                <label for="pname" class="col-sm-3 text-right control-label col-form-label">Product
                                    Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="pname" placeholder="Product Name Here"
                                        name="product_name" value="{{old('product_name')}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pname" class="col-sm-3 text-right control-label col-form-label">Product
                                    Description</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="pdescription"
                                        placeholder="Product description Here" name="product_description"
                                        value="{{old('product_description')}}">
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="stock" class="col-sm-3 text-right control-label col-form-label">Stock</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="stock" placeholder="Stock Here" name="stock"
                                        value="{{old('stock')}}">
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="fstock" class="col-sm-3 text-right control-label col-form-label">Defective
                                    Stock</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="fstock" placeholder="Folty Stock Here"
                                        name="defective_stock" value="{{old('defective_stock')}}">
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="cost"
                                    class="col-sm-3 text-right control-label col-form-label invalid">Cost</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="cost" placeholder="cost Here" name="cost"
                                        value="{{old('cost')}}">
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="price"
                                    class="col-sm-3 text-right control-label col-form-label invalid">Price</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="price" placeholder="Price Here" name="price"
                                        value="{{old('price')}}">
                                </div>
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
<script>
    $('#non-lens').hide();
    $('#lens').hide();
    $('#category').on('change',function(){
        if ($(this).val()=='1') {
            $('#non-lens').hide();
            $('#lens').show();
        } 
        else if($(this).val()=='')
        {
            $('#non-lens').hide();
            $('#lens').hide();
        }
        else {
            $('#lens').hide();
            $('#non-lens').show();
        }
    });

    // $('#type').on('change',function(){
    //     if ($(this).val()=='2') {
    //         $('#add').hide();
    //         $('#axis').hide();
    //         $('#eyes').hide();
    //     }
    //     else{
    //         $('#add').show();
    //         $('#axis').show();
    //         $('#eyes').show();
    //     }
    });
</script>
@endpush
