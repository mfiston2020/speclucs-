@extends('manager.includes.app')

@section('title','Manager Dashboard - Quotation Detail')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Quotation Detail')
@section('page_name','Quotation Detail')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
                <form action="{{route('manager.quation.order')}}" class="col-12" method="POST">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h4 class="card-title">All Quotation Products</h4><hr>
                            {{-- <a href="{{route('manager.supplier.create')}}" type="button" class="btn waves-effect waves-light btn-rounded btn-outline-primary" style="align-items: right;">
                                <i class="fa fa-plus"></i> New Supplier</a> --}}
                        </div>
                            <hr>
                            {{-- ================================= --}}
                            @include('manager.includes.layouts.message')
                            {{-- ========================== --}}

                        <div class="table-responsive">
                            <table id="zero_config" class="table table-striped table-bordered nowrap"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Supplier</th>
                                        <th>Quantity</th>
                                        <th>Cost</th>
                                        <th>Total cost</th>
                                        <th>Creation Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   @foreach ($products as $key=> $item)
                                       <tr>
                                           <td>{{$key+1}}</td>
                                        <td>
                                            <span
                                                hidden>{{$prod=\App\Models\Product::where(['id'=>$item->product_id])->where('company_id',Auth::user()->company_id)->select('*')->first()}}</span>
                                            <span
                                                hidden>{{$power=\App\Models\Power::where(['product_id'=>$item->product_id])->where('company_id',Auth::user()->company_id)->select('*')->first()}}</span>

                                            {{$item->product_name}}

                                            @if ($power)
                                                @if (initials($prod->product_name)=='SV')
                                                <span>{{$prod->description }} - {{$power->sphere}} /
                                                    {{$power->cylinder}}</span>
                                                @else
                                                <span>{{$prod->description }} -{{$power->sphere}} / {{$power->cylinder}}
                                                    *{{$power->axis}} {{$power->add}}</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{\App\Models\Supplier::where('id',$item->supplier_id)->pluck('name')->first()}}</td>
                                        <td>{{$quantity=$item->forecast+$item->addition}}</td>
                                        <td>{{$item->cost}}</td>
                                        <td>{{$item->cost*$quantity}}</td>
                                        <td>{{date('Y-m-d',strtotime($item->created_at))}}</td>
                                       </tr>

                                       <input type="hidden" name="product_id[]" value="{{$item->product_id}}">
                                       <input type="hidden" name="supplier_id[]" value="{{$item->supplier_id}}">
                                       <input type="hidden" name="po_number" value="{{$item->quotation_number}}">
                                       <input type="hidden" name="cost[]" value="{{$item->cost}}">
                                       <input type="hidden" name="quantity[]" value="{{$quantity}}">
                                   @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">

                        <div class="form-group m-b-0 text-center">
                            <button type="submit" class="btn btn-info waves-effect waves-light">Place Order</button>
                            <a href="{{url()->previous()}}" type="reset" class="btn btn-dark waves-effect waves-light">Go Back</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js')}}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js')}}"></script>
@endpush
