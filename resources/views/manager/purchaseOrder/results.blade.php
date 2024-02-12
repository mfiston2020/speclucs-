@extends('manager.includes.app')

@section('title','Manager Dashboard - Purchase order')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Products')
@section('page_name','Products\'s List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <form action="{{route('manager.quotation')}}" class="col-12" method="POST">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Products: <strong>{{count($products_array)}}</strong></h4>
                            <hr>
                            <a onclick="exportAll('xls');" href="#" type="button"
                                class="ml-2 btn waves-effect waves-light btn-rounded btn-outline-success"
                                style="align-items: right;">
                                <i class="fa fa-download"></i> Export To Excel</a>
                            <hr>
                        </div>
                        <div class="table-responsive m-t-40">
                            {{-- ====== input error message ========== --}}
                            @include('manager.includes.layouts.message')
                            {{-- ====================================== --}}
                            <table class="table stylish-table" id="zero_config">
                                <thead>
                                    <tr>
                                        <th>Product No</th>
                                        <th>Product</th>
                                        <th>Current Stock</th>
                                        <th>Sales (Usage)</th>
                                        <th>Addition</th>
                                        <th>Min. Stock</th>
                                        <th>Forecast Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products_array as $key=> $product)
                                    <tr>
                                        <td style="width:50px;"><span>{{sprintf('%04d',$product)}}</span></td>
                                        <span hidden>{{$prod=\App\Models\Product::where('id',$product)->select('*')->first()}}</span>
                                        <span hidden>{{$power=\App\Models\Power::where(['product_id'=>$product])->select('*')->first()}}</span>
                                        <?php $prod=\App\Models\Product::where('id',$product)->select('*')->first(); ?>
                                        <span
                                            hidden>{{$power=\App\Models\Power::where(['product_id'=>$product])->select('*')->first()}}</span>
                                        <td>
                                            <h6>{{$prod->product_name}} {{initials($prod->product_name)=='SV'?'':$prod->power->eye}}</h6>
                                            <small class="text-muted">
                                                @if ($power)
                                                @if (initials($prod->product_name)=='SV')
                                                <span>{{$prod->description }} / {{$power->sphere}} /
                                                    {{$power->cylinder}}</span>
                                                @else
                                                <span>{{$prod->description }} / {{$power->sphere}} / {{$power->cylinder}}
                                                    *{{$power->axis}} {{$power->add}}</span>
                                                @endif
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            {{$prod->stock}}
                                        </td>
                                        <td>
                                            {{$total_sales=\App\Models\SoldProduct::where('product_id',$product)->select('*')->sum('quantity')}}
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="addition{{$key}}" onkeyup="testing({{$key}})" name="addition[]">
                                        </td>
                                        <td>{{$total_sales}}</td>
                                        <td>
                                            <span id="number{{$key}}">{{$total_sales+$total_sales-$prod->stock+$total_sales}}</span>

                                            <input type="hidden" value="{{$product}}" name="product_id[]">
                                            {{-- <input type="hidden" value="{{$" name="supplier_id"> --}}
                                            <input type="hidden" value="{{$prod->stock}}" name="stock[]">
                                            <input type="hidden" value="{{$total_sales}}" name="modal_stock[]">
                                            <input type="hidden" value="{{$total_sales+$total_sales-$prod->stock+$total_sales}}" id="forecast{{$key}}" name="forecast[]">
                                            <input type="hidden" value="{{$total_sales+$total_sales-$prod->stock+$total_sales}}" id="forecast2{{$key}}">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    {{-- <form action="{{route('manager.supplier.list')}}" method="get"> --}}
                        {{-- <div class="card-body">
                            <div class="form-group row">
                                <label for="pname" class="col-sm-3 text-right control-label col-form-label">Select supplier</label>
                                <div class="col-sm-9">
                                    <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                        name="supplier" id="supplier" required>
                                        <option value="">Select</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{$supplier->id}}" {{(old('supplier')==$supplier->id)?'selected':''}}>
                                                {{\App\Models\CompanyInformation::where('id',$supplier->company_id)->pluck('company_name')->first()}}
                                            </option>
                                        @endforeach

                                    </select>
                                        <input type="hidden" value="{{$category}}" name="category">
                                        <input type="hidden" value="{{$from}}" name="from">
                                        <input type="hidden" value="{{$to}}" name="to">
                                </div>
                            </div>

                            <div class="form-group m-b-0 text-center">
                                <button type="submit" class="btn btn-info waves-effect waves-light">Request for Quotation</button>
                                {{-- <a href="#" type="reset" class="btn btn-success waves-effect waves-light">Save Purchase Order</a> --}
                                <a href="{{url()->previous()}}" type="reset" class="btn btn-dark waves-effect waves-light">Cancel</a>
                            </div>
                        </div> --}}
                    {{-- </form> --}}
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js')}}"></script>
<script src="{{ asset('dashboard/assets/dist/js/export.js')}}"></script>

<script>
    function exportAll(type) {

        $('#zero_config').tableExport({
            filename: 'table_%DD%-%MM%-%YY%-month(%MM%)',
            format: type
        });
    }
    function testing(key)
    {
        var forecast    =   Number($('#forecast'+key).val());
        var additon     =   Number($('#addition'+key).val());
        var add         =   forecast+additon;
        $('#number'+key).text(add);
        $('#addition'+key).text(add);
        $('#forecast2'+key).val(add);
    }
</script>
@endpush
