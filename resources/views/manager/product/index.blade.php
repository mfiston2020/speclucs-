@extends('manager.includes.app')

@section('title','Admin Dashboard - Product')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css"
    rel="stylesheet" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
@section('current','Products')
@section('page_name','Product List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <!-- Sales chart -->
    <div class="row">
        <span hidden>{{$product_sold =   0}}</span>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">All Products</h4><hr>
                        <a href="{{route('manager.product.create')}}" class="ml-2 btn waves-effect waves-light btn-rounded btn-outline-primary" style="align-items: right;">
                            <i class="fa fa-plus"></i> New Product
                        </a>
                            <a href="{{route('manager.lens.stock',0)}}" class="ml-2 btn waves-effect waves-light btn-rounded btn-outline-warning" style="align-items: right;">
                                <i class="fa fa-inbox"></i> Lens Stock
                            </a>
                            <a onclick="exportAll('xls');" href="#" class="ml-2 btn waves-effect waves-light btn-rounded btn-outline-success" style="align-items: right;">
                                <i class="fa fa-download"></i> Export To Excel
                            </a>
                            <a href="{{ route('manager.product.import') }}" class="ml-2 btn waves-effect waves-light btn-rounded btn-outline-primary" style="align-items: right;">
                                <i class="fa fa-upload"></i> Import Excel
                            </a>
                    </div> <hr>
                    {{-- ============================== --}}
                    @include('manager.includes.layouts.message')
                    {{-- =============================== --}}

                    <div class="table-responsive">
                        <table id="" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Product</th>
                                    <th>Description</th>
                                    <th>Power</th>
                                    <th>Price</th>
                                    <th>cost</th>
                                    <th>Stock</th>
                                    <th>Fitting Cost</th>
                                    <th>Total Sold</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                <span hidden>{{$quantity = \App\Models\SoldProduct::where(['product_id'=>$product->id])->where('company_id',Auth::user()->company_id)->select('quantity')->get()}}</span>
                                @foreach ($quantity as $item)
                                    <span hidden>{{$product_sold   =   $product_sold + $item->quantity}}</span>
                                @endforeach
                                <tr>
                                    <td>{{\App\Models\Category::where(['id'=>$product->category_id])->pluck('name')->first()}}</td>
                                    <td>{{$product->product_name}}</td>
                                    <span hidden>{{$power=\App\Models\Power::where(['product_id'=>$product->id])->where('company_id',Auth::user()->company_id)->select('*')->first()}}</span>

                                    <td>{{$product->description}}</td>
                                    <td>
                                        @if ($power)
                                            @if (initials($product->product_name)=='SV')
                                                <span>{{$power->sphere}} / {{$power->cylinder}}</span>
                                            @else
                                                <span>{{$power->sphere}} / {{$power->cylinder}} *{{$power->axis}}  {{$power->add}}</span>
                                            @endif
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>
                                    <td>{{format_money($product->price)}}</td>
                                    <td>{{format_money($product->cost)}}</td>

                                    <td>
                                        <a href="" class="update" data-name="stock" data-type="text"
                                        data-pk="{{ $product->id }}"
                                        data-title="Enter Product Name">{{$product->stock}}</a>

                                        </td>
                                    {{-- <td>{{$product->deffective_stock}}</td> --}}
                                    <td>{{$product->fitting_cost}}</td>
                                    <td>
                                        {{$product_sold}}
                                    </td>
                                </tr>
                                <span hidden>{{$product_sold =   0}}</span>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Category</th>
                                    <th>Product</th>
                                    <th>Description</th>
                                    <th>Power</th>
                                    <th>Price</th>
                                    <th>cost</th>
                                    <th>Stock</th>
                                    <th>Faulty</th>
                                    <th>Total Sold</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js')}}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js')}}"></script>

    <script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js')}}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <script src="{{ asset('dashboard/assets/dist/js/export.js')}}"></script>

    <script>
        function exportAll(type) {

        $('#zero_config').tableExport({
            filename: 'table_%DD%-%MM%-%YY%-month(%MM%)',
            format: type
        });
}
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{csrf_token()}}'
        }
    });

    $('.update').editable({
        validate: function (value) {
            if ($.trim(value) == '')
                return 'Value is required.';
        },
        mode: 'inline',
        url: '{{url("/manager/updateStock")}}',
        title: 'Update',
        success: function (response, newValue) {
            console.log('Updated', response)
        }
    });
    </script>
@endpush
