@extends('manager.includes.app')

@section('title','Manager Dashboard - My Orders')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Orders')
@section('page_name','Orders List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">

    </div>

    {{-- ================================== --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">
                            <a href="{{ route('manager.order.add')}}" class="btn btn-primary btn-rounded"><i class="mdi mdi-cart-plus"></i> New Order</a>
                            <a href="{{ route('manager.order.track')}}" class="btn btn-success btn-rounded"><i class="mdi mdi-clock-fast"></i> Track Order</a>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">All Orders</h4>
                        @if ($orders)

                    <hr>
                    <a href="#" onclick="exportAll('xls')" class="btn btn-success btn-rounded"><i class="mdi mdi-file-excel"></i> Export To Excel</a>
                        @endif
                    </div>
                    <hr>
                    {{-- ================================= --}}
                    @include('manager.includes.layouts.message')
                    {{-- ========================== --}}

                    <div class="table-responsive">
                        <table id="zero_config" class="table table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order Number</th>
                                    <th>Prescription Hospital </th>
                                    <th>Order Age</th>
                                    <th>Order Date</th>
                                    <th>Expected Delivery Date</th>
                                    <th>Supplier Name</th>
                                    <th>Order Cost</th>
                                    <th>Order Status</th>
                                    <th>Production Date</th>
                                    <th>Completion Date</th>
                                    <th>Delivery Date</th>
                                    <th>Invoice Date</th>
                                    <th>Type</th>
                                    <th>Coating</th>
                                    <th>Index</th>
                                    <th>Chromatic Aspect</th>

                                    <th>Sphere Right</th>
                                    <th>Cylinder Right</th>
                                    <th>Axis Right</th>
                                    <th>Addition Right</th>

                                    <th>Sphere Left</th>
                                    <th>Cylinder Left</th>
                                    <th>Axis Left</th>
                                    <th>Addition Left</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $key=> $order)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$order->order_number}}</td>
                                        <td>{{$order->prescription}}</td>
                                        <td>{{\Carbon\Carbon::parse($order->created_at)->diffForHumans()}}</td>
                                        <td>{{date('Y-m-s',strtotime($order->created_at))}}</td>
                                        <td>{{$order->expected_delivery}}</td>
                                        <td>{{\App\Models\CompanyInformation::where('id',$order->supplier_id)->pluck('company_name')->first()}}</td>
                                        <td>{{format_money($order->order_cost)}}</td>
                                        <td>
                                            @if ($order->status=='submitted')
                                                <span class="badge badge-warning">{{$order->status}}</span>
                                            @endif
                                            @if ($order->status=='canceled')
                                                <span class="badge badge-danger">{{$order->status}}</span>
                                            @endif
                                            @if ($order->status!='submitted' && $order->status!='canceled')
                                                <span class="badge badge-secondary">{{$order->status}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->production_date)
                                                {{date('Y-m-d',strtotime($order->production_date))}}
                                            @else
                                                <span class="text-center">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->completed_date)
                                                {{date('Y-m-d',strtotime($order->completed_date))}}
                                            @else
                                                <span class="text-center">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->delivery_date)
                                                {{date('Y-m-d',strtotime($order->delivery_date))}}
                                            @else
                                                <span class="text-center">-</span>
                                            @endif
                                        </td>
                                        <td>{{date('Y-m-d',strtotime($order->invoice_date))}}</td>
                                        <td>{{\App\Models\LensType::where('id',$order->type_id)->pluck('name')->first()}}</td>
                                        <td>{{\App\Models\PhotoCoating::where('id',$order->type_id)->pluck('name')->first()}}</td>
                                        <td>{{\App\Models\PhotoIndex::where('id',$order->type_id)->pluck('name')->first()}}</td>
                                        <td>{{\App\Models\PhotoChromatics::where('id',$order->type_id)->pluck('name')->first()}}</td>
                                        <td>{{$order->sphere_r}}</td>
                                        <td>{{$order->cylinder_r}}</td>
                                        <td>{{$order->axis_r}}</td>
                                        <td>{{$order->addition_r}}</td>

                                        <td>{{$order->sphere_l}}</td>
                                        <td>{{$order->cylinder_l}}</td>
                                        <td>{{$order->axis_l}}</td>
                                        <td>{{$order->addition_l}}</td>
                                        <td>
                                            @if ($order->status=='submitted')
                                                <a data-toggle="modal" data-target="#cancel-{{$key}}" class="btn btn-danger btn-sm text-white">Cancel Order</a>
                                            @endif
                                            @if ($order->status=='delivery ')
                                                <a data-toggle="modal" data-target="#received-{{$key}}" class="btn btn-success btn-sm text-white">Received Product</a>
                                            @endif
                                        </td>
                                    </tr>

                                    <div id="cancel-{{$key}}" class="modal fade" tabindex="-1" role="dialog"
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
                                                    <h4>Do you want to cancel {{$order->order_number}}??</h4>

                                                </div>
                                                <div class="modal-footer">
                                                    <a href="{{route('manager.order.cancelOrder',Crypt::encrypt($order->id))}}"
                                                        class="btn btn-info waves-effect">Yes</a>
                                                    <button type="button" class="btn btn-danger waves-effect"
                                                        data-dismiss="modal">No</button>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>

                                    <div id="received-{{$key}}" class="modal fade" tabindex="-1" role="dialog"
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
                                                    <h4>Have you received Order {{$order->order_number}}??</h4>

                                                </div>
                                                <div class="modal-footer">
                                                    <a href="{{route('manager.order.receivedOrder',Crypt::encrypt($order->id))}}"
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
    </div>
</div>
    @endsection

    @push('scripts')
    <script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js')}}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js')}}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/export.js')}}"></script>
    <script>
        function exportAll(type)
        {
            $('#scroll_ver_hor').tableExport({
                filename: 'table_%DD%-%MM%-%YY%-month(%MM%)',
                format: type
            });
        }
    </script>
    @endpush
