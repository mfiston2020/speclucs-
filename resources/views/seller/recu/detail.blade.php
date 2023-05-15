@extends('seller.includes.app')

@section('title','Seller - Receipt Detail')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Receipt Detail')
@section('page_name','Receipt Detail')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Products: <strong>{{count($products)}}</strong></h4>
                        <span hidden> {{$total_cost = 0}}</span>
                        <span hidden> {{$gnereral_cost = 0}}</span>
                        <span hidden> {{$total_stock = 0}}</span>
                        <hr>
                        @if ($receiptDetail->status=='completed')
                            <a href="{{route('seller.payment.add')}}"
                                class="pull-right btn btn-outline-primary">Pay Receipt</a>
                        @elseif($receiptDetail->status=='paid')  
                            <a href="{{url()->previous()}}"
                                class="pull-right btn btn-outline-secondary">Back</a>        
                        @else
                            <a href="{{route('seller.receipt.add.product',Crypt::encrypt($receiptDetail->id))}}"
                            class="pull-right btn btn-outline-secondary">Add Product</a>
                        @endif
                    </div>
                    <div class="table-responsive m-t-40">
                        {{-- ====== input error message ========== --}}
                        @include('seller.includes.layouts.message')
                        {{-- ====================================== --}}
                        <table class="table stylish-table">
                            <thead>
                                <tr>
                                    <th colspan="2">Product</th>
                                    <th>Stock</th>
                                    <th>Faulty Stock</th>
                                    <th>Cost</th>
                                    <th>Total Cost</th>
                                    @if ($receiptDetail->status=='completed' || $receiptDetail->status=='paid')
                                        
                                    @else
                                        <th>Operation</th>
                                    @endif                                   
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $key=> $product)
                                <span hidden>{{$total_cost=$product->cost * $product->stock}}</span>
                                <tr>
                                    <td style="width:50px;"><span class="round">P</span></td>
                                    <span
                                        hidden>{{$prod=\App\Models\Product::where(['id'=>$product->product_id])->where('company_id',Auth::user()->company_id)->select('*')->first()}}</span>
                                    <td>
                                        <h6>{{$prod->product_name}}</h6>
                                        <small class="text-muted">
                                            <span hidden>{{$power=\App\Models\Power::where(['product_id'=>$prod->id])->where('company_id',Auth::user()->company_id)->select('*')->first()}}</span>
                                            @if ($power)
                                            @if (initials($prod->product_name)=='SV')
                                                <span>{{$power->sphere}} / {{$power->sphere}}</span>
                                            @else
                                                <span>{{$power->sphere}} / {{$power->sphere}} *{{$power->axis}}  {{$power->add}}</span>
                                            @endif
                                        @else
                                            <span>
                                                {{\App\Models\Category::where(['id'=>$prod->category_id])->where('company_id',Auth::user()->company_id)->pluck('name')->first()}}
                                            </span>
                                        @endif
                                            
                                        </small>
                                    </td>
                                    <td>{{$product->stock}}</td>
                                    <td>{{$product->faulty_stock}}</td>
                                    <td>{{format_money($product->cost)}}</td>
                                    <td>{{format_money($total_cost)}}</td>
                                    <span hidden> {{$gnereral_cost = $total_cost + $gnereral_cost }}</span>
                                    <span hidden> {{$total_stock = $total_stock + $product->stock }}</span>
                                    
                                    @if ($receiptDetail->status=='completed' || $receiptDetail->status=='paid')
                                        
                                    @else
                                    <td>
                                        <a href="{{route('seller.edit.receipt',Crypt::encrypt($product->id))}}" style="color: rgb(0, 38, 255)">Edit</a>
                                            <a href="#" class="pl-2" data-toggle="modal" data-target="#myModal-{{$key}}"
                                                style="color: red">Delete</a>
                                    </td>
                                    @endif
                                </tr>

                                <div id="myModal-{{$key}}" class="modal fade" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel"><i
                                                        class="fa fa-exclamation-triangle"></i> Warning</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                <h4>{{$prod->product_name}} have {{$product->quantity}} quantity!
                                                    continue??</h4>

                                            </div>
                                            <div class="modal-footer">
                                                <a href="{{route('seller.sales.remove.product',Crypt::encrypt($product->id))}}"
                                                    class="btn btn-info waves-effect">Yes</a>
                                                <button type="button" class="btn btn-danger waves-effect"
                                                    data-dismiss="modal">No</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Receipt Number #{{sprintf('%04d',$receiptDetail->id)}} | {{\App\Models\Supplier::where(['id'=>$receiptDetail->supplier_id])->where('company_id',Auth::user()->company_id)->pluck('name')->first()}}</h4>

                </div>
                <div class="card-body bg-light">
                    <div class="row text-center">
                        <div class="col-6 m-t-10 m-b-10">
                            <span class="label label-{{(($receiptDetail->status)=='completed')?'primary':'warning'}}">{{$receiptDetail->status}}</span>
                        </div>
                        <div class="col-6 m-t-10 m-b-10">
                            {{\Carbon\Carbon::parse($receiptDetail->created_at)->diffForHumans()}}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                        <h5 class="p-t-20">Products</h5>
                        <span>{{count($products)}}</span>
                        <h5 class="m-t-30">Total Stock</h5>
                        <span>{{$total_stock}}</span>
                        <h5 class="m-t-30">Total Amount</h5>
                        <span>{{format_money($gnereral_cost)}}
                            </span>
                        <br />
                        @if ($receiptDetail->status=='pending')
                        <a href="" data-toggle="modal" data-target="#myModal" type="button" class="m-t-20 btn waves-effect waves-light btn-success">Finalize Receipt</a>
                        @else
                        @endif
                        
                        

                    <div id="myModal" class="modal fade" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{route('seller.finalize.receipt',Crypt::encrypt($receiptDetail->id))}}" method="post">
                                @csrf
                            <div class="modal-content">
                               
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle"></i>
                                        Warning</h4>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="total_cost" value="{{$gnereral_cost}}">

                                    <h4>Do you want to finalize this Receipt??</h4>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit"
                                        class="btn btn-info waves-effect">Yes</button>
                                    <button type="button" class="btn btn-danger waves-effect"
                                        data-dismiss="modal">No</button>
                                </div>
                            </div>
                            </form>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
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
@endpush
