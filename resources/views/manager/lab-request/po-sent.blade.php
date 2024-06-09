@extends('manager.includes.app')


@section('title', 'Dashboard - Product')

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
@section('current', 'Lab invoicess')
@section('page_name', 'List of Lab invoicess')
{{-- === End of breadcumb == --}}

@section('content')

    <div class="container-fluid">
        <!-- Sales chart -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h4 class="card-title">
                                Requests
                                <span class="badge badge-danger badge-pill ml-2">
                                    {{ number_format(count($requests_supplier)+count($requests_supplier_count)) }}</h4>
                                </span>
                        </div>
                        {{-- ============================== --}}
                        @include('manager.includes.layouts.message')
                        {{-- =============================== --}}

                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content">
            {{-- requested --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills mt-4 mb-4">
                                <li class="nav-item">
                                    <a href="#complete-orders" class="nav-link active" data-toggle="tab"
                                        aria-expanded="false">
                                        Internal Po Sent
                                        <span class="badge badge-danger badge-pill ml-2">
                                            {{count($requests_supplier)}}
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="#out-complete-orders" class="nav-link" data-toggle="tab"
                                        aria-expanded="false">
                                        External Po Sent
                                        <span class="badge badge-danger badge-pill ml-2">
                                            {{count($requests_supplier_count)}}
                                        </span>
                                    </a>
                                </li>
                            </ul>
                            <hr>
                            <div class="tab-content br-n pn">
                                {{-- completed and in stock --}}
                                <div id="complete-orders" class="tab-pane active">
                                    @if (count($requests_supplier)>0)
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        @if (count($requests_supplier)<=0)
                                                            <div class="alert alert-warning alert-rounded col-lg-7 col-md-9 col-sm-12">
                                                                <b>Warning! </b> Nothing to show here
                                                                </button>
                                                            </div>
                                                        @else
                                                            <div class="table-responsive mt-4">
                                                                <button onclick="exportOutOfStock('xls','PO Sent');"
                                                                    class="btn btn-success float-right mb-3">
                                                                    <i class="fa fa-cloud-download-alt"></i>
                                                                    Excel
                                                                </button>
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

                                                                                <div class="modal fade bs-example-modal-lg" id="proddd-{{ $key }}-detail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                                                                    @php
                                                                                        $isOutOfStock='no';
                                                                                    @endphp
                                                                                    <div class="modal-dialog modal-xl d-print-inline">
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
                                                                                                    </h4>
                                                                                                    <br>

                                                                                                    <h4 class="modal-title" id="content-detail-{{ $key }}">
                                                                                                        Request #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                                                    </h4>
                                                                                                </div>
                                                                                                <button type="button" class="close d-print-none" data-dismiss="modal" aria-hidden="true">×</button>
                                                                                                <div class="pull-left mb-4 d-none d-print-block">
                                                                                                    <address>

                                                                                                        <img src="{{ asset('documents/logos/' . getuserCompanyInfo()->logo) }}" alt=""
                                                                                                            width="300px">
                                                                                                        <p class="text-muted m-l-5">
                                                                                                            <strong class="text-black-50">TIN Number:</strong>
                                                                                                            {{ getuserCompanyInfo()->company_tin_number }}
                                                                                                            <br />
                                                                                                            <strong class="text-black-50">Phone Number:</strong>
                                                                                                            {{ getuserCompanyInfo()->company_phone }}
                                                                                                            <br />
                                                                                                            <strong class="text-black-50">Email:</strong>
                                                                                                            {{ getuserCompanyInfo()->company_email }}
                                                                                                        </p>
                                                                                                    </address>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="modal-body" id="printable">
                                                                                                <h4 class="text-info">Lens</h4>
                                                                                                <hr>
                                                                                                @foreach ($request->soldproduct as $product)
                                                                                                    @php
                                                                                                        $invoice_product = $product->product;
                                                                                                    @endphp

                                                                                                    @if ($invoice_product->category_id == 1)
                                                                                                        <div class="row mb-2">
                                                                                                            <div class="col-1">
                                                                                                                <h4 class="text-capitalize">
                                                                                                                    {{ $product->eye == null ? '' : Oneinitials($product->eye) }}
                                                                                                                </h4>
                                                                                                            </div>
                                                                                                            <div class="col-3">
                                                                                                                <span>
                                                                                                                    {{ $invoice_product->description }}
                                                                                                                </span>
                                                                                                            </div>
                                                                                                            <div class="col-2">
                                                                                                                <span>{{ $invoice_product->power->sphere }}
                                                                                                                    /
                                                                                                                    {{ $invoice_product->power->cylinder }}
                                                                                                                <span class="text-primary"> *{{ $product->axis ?? 0}} </span>
                                                                                                                    {{ $invoice_product->power->add }}
                                                                                                                </span>
                                                                                                            </div>
                                                                                                            <div class="col-2 row">
                                                                                                                <span>
                                                                                                                    <h6>Location: </h6>
                                                                                                                </span>
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
                                                                                                @foreach ($request->unavailableproducts as $product)
                                                                                                    @php
                                                                                                        $invoice_product = $product->product;


                                                                                                    @endphp

                                                                                                    {{-- for lens --}}

                                                                                                    <div class="row mb-2">
                                                                                                        <div class="col-1">
                                                                                                            <h4 class="text-capitalize">
                                                                                                                {{ $product->eye == null ? '' : Oneinitials($product->eye) }}
                                                                                                            </h4>
                                                                                                        </div>
                                                                                                        <div class="col-3">
                                                                                                            <span>
                                                                                                                {{ initials($product->type->name)=='BT'?'Bifocal Round Top':initials($product->type->name) }} {{ $product->uchromatic->name }} {{ $product->coating->name }} {{ $product->uindex->name }}
                                                                                                            </span>
                                                                                                        </div>
                                                                                                        <div class="col-2">
                                                                                                                <span>{{ format_values($product->sphere) }}
                                                                                                                    /
                                                                                                                    {{ format_values($product->cylinder) }}
                                                                                                                    <span class="text-primary">*{{ $product->axis }}</span>
                                                                                                                    {{ $product->addition }}
                                                                                                                </span>
                                                                                                        </div>
                                                                                                        <div class="col-2 row">
                                                                                                            <span>
                                                                                                                <h6>Location: </h6>
                                                                                                            </span>
                                                                                                            {{ $product->product?->location == null ? '-' : $product->product?->location }}
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
                                                                                                @endforeach

                                                                                                {{-- for frame --}}
                                                                                                <hr>
                                                                                                <h4 class="text-info">Frame</h4>
                                                                                                <hr>
                                                                                                @if ($product)
                                                                                                    @foreach ($request->soldproduct as $product)
                                                                                                        @php
                                                                                                            $invoice_product = $product->product;
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
                                                                                                @if ($product)
                                                                                                    @foreach ($request->soldproduct as $product)
                                                                                                        @php
                                                                                                            $invoice_product = $product->product;
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
                                                                                            <div class="modal-footer d-flex justify-content-between d-print-none">
                                                                                                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">
                                                                                                    Close
                                                                                                </button>
                                                                                                <button type="button" onclick="printModal('proddd-{{ $key }}-detail')" class="btn btn-success waves-effect text-left"
                                                                                                    id="print">Print</button>
                                                                                            </div>
                                                                                        </div>
                                                                                        <!-- /.modal-content -->
                                                                                    </div>
                                                                                    <!-- /.modal-dialog -->
                                                                                </div>
                                                                            @endforeach
                                                                        </tbody>
                                                                </table>

                                                                <hr>
                                                                <button class="btn btn-success">Send to lab</button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                </div>

                                @if (is_null(getUserCompanyInfo()->is_vision_center) && getUserCompanyInfo()->can_supply=='1')
                                    <div id="out-complete-orders" class="tab-pane">

                                    @if (count($requests_supplier_count)>0)
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        @if (count($requests_supplier_count)<=0)
                                                            <div class="alert alert-warning alert-rounded col-lg-7 col-md-9 col-sm-12">
                                                                <b>Warning! </b> Nothing to show here
                                                                </button>
                                                            </div>
                                                        @else
                                                            <div class="table-responsive mt-4">
                                                                <button onclick="exportOutOfStock('xls','PO Sent');"
                                                                    class="btn btn-success float-right mb-3">
                                                                    <i class="fa fa-cloud-download-alt"></i>
                                                                    Excel
                                                                </button>
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

                                                                <form method="post" action="{{ route('manager.sent.request.send.to.lab') }}">

                                                                    @csrf
                                                                    <tbody>
                                                                        @foreach ($requests_supplier_count as $key => $request)
                                                                            <tr>
                                                                                <td>
                                                                                    <input type='checkbox' name="requestId[]"
                                                                                        value={{ $request->id }} />
                                                                                </td>
                                                                                <td>
                                                                                    <a href="#!" data-toggle="modal"
                                                                                    data-target="#extproddd-{{ $key }}-detail">
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

                                                                            {{-- modal --}}

                                                                            <div class="modal fade bs-example-modal-lg" id="extproddd-{{ $key }}-detail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                                                                @php
                                                                                    $isOutOfStock='no';
                                                                                @endphp
                                                                                <div class="modal-dialog modal-xl d-print-inline">
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
                                                                                                </h4>
                                                                                                <br>

                                                                                                <h4 class="modal-title" id="content-detail-{{ $key }}">
                                                                                                    Request #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                                                </h4>
                                                                                            </div>
                                                                                            <button type="button" class="close d-print-none" data-dismiss="modal" aria-hidden="true">×</button>

                                                                                            <div class="pull-left mb-4 d-none d-print-block">
                                                                                                <address>

                                                                                                    <img src="{{ asset('documents/logos/' . getuserCompanyInfo()->logo) }}" alt=""
                                                                                                        width="300px">
                                                                                                    <p class="text-muted m-l-5"><strong class="text-black-50">TIN Number:</strong>
                                                                                                        {{ getuserCompanyInfo()->company_tin_number }}
                                                                                                        <br /><strong class="text-black-50">Phone Number:</strong>
                                                                                                        {{ getuserCompanyInfo()->company_phone }}
                                                                                                        <br /><strong class="text-black-50">Email:</strong>
                                                                                                        {{ getuserCompanyInfo()->company_email }}
                                                                                                    </p>
                                                                                                </address>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="modal-body" id="printable">
                                                                                            <h4 class="text-info">Lens</h4>
                                                                                            <hr>
                                                                                            @foreach ($request->soldproduct as $product)
                                                                                                @php
                                                                                                    $invoice_product = $product->product;
                                                                                                @endphp

                                                                                                {{-- for lens --}}
                                                                                                @if ($invoice_product->category_id == 1)

                                                                                                    <div class="row mb-2">
                                                                                                        <div class="col-1">
                                                                                                            <h4 class="text-capitalize">
                                                                                                                {{ $product->eye == null ? '' : Oneinitials($product->eye) }}
                                                                                                            </h4>
                                                                                                        </div>
                                                                                                        <div class="col-3">
                                                                                                            <span>
                                                                                                                {{ $invoice_product->description }}
                                                                                                            </span>
                                                                                                        </div>
                                                                                                        <div class="col-2">
                                                                                                            <span>{{ $invoice_product->power->sphere }}
                                                                                                                /
                                                                                                                {{ $invoice_product->power->cylinder }}
                                                                                                                <span class='text-primary'>*{{ $product->axis??0 }}</span>
                                                                                                                {{ $invoice_product->power->add }}
                                                                                                            </span>
                                                                                                        </div>
                                                                                                        <div class="col-2 row">
                                                                                                            <span>
                                                                                                                <h6>Location: </h6>
                                                                                                            </span>
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
                                                                                            @foreach ($request->unavailableproducts as $product)
                                                                                                @php
                                                                                                    $invoice_product = $product;
                                                                                                @endphp

                                                                                                {{-- for lens --}}

                                                                                                <div class="row mb-2">
                                                                                                    <div class="col-1">
                                                                                                        <h4 class="text-capitalize">
                                                                                                            {{ $invoice_product->eye == null ? '' : Oneinitials($invoice_product->eye) }}
                                                                                                        </h4>
                                                                                                    </div>
                                                                                                    <div class="col-3">
                                                                                                        <span>
                                                                                                            {{ initials($invoice_product->type->name)=='BT'?'Bifocal Round Top':
                                                                                                            initials($invoice_product->type->name) }} 
                                                                                                            {{ $invoice_product->uchromatic->name }} 
                                                                                                            {{ $invoice_product->coating->name }} 
                                                                                                            {{ $invoice_product->uindex->name }} 
                                                                                                        </span>
                                                                                                    </div>
                                                                                                    <div class="col-2">
                                                                                                        <span>{{ format_values($invoice_product->sphere) }}
                                                                                                            /
                                                                                                            {{ format_values($invoice_product->cylinder) }}
                                                                                                            <span class='text-primary'>*{{ $product->axis ?? 0 }}</span>
                                                                                                            {{ $invoice_product->addition }}
                                                                                                        </span>
                                                                                                    </div>
                                                                                                    <div class="col-2 row">
                                                                                                        <span>
                                                                                                            <h6>Location: </h6>
                                                                                                        </span>
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
                                                                                                                {{ $invoice_product->mono_pd }}
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
                                                                                                                {{ $invoice_product->segment_h }}
                                                                                                            </span>
                                                                                                        </span>
                                                                                                    </div>
                                                                                                </div>
                                                                                            @endforeach

                                                                                            {{-- for frame --}}
                                                                                            <hr>
                                                                                            <h4 class="text-info">Frame</h4>
                                                                                            <hr>
                                                                                            @if ($product)
                                                                                                @foreach ($request->soldproduct as $product)
                                                                                                    @php
                                                                                                        $invoice_product = $product->product;
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
                                                                                            @if ($product)
                                                                                                @foreach ($request->soldproduct as $product)
                                                                                                    @php
                                                                                                        $invoice_product = $product->product;
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
                                                                                        <div class="modal-footer d-flex justify-content-between d-print-none">
                                                                                            <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">
                                                                                                Close
                                                                                            </button>
                                                                                            <button type="button" class="btn btn-success waves-effect text-left"
                                                                                                id="print" onclick="printModal('extproddd-{{ $key }}-detail')">Print</button>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- /.modal-content -->
                                                                                </div>
                                                                                <!-- /.modal-dialog -->
                                                                            </div>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>

                                                                <hr>
                                                                <button class="btn btn-success">Send to lab</button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/samplepages/jquery.PrintArea.js') }}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/export.js') }}"></script>

    <script>
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

            $('#priced-table').tableExport({
                filename: tableName + '_%DD%-%mm%-%YY%',
                format: type
            });
        }

        function printModal(name) {
            var mode = 'iframe'; //popup
            var close = mode == "popup";
            var options = {
                mode: mode,
                popClose: close
            };
            $('.modal-footer').hide();
            $("#"+name).printArea(options);
        };
    </script>
@endpush
