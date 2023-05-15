@extends('manager.includes.app')

@section('title','Manager Dashboard - Proforma Detail')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Proforma Detail')
@section('page_name','Proforma Detail')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Products </h4>
                        <hr>
                        {{-- @if ($proforma->status=='completed')
                            @if ($proforma->payment=='paid') --}}
                        @if ($proforma->status!='pending')
                            <a href="{{route('manager.proforma.print.proforma',Crypt::encrypt($proforma->id))}}" class="pull-right btn btn-outline-warning">
                                <i class="fa fa-print"></i> Print proforma
                            </a>
                        @endif
                            
                        @if ($proforma->status=='pending')
                            <a href="{{route('manager.proforma.add.product',Crypt::encrypt($proforma->id))}}"
                            class="pull-right btn btn-outline-secondary mx-2">Add Product</a>
                        @endif
                    </div>
                    <div class="table-responsive m-t-40">
                        {{-- ====== input error message ========== --}}
                        @include('manager.includes.layouts.message')
                        {{-- ====================================== --}}
                        <table class="table stylish-table">
                            <thead>
                                <tr>
                                    <th colspan="2">Product</th>
                                    <th>quantity</th>
                                    <th>Price / unit</th>
                                    <th>Percentage</th>
                                    <th>Total</th>
                                    @if ($proforma->status!='pending')
                                        
                                    @else
                                        <th>Operation</th>
                                    @endif
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proforma_products as $key=> $product)
                                <tr>
                                    <td style="width:50px;"><span class="round">P</span></td>
                                    <span
                                        hidden>{{$prod=\App\Models\Product::where(['id'=>$product->product_id])->where('company_id',Auth::user()->company_id)->select('*')->first()}}</span>
                                        <span 
                                            hidden>{{$power=\App\Models\Power::where(['product_id'=>$product->product_id])->where('company_id',Auth::user()->company_id)->select('*')->first()}}</span>
                                    <td>
                                        <h6>{{$prod->product_name}}</h6>
                                        <small class="text-muted">
                                            @if ($power)
                                            @if (initials($prod->product_name)=='SV')
                                                <span> {{$power->sphere}} / {{$power->cylinder}}</span>
                                            @else
                                                <span>{{$power->sphere}} / {{$power->cylinder}} *{{$power->axis}}  {{$power->add}}</span>
                                            @endif
                                            @endif
                                        </small>
                                    </td>
                                    <td>{{$product->quantity}}</td>
                                    <td>{{format_money($product->unit_price)}}</td>
                                    <td>
                                        <strong>{{$product->insurance_percentage}} %</strong>
                                    </td>
                                    <td>{{format_money($product->total_amount)}}</td>
                                    @if ($proforma->status!='pending')
                                        
                                    @else
                                    <td>
                                        <a href="{{route('manager.proforma.edit.product',Crypt::encrypt($product->id))}}" style="color: rgb(0, 38, 255)">Edit</a>
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
                                                <a href="{{route('manager.proforma.remove.product',Crypt::encrypt($product->id))}}"
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
                   
                   <h4 class="card-title">Proforma #{{sprintf('%04d',$proforma->id)}} 

                </h4>

                </div>
                <div class="card-body bg-light">
                    <div class="row text-center">
                        <div class="col-6 m-t-10 m-b-10">
                            @if ($proforma->status!='declined')
                                <span class="label label-{{(($proforma->status)=='approved')?'success':'warning'}}">{{$proforma->status}}</span>
                            @else
                                <span class="label label-danger">{{$proforma->status}}</span>
                            @endif
                        </div>
                        <div class="col-6 m-t-10 m-b-10">
                            {{\Carbon\Carbon::parse($proforma->created_at)->diffForHumans()}}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                        <h5 class="p-t-20">Products</h5>
                        <span>{{count($proforma_products)}}</span>
                        <h5 class="m-t-30">Total Stock</h5>
                        <span>{{\App\Models\ProformaProduct::where(['proforma_id'=>$proforma->id])->where('company_id',Auth::user()->company_id)->select('*')->sum('quantity')}}</span>
                        @if ($proforma->due!=0)
                        <hr>
                        <h5 class="m-t-30">Due Amount</h5>
                        <span>{{format_money($proforma->due)}}
                        </span>
                        @endif
                        <h5 class="m-t-30">Total Amount</h5>
                        <span>{{format_money(\App\Models\ProformaProduct::where(['proforma_id'=>$proforma->id])->where('company_id',Auth::user()->company_id)->select('*')->sum('total_amount'))}}
                        </span>
                        <br />
                        @if ($proforma->status=='pending')
                            <a href="" data-toggle="modal" data-target="#myModal" type="button" class="m-t-20 btn waves-effect waves-light btn-success">Finalize Proforma</a>
                        @endif

                        @if ($proforma->status=='finalized')
                        <hr>
                            <a href="{{route('manager.proforma.approve.proforma',Crypt::encrypt($proforma->id))}}" 
                                type="button" class="m-t-20 btn waves-effect waves-light btn-success">
                                Approve Proforma
                            </a>
                            <a href="{{route('manager.proforma.decline.proforma',Crypt::encrypt($proforma->id))}}" 
                                type="button" class="m-t-20 btn waves-effect waves-light btn-warning">
                                Reject Proforma
                            </a>
                        @endif

                    <div id="myModal" class="modal fade" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle"></i>
                                        Warning</h4>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                        <h4>Do you want to finalize this Proforma??</h4>
                                </div>
                                <div class="modal-footer">
                                    <a href="{{route('manager.proforma.finalize.proforma',Crypt::encrypt($proforma->id))}}"
                                        class="btn btn-info waves-effect">Finalize</a>
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
