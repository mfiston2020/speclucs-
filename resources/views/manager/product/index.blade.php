@extends('manager.includes.app')

@section('title', 'Dashboard - Product')

@push('css')
    <link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link href="{{asset('css/editable.min.css')}}"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{asset('css/datatable.min')}}">

    <style>
        .glyphicon-ok:before {
            content: "\f00c";
        }

        .glyphicon-remove:before {
            content: "\f00d";
        }

        .glyphicon {
            display: inline-block;
            font: normal normal normal 14px/1 FontAwesome;
            font-size: inherit;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current', 'Products')
@section('page_name', 'Product List')
{{-- === End of breadcumb == --}}

@section('content')

    <div class="container-fluid">
        <!-- Sales chart -->
        <div class="row">
            <span hidden>{{ $product_sold = 0 }}</span>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h4 class="card-title">All Products</h4>
                            <hr>
                                @if (userInfo()->permissions == 'manager' || userInfo()->permissions == 'store')
                                    <a href="{{ route('manager.product.create') }}"
                                        class="ml-2 btn waves-effect waves-light btn-rounded btn-outline-primary"
                                        style="align-items: right;">
                                        <i class="fa fa-plus"></i> New Product
                                    </a>
                                    <a onclick="exportAll('xls');" href="#"
                                        class="ml-2 btn waves-effect waves-light btn-rounded btn-outline-success"
                                        style="align-items: right;">
                                        <i class="fa fa-download"></i> Export To Excel
                                    </a>
                                    <a href="{{ route('manager.product.import') }}"
                                        class="ml-2 btn waves-effect waves-light btn-rounded btn-outline-primary"
                                        style="align-items: right;">
                                        <i class="fa fa-upload"></i> Import Excel
                                    </a> {{-- --}}

                            @endif
                                <a href="{{ route('manager.lens.stock', 0) }}"
                                    class="ml-2 btn waves-effect waves-light btn-rounded btn-outline-warning"
                                    style="align-items: right;">
                                    <i class="fa fa-inbox"></i> Lens Stock
                                </a>

                        </div>
                        <hr>
                        {{-- ============================== --}}
                        @include('manager.includes.layouts.message')
                        {{-- =============================== --}}

                        <div class="table-responsive">
                            {{ $products->links() }}
                            <table id="zero_config" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category</th>
                                        <th>Product</th>
                                        <th>Description</th>
                                        <th>Power</th>
                                        <th>Price [RWF]</th>
                                        <th>Wholesale Price [RWF]</th>
                                        <th>cost</th>
                                        <th>Stock</th>
                                        <th>Location</th>
                                        <th>Supplier</th>
                                        {{-- <th>Total Sold</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $key => $product)
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>{{ $product->category->name }}
                                            </td>
                                            <td>{{ $product->product_name }}</td>

                                            <td>
                                                @if (initials($product->product_name) == 'SV' || initials($product->product_name) == 'BT')
                                                    {{ lensDescription($product->description) }}
                                                @else
                                                    {{ lensDescription($product->description) }} {{$product->power?'- '.$product->power->eye:''}}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($product->power)
                                                    @if (initials($product->product_name) == 'SV')
                                                        <span>{{ $product->power->sphere }} / {{ $product->power->cylinder }}</span>
                                                    @else
                                                        <span>{{ $product->power->sphere }} / {{ $product->power->cylinder }}
                                                            *{{ $product->power->axis }} {{ $product->power->add }}</span>
                                                    @endif
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </td>
                                            @if (userInfo()->permissions == 'manager' || userInfo()->permissions == 'store')
                                                <td>
                                                    <a href="#!" class="update" data-name="price" data-type="text" data-pk="{{ $product->id }}" data-title="Enter Product Name">{{ $product->price }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="#!" class="update" data-name="wholesaleprice" data-type="text" data-pk="{{ $product->id }}" data-title="Enter Product Name">{{ $product->wholesale_price }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="#!" class="update" data-name="cost" data-type="text" data-pk="{{ $product->id }}" data-title="Enter Product Name">{{ $product->cost }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="#!" class="update" data-name="stock" data-type="text"
                                                        data-pk="{{ $product->id }}"
                                                        data-title="Enter Product Name">{{ $product->stock }}
                                                    </a>
                                                </td>
                                            @else
                                                <td>
                                                    <span class="text-primary">{{ $product->price }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-primary">{{ $product->cost }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-primary">{{ $product->stock }}
                                                    </span>
                                                </td>
                                            @endif
                                            {{-- <td>{{$product->deffective_stock}}</td> --}}
                                            <td>
                                                <center>
                                                    {{ $product->location == null ? '-' : $product->location }}
                                                </center>
                                            </td>
                                            <td>
                                                <center>
                                                    {{ $product->supplier == null? '-': $product->supplier->name }}
                                                </center>
                                            </td>
                                            {{-- <td>
                                                {{ number_format($product_sold) }}
                                            </td> --}}
                                        </tr>
                                        <span hidden>{{ $product_sold = 0 }}</span>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Category</th>
                                        <th>Product</th>
                                        <th>Description</th>
                                        <th>Power</th>
                                        <th>Price</th>
                                        <th>cost</th>
                                        <th>Stock</th>
                                        <th>Location</th>
                                        <th>Supplier</th>
                                        {{-- <th>Total Sold</th> --}}
                                    </tr>
                                </tfoot>
                            </table>
                        {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>

    <script src="{{asset('dashboard/assets/dist/js/editable.min.js')}}">
    </script>
    <script src="{{ asset('dashboard/assets/dist/js/export.js') }}"></script>

    <script>
        function exportAll(type) {

            $('#zero_config').tableExport({
                filename: 'table_%DD%-%MM%-%YY%-month(%MM%)',
                format: type
            });
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        $('.update').editable({
            validate: function(value) {
                if ($.trim(value) == '')
                    return 'Value is required.';
            },
            mode: 'inline',
            url: '{{ url('/manager/updateStock') }}',
            title: 'Update',
            success: function(response, newValue) {
                console.log('Updated', response)
            }
        });

        $('.updateCost').editable({
            validate: function(value) {
                if ($.trim(value) == '')
                    return 'Value is required.';
            },
            mode: 'inline',
            url: '{{ url('/manager/updateCost') }}',
            title: 'Update',
            success: function(response, newValue) {
                console.log('Updated', response)
            }
        });
    </script>
@endpush
