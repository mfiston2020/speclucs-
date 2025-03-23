@extends('manager.includes.app')

@section('title', 'Dashboard - Add Product')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/select2/dist/css/select2.min.css') }}">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name', 'New Product')
@section('current', 'Add Product')
{{-- === End of breadcumb == --}}

@section('content')
    <div class="container-fluid">
        <form class="form-horizontal" action="{{ route('manager.product.save') }}" method="POST">
            @csrf
            <div class="row">

                @include('manager.includes.layouts.message')

                {{-- =========== Product category start =============== --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Product & Supplier</h4>
                            <div class="form-group row">
                                {{-- <label for="pname" class="col-sm-3 text-left control-label col-form-label">Product
                                Category</label> --}}
                                <div class="col-md-6 col-sm-12">
                                    <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                        name="category" id="category" required>
                                        <option value="" selected>Select</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                        name="supplier" id="supplier">
                                        <option value="" selected>Select Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- =========== Product category End =============== --}}


                {{-- =========== Other Products division start =============== --}}
                <div class="col-md-12" id="non-lens">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Product Information</h4>
                            <div class="form-group row">
                                <label for="pname" class="col-sm-3 text-right control-label col-form-label">Product
                                    Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="pname"
                                        placeholder="Product Name Here" name="product_name"
                                        value="{{ old('product_name') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pname" class="col-sm-3 text-right control-label col-form-label">Product
                                    Description</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="pdescription"
                                        placeholder="Product description Here" name="product_description"
                                        value="{{ old('product_description') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="stock" class="col-sm-3 text-right control-label col-form-label">Stock</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="stock" placeholder="Stock Here"
                                        name="stock" value="{{ old('stock') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="fstock" class="col-sm-3 text-right control-label col-form-label">Defective
                                    Stock</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="fstock" placeholder="Folty Stock Here"
                                        name="defective_stock" value="{{ old('defective_stock') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="cost"
                                    class="col-sm-3 text-right control-label col-form-label invalid">Cost</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="cost" placeholder="cost Here"
                                        name="cost" value="{{ old('cost') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="price"
                                    class="col-sm-3 text-right control-label col-form-label invalid">Price</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="price" placeholder="Price Here"
                                        name="price" value="{{ old('price') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="price"
                                    class="col-sm-3 text-right control-label col-form-label invalid">Location</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="location" placeholder="Location Here"
                                        name="location" value="{{ old('location') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- =========== Other Products division End =============== --}}


                {{-- =========== Lens division start =============== --}}
                <div class="col-md-12" id="lens">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Lens Description</h4>
                            <div class="form-body">
                                <div class="row">
                                    <!--/span-->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Lens Type</label>
                                            <select class="form-control custom-select" name="lens_type" id="type">
                                                <option value="">-- Select --</option>
                                                @foreach ($lens_types as $lens_type)
                                                    <option value="{{ $lens_type->id }}"
                                                        {{ old('lens_type') == $lens_type->id ? 'selected' : '' }}>
                                                        {{ $lens_type->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Index</label>
                                            <select class="form-control custom-select" name="index" id="index">
                                                <option value="">-- Select --</option>
                                                @foreach ($index as $index)
                                                    <option value="{{ $index->id }}"
                                                        {{ old('index') == $index->id ? 'selected' : '' }}>
                                                        {{ $index->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Chromatics Aspects</label>
                                            <select class="form-control custom-select" name="chromatics" id="chrm">
                                                <option value="">-- Select --</option>
                                                @foreach ($chromatics as $chromatics)
                                                    <option value="{{ $chromatics->id }}"
                                                        {{ old('chromatics') == $chromatics->id ? 'selected' : '' }}>
                                                        {{ $chromatics->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Coating</label>
                                            <select class="form-control custom-select" name="coating" id="coating">
                                                <option value="">-- Select --</option>
                                                @foreach ($coatings as $coatings)
                                                    <option value="{{ $coatings->id }}"
                                                        {{ old('coating') == $coatings->id ? 'selected' : '' }}>
                                                        {{ $coatings->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- =========== Lens division End =============== --}}

                {{-- =========== Powe division for addition =============== --}}
                <div class="col-md-12" id="power">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Lens Power</h4>


                            <div id="education_fields" class=" m-t-20"></div>

                            <div class="row">
                                <div class="col-md-12" id="eyes">
                                    <div class="form-group row">



                                        <div class="col-12">
                                            <div class="form-group">
                                                <button class="btn btn-success" type="button"
                                                    onclick="education_fields();"><i class="fa fa-plus"></i> Add
                                                    Field</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- =========== Powe division for addition =============== --}}

                {{-- =========== Powe division for addition =============== --}}
                <div class="col-md-12" id="action_buttons">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group m-b-0 text-center">
                                <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                                <a href="{{ url()->previous() }}"
                                    class="btn btn-dark waves-effect waves-light">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- =========== Powe division for addition =============== --}}
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('dashboard/assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/libs/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/forms/select2/select2.init.js') }}"></script>
    <script src="{{ asset('dashboard/assets/extra-libs/jquery.repeater/dff.js') }}"></script>
    <script>
        $('#non-lens').hide();
        $('#lens').hide();
        $('#power').hide();
        $('#action_buttons').hide();

        $('#category').on('change', function() {
            if ($(this).val() == '1') {
                $('#non-lens').hide();
                $('#lens').show();
                $('#power').show();
                $('#action_buttons').show();

                $('#type').attr('required', 'required');
                $('#index').attr('required', 'required');
                $('#chrm').attr('required', 'required');
                $('#coating').attr('required', 'required');

                $('#sphere').attr('required', 'required');
                $('#cylinder').attr('required', 'required');

                $('#lens_stock').attr('required', 'required');
                $('#lens_cost').attr('required', 'required');
                $('#lens_price').attr('required', 'required');

            } else if ($(this).val() == '') {
                $('#non-lens').hide();
                $('#lens').hide();
                $('#power').hide();
                $('#action_buttons').hide();

                $('#type').removeAttr('required');
                $('#index').removeAttr('required');
                $('#chrm').removeAttr('required');
                $('#coating').removeAttr('required');

                $('#sphere').removeAttr('required');
                $('#cylinder').removeAttr('required');

                $('#lens_stock').removeAttr('required');
                $('#lens_cost').removeAttr('required');
                $('#lens_price').removeAttr('required');
            } else {
                $('#lens').hide();
                $('#power').hide();
                $('#non-lens').show();
                $('#action_buttons').show();

                $('#lens_stock').removeAttr('required');
                $('#lens_cost').removeAttr('required');
                $('#lens_price').removeAttr('required');

                $('#sphere').removeAttr('required');
                $('#cylinder').removeAttr('required');

                $('#add').removeAttr('required');
                $('#axis').removeAttr('required');
                // $('#eyes0').removeAttr('required');
                // $('#eyess').removeAttr('required');

                $('#type').removeAttr('required');
                $('#index').removeAttr('required');
                $('#chrm').removeAttr('required');
                $('#coating').removeAttr('required');
            }
        });

        $('#type').on('change', function() {
            if ($(this).val() == '2') {
                $('#sphere').attr('required', 'required');
                $('#cylinder').attr('required', 'required');

                $('#add').removeAttr('required');
                $('#axis').removeAttr('required');
                // $('#eyes0').removeAttr('required');
                // $('#eyess').removeAttr('required');


            } else {
                $('#add').attr('required', 'required');
                $('#axis').attr('required', 'required');
                // $('#eyes0').attr('required','required');
                // $('#eyess').attr('required','required');

                $('#lens_stock').attr('required', 'required');
                $('#lens_cost').attr('required', 'required');
                $('#lens_price').attr('required', 'required');

            }
        });
    </script>
@endpush
