@extends('manager.includes.app')

@section('title','Manager Dashboard - Expenses')

@push('css')
{{-- <link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
--}}
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Products')
@section('page_name','Products\'s List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card card-body printableArea">
                    <h3><b>PURCHASE ORDER</b></h3>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                <address>
                                    <span
                                        hidden>{{$company=\App\Models\CompanyInformation::where(['id'=>Auth::user()->company_id])->select('*')->first()}}</span>
                                    <h3> &nbsp;<b class="text-danger">{{$company->company_name}}</b></h3>
                                    <p class="text-muted m-l-5">{{$company->company_tin_number}}
                                        <br /> {{$company->company_street}}
                                        <br /> {{$company->company_phone}}
                                        <br /> {{$company->company_email}}</p>
                                </address>
                            </div>
                            <span hidden>{{$count=1}}</span>
                            <div class="pull-right text-right">
                                <address>
                                    <p class="m-t-30"><b>Name :</b> {{$supplier->name}}</p>
                                    <p class="m-t-30"><b>Phone :</b> {{$supplier->phone}}</p>
                                    <p class="m-t-30"><b>TIN Number :</b> {{$supplier->tin_number}}</p>
                                    <p><b>Due Date :</b> <i class="fa fa-calendar"></i>
                                        {{date('Y-m-d H:m:s')}}</p>
                                </address>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive m-t-40" style="clear: both;">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-left">#</th>
                                            <th>Product Name</th>
                                            <th class="text-right">Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products_array as $key=> $product)
                                        <span hidden>{{$prod=\App\Models\Product::where(['id'=>$product])->where('company_id',Auth::user()->company_id)->select('*')->first()}}</span>
                                                        
                                        <span hidden>{{$total_sales=\App\Models\SoldProduct::where('product_id',$product)->select('*')->sum('quantity')}}</span>
                                        <span hidden>{{$hello  =   $total_sales+$total_sales-$prod->stock+$total_sales}}</span>
                                            @if ($hello>0)
                                                <tr>
                                                    <td>{{$count}}</td>
                                                    <span
                                                        hidden>{{$prod=\App\Models\Product::where(['id'=>$product])->where('company_id',Auth::user()->company_id)->select('*')->first()}}</span>
                                                    <span
                                                        hidden>{{$power=\App\Models\Power::where(['product_id'=>$product])->where('company_id',Auth::user()->company_id)->select('*')->first()}}</span>
                                                    <td>
                                                        <h6>{{$prod->product_name}}</h6>
                                                        @if ($power)
                                                        @if (initials($prod->product_name)=='SV')
                                                        <span>{{$prod->description }} - {{$power->sphere}} /
                                                            {{$power->cylinder}}</span>
                                                        @else
                                                        <span>{{$prod->description }} -{{$power->sphere}} /
                                                            {{$power->cylinder}}
                                                            *{{$power->axis}} {{$power->add}}</span>
                                                        @endif
                                                        @endif
                                                    </td>
                                                    <td class="text-right">
                                                        {{$total_sales+$total_sales-$prod->stock+$total_sales}}
                                                    </td>
                                                </tr>
                                                <span hidden>{{$count=$count+1}}</span>
                                            @endif
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <form action="{{route('manager.supplier.list')}}" method="get">
                    <div class="card-body">

            <div class="form-group m-b-0 text-center">
                <button type="submit" class="btn btn-info waves-effect waves-light">Print Purchase Order</button>
                <a href="{{url()->previous()}}" type="reset" class="btn btn-dark waves-effect waves-light">Cancel</a>
            </div>
        </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('dashboard/assets/dist/js/export.js')}}"></script>

<script>
    function exportAll(type) {

        $('#zero_config').tableExport({
            filename: 'table_%DD%-%MM%-%YY%-month(%MM%)',
            format: type
        });
    }
</script>
@endpush
