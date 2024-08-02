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
        <form action="{{route('manager.received.order.production')}}" method="post" class="col-12">
            @csrf
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h4 class="card-title">
                                <a href="{{ url()->previous()}}" class="btn btn-secondary btn-rounded"><i class="mdi mdi-arrow-left-bold"></i> Back to List</a>
                                <button href="{{ route('manager.order.add')}}" class="btn btn-primary btn-rounded"><i class="mdi mdi-factory"></i> Send to production</button>
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
                        @if (!$orders)
                            <hr>
                            <a href="#" onclick="exportAll('xlsx')" class="btn btn-success btn-rounded"><i class="mdi mdi-file-excel"></i> Export To Excel</a>
                        @endif
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
                                        <th>
                                            <div class="form-check form-check-inline align-content-center">
                                                <input class="form-check-input" type="checkbox" id="checkall"
                                                    value="option1">
                                            </div>
                                        </th>
                                        <th>
                                            Expected Delivery date
                                        </th>
                                        <th>Order Number</th>
                                        <th>Prescription Hospital </th>
                                        <th>Order Age</th>
                                        <th>Order Date</th>
                                        <th>Supplier Name</th>
                                        <th>Order Cost</th>
                                        <th>Order Status</th>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $key=> $order)
                                        <tr>
                                            <td>
                                                <div class="form-check form-check-inline align-content-center">
                                                    <input class="form-check-input allCheckbox" type="checkbox"
                                                        name="order[]" id="inlineCheckbox1" value="{{$order->id}}">
                                                </div>
                                            </td>
                                            <td>
                                                <input class="form-control" type="date" min="{{date('Y-m-d')}}"
                                                    name="delivery_date[]" id="inlineCheckbox1" value="{{$order->id}}">
                                            </td>
                                            <td>{{$order->order_number}}</td>
                                            <td>{{$order->prescription}}</td>
                                            <td>{{\Carbon\Carbon::parse($order->created_at)->diffForHumans()}}</td>
                                            <td>{{date('Y-m-s',strtotime($order->created_at))}}</td>
                                            <td>{{\App\Models\CompanyInformation::where('id',$order->supplier_id)->pluck('company_name')->first()}}</td>
                                            <td>{{format_money($order->order_cost)}}</td>
                                            <td>
                                                @if ($order->status=='submitted')
                                                    <span class="badge badge-warning">{{$order->status}}</span>
                                                @endif
                                                @if ($order->status=='canceled')
                                                    <span class="badge badge-danger">{{$order->status}}</span>
                                                @endif
                                            </td>
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
                                        </tr>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
        $("#checkall").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>
    @endpush
