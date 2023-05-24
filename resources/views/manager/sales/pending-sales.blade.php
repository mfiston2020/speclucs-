@extends('manager.includes.app')

@section('title','Manager Dashboard - My Requests')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Requests')
@section('page_name','Requests List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">

    </div>

    {{-- ================================== --}}
    <div class="row">
        {{-- <div class="col-12">
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
        </div> --}}
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">All Requests</h4>
                        @if ($pendingOrders)

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
                                    <th>Request Number</th>
                                    <th>Request Age</th>
                                    <th>Request Date</th>
                                    @if (userInfo()->role=='store' || userInfo()->role=='manager')
                                        <th>Request Cost</th>
                                    @endif
                                    <th>Request Price</th>
                                    <th>Request Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingOrders as $key=> $order)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>
                                            <a href="#!" data-toggle="modal" data-target="#price-{{$key}}">
                                                #RQST {{sprintf('%04d',$order->id)}}
                                            </a>
                                        </td>
                                        <td>{{\Carbon\Carbon::parse($order->created_at)->diffForHumans()}}</td>
                                        <td>{{date('Y-m-d',strtotime($order->date_of_birth))}}</td>

                                        @if (userInfo()->role=='store' || userInfo()->role=='manager')
                                            <td>{{format_money($order->order_cost)}}</td>
                                        @endif

                                        <td>{{format_money($order->order_price)}}</td>
                                        <td>
                                            <span @class([
                                                'badge badge-warning' => $order->status=='pending',
                                                'badge badge-info' => $order->status=='approved',
                                                'badge badge-success' => $order->status=='paid',
                                                'text-success' => $order->status=='sold',
                                                'badge badge-secondary' => $order->status=='declined',
                                                ])>{{$order->status}}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($order->status!='sold')
                                                @if (userInfo()->role=='store' || userInfo()->role=='manager')
                                                    @if ($order->status=='pending')
                                                        <a data-toggle="modal" data-target="#price-{{$key}}" class="btn btn-success btn-sm text-white">{{__('manager/sales.add_price')}}</a>
                                                    @endif
                                                @endif

                                                @if (userInfo()->role=='seller' || userInfo()->role=='manager')
                                                    @if ($order->status=='approved')
                                                        <a data-toggle="modal" data-target="#reaction-{{$key}}" class="btn btn-warning btn-sm text-white">{{__('manager/sales.paid')}}</a>
                                                    @endif
                                                @endif

                                                @if (userInfo()->role=='seller' || userInfo()->role=='manager')
                                                    @if ($order->status=='paid')
                                                        <a data-toggle="modal" data-target="#sell-{{$key}}" class="btn btn-primary btn-sm text-white">{{__('manager/sales.sell')}}</a>
                                                    @endif
                                                @endif
                                                <a  data-toggle="modal" data-target="#cancel-{{$key}}" class="text-danger ml-4">{{__('manager/sales.cancel_order')}}</a>

                                            @else
                                                <span class="text-info">No Action Needed!</span>
                                            @endif
                                        </td>
                                    </tr>

                                    {{-- setting price modal --}}
                                    <div id="price-{{$key}}" class="modal fade" tabindex="-1" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myModalLabel"><i
                                                            class="fa fa-exclamation-triangle"></i> {{__('manager/sales.order_detail')}}</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-hidden="true">×</button>
                                                </div>
                                                <div class="modal-body">

                                                    <h5 class="text-secondary">{{__('manager/sales.order_detail')}}</h5>
                                                    <br>
                                                    {{-- <div class="row"> --}}
                                                        <strong>Description: </strong>
                                                        <span>
                                                            {{initials2(\App\Models\LensType::where('id',$order->type_id)->pluck('name')->first())}}

                                                            {{\App\Models\PhotoIndex::where('id',$order->index_id)->pluck('name')->first()}}

                                                            {{\App\Models\PhotoChromatics::where('id',$order->chromatic_id)->pluck('name')->first()}}

                                                            {{\App\Models\PhotoCoating::where('id',$order->coating_id)->pluck('name')->first()}} - {{($order->sphere_r==null)?'left':'right'}}
                                                        </span>
                                                    {{-- </div> --}}
                                                    <br>
                                                    <br>
                                                    <div class="row">
                                                        <div class="row col-md-3">
                                                            <strong class="col-12">Sphere: </strong>
                                                            <span class="col-12">{{($order->sphere_r==null)?format_values($order->sphere_l):format_values($order->sphere_r)}}</span>
                                                        </div>

                                                        <div class="row col-md-3">
                                                            <strong class="col-12">Cylinder: </strong>
                                                            <span class="col-12">{{($order->cylinder_r==null)?format_values($order->cylinder_l):format_values($order->cylinder_r)}}</span>
                                                        </div>

                                                        <div class="row col-md-3">
                                                            <strong class="col-12">Axis: </strong>
                                                            <span class="col-12">{{($order->axis_r==null)?format_values($order->axis_l):format_values($order->axis_r)}}</span>
                                                        </div>

                                                        <div class="row col-md-3">
                                                            <strong class="col-12">Addition: </strong>
                                                            <span class="col-12">{{($order->addition_r==null)?format_values($order->addition_l):format_values($order->addition_r)}}</span>
                                                        </div>

                                                    </div>
                                                    @if ($order->status!='sold')
                                                        <hr>
                                                        <br>
                                                        <h4>{{__('manager/sales.add_price')}}</h4>
                                                        {{-- <hr> --}}

                                                        <form action="{{ route('manager.pending.order.price')}}" method="post" id="setPriceForm-{{$key}}">
                                                            @csrf
                                                            <input type="hidden" name="thisName" value="{{Crypt::encrypt($order->id)}}">

                                                            <div class="form-group row">
                                                                <label for="stock" class="col-sm-3 text-right control-label col-form-label">{{__('manager/sales.cost')}}</label>
                                                                <div class="col-sm-9">
                                                                    <input type="number" id="cost" class="form-control" placeholder="0" name="cost" required value="{{$order->order_cost}}" min="1">
                                                                </div>
                                                            </div>


                                                            <div class="form-group row">
                                                                <label for="stock" class="col-sm-3 text-right control-label col-form-label">{{__('manager/sales.price')}}</label>
                                                                <div class="col-sm-9">
                                                                    <input type="number" id="price" class="form-control" placeholder="0" name="price" required value="{{$order->order_cost}}" min="1">
                                                                </div>
                                                            </div>
                                                        </form>
                                                    @endif

                                                </div>
                                                <div class="modal-footer">
                                                    @if ($order->status!='sold')
                                                    <button class="btn btn-info waves-effect" onclick="document.getElementById('setPriceForm-{{$key}}').submit()" >{{__('manager/sales.add_price')}}</button>
                                                    @endif
                                                    <button type="button" class="btn btn-danger waves-effect"
                                                        data-dismiss="modal">{{__('manager/sales.cancel')}}</button>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>

                                    {{-- client reaction modal --}}
                                    <div id="reaction-{{$key}}" class="modal fade" tabindex="-1" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myModalLabel"><i
                                                            class="fa fa-exclamation-triangle"></i> {{__('manager/sales.client_feedback')}}</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-hidden="true">×</button>
                                                </div>
                                                <div class="modal-body">

                                                    <h4>{{__('manager/sales.client_feedback_detail')}}</h4>
                                                    {{-- <hr> --}}

                                                    <form action="{{ route('manager.client.request.feedback')}}" method="post" id="reactionForm-{{$key}}">
                                                        @csrf
                                                        <input type="hidden" name="thisName" value="{{Crypt::encrypt($order->id)}}">
                                                    </form>

                                                </div>
                                                <div class="modal-footer">
                                                    <button onclick="document.getElementById('reactionForm-{{$key}}').submit()" class="btn btn-info waves-effect">{{__('navigation.yes')}}</button>
                                                    <button type="button" class="btn btn-danger waves-effect"
                                                        data-dismiss="modal">{{__('navigation.no')}}</button>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>

                                    {{-- client sell modal --}}
                                    <div id="sell-{{$key}}" class="modal fade" tabindex="-1" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myModalLabel"><i
                                                            class="fa fa-exclamation-triangle"></i> {{__('manager/sales.sell')}}</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-hidden="true">×</button>
                                                </div>
                                                <div class="modal-body">

                                                    <h4>{{__('manager/sales.sell_message')}}</h4>
                                                    {{-- <hr> --}}

                                                    <form action="{{ route('manager.pending.order.sell')}}" method="post" id="sellForm-{{$key}}">
                                                        @csrf
                                                        <input type="hidden" name="thisName" value="{{Crypt::encrypt($order->id)}}">
                                                    </form>

                                                </div>
                                                <div class="modal-footer">
                                                    <button onclick="document.getElementById('sellForm-{{$key}}').submit()" class="btn btn-info waves-effect">{{__('navigation.yes')}}</button>
                                                    <button type="button" class="btn btn-danger waves-effect"
                                                        data-dismiss="modal">{{__('navigation.no')}}</button>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>

                                    {{-- cancel modal --}}
                                    <div id="cancel-{{$key}}" class="modal fade" tabindex="-1" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myModalLabel"><i
                                                            class="fa fa-exclamation-triangle"></i> {{__('manager/sales.client_feedback')}}</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-hidden="true">×</button>
                                                </div>
                                                <div class="modal-body">

                                                    <h4>{{__('manager/sales.cancel_order_message')}}</h4>
                                                    {{-- <hr> --}}

                                                    <form action="{{ route('manager.pending.order.cancel') }}" method="post" id="cancelForm-{{$key}}">
                                                        @csrf
                                                        <input type="hidden" name="thisName" value="{{Crypt::encrypt($order->id)}}">
                                                    </form>

                                                </div>
                                                <div class="modal-footer">
                                                    <button onclick="document.getElementById('cancelForm-{{$key}}').submit()" class="btn btn-info waves-effect">{{__('navigation.yes')}}</button>
                                                    <button type="button" class="btn btn-danger waves-effect"
                                                        data-dismiss="modal">{{__('navigation.no')}}</button>
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
