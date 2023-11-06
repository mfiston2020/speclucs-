@extends('manager.includes.app')

@section('title', 'Admin Dashboard - Product')

@push('css')
    <link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <style>
        @media print {
            .modal-footer {
                display: none;
            }
        }
    </style>
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current', 'Lab Requests')
@section('page_name', 'List of Lab requests')
{{-- === End of breadcumb == --}}

@section('content')


    @php
        $isOutOfStock==null;
        $powers =   \App\Models\Power::where('company_id',userInfo()->company_id)->get();
    @endphp
    <div class="container-fluid">
        <!-- Sales chart -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h4 class="card-title">All Requested Products</h4>
                        </div>
                        {{-- ============================== --}}
                        @include('manager.includes.layouts.message')
                        {{-- =============================== --}}

                        <hr>

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">

                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#requested" role="tab">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">
                                        Requested Products
                                        <span class="badge badge-danger badge-pill">
                                            {{ count($requests) }}
                                        </span>
                                    </span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#priced" role="tab">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">
                                        Priced
                                        <span class="badge badge-danger badge-pill">
                                            {{ count($requests_priced) }}
                                        </span>
                                    </span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#po-sent" role="tab">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">
                                        PO Sent
                                        <span class="badge badge-danger badge-pill">
                                            {{ count($requests_supplier) }}
                                        </span>
                                    </span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link " data-toggle="tab" href="#provided-to-lab" role="tab">
                                    <span class="hidden-sm-up">
                                        <i class="ti-home"></i>
                                    </span>
                                    <span class="hidden-xs-down">
                                        Provided to Lab
                                        <span class="badge badge-danger badge-pill">
                                            {{ count($requests_lab) }}
                                        </span>
                                    </span>
                                </a>
                            </li>

                        </ul>

                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content">
            {{-- requested --}}
            <div class="tab-pane active" id="requested" role="tabpanel">
                <div class="tab-pane  p-20" id="profile2" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="nav nav-pills mt-4 mb-4">
                                        <li class=" nav-item">
                                            <a href="#complete-orders" class="nav-link active" data-toggle="tab"
                                                aria-expanded="false">
                                                Available Stock Order(s)
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#incomplete-orders" class="nav-link" data-toggle="tab"
                                                aria-expanded="false">
                                                Out of Stock
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#new-orders" class="nav-link" data-toggle="tab" aria-expanded="false">
                                                N/A Stock Order(s)
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content br-n pn">
                                        {{-- completed and in stock --}}
                                        <div id="complete-orders" class="tab-pane active">
                                            @if (!$requests->isEmpty())
                                                <div class="table-responsive mt-4">
                                                    <table id="zero_config"
                                                        class="table table-striped table-bordered nowrap"
                                                        style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Request # </th>
                                                                <th>Patient Name</th>
                                                                <th>Request Date</th>
                                                                <th>Request Age</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach ($requests as $key => $request)
                                                                @if (count($request->unavailableproducts) <= 0)
                                                                    @foreach ($request->soldproduct as $product)
                                                                        @php
                                                                            $invoice_product = $products->where('id', $product->product_id)->first();



                                                                            if ($invoice_product->stock>2){
                                                                                $isOutOfStock='no';
                                                                            }
                                                                            else{
                                                                                $isOutOfStock='yes';
                                                                            // dd($isOutOfStock);
                                                                            }

                                                                        @endphp
                                                                    @endforeach
                                                                    @if ($isOutOfStock=='no')

                                                                        <tr>
                                                                            <td>-</td>
                                                                            <td>
                                                                                <a href="#!" data-toggle="modal"
                                                                                    data-target="#request-{{ $key }}-detail">
                                                                                    Request
                                                                                    #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                                </a>
                                                                            </td>
                                                                            <td>
                                                                                @if ($request->client_id != null)
                                                                                    {{$request->client->name}}
                                                                                @else
                                                                                    @if ($request->hospital_name!=null)
                                                                                    [{{$request->cloud_id}}] {{$request->hospital_name}}
                                                                                    @else
                                                                                        {{$request->client_name}}
                                                                                    @endif
                                                                                @endif
                                                                                {{-- {{ $request->client_id != null ? $request->client->name : $request->client_name }} --}}
                                                                            </td>
                                                                            <td>
                                                                                {{ date('Y-m-d H:i', strtotime($request->created_at)) }}
                                                                            </td>
                                                                            <td>
                                                                                {{ \Carbon\Carbon::parse($request->created_at)->diffForHumans() }}
                                                                            </td>
                                                                            <td class="text-start">
                                                                                <span @class([
                                                                                    'text-warning' => $request->status == 'requested',
                                                                                ])>
                                                                                    {{ $request->status }}
                                                                                </span>
                                                                            </td>
                                                                        </tr>


                                                                        {{-- modal --}}
                                                                        <div class="modal fade bs-example-modal-lg"
                                                                            id="request-{{ $key }}-detail"
                                                                            tabindex="-1" role="dialog"
                                                                            aria-labelledby="myLargeModalLabel"
                                                                            aria-hidden="true" style="display: none;">
                                                                            @php
                                                                                $isOutOfStock='no';
                                                                            @endphp
                                                                            <div
                                                                                class="modal-dialog modal-xl modal-dialog-centered">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <div>
                                                                                            <h4 class="modal-title text-info">
                                                                                                @if ($request->client_id != null)
                                                                                                    {{$request->client->name}}
                                                                                                @else
                                                                                                    @if ($request->hospital_name!=null)
                                                                                                        [{{$request->cloud_id}}] {{$request->hospital_name}}
                                                                                                    @else
                                                                                                        {{$request->client_name}}
                                                                                                    @endif
                                                                                                @endif
                                                                                                {{-- {{ $request->client_id != null ? $request->client->name : $request->client_name }} --}}
                                                                                            </h4>
                                                                                            <br>

                                                                                            <h4 class="modal-title"
                                                                                                id="content-detail-{{ $key }}">
                                                                                                Request
                                                                                                #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                                            </h4>
                                                                                        </div>
                                                                                        <button type="button" class="close"
                                                                                            data-dismiss="modal"
                                                                                            aria-hidden="true">×</button>
                                                                                    </div>
                                                                                    <div class="modal-body" id="printable">
                                                                                        <h4 class="text-info">Lens</h4>
                                                                                        <hr>
                                                                                        @foreach ($request->soldproduct as $product)
                                                                                            @php
                                                                                                $invoice_product = $products->where('id', $product->product_id)->first();

                                                                                                if ($invoice_product->stock<2){
                                                                                                    $isOutOfStock='yes';
                                                                                                }

                                                                                            @endphp

                                                                                            {{-- for lens --}}
                                                                                            @if ($invoice_product->category_id == 1)

                                                                                                <div class="row mb-2">
                                                                                                    <div class="col-1">
                                                                                                        <h4
                                                                                                            class="text-capitalize">
                                                                                                            {{ $request->eye == null ? '' : Oneinitials($request->eye) }}
                                                                                                        </h4>
                                                                                                    </div>
                                                                                                    <div class="col-3">
                                                                                                        <span>
                                                                                                            {{ $invoice_product->description }}
                                                                                                        </span>
                                                                                                    </div>
                                                                                                    <div class="col-2">
                                                                                                        @if (initials($invoice_product->product_name) == 'SV')
                                                                                                            <span>{{ $invoice_product->power->sphere }}
                                                                                                                /
                                                                                                                {{ $invoice_product->power->cylinder }}</span>
                                                                                                        @else
                                                                                                            <span>{{ $invoice_product->power->sphere }}
                                                                                                                /
                                                                                                                {{ $invoice_product->power->cylinder }}
                                                                                                                *{{ $invoice_product->power->axis }}
                                                                                                                {{ $invoice_product->power->add }}</span>
                                                                                                        @endif
                                                                                                    </div>
                                                                                                    <div class="col-2 row">
                                                                                                        <span>
                                                                                                            <h6>Location: </h6>
                                                                                                        </span>
                                                                                                        {{-- </div>
                                                                                    <div class="col-2"> --}}
                                                                                                        {{ $invoice_product->location == null ? '-' : $invoice_product->location }}
                                                                                                    </div>
                                                                                                    <div class="col-2 ">
                                                                                                        <span
                                                                                                            class="text-capitalize d-flex justify-content-around items-center">
                                                                                                            <h6
                                                                                                                class="text-dark">
                                                                                                                Mono PD:
                                                                                                            </h6>
                                                                                                            <span
                                                                                                                class="text-capitalize">
                                                                                                                {{ $product->mono_pd }}
                                                                                                            </span>
                                                                                                        </span>
                                                                                                    </div>
                                                                                                    <div class="col-2 ">
                                                                                                        <span
                                                                                                            class="text-capitalize d-flex justify-content-around items-center">
                                                                                                            <h6
                                                                                                                class="text-dark">
                                                                                                                Seg H:
                                                                                                            </h6>
                                                                                                            <span
                                                                                                                class="text-capitalize">
                                                                                                                {{ $product->segment_h }}
                                                                                                            </span>
                                                                                                        </span>
                                                                                                    </div>
                                                                                                </div>
                                                                                            @endif
                                                                                        @endforeach

                                                                                        {{-- for frame --}}
                                                                                        <hr>
                                                                                        <h4 class="text-info">Frame</h4>
                                                                                        <hr>
                                                                                        @if ($products)
                                                                                            @foreach ($request->soldproduct as $product)
                                                                                                @php
                                                                                                    $invoice_product = $products->where('id', $product->product_id)->first();
                                                                                                @endphp
                                                                                                @if ($invoice_product->category_id == 2)
                                                                                                    <div class="row mb-2">
                                                                                                        <div class="col-6">
                                                                                                            <span
                                                                                                                class="text-capitalize">
                                                                                                                {{ $invoice_product->product_name }}
                                                                                                                -
                                                                                                                {{ $invoice_product->description }}
                                                                                                            </span>
                                                                                                        </div>
                                                                                                        <div class="col-3">
                                                                                                            <span
                                                                                                                class="text-capitalize">
                                                                                                                <h4>Location:
                                                                                                                </h4>
                                                                                                            </span>
                                                                                                        </div>
                                                                                                        <div class="col-3">
                                                                                                            <span
                                                                                                                class="text-capitalize">
                                                                                                                {{ $product->location == null ? '-' : $product->location }}
                                                                                                            </span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                @endif
                                                                                            @endforeach
                                                                                        @endif

                                                                                        <hr>
                                                                                        <h4 class="text-info">Accessories &
                                                                                            Others
                                                                                        </h4>
                                                                                        <hr>
                                                                                        {{-- for accessories --}}
                                                                                        @if ($products)
                                                                                            @foreach ($request->soldproduct as $product)
                                                                                                @php
                                                                                                    $invoice_product = $products->where('id', $product->product_id)->first();
                                                                                                @endphp
                                                                                                @if ($invoice_product->category_id != 2 && $invoice_product->category_id != 1)
                                                                                                    <div class="row mb-2">
                                                                                                        <div class="col-3">
                                                                                                            <span
                                                                                                                class="text-capitalize">
                                                                                                                {{ $invoice_product->product_name }}
                                                                                                                -
                                                                                                                {{ $invoice_product->description }}
                                                                                                            </span>
                                                                                                        </div>
                                                                                                        <div class="col-3">
                                                                                                            <span
                                                                                                                class="text-capitalize">
                                                                                                                <h4
                                                                                                                    class="text-dark">
                                                                                                                    Location:
                                                                                                                </h4>
                                                                                                            </span>
                                                                                                        </div>
                                                                                                        <div class="col-2">
                                                                                                            <span
                                                                                                                class="text-capitalize">
                                                                                                                {{ $invoice_product->location == null ? '-' : $invoice_product->location }}
                                                                                                            </span>
                                                                                                        </div>
                                                                                                        <div class="col-2 ">
                                                                                                            <span
                                                                                                                class="text-capitalize d-flex justify-content-around items-center">
                                                                                                                <h4
                                                                                                                    class="text-dark">
                                                                                                                    Qty:
                                                                                                                </h4>
                                                                                                                <span
                                                                                                                    class="text-capitalize">
                                                                                                                    {{ $product->quantity }}
                                                                                                                </span>
                                                                                                            </span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                @endif
                                                                                            @endforeach
                                                                                        @endif
                                                                                    </div>
                                                                                    <div
                                                                                        class="modal-footer d-flex justify-content-between">

                                                                                            @if ($isOutOfStock=='yes')
                                                                                                <center><h4 class="text-danger">Product out of stock</h4></center>
                                                                                            @else
                                                                                                <button type="button"
                                                                                                    class="btn btn-danger waves-effect text-left"
                                                                                                    data-dismiss="modal">
                                                                                                    Close
                                                                                                </button>
                                                                                                <button type="button"
                                                                                                    class="btn btn-success waves-effect text-left"
                                                                                                    id="print">Print</button>
                                                                                                <a href="{{ route('manager.send.request.lab', Crypt::encrypt($request->id)) }}"
                                                                                                    onclick="return confirm('are you sure?')"
                                                                                                    class="btn btn-info waves-effect text-left">
                                                                                                    Send to Lab
                                                                                                </a>

                                                                                            @endif
                                                                                    </div>
                                                                                </div>
                                                                                <!-- /.modal-content -->
                                                                            </div>
                                                                            <!-- /.modal-dialog -->
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>


                                                </div>
                                            @endif
                                        </div>

                                        {{-- out of stock --}}
                                        <div id="incomplete-orders" class="tab-pane">
                                            @if (!$requests->isEmpty())
                                                <div class="table-responsive mt-4">
                                                    <button onclick="exportOutOfStock('xls','Priced Lens');"
                                                        class="btn btn-success float-right mb-3">
                                                        <i class="fa fa-cloud-download-alt"></i>
                                                        Excel
                                                    </button>
                                                            <form method="post" action="{{ route('manager.sent.request.send.to.supplier') }}">
                                                    <table id="outOfStock"
                                                        class="table table-striped table-bordered nowrap"
                                                        style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    <input type="checkbox" onclick="checkUncheckrequestId(this)">
                                                                </th>
                                                                <th>Request # </th>
                                                                <th>Patient Name</th>
                                                                <th>Request Date</th>
                                                                <th>Request Age</th>
                                                                <th>Description</th>
                                                                <th>Right Eye</th>
                                                                <th>Left Eye</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>
                                                                @csrf

                                                                <tbody>
                                                                        @foreach ($bookings as $key => $request)
                                                                            @if (count($request->unavailableproducts) <= 0)

                                                                                <tr>
                                                                                    <td>
                                                                                        <input type='checkbox' name="requestId[]" value={{ $request->id }} />
                                                                                    </td>
                                                                                    <td>
                                                                                        <a href="#!" data-toggle="modal"
                                                                                            data-target="#proddd-{{ $key }}-detail">
                                                                                            Request
                                                                                            #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                                        </a>
                                                                                    </td>
                                                                                    <td>
                                                                                        @if ($request->client_id != null)
                                                                                            {{$request->client->name}}
                                                                                        @else
                                                                                            @if ($request->hospital_name!=null)
                                                                                            [{{$request->cloud_id}}] {{$request->hospital_name}}
                                                                                            @else
                                                                                                {{$request->client_name}}
                                                                                            @endif
                                                                                        @endif
                                                                                        {{-- {{ $request->client_id != null ? $request->client->name : $request->client_name }} --}}
                                                                                    </td>
                                                                                    <td>
                                                                                        {{ date('Y-m-d H:i', strtotime($request->created_at)) }}
                                                                                    </td>
                                                                                    <td>
                                                                                        {{ \Carbon\Carbon::parse($request->created_at)->diffForHumans() }}
                                                                                    </td>

                                                                                    </td>
                                                                                    @php
                                                                                        $availability = true;
                                                                                        $description = null;
                                                                                        $right_len = $request->unavailableproducts->where('eye', 'right')->first();
                                                                                        if (!$right_len) {
                                                                                            $right_len = $request->soldproduct->where('eye', 'right')->first();
                                                                                            if ($right_len==null) {
                                                                                                continue;
                                                                                            }
                                                                                            $right_len = $powers->where('product_id',$right_len->product_id)->first();
                                                                                            $availability = false;
                                                                                        }

                                                                                        if ($availability == true) {
                                                                                            $type = $lens_type
                                                                                                ->where('id', $right_len->type_id)
                                                                                                ->pluck('name')
                                                                                                ->first();

                                                                                            $indx = $index
                                                                                                ->where('id', $right_len->index_id)
                                                                                                ->pluck('name')
                                                                                                ->first();

                                                                                            $ct = $coatings
                                                                                                ->where('id', $right_len->coating_id)
                                                                                                ->pluck('name')
                                                                                                ->first();

                                                                                            $chrm = $chromatics
                                                                                                ->where('id', $right_len->chromatic_id)
                                                                                                ->pluck('name')
                                                                                                ->first();
                                                                                        } else {
                                                                                            if ($right_len) {
                                                                                                $description = $products
                                                                                                ->where('id', $right_len->product_id)
                                                                                                ->pluck('description')
                                                                                                ->first();
                                                                                            }
                                                                                        }

                                                                                        $left_len = $request->unavailableproducts->where('eye', 'left')->first();
                                                                                        if (!$left_len) {
                                                                                            $left_len = $request->soldproduct->where('eye', 'left')->first();
                                                                                            $left_len = $powers->where('product_id',$right_len->product_id)->first();
                                                                                        }
                                                                                    @endphp
                                                                                    <td>
                                                                                        @if ($availability)
                                                                                            {{ initials($type) }} {{ $chrm }}
                                                                                            {{ $ct }} {{ $indx }}
                                                                                        @else
                                                                                            {{ $description }}
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>
                                                                                        @if ($right_len)
                                                                                            @if ($availability)
                                                                                                <span>
                                                                                                    {{ format_values($right_len->sphere) }}
                                                                                                    /
                                                                                                    {{ format_values($right_len->cylinder) }}
                                                                                                    *{{ format_values($right_len->axis) }}
                                                                                                    {{ format_values($right_len->addition) }}
                                                                                                </span>
                                                                                            @else
                                                                                                <span>
                                                                                                    {{ format_values($right_len->sphere) }}
                                                                                                    /
                                                                                                    {{ format_values($right_len->cylinder) }}
                                                                                                    *{{ format_values($right_len->axis) }}
                                                                                                    {{ format_values($right_len->add) }}
                                                                                                </span>
                                                                                            @endif
                                                                                        @else
                                                                                            <span>-</span>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>
                                                                                        @if ($left_len)
                                                                                            @if ($availability)
                                                                                                {{ format_values($left_len->sphere) }}
                                                                                                /
                                                                                                {{ format_values($left_len->cylinder) }}
                                                                                                *{{ format_values($left_len->axis) }}
                                                                                                {{ format_values($left_len->addition) }}
                                                                                            @else
                                                                                                {{ format_values($left_len->sphere) }}
                                                                                                /
                                                                                                {{ format_values($left_len->cylinder) }}
                                                                                                *{{ format_values($left_len->axis) }}
                                                                                                {{ format_values($left_len->add) }}
                                                                                            @endif
                                                                                        @else
                                                                                            <span class="text-center">-</span>
                                                                                        @endif
                                                                                    </td>

                                                                                    <td class="text-start">
                                                                                        <span @class([
                                                                                            'text-warning' => $request->status == 'requested',
                                                                                        ])>
                                                                                            {{ $request->status }}
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>


                                                                                {{-- modal --}}

                                                                                <div class="modal fade bs-example-modal-lg"
                                                                                    id="proddd-{{ $key }}-detail"
                                                                                    tabindex="-1" role="dialog"
                                                                                    aria-labelledby="myLargeModalLabel"
                                                                                    aria-hidden="true" style="display: none;">
                                                                                    @php
                                                                                        $isOutOfStock='no';
                                                                                    @endphp
                                                                                    <div
                                                                                        class="modal-dialog modal-xl modal-dialog-centered">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <div>
                                                                                                    <h4 class="modal-title text-info">
                                                                                                        @if ($request->client_id != null)
                                                                                                            {{$request->client->name}}
                                                                                                        @else
                                                                                                            @if ($request->hospital_name!=null)
                                                                                                                [{{$request->cloud_id}}] {{$request->hospital_name}}
                                                                                                            @else
                                                                                                                {{$request->client_name}}
                                                                                                            @endif
                                                                                                        @endif
                                                                                                        {{-- {{ $request->client_id != null ? $request->client->name : $request->client_name }} --}}
                                                                                                    </h4>
                                                                                                    <br>

                                                                                                    <h4 class="modal-title"
                                                                                                        id="content-detail-{{ $key }}">
                                                                                                        Request
                                                                                                        #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                                                    </h4>
                                                                                                </div>
                                                                                                <button type="button" class="close"
                                                                                                    data-dismiss="modal"
                                                                                                    aria-hidden="true">×</button>
                                                                                            </div>
                                                                                            <div class="modal-body" id="printable">
                                                                                                <h4 class="text-info">Lens</h4>
                                                                                                <hr>
                                                                                                @foreach ($request->soldproduct as $product)
                                                                                                    @php
                                                                                                        $invoice_product = $products->where('id', $product->product_id)->first();

                                                                                                        if ($invoice_product->stock<2){
                                                                                                            $isOutOfStock='yes';
                                                                                                        }
                                                                                                        else {
                                                                                                            $isOutOfStock='no';
                                                                                                        }

                                                                                                    @endphp

                                                                                                    {{-- for lens --}}
                                                                                                    @if ($invoice_product->category_id == 1)

                                                                                                        <div class="row mb-2">
                                                                                                            <div class="col-1">
                                                                                                                <h4
                                                                                                                    class="text-capitalize">
                                                                                                                    {{ $request->eye == null ? '' : Oneinitials($request->eye) }}
                                                                                                                </h4>
                                                                                                            </div>
                                                                                                            <div class="col-3">
                                                                                                                <span>
                                                                                                                    {{ $invoice_product->description }}
                                                                                                                </span>
                                                                                                            </div>
                                                                                                            <div class="col-2">
                                                                                                                @if (initials($invoice_product->product_name) == 'SV')
                                                                                                                    <span>{{ $invoice_product->power->sphere }}
                                                                                                                        /
                                                                                                                        {{ $invoice_product->power->cylinder }}</span>
                                                                                                                @else
                                                                                                                    <span>{{ $invoice_product->power->sphere }}
                                                                                                                        /
                                                                                                                        {{ $invoice_product->power->cylinder }}
                                                                                                                        *{{ $invoice_product->power->axis }}
                                                                                                                        {{ $invoice_product->power->add }}</span>
                                                                                                                @endif
                                                                                                            </div>
                                                                                                            <div class="col-2 row">
                                                                                                                <span>
                                                                                                                    <h6>Location: </h6>
                                                                                                                </span>
                                                                                                                {{-- </div>
                                                                                            <div class="col-2"> --}}
                                                                                                                {{ $invoice_product->location == null ? '-' : $invoice_product->location }}
                                                                                                            </div>
                                                                                                            <div class="col-2 ">
                                                                                                                <span
                                                                                                                    class="text-capitalize d-flex justify-content-around items-center">
                                                                                                                    <h6
                                                                                                                        class="text-dark">
                                                                                                                        Mono PD:
                                                                                                                    </h6>
                                                                                                                    <span
                                                                                                                        class="text-capitalize">
                                                                                                                        {{ $product->mono_pd }}
                                                                                                                    </span>
                                                                                                                </span>
                                                                                                            </div>
                                                                                                            <div class="col-2 ">
                                                                                                                <span
                                                                                                                    class="text-capitalize d-flex justify-content-around items-center">
                                                                                                                    <h6
                                                                                                                        class="text-dark">
                                                                                                                        Seg H:
                                                                                                                    </h6>
                                                                                                                    <span
                                                                                                                        class="text-capitalize">
                                                                                                                        {{ $product->segment_h }}
                                                                                                                    </span>
                                                                                                                </span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    @endif
                                                                                                @endforeach

                                                                                                {{-- for frame --}}
                                                                                                <hr>
                                                                                                <h4 class="text-info">Frame</h4>
                                                                                                <hr>
                                                                                                @if ($products)
                                                                                                    @foreach ($request->soldproduct as $product)
                                                                                                        @php
                                                                                                            $invoice_product = $products->where('id', $product->product_id)->first();
                                                                                                        @endphp
                                                                                                        @if ($invoice_product->category_id == 2)
                                                                                                            <div class="row mb-2">
                                                                                                                <div class="col-6">
                                                                                                                    <span
                                                                                                                        class="text-capitalize">
                                                                                                                        {{ $invoice_product->product_name }}
                                                                                                                        -
                                                                                                                        {{ $invoice_product->description }}
                                                                                                                    </span>
                                                                                                                </div>
                                                                                                                <div class="col-3">
                                                                                                                    <span
                                                                                                                        class="text-capitalize">
                                                                                                                        <h4>Location:
                                                                                                                        </h4>
                                                                                                                    </span>
                                                                                                                </div>
                                                                                                                <div class="col-3">
                                                                                                                    <span
                                                                                                                        class="text-capitalize">
                                                                                                                        {{ $product->location == null ? '-' : $product->location }}
                                                                                                                    </span>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                @endif

                                                                                                <hr>
                                                                                                <h4 class="text-info">Accessories &
                                                                                                    Others
                                                                                                </h4>
                                                                                                <hr>
                                                                                                {{-- for accessories --}}
                                                                                                @if ($products)
                                                                                                    @foreach ($request->soldproduct as $product)
                                                                                                        @php
                                                                                                            $invoice_product = $products->where('id', $product->product_id)->first();
                                                                                                        @endphp
                                                                                                        @if ($invoice_product->category_id != 2 && $invoice_product->category_id != 1)
                                                                                                            <div class="row mb-2">
                                                                                                                <div class="col-3">
                                                                                                                    <span
                                                                                                                        class="text-capitalize">
                                                                                                                        {{ $invoice_product->product_name }}
                                                                                                                        -
                                                                                                                        {{ $invoice_product->description }}
                                                                                                                    </span>
                                                                                                                </div>
                                                                                                                <div class="col-3">
                                                                                                                    <span
                                                                                                                        class="text-capitalize">
                                                                                                                        <h4
                                                                                                                            class="text-dark">
                                                                                                                            Location:
                                                                                                                        </h4>
                                                                                                                    </span>
                                                                                                                </div>
                                                                                                                <div class="col-2">
                                                                                                                    <span
                                                                                                                        class="text-capitalize">
                                                                                                                        {{ $invoice_product->location == null ? '-' : $invoice_product->location }}
                                                                                                                    </span>
                                                                                                                </div>
                                                                                                                <div class="col-2 ">
                                                                                                                    <span
                                                                                                                        class="text-capitalize d-flex justify-content-around items-center">
                                                                                                                        <h4
                                                                                                                            class="text-dark">
                                                                                                                            Qty:
                                                                                                                        </h4>
                                                                                                                        <span
                                                                                                                            class="text-capitalize">
                                                                                                                            {{ $product->quantity }}
                                                                                                                        </span>
                                                                                                                    </span>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                @endif
                                                                                            </div>
                                                                                            <div
                                                                                                class="modal-footer d-flex justify-content-between">

                                                                                                    @if ($isOutOfStock=='yes')
                                                                                                        <center><h4 class="text-danger">Product out of stock</h4></center>
                                                                                                    @else
                                                                                                        <button type="button"
                                                                                                            class="btn btn-danger waves-effect text-left"
                                                                                                            data-dismiss="modal">
                                                                                                            Close
                                                                                                        </button>
                                                                                                        <button type="button"
                                                                                                            class="btn btn-success waves-effect text-left"
                                                                                                            id="print">Print</button>
                                                                                                        <a href="{{ route('manager.send.request.lab', Crypt::encrypt($request->id)) }}"
                                                                                                            onclick="return confirm('are you sure?')"
                                                                                                            class="btn btn-info waves-effect text-left">
                                                                                                            Send to Lab
                                                                                                        </a>

                                                                                                    @endif
                                                                                            </div>
                                                                                        </div>
                                                                                        <!-- /.modal-content -->
                                                                                    </div>
                                                                                    <!-- /.modal-dialog -->
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                </tbody>
                                                    </table>

                                                            <hr>
                                                            <button class="btn btn-primary">Order from Supplier</button>
                                                        </form>


                                                </div>
                                            @endif
                                        </div>

                                        {{-- never been in stock before --}}
                                        <div id="new-orders" class="tab-pane">
                                            @if (!$requests->isEmpty())
                                                <div class="table-responsive mt-4">
                                                    <table id="zero_config"
                                                        class="table table-striped table-bordered nowrap"
                                                        style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Request # </th>
                                                                <th>Patient Name</th>
                                                                <th>Request Date</th>
                                                                <th>Request Age</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach ($requests as $key => $request)
                                                                @if (!$request->unavailableproducts()->exists())
                                                                    <tr>
                                                                        <td>{{ $key + 1 }}</td>
                                                                        <td>
                                                                            <a href="#!" data-toggle="modal"
                                                                                data-target="#request-{{ $key }}-detail">
                                                                                Request
                                                                                #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                            </a>
                                                                        </td>
                                                                        <td>
                                                                            @if ($request->client_id != null)
                                                                                {{$request->client->name}}
                                                                            @else
                                                                                @if ($request->hospital_name!=null)
                                                                                [{{$request->cloud_id}}] {{$request->hospital_name}}
                                                                                @else
                                                                                    {{$request->client_name}}
                                                                                @endif
                                                                            @endif
                                                                            {{-- {{ $request->client_id != null ? $request->client->name : $request->client_name }} --}}
                                                                        </td>
                                                                        <td>
                                                                            {{ date('Y-m-d H:i', strtotime($request->created_at)) }}
                                                                        </td>
                                                                        <td>
                                                                            {{ \Carbon\Carbon::parse($request->created_at)->diffForHumans() }}
                                                                        </td>
                                                                        <td class="text-start">
                                                                            <span @class([
                                                                                'text-warning' => $request->status == 'requested',
                                                                            ])>
                                                                                {{ $request->status }}
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>

                                                    {{-- looping to add modals on the page separately from the table --}}
                                                    @foreach ($requests as $key => $request)
                                                        {{-- modal --}}

                                                        <div class="modal fade bs-example-modal-lg"
                                                            id="request-{{ $key }}-detail" tabindex="-1"
                                                            role="dialog" aria-labelledby="myLargeModalLabel"
                                                            aria-hidden="true" style="display: none;">
                                                            <div class="modal-dialog modal-xl modal-dialog-centered">
                                                                <div class="modal-content"
                                                                    id="request-{{ $key }}-contents">
                                                                    <div class="modal-header">
                                                                        <div>
                                                                            <h4 class="modal-title text-info">
                                                                                @if ($request->client_id != null)
                                                                                    {{$request->client->name}}
                                                                                @else
                                                                                    @if ($request->hospital_name!=null)
                                                                                    [{{$request->cloud_id}}] {{$request->hospital_name}}
                                                                                    @else
                                                                                        {{$request->client_name}}
                                                                                    @endif
                                                                                @endif
                                                                                {{-- {{ $request->client_id != null ? $request->client->name : $request->client_name }} --}}
                                                                            </h4>
                                                                            <br>

                                                                            <h4 class="modal-title"
                                                                                id="content-detail-{{ $key }}">
                                                                                Request
                                                                                #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                            </h4>
                                                                        </div>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal"
                                                                            aria-hidden="true">×</button>
                                                                    </div>

                                                                    <div class="modal-body" id="printable">
                                                                        <h4 class="text-info">Lens</h4>
                                                                        <hr>

                                                                        <table style="width:100%" class="">
                                                                            <tr class="mb-4">
                                                                                <th>Eye</th>
                                                                                <th>Description</th>
                                                                                <th>Power</th>
                                                                                <th>Location</th>
                                                                                <th>Mono PD</th>
                                                                                <th>Segment H</th>
                                                                            </tr>
                                                                            <tbody>
                                                                                {{-- looping through available products --}}
                                                                                @if (!$request->soldproduct->isEmpty())
                                                                                    @foreach ($request->soldproduct as $productsold)
                                                                                        @php
                                                                                            $invoice_product = $products->where('id', $productsold->product_id)->first();
                                                                                        @endphp

                                                                                        @if ($invoice_product->category_id == 1)
                                                                                            <tr>
                                                                                                <td>
                                                                                                    {{ $productsold->eye == null ? '' : Oneinitials($productsold->eye) }}
                                                                                                </td>
                                                                                                <td>
                                                                                                    {{ $invoice_product->description }}
                                                                                                </td>
                                                                                                <td>
                                                                                                    @if (initials($invoice_product->product_name) == 'SV')
                                                                                                        <span>{{ $invoice_product->power->sphere }}
                                                                                                            /
                                                                                                            {{ $invoice_product->power->cylinder }}</span>
                                                                                                    @else
                                                                                                        <span>{{ $invoice_product->power->sphere }}
                                                                                                            /
                                                                                                            {{ $invoice_product->power->cylinder }}
                                                                                                            *{{ $invoice_product->power->axis }}
                                                                                                            {{ $invoice_product->power->add }}</span>
                                                                                                    @endif
                                                                                                </td>
                                                                                                <td>
                                                                                                    {{ $invoice_product->location == null ? '-' : $invoice_product->location }}
                                                                                                </td>
                                                                                                <td>
                                                                                                    <span
                                                                                                        class="text-capitalize">
                                                                                                        {{ $productsold->mono_pd }}
                                                                                                    </span>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <span
                                                                                                        class="text-capitalize">
                                                                                                        {{ $productsold->segment_h }}
                                                                                                    </span>
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endif
                                                                                    @endforeach
                                                                                @endif

                                                                                {{-- looping through unavailable products --}}
                                                                                @if (!$request->unavailableproducts()->exists())
                                                                                    @foreach ($request->unavailableproducts as $productsold)
                                                                                        @php
                                                                                            $availability = true;
                                                                                            $descripition = null;
                                                                                            $right_len = $request->unavailableproducts->where('eye', 'right')->first();
                                                                                            if (!$right_len) {
                                                                                                $right_len = $request->soldproduct->where('eye', 'right')->first();
                                                                                                $availability = false;
                                                                                            }
                                                                                            if ($availability == true) {
                                                                                                $type = $lens_type
                                                                                                    ->where('id', $right_len->type_id)
                                                                                                    ->pluck('name')
                                                                                                    ->first();

                                                                                                $indx = $index
                                                                                                    ->where('id', $right_len->index_id)
                                                                                                    ->pluck('name')
                                                                                                    ->first();

                                                                                                $ct = $coatings
                                                                                                    ->where('id', $right_len->coating_id)
                                                                                                    ->pluck('name')
                                                                                                    ->first();

                                                                                                $chrm = $chromatics
                                                                                                    ->where('id', $right_len->chromatic_id)
                                                                                                    ->pluck('name')
                                                                                                    ->first();
                                                                                            } else {
                                                                                                $description = $products
                                                                                                    ->where('id', $right_len->product_id)
                                                                                                    ->pluck('description')
                                                                                                    ->first();
                                                                                            }

                                                                                            $left_len = $request->unavailableproducts->where('eye', 'left')->first();
                                                                                            if (!$left_len) {
                                                                                                $left_len = $request->soldproduct->where('eye', 'left')->first();
                                                                                            }
                                                                                        @endphp
                                                                                        <tr>
                                                                                            <td>
                                                                                                {{ $productsold->eye == null ? '' : Oneinitials($productsold->eye) }}
                                                                                            </td>
                                                                                            <td>
                                                                                                @if ($availability)
                                                                                                    {{ initials($type) }}
                                                                                                    {{ $chrm }}
                                                                                                    {{ $ct }}
                                                                                                    {{ $indx }}
                                                                                                @else
                                                                                                    {{ $description }}
                                                                                                @endif
                                                                                            </td>
                                                                                            <td>
                                                                                                {{ format_values($productsold->sphere) }}
                                                                                                /
                                                                                                {{ format_values($productsold->cylinder) }}
                                                                                                *{{ format_values($productsold->axis) }}
                                                                                                {{ format_values($productsold->addition) }}
                                                                                            </td>
                                                                                            <td>
                                                                                                {{ $productsold->location == null ? '-' : $productsold->location }}
                                                                                            </td>
                                                                                            <td>
                                                                                                {{ $productsold->mono_pd }}
                                                                                            </td>
                                                                                            <td>
                                                                                                <span
                                                                                                    class="text-capitalize">
                                                                                                    {{ $productsold->segment_h }}
                                                                                                </span>
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                @endif

                                                                            </tbody>
                                                                        </table>

                                                                        {{-- for frame --}}
                                                                        <hr>
                                                                        <h4 class="text-info">Frame</h4>
                                                                        <hr>
                                                                        @if ($products)
                                                                            @foreach ($request->soldproduct as $product)
                                                                                @php
                                                                                    $invoice_product = $products->where('id', $product->product_id)->first();
                                                                                @endphp
                                                                                @if ($invoice_product->category_id == 2)
                                                                                    <div class="row mb-2">
                                                                                        <div class="col-6">
                                                                                            <span class="text-capitalize">
                                                                                                {{ $invoice_product->product_name }}
                                                                                                -
                                                                                                {{ $invoice_product->description }}
                                                                                            </span>
                                                                                        </div>
                                                                                        <div class="col-3">
                                                                                            <span class="text-capitalize">
                                                                                                <h4>Location:
                                                                                                </h4>
                                                                                            </span>
                                                                                        </div>
                                                                                        <div class="col-3">
                                                                                            <span class="text-capitalize">
                                                                                                {{ $product->location == null ? '-' : $product->location }}
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif

                                                                        <hr>
                                                                        <h4 class="text-info">Accessories &
                                                                            Others
                                                                        </h4>
                                                                        <hr>
                                                                        {{-- for accessories --}}
                                                                        @if ($products)
                                                                            @foreach ($request->soldproduct as $product)
                                                                                @php
                                                                                    $invoice_product = $products->where('id', $product->product_id)->first();
                                                                                @endphp

                                                                                @if ($invoice_product->category_id != 2 && $invoice_product->category_id != 1)
                                                                                    <div class="row mb-2">
                                                                                        <div class="col-3">
                                                                                            <span class="text-capitalize">
                                                                                                {{ $invoice_product->product_name }}
                                                                                                -
                                                                                                {{ $invoice_product->description }}
                                                                                            </span>
                                                                                        </div>
                                                                                        <div class="col-3">
                                                                                            <span class="text-capitalize">
                                                                                                <h4 class="text-dark">
                                                                                                    Location:
                                                                                                </h4>
                                                                                            </span>
                                                                                        </div>
                                                                                        <div class="col-2">
                                                                                            <span class="text-capitalize">
                                                                                                {{ $product->location == null ? '-' : $product->location }}
                                                                                            </span>
                                                                                        </div>
                                                                                        <div class="col-2 ">
                                                                                            <span
                                                                                                class="text-capitalize d-flex justify-content-around items-center">
                                                                                                <h4 class="text-dark">
                                                                                                    Qty:
                                                                                                </h4>
                                                                                                <span
                                                                                                    class="text-capitalize">
                                                                                                    {{ $product->quantity }}
                                                                                                </span>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                        <hr>
                                                                        <h4 class="text-info">Operations</h4>
                                                                        <hr>
                                                                        <form method="post" id="priceSettingForm"
                                                                            onsubmit="return submitPricing();"
                                                                            action="{{ route('manager.sent.request.to.addprice') }}">
                                                                            @csrf
                                                                            @foreach ($request->unavailableproducts as $unavail)
                                                                                <h5>{{ Oneinitials($unavail->eye) }}
                                                                                </h5>
                                                                                <input type="hidden" name="invoiceID"
                                                                                    value="{{ $request->id }}" />
                                                                                <input type="hidden" name="prodId[]"
                                                                                    value="{{ $unavail->id }}" />
                                                                                <div class="row">
                                                                                    <div class="col-sm-12 col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label for="inputlname"
                                                                                                class="control-label col-form-label">Cost</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                name="cost[]"
                                                                                                id="inputlname"
                                                                                                placeholder="cost"
                                                                                                required>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-12 col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label for="inputname"
                                                                                                class="control-label col-form-label">
                                                                                                Price</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                id="inputname"
                                                                                                name="price[]"
                                                                                                placeholder="price"
                                                                                                required>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-12 col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label for="inputlname"
                                                                                                class="control-label col-form-label">Location</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                id="inputlname"
                                                                                                name="location[]"
                                                                                                placeholder="Location">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-12 col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label for="inputlname"
                                                                                                class="control-label col-form-label">Supplier</label>
                                                                                            <select class="form-control"
                                                                                                id="inputlname"
                                                                                                name="supplier[]">
                                                                                                <option value="">
                                                                                                    select
                                                                                                    supplier
                                                                                                </option>
                                                                                                @foreach ($suppliers as $supplier)
                                                                                                    <option
                                                                                                        value="{{ $supplier->id }}">
                                                                                                        {{ $supplier->name }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                    </div>
                                                                    <div
                                                                        class="modal-footer d-flex justify-content-between">
                                                                        <button type="button"
                                                                            class="btn btn-danger waves-effect text-left"
                                                                            data-dismiss="modal">
                                                                            Close
                                                                        </button>
                                                                        <button type="button"
                                                                            onclick="printModal({{ $key }})"
                                                                            class="btn btn-success waves-effect text-left"
                                                                            id="print">Print</button>
                                                                        <button
                                                                            class="btn btn-info waves-effect text-left">
                                                                            Set Price
                                                                        </button>
                                                                    </div>
                                                                    </form>
                                                                </div>
                                                                <!-- /.modal-content -->
                                                            </div>
                                                            <!-- /.modal-dialog -->
                                                        </div>
                                                    @endforeach

                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Priced --}}
            <div class="tab-pane" id="priced" role="tabpanel">
                <div class="tab-pane  p-20" id="profile2" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    @if ($requests_priced->isEmpty())
                                        <div class="alert alert-warning alert-rounded col-lg-7 col-md-9 col-sm-12">
                                            <b>Warning! </b> Nothing to show here
                                            </button>
                                        </div>
                                    @else
                                        <button onclick="exportAll('xls','Priced Lens');"
                                            class="btn btn-success float-right mb-3">
                                            <i class="fa fa-cloud-download-alt"></i>
                                            Excel
                                        </button>
                                        <div class="table-responsive mt-4">
                                            <table id="priced-table" class="table table-striped table-bordered nowrap"
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th> <input type="checkbox" onclick="checkUncheckrequestId(this)"> </th>
                                                        <th>Request # </th>
                                                        <th>Patient Name</th>
                                                        <th>Description</th>
                                                        <th>Right Eye</th>
                                                        <th>Left Eye</th>
                                                        <th>Request Date</th>
                                                        <th>Request Age</th>
                                                        <th>Cost</th>
                                                        <th>Payment</th>
                                                    </tr>
                                                </thead>

                                                <form method="post"
                                                    action="{{ route('manager.sent.request.send.to.supplier') }}">

                                                    @csrf
                                                    <tbody>
                                                        @foreach ($requests_priced as $key => $request)
                                                            <tr>
                                                                <td>
                                                                    @if ($request->status == 'Confirmed')
                                                                        <input type='checkbox' name="requestId[]"
                                                                            value={{ $request->id }} />
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <a href="#!">
                                                                        Request
                                                                        #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    @if ($request->client_id != null)
                                                                        {{$request->client->name}}
                                                                    @else
                                                                        @if ($request->hospital_name!=null)
                                                                            [{{$request->cloud_id}}] {{$request->hospital_name}}
                                                                        @else
                                                                            {{$request->client_name}}
                                                                        @endif
                                                                    @endif
                                                                    {{-- {{ $request->client_id != null ? $request->client->name : $request->client_name }} --}}
                                                                </td>

                                                                @php
                                                                    $availability = true;
                                                                    $description = null;
                                                                    $right_len = $request->unavailableproducts->where('eye', 'right')->first();
                                                                    if (!$right_len) {
                                                                        $right_len = $request->soldproduct->where('eye', 'right')->first();
                                                                        if ($right_len==null) {
                                                                            continue;
                                                                        }
                                                                        $right_len = $powers->where('product_id',$right_len->product_id)->first();
                                                                        $availability = false;
                                                                    }

                                                                    if ($availability == true) {
                                                                        $type = $lens_type
                                                                            ->where('id', $right_len->type_id)
                                                                            ->pluck('name')
                                                                            ->first();

                                                                        $indx = $index
                                                                            ->where('id', $right_len->index_id)
                                                                            ->pluck('name')
                                                                            ->first();

                                                                        $ct = $coatings
                                                                            ->where('id', $right_len->coating_id)
                                                                            ->pluck('name')
                                                                            ->first();

                                                                        $chrm = $chromatics
                                                                            ->where('id', $right_len->chromatic_id)
                                                                            ->pluck('name')
                                                                            ->first();
                                                                    } else {
                                                                        if ($right_len) {
                                                                            $description = $products
                                                                            ->where('id', $right_len->product_id)
                                                                            ->pluck('description')
                                                                            ->first();
                                                                        }
                                                                    }

                                                                    $left_len = $request->unavailableproducts->where('eye', 'left')->first();
                                                                    if (!$left_len) {
                                                                        $left_len = $request->soldproduct->where('eye', 'left')->first();
                                                                        $left_len = $powers->where('product_id',$right_len->product_id)->first();
                                                                    }
                                                                @endphp
                                                                <td>
                                                                    @if ($availability)
                                                                        {{ initials($type) }} {{ $chrm }}
                                                                        {{ $ct }} {{ $indx }}
                                                                    @else
                                                                        {{ $description }}
                                                                    @endif
                                                                </td>
                                                                <td>

                                                                    @if ($right_len)
                                                                        @if ($availability)
                                                                            <span>
                                                                                {{ format_values($right_len->sphere) }}
                                                                                /
                                                                                {{ format_values($right_len->cylinder) }}
                                                                                *{{ format_values($right_len->axis) }}
                                                                                {{ format_values($right_len->addition) }}
                                                                            </span>
                                                                        @else
                                                                            <span>
                                                                                {{ format_values($right_len->sphere) }}
                                                                                /
                                                                                {{ format_values($right_len->cylinder) }}
                                                                                *{{ format_values($right_len->axis) }}
                                                                                {{ format_values($right_len->add) }}
                                                                            </span>
                                                                        @endif
                                                                    @else
                                                                        <span>-</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($left_len)
                                                                        @if ($availability)
                                                                            {{ format_values($left_len->sphere) }}
                                                                            /
                                                                            {{ format_values($left_len->cylinder) }}
                                                                            *{{ format_values($left_len->axis) }}
                                                                            {{ format_values($left_len->addition) }}
                                                                        @else
                                                                            {{ format_values($left_len->sphere) }}
                                                                            /
                                                                            {{ format_values($left_len->cylinder) }}
                                                                            *{{ format_values($left_len->axis) }}
                                                                            {{ format_values($left_len->add) }}
                                                                        @endif
                                                                    @else
                                                                        <span class="text-center">-</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    {{ date('Y-m-d H:i', strtotime($request->created_at)) }}
                                                                </td>
                                                                <td>
                                                                    {{ \Carbon\Carbon::parse($request->created_at)->diffForHumans() }}
                                                                </td>
                                                                <td>
                                                                    {{ $request->cost }}
                                                                </td>
                                                                <td class="text-start">
                                                                    <span @class([
                                                                        'text-info' => $request->status == 'priced',
                                                                        'text-success' => $request->status == 'Confirmed',
                                                                    ])>
                                                                        {{ $request->status }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                            </table>
                                            <hr>
                                            <button class="btn btn-primary">Order from Supplier</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- po sent --}}
            <div class="tab-pane" id="po-sent" role="tabpanel">
                <div class="tab-pane  p-20" id="profile2" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    @if ($requests_supplier->isEmpty())
                                        <div class="alert alert-warning alert-rounded col-lg-7 col-md-9 col-sm-12">
                                            <b>Warning! </b> Nothing to show here
                                            </button>
                                        </div>
                                    @else
                                        <div class="table-responsive mt-4">
                                            <table id="priced-table" class="table table-striped table-bordered nowrap"
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Request # </th>
                                                        <th>Patient Name</th>
                                                        <th>Order Date</th>
                                                        <th>Order Age</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>

                                                <form method="post"
                                                    action="{{ route('manager.sent.request.send.to.lab') }}">

                                                    @csrf
                                                    <tbody>
                                                        @foreach ($requests_supplier as $key => $request)
                                                            <tr>
                                                                <td>
                                                                    <input type='checkbox' name="requestId[]"
                                                                        value={{ $request->id }} />
                                                                </td>
                                                                <td>
                                                                    <a href="#!" data-toggle="modal"
                                                                    data-target="#sent-sent-{{ $key }}-detail">
                                                                        Request
                                                                        #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    @if ($request->client_id != null)
                                                                        {{$request->client->name}}
                                                                    @else
                                                                        @if ($request->hospital_name!=null)
                                                                            [{{$request->cloud_id}}] {{$request->hospital_name}}
                                                                        @else
                                                                            {{$request->client_name}}
                                                                        @endif
                                                                    @endif
                                                                    {{-- {{ $request->client_id != null ? $request->client->name : $request->client_name }} --}}
                                                                </td>
                                                                <td>
                                                                    {{ date('Y-m-d H:i', strtotime($request->created_at)) }}
                                                                </td>
                                                                <td>
                                                                    {{ \Carbon\Carbon::parse($request->created_at)->diffForHumans() }}
                                                                </td>
                                                                <td class="text-start">
                                                                    <span @class([
                                                                        'text-success' => $request->status == 'sent to supplier',
                                                                    ])>
                                                                        {{ $request->status }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                            </table>

                                            <hr>
                                            <button class="btn btn-success">Send to lab</button>
                                            </form>
                                        </div>

                                        @foreach ($requests_lab as $key => $request)
                                            {{-- modal --}}

                                            <div class="modal fade bs-example-modal-lg"
                                                id="sent-sent-{{ $key }}-detail" tabindex="-1"
                                                role="dialog" aria-labelledby="myLargeModalLabel"
                                                aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                                    <div class="modal-content"
                                                        id="request-{{ $key }}-contents">
                                                        <div class="modal-header">
                                                            <div>
                                                                <h4 class="modal-title text-info">
                                                                    @if ($request->client_id != null)
                                                                        {{$request->client->name}}
                                                                    @else
                                                                        @if ($request->hospital_name!=null)
                                                                        [{{$request->cloud_id}}] {{$request->hospital_name}}
                                                                        @else
                                                                            {{$request->client_name}}
                                                                        @endif
                                                                    @endif
                                                                    {{-- {{ $request->client_id != null ? $request->client->name : $request->client_name }} --}}
                                                                </h4>
                                                                <br>

                                                                <h4 class="modal-title"
                                                                    id="content-detail-{{ $key }}">
                                                                    Request
                                                                    #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                </h4>
                                                            </div>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal"
                                                                aria-hidden="true">×</button>
                                                        </div>

                                                        <div class="modal-body" id="printable">
                                                            <h4 class="text-info">Lens</h4>
                                                            <hr>

                                                            <table style="width:100%" class="">
                                                                <tr class="mb-4">
                                                                    <th>Eye</th>
                                                                    <th>Description</th>
                                                                    <th>Power</th>
                                                                    <th>Location</th>
                                                                    <th>Mono PD</th>
                                                                    <th>Segment H</th>
                                                                </tr>
                                                                <tbody>
                                                                    {{-- looping through available products --}}
                                                                    @if ($request->soldproduct->isNotEmpty())
                                                                        @foreach ($request->soldproduct as $productsold)
                                                                            @php
                                                                                $invoice_product = $products->where('id', $productsold->product_id)->first();
                                                                            @endphp

                                                                            @if ($invoice_product->category_id == 1)
                                                                                <tr>
                                                                                    <td>
                                                                                        {{ $productsold->eye == null ? '' : Oneinitials($productsold->eye) }}
                                                                                    </td>
                                                                                    <td>
                                                                                        {{ $invoice_product->description }}
                                                                                    </td>
                                                                                    <td>
                                                                                        @if (initials($invoice_product->product_name) == 'SV')
                                                                                            <span>{{ $invoice_product->power->sphere }}
                                                                                                /
                                                                                                {{ $invoice_product->power->cylinder }}</span>
                                                                                        @else
                                                                                            <span>{{ $invoice_product->power->sphere }}
                                                                                                /
                                                                                                {{ $invoice_product->power->cylinder }}
                                                                                                *{{ $invoice_product->power->axis }}
                                                                                                {{ $invoice_product->power->add }}</span>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>
                                                                                        {{ $invoice_product->location == null ? '-' : $invoice_product->location }}
                                                                                    </td>
                                                                                    <td>
                                                                                        <span
                                                                                            class="text-capitalize">
                                                                                            {{ $productsold->mono_pd }}
                                                                                        </span>
                                                                                    </td>
                                                                                    <td>
                                                                                        <span
                                                                                            class="text-capitalize">
                                                                                            {{ $productsold->segment_h }}
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif

                                                                    {{-- looping through unavailable products --}}
                                                                    @if (!$request->unavailableproducts()->exists())
                                                                        @foreach ($request->unavailableproducts as $productsold)
                                                                            @php
                                                                                $availability = true;
                                                                                $descripition = null;
                                                                                $right_len = $request->unavailableproducts->where('eye', 'right')->first();
                                                                                if (!$right_len) {
                                                                                    $right_len = $request->soldproduct->where('eye', 'right')->first();
                                                                                    $availability = false;
                                                                                }
                                                                                if ($availability == true) {
                                                                                    $type = $lens_type
                                                                                        ->where('id', $right_len->type_id)
                                                                                        ->pluck('name')
                                                                                        ->first();

                                                                                    $indx = $index
                                                                                        ->where('id', $right_len->index_id)
                                                                                        ->pluck('name')
                                                                                        ->first();

                                                                                    $ct = $coatings
                                                                                        ->where('id', $right_len->coating_id)
                                                                                        ->pluck('name')
                                                                                        ->first();

                                                                                    $chrm = $chromatics
                                                                                        ->where('id', $right_len->chromatic_id)
                                                                                        ->pluck('name')
                                                                                        ->first();
                                                                                } else {
                                                                                    $description = $products
                                                                                        ->where('id', $right_len->product_id)
                                                                                        ->pluck('description')
                                                                                        ->first();
                                                                                }

                                                                                $left_len = $request->unavailableproducts->where('eye', 'left')->first();
                                                                                if (!$left_len) {
                                                                                    $left_len = $request->soldproduct->where('eye', 'left')->first();
                                                                                }
                                                                            @endphp
                                                                            <tr>
                                                                                <td>
                                                                                    {{ $productsold->eye == null ? '' : Oneinitials($productsold->eye) }}
                                                                                </td>
                                                                                <td>
                                                                                    @if ($availability)
                                                                                        {{ initials($type) }}
                                                                                        {{ $chrm }}
                                                                                        {{ $ct }}
                                                                                        {{ $indx }}
                                                                                    @else
                                                                                        {{ $description }}
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    {{ format_values($productsold->sphere) }}
                                                                                    /
                                                                                    {{ format_values($productsold->cylinder) }}
                                                                                    *{{ format_values($productsold->axis) }}
                                                                                    {{ format_values($productsold->addition) }}
                                                                                </td>
                                                                                <td>
                                                                                    {{ $productsold->location == null ? '-' : $productsold->location }}
                                                                                </td>
                                                                                <td>
                                                                                    {{ $productsold->mono_pd }}
                                                                                </td>
                                                                                <td>
                                                                                    <span
                                                                                        class="text-capitalize">
                                                                                        {{ $productsold->segment_h }}
                                                                                    </span>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @endif

                                                                </tbody>
                                                            </table>

                                                            {{-- for frame --}}
                                                            <hr>
                                                            <h4 class="text-info">Frame</h4>
                                                            <hr>
                                                            @if ($products)
                                                                @foreach ($request->soldproduct as $product)
                                                                    @php
                                                                        $invoice_product = $products->where('id', $product->product_id)->first();
                                                                    @endphp
                                                                    @if ($invoice_product->category_id == 2)
                                                                        <div class="row mb-2">
                                                                            <div class="col-6">
                                                                                <span class="text-capitalize">
                                                                                    {{ $invoice_product->product_name }}
                                                                                    -
                                                                                    {{ $invoice_product->description }}
                                                                                </span>
                                                                            </div>
                                                                            <div class="col-3">
                                                                                <span class="text-capitalize">
                                                                                    <h4>Location:
                                                                                    </h4>
                                                                                </span>
                                                                            </div>
                                                                            <div class="col-3">
                                                                                <span class="text-capitalize">
                                                                                    {{ $product->location == null ? '-' : $product->location }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @endif

                                                            <hr>
                                                            <h4 class="text-info">Accessories &
                                                                Others
                                                            </h4>
                                                            <hr>
                                                            {{-- for accessories --}}
                                                            @if ($products)
                                                                @foreach ($request->soldproduct as $product)
                                                                    @php
                                                                        $invoice_product = $products->where('id', $product->product_id)->first();
                                                                    @endphp

                                                                    @if ($invoice_product->category_id != 2 && $invoice_product->category_id != 1)
                                                                        <div class="row mb-2">
                                                                            <div class="col-3">
                                                                                <span class="text-capitalize">
                                                                                    {{ $invoice_product->product_name }}
                                                                                    -
                                                                                    {{ $invoice_product->description }}
                                                                                </span>
                                                                            </div>
                                                                            <div class="col-3">
                                                                                <span class="text-capitalize">
                                                                                    <h4 class="text-dark">
                                                                                        Location:
                                                                                    </h4>
                                                                                </span>
                                                                            </div>
                                                                            <div class="col-2">
                                                                                <span class="text-capitalize">
                                                                                    {{ $product->location == null ? '-' : $product->location }}
                                                                                </span>
                                                                            </div>
                                                                            <div class="col-2 ">
                                                                                <span
                                                                                    class="text-capitalize d-flex justify-content-around items-center">
                                                                                    <h4 class="text-dark">
                                                                                        Qty:
                                                                                    </h4>
                                                                                    <span
                                                                                        class="text-capitalize">
                                                                                        {{ $product->quantity }}
                                                                                    </span>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div
                                                            class="modal-footer d-flex justify-content-between">
                                                            <button type="button"
                                                                class="btn btn-danger waves-effect text-left"
                                                                data-dismiss="modal">
                                                                Close
                                                            </button>
                                                            <button type="button"
                                                                onclick="printModal({{ $key }})"
                                                                class="btn btn-success waves-effect text-left"
                                                                id="print">Print</button>
                                                            {{-- <button
                                                                class="btn btn-info waves-effect text-left">
                                                                Set Price
                                                            </button> --}}
                                                        </div>
                                                        </form>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- sent to lab --}}
            <div class="tab-pane" id="provided-to-lab" role="tabpanel">
                <div class="tab-pane  p-20" id="profile2" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    @if ($requests_lab->isEmpty())
                                        <div class="alert alert-warning alert-rounded col-lg-7 col-md-9 col-sm-12">
                                            <b>Warning! </b> Nothing to show here
                                            </button>
                                        </div>
                                    @else
                                        <div class="table-responsive mt-4">
                                            <table id="zero_config" class="table table-striped table-bordered nowrap"
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Request # </th>
                                                        <th>Patient Name</th>
                                                        <th>Request Date</th>
                                                        <th>Request Age</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ($requests_lab as $key => $request)
                                                        <tr>
                                                            <td>
                                                                {{ $key + 1 }}
                                                            </td>
                                                            <td>
                                                                <a href="#!" data-toggle="modal"
                                                                data-target="#sent-to-lab-{{ $key }}-detail">
                                                                    Request
                                                                    #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                @if ($request->client_id != null)
                                                                    {{$request->client->name}}
                                                                @else
                                                                    @if ($request->hospital_name!=null)
                                                                        [{{$request->cloud_id}}] {{$request->hospital_name}}
                                                                    @else
                                                                        {{$request->client_name}}
                                                                    @endif
                                                                @endif
                                                                {{-- {{ $request->client_id != null ? $request->client->name : $request->client_name }} --}}
                                                            </td>
                                                            <td>
                                                                {{ date('Y-m-d H:i', strtotime($request->created_at)) }}
                                                            </td>
                                                            <td>
                                                                {{ \Carbon\Carbon::parse($request->created_at)->diffForHumans() }}
                                                            </td>
                                                            <td class="text-start">
                                                                <span @class([
                                                                    'text-warning' => $request->status == 'requested',
                                                                ])>
                                                                    {{ $request->status }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                            @foreach ($requests_lab as $key => $request)
                                            {{-- modal --}}

                                                <div class="modal fade bs-example-modal-lg"
                                                    id="sent-to-lab-{{ $key }}-detail" tabindex="-1"
                                                    role="dialog" aria-labelledby="myLargeModalLabel"
                                                    aria-hidden="true" style="display: none;">
                                                    <div class="modal-dialog modal-xl modal-dialog-centered">
                                                        <div class="modal-content"
                                                            id="request-{{ $key }}-contents">
                                                            <div class="modal-header">
                                                                <div>
                                                                    <h4 class="modal-title text-info">
                                                                        @if ($request->client_id != null)
                                                                            {{$request->client->name}}
                                                                        @else
                                                                            @if ($request->hospital_name!=null)
                                                                            [{{$request->cloud_id}}] {{$request->hospital_name}}
                                                                            @else
                                                                                {{$request->client_name}}
                                                                            @endif
                                                                        @endif
                                                                        {{-- {{ $request->client_id != null ? $request->client->name : $request->client_name }} --}}
                                                                    </h4>
                                                                    <br>

                                                                    <h4 class="modal-title"
                                                                        id="content-detail-{{ $key }}">
                                                                        Request
                                                                        #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                    </h4>
                                                                </div>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal"
                                                                    aria-hidden="true">×</button>
                                                            </div>

                                                            <div class="modal-body" id="printable">
                                                                <h4 class="text-info">Lens</h4>
                                                                <hr>

                                                                <table style="width:100%" class="">
                                                                    <tr class="mb-4">
                                                                        <th>Eye</th>
                                                                        <th>Description</th>
                                                                        <th>Power</th>
                                                                        <th>Location</th>
                                                                        <th>Mono PD</th>
                                                                        <th>Segment H</th>
                                                                    </tr>
                                                                    <tbody>
                                                                        {{-- looping through available products --}}
                                                                        @if ($request->soldproduct->isNotEmpty())
                                                                            @foreach ($request->soldproduct as $productsold)
                                                                                @php
                                                                                    $invoice_product = $products->where('id', $productsold->product_id)->first();
                                                                                @endphp

                                                                                @if ($invoice_product->category_id == 1)
                                                                                    <tr>
                                                                                        <td>
                                                                                            {{ $productsold->eye == null ? '' : Oneinitials($productsold->eye) }}
                                                                                        </td>
                                                                                        <td>
                                                                                            {{ $invoice_product->description }}
                                                                                        </td>
                                                                                        <td>
                                                                                            @if (initials($invoice_product->product_name) == 'SV')
                                                                                                <span>{{ $invoice_product->power->sphere }}
                                                                                                    /
                                                                                                    {{ $invoice_product->power->cylinder }}</span>
                                                                                            @else
                                                                                                <span>{{ $invoice_product->power->sphere }}
                                                                                                    /
                                                                                                    {{ $invoice_product->power->cylinder }}
                                                                                                    *{{ $invoice_product->power->axis }}
                                                                                                    {{ $invoice_product->power->add }}</span>
                                                                                            @endif
                                                                                        </td>
                                                                                        <td>
                                                                                            {{ $invoice_product->location == null ? '-' : $invoice_product->location }}
                                                                                        </td>
                                                                                        <td>
                                                                                            <span
                                                                                                class="text-capitalize">
                                                                                                {{ $productsold->mono_pd }}
                                                                                            </span>
                                                                                        </td>
                                                                                        <td>
                                                                                            <span
                                                                                                class="text-capitalize">
                                                                                                {{ $productsold->segment_h }}
                                                                                            </span>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif

                                                                        {{-- looping through unavailable products --}}
                                                                        @if (!$request->unavailableproducts()->exists())
                                                                            @foreach ($request->unavailableproducts as $productsold)
                                                                                @php
                                                                                    $availability = true;
                                                                                    $descripition = null;
                                                                                    $right_len = $request->unavailableproducts->where('eye', 'right')->first();
                                                                                    if (!$right_len) {
                                                                                        $right_len = $request->soldproduct->where('eye', 'right')->first();
                                                                                        $availability = false;
                                                                                    }
                                                                                    if ($availability == true) {
                                                                                        $type = $lens_type
                                                                                            ->where('id', $right_len->type_id)
                                                                                            ->pluck('name')
                                                                                            ->first();

                                                                                        $indx = $index
                                                                                            ->where('id', $right_len->index_id)
                                                                                            ->pluck('name')
                                                                                            ->first();

                                                                                        $ct = $coatings
                                                                                            ->where('id', $right_len->coating_id)
                                                                                            ->pluck('name')
                                                                                            ->first();

                                                                                        $chrm = $chromatics
                                                                                            ->where('id', $right_len->chromatic_id)
                                                                                            ->pluck('name')
                                                                                            ->first();
                                                                                    } else {
                                                                                        $description = $products
                                                                                            ->where('id', $right_len->product_id)
                                                                                            ->pluck('description')
                                                                                            ->first();
                                                                                    }

                                                                                    $left_len = $request->unavailableproducts->where('eye', 'left')->first();
                                                                                    if (!$left_len) {
                                                                                        $left_len = $request->soldproduct->where('eye', 'left')->first();
                                                                                    }
                                                                                @endphp
                                                                                <tr>
                                                                                    <td>
                                                                                        {{ $productsold->eye == null ? '' : Oneinitials($productsold->eye) }}
                                                                                    </td>
                                                                                    <td>
                                                                                        @if ($availability)
                                                                                            {{ initials($type) }}
                                                                                            {{ $chrm }}
                                                                                            {{ $ct }}
                                                                                            {{ $indx }}
                                                                                        @else
                                                                                            {{ $description }}
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>
                                                                                        {{ format_values($productsold->sphere) }}
                                                                                        /
                                                                                        {{ format_values($productsold->cylinder) }}
                                                                                        *{{ format_values($productsold->axis) }}
                                                                                        {{ format_values($productsold->addition) }}
                                                                                    </td>
                                                                                    <td>
                                                                                        {{ $productsold->location == null ? '-' : $productsold->location }}
                                                                                    </td>
                                                                                    <td>
                                                                                        {{ $productsold->mono_pd }}
                                                                                    </td>
                                                                                    <td>
                                                                                        <span
                                                                                            class="text-capitalize">
                                                                                            {{ $productsold->segment_h }}
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        @endif

                                                                    </tbody>
                                                                </table>

                                                                {{-- for frame --}}
                                                                <hr>
                                                                <h4 class="text-info">Frame</h4>
                                                                <hr>
                                                                @if ($products)
                                                                    @foreach ($request->soldproduct as $product)
                                                                        @php
                                                                            $invoice_product = $products->where('id', $product->product_id)->first();
                                                                        @endphp
                                                                        @if ($invoice_product->category_id == 2)
                                                                            <div class="row mb-2">
                                                                                <div class="col-6">
                                                                                    <span class="text-capitalize">
                                                                                        {{ $invoice_product->product_name }}
                                                                                        -
                                                                                        {{ $invoice_product->description }}
                                                                                    </span>
                                                                                </div>
                                                                                <div class="col-3">
                                                                                    <span class="text-capitalize">
                                                                                        <h4>Location:
                                                                                        </h4>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="col-3">
                                                                                    <span class="text-capitalize">
                                                                                        {{ $product->location == null ? '-' : $product->location }}
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @endif

                                                                <hr>
                                                                <h4 class="text-info">Accessories &
                                                                    Others
                                                                </h4>
                                                                <hr>
                                                                {{-- for accessories --}}
                                                                @if ($products)
                                                                    @foreach ($request->soldproduct as $product)
                                                                        @php
                                                                            $invoice_product = $products->where('id', $product->product_id)->first();
                                                                        @endphp

                                                                        @if ($invoice_product->category_id != 2 && $invoice_product->category_id != 1)
                                                                            <div class="row mb-2">
                                                                                <div class="col-3">
                                                                                    <span class="text-capitalize">
                                                                                        {{ $invoice_product->product_name }}
                                                                                        -
                                                                                        {{ $invoice_product->description }}
                                                                                    </span>
                                                                                </div>
                                                                                <div class="col-3">
                                                                                    <span class="text-capitalize">
                                                                                        <h4 class="text-dark">
                                                                                            Location:
                                                                                        </h4>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="col-2">
                                                                                    <span class="text-capitalize">
                                                                                        {{ $product->location == null ? '-' : $product->location }}
                                                                                    </span>
                                                                                </div>
                                                                                <div class="col-2 ">
                                                                                    <span
                                                                                        class="text-capitalize d-flex justify-content-around items-center">
                                                                                        <h4 class="text-dark">
                                                                                            Qty:
                                                                                        </h4>
                                                                                        <span
                                                                                            class="text-capitalize">
                                                                                            {{ $product->quantity }}
                                                                                        </span>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div
                                                                class="modal-footer d-flex justify-content-between">
                                                                <button type="button"
                                                                    class="btn btn-danger waves-effect text-left"
                                                                    data-dismiss="modal">
                                                                    Close
                                                                </button>
                                                                <button type="button"
                                                                    onclick="printModal({{ $key }})"
                                                                    class="btn btn-success waves-effect text-left"
                                                                    id="print">Print</button>
                                                                {{-- <button
                                                                    class="btn btn-info waves-effect text-left">
                                                                    Set Price
                                                                </button> --}}
                                                            </div>
                                                            </form>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- looping to add modals on the page separately from the table --}}

        </div>

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/samplepages/jquery.PrintArea.js') }}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/export.js') }}"></script>

    <script>
        function checkUncheckpriced(checkBox) {

            get = document.getElementsByName('requestId[]');

            for(var i=0; i<get.length; i++) {

            get[i].checked = checkBox.checked;}

        }
        function checkUncheckrequestId(checkBox) {

            get = document.getElementsByName('requestId[]');

            for(var i=0; i<get.length; i++) {

            get[i].checked = checkBox.checked;}

        }

        function exportAll(type, tableName) {

            $('#priced-table').tableExport({
                filename: tableName + '_%DD%-%mm%-%YY%',
                format: type
            });
        }

        function exportOutOfStock(type, tableName) {

            $('#outOfStock').tableExport({
                filename: tableName + '_%DD%-%mm%-%YY%',
                format: type
            });
        }

        function printModal(key) {
            var mode = 'iframe'; //popup
            var close = mode == "popup";
            var options = {
                mode: mode,
                popClose: close
            };
            $('.modal-footer').hide();
            $("#request-" + key + "-contents").printArea(options);
        };
    </script>
@endpush
