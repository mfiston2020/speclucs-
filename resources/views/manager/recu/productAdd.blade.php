@extends('manager.includes.app')

@section('title', 'Manager Dashboard - Add Product')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/dist/css/style.css') }}">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name', 'New Receipt Product')
@section('current', 'Add Receipt Product')
{{-- === End of breadcumb == --}}

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <h4 class="m-b-0 text-white">Product Type</h4>
                    </div>
                    <form class="form-horizontal" action="#" method="POST">
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
                                            <span hidden>{{ $initial = initials($category->name) }}</span>
                                            @if ($initial == 'LL')
                                                <option value="{{ $category->id }}">
                                                    {{ $category->name }}
                                                </option>
                                            @else
                                            @endif
                                        @endforeach
                                        <option value="others" {{ old('category') == 'others' ? 'selected' : '' }}>
                                            Others
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                {{-- second card for selecting lens characteristics --}}
                <div class="card" id="lens">
                    <div class="card-header bg-info">
                        <h4 class="m-b-0 text-white">Lens Information</h4>
                    </div>
                    <form class="form-horizontal" onsubmit="searchProduct(); return false;" id="searchForm">
                        @csrf
                        <div class="form-body">
                            <br>
                            <div class="card-body">
                                <div class="alert alert-warning alert-rounded col-7" id="warning">
                                    <b>Warning! </b>No Results Found!!
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span
                                            aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <!--/row-->
                                <div class="row">
                                    <!--/span-->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Lens Type</label>
                                            <select class="form-control custom-select" name="lens_type" id="lens_type"
                                                required>
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
                                            <select class="form-control custom-select" name="index" id="index"
                                                required>
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
                                            <select class="form-control custom-select" name="chromatics" id="chromatics"
                                                required>
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
                                            <select class="form-control custom-select" name="coating" id="coating"
                                                required>
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
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>SPHERE</label>
                                            <input type="text" class="form-control" name="sphere"
                                                value="{{ old('sphere') }}" id="sphere" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>CYLINDER</label>
                                            <input type="text" class="form-control" name="cylinder"
                                                value="{{ old('cylinder') }}" id="cylinder" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group" id="axiss">
                                            <label>AXIS</label>
                                            <input type="text" class="form-control" name="axis"
                                                value="{{ old('axis') }}" id="axis">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group" id="adds">
                                            <label>ADD</label>
                                            <input type="text" class="form-control" name="add"
                                                value="{{ old('add') }}" id="add">
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="eyes">
                                        <div class="form-group row">
                                            <div class="radiobtn col-4">
                                                <input type="radio" id="Left" name="eye" value="left" />
                                                <label for="Left">Left Eye</label>
                                            </div>

                                            <div class="radiobtn col-4">
                                                <input type="radio" id="Right" name="eye" value="right" />
                                                <label for="Right">Right Eye</label>
                                            </div>

                                            {{-- <div class="radiobtn col-4">
                                            <input type="radio" id="Both" name="eye" value="both" checked />
                                            <label for="Both">Both Eyes</label>
                                        </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                    </form>
                    {{-- ============================================================ --}}
                    <div id="search_results" class="modal fade" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Modal Content is Responsive</h4>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">×</button>
                                </div>
                                <form method="POST" action="{{ route('manager.receipt.save.product') }}"
                                    method="POST">
                                    <div class="modal-body">
                                        @csrf
                                        <input type="hidden" value="{{ $id }}" name="receipt_id">
                                        <input type="hidden" value="{{ $id }}" name="product"
                                            id="product_id">
                                        <div class="form-group row">
                                            <label for="stock"
                                                class="col-sm-4 text-right control-label col-form-label">Cost</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="cost" class="form-control"
                                                    placeholder="0" name="cost" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="fstock"
                                                class="col-sm-4 text-right control-label col-form-label">Quantity
                                                <span id="left" style="color: red"></span>
                                            </label>
                                            <div class="col-sm-8">
                                                <input type="number" class="form-control" id="quantity"
                                                    placeholder="stock" name="stock" value="{{ old('stock') }}"
                                                    min="1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-warning waves-effect"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-default waves-effect waves-light">Add
                                            Product</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- ============================================================ --}}
                    <div id="prompt" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle"></i>
                                        Warning</h4>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                    <h4>Product not found! would like to add it as a new Product??</h4>

                                </div>
                                <div class="modal-footer">
                                    <a href="#" onclick="product()" class="btn btn-info waves-effect">Yes</a>
                                    <button type="button" class="btn btn-danger waves-effect"
                                        data-dismiss="modal">No</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    {{-- ======================================================================= --}}
                    <div class="card-body">
                        <div class="form-group m-b-0 text-center">
                            <img src="{{ asset('dashboard/assets/images/loading.gif') }}" alt="" height="50px"
                                width="50px" id="loading">
                            <button type="submit" class="btn btn-info waves-effect waves-light text-white"
                                form="searchForm">Search</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- second card for selecting lens characteristics --}}
            <div class="card" id="non-lens">
                <div class="card-header bg-info">
                    <h4 class="m-b-0 text-white">Product Information</h4>
                </div>
                <br>
                <form class="form-horizontal" action="{{ route('manager.receipt.save.product') }}" method="POST">
                    @csrf
                    <div class="card-body">

                        <input type="hidden" name="receipt_id" value="{{ $id }}">
                        <div class="form-group row">
                            <label for="pname" class="col-sm-3 text-right control-label col-form-label">Product</label>
                            <div class="col-sm-9">
                                <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                    name="product" id="product" required>
                                    <option value="">Select</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ old('product') == $product->id ? 'selected' : '' }}>
                                            {{ $product->product_name }} - {{ $product->description }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="stock" class="col-sm-3 text-right control-label col-form-label">Stock</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="stock Here" name="stock"
                                    value="{{ old('stock') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cost" class="col-sm-3 text-right control-label col-form-label">Cost</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="cost" id="cost2"
                                    value="{{ old('cost') }}">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="form-group m-b-0 text-right">
                            <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                            <a href="{{ url()->previous() }}" class="btn btn-dark waves-effect waves-light">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
            {{-- second card for selecting lens characteristics --}}
            <div class="card" id="productPrompt">
                <div class="card-header bg-info">
                    <h4 class="m-b-0 text-white">Product Information</h4>
                </div>
                <br>
                <form class="form-horizontal" action="{{ route('manager.receipt.new.product') }}" method="POST">
                    @csrf
                    <div class="card-body">

                        {{-- <input type="hidden" name="receipt_id" value="{{$id}}">
                    <div class="form-group row">
                        <label for="pname" class="col-sm-3 text-right control-label col-form-label">Product</label>
                        <div class="col-sm-9">
                            <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                name="product" id="product" required>
                                <option value="">Select</option>
                                @foreach ($products as $product)
                                <option value="{{$product->id}}" {{(old('product')==$product->id)?'selected':''}}>
                                    {{$product->product_name}} - {{$product->description}}
                                </option>
                                @endforeach

                            </select>
                        </div>
                    </div> --}}

                        <input type="hidden" name="receipt_id" value="{{ $id }}">
                        <input type="hidden" name="category" id="category_">

                        <input type="hidden" name="lens_type" id="lens_type_">
                        <input type="hidden" name="index" id="index_">
                        <input type="hidden" name="chromatics" id="chromatics_">
                        <input type="hidden" name="coating" id="coating_">

                        <input type="hidden" name="sphere" id="sphere_">
                        <input type="hidden" name="cylinder" id="cylinder_">
                        <input type="hidden" name="axis" id="axis_">
                        <input type="hidden" name="add" id="add_">
                        <input type="hidden" name="eye" id="eye_">

                        <div class="form-group row">
                            <label for="stock" class="col-sm-3 text-right control-label col-form-label">Stock</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="lens_stock"
                                    value="{{ old('lens_stock') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 text-right control-label col-form-label">price</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="lens_price"
                                    value="{{ old('lens_price') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cost" class="col-sm-3 text-right control-label col-form-label">Cost</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="lens_cost"
                                    value="{{ old('lens_cost') }}" required>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="form-group m-b-0 text-center">
                            <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                            <a href="{{ url()->previous() }}" class="btn btn-dark waves-effect waves-light">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('dashboard/assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/libs/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/forms/select2/select2.init.js') }}"></script>

    <script>
        $('#warning').hide();
        $('#loading').hide();
        $('#non-lens').hide();
        $('#productPrompt').hide();
        $('#lens').hide();

        function product() {
            // $('#lens').hide();
            $('#productPrompt').show();
            $('#prompt').modal('hide');

            var category = $('#category').val();

            var eye = '';
            var lens_type = $('#lens_type').val();
            var index = $('#index').val();
            var chromatics = $('#chromatics').val();
            var coating = $('#coating').val();
            var sphere = $('#sphere').val();
            var cylinder = $('#cylinder').val();
            var axis = $('#axis').val();
            var add = $('#add').val();
            var eye_array = document.getElementsByName('eye');

            for (i = 0; i < eye_array.length; i++) {
                if (eye_array[i].checked) {
                    eye = eye + eye_array[i].value;
                }
            }

            $("#category_").val(category);

            $("#lens_type_").val(lens_type);
            $("#index_").val(index);
            $("#chromatics_").val(chromatics);
            $("#coating_").val(coating);

            $("#sphere_").val(sphere);
            $("#cylinder_").val(cylinder);
            $("#axis_").val(axis);
            $("#add_").val(add);
            $("#eye_").val(eye);
        }

        $('#category').on('change', function() {
            if ($(this).val() == '1') {
                $('#non-lens').hide();
                $('#lens').show();
            } else if ($(this).val() == '') {
                $('#non-lens').hide();
                $('#lens').hide();
            } else {
                $('#lens').hide();
                $('#non-lens').show();
                $('#warning').hide();
            }
        });

        // ====================================
        function searchProduct() {
            const element = document.getElementById('searchForm');
            element.addEventListener('submit', event => {
                event.preventDefault();

                $('#loading').show();

                var eye = '';
                var lens_type = $('#lens_type').val();
                var index = $('#index').val();
                var chromatics = $('#chromatics').val();
                var coating = $('#coating').val();
                var sphere = $('#sphere').val();
                var cylinder = $('#cylinder').val();
                var axis = $('#axis').val();
                var add = $('#add').val();
                var eye_array = document.getElementsByName('eye');

                for (i = 0; i < eye_array.length; i++) {
                    if (eye_array[i].checked) {
                        eye = eye + eye_array[i].value;
                    }
                }

                $.ajax({
                    url: "{{ route('manager.fetchProductData') }}",
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",

                        lens_types: lens_type,
                        index: index,
                        chromatics: chromatics,
                        coating: coating,
                        sphere: sphere,
                        cylinder: cylinder,
                        axis: axis,
                        add: add,
                        eye: eye,
                    },

                    success: function(data) {
                        if (!data.length) {
                            $('#loading').hide();
                            $('#prompt').modal('show');
                        } else {
                            $('#loading').hide();
                            $('#warning').hide();

                            $("#unit_price").val(data[0].price);
                            $("#left").html('| ' + data[0].stock + ' left');
                            $('#product_id').val(data[0].id);

                            $('#search_results').modal('show');

                            // console.log(data);
                        }

                    },
                    error: function(data) {
                        console.log(data.length);
                    }
                });

            });
        }
        // ====================================

        $('#product').on('change', function() {
            var id = ($(this).val());

            $.ajax({
                url: "{{ route('manager.fetchProduct') }}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                },

                success: function(data) {
                    $("#unit_price2").val(data.price);
                    $("#left2").html('| ' + data.stock + ' left');
                    // console.log(data.price);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });


        $('#lens_type').on('change', function() {
            if ($(this).val() == '2') {
                $('#adds').hide();
                $('#axiss').hide();
                $('#eyes').hide();
            } else {
                $('#adds').show();
                $('#axiss').show();
                $('#eyes').show();
            }
        });

        //
        $('#product').on('change', function() {
            var id = ($(this).val());

            $.ajax({
                url: "{{ route('manager.fetchProduct') }}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                },

                success: function(data) {
                    $("#cost2").val(data.cost);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });
    </script>
@endpush
