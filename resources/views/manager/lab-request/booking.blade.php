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
                            All Requested Products
                        </h4>
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
        <div class="tab-pane active" id="requested" role="tabpanel">
            <div class="tab-pane  p-20" id="profile2" role="tabpanel">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-pills mt-4 mb-4">
                                    <li class="nav-item">
                                        <a href="#incomplete-orders" class="nav-link active" data-toggle="tab" aria-expanded="false">
                                            Internal Booking(s)
                                            <span class="badge badge-danger badge-pill">{{number_format(count($bookings))}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#external-booking-orders" class="nav-link" data-toggle="tab" aria-expanded="false">
                                            External Booking(s)
                                            <span class="badge badge-danger badge-pill">{{number_format(count($bookings_out))}}</span>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content br-n pn">
                                    {{-- out of stock --}}
                                    <div id="incomplete-orders" class="tab-pane active">
                                        @if (count($bookings)>0)
                                        <div class="table-responsive mt-4">
                                            <button onclick="ExportToExcel('xlsx');" class="btn btn-success float-right mb-3">
                                                <i class="fa fa-cloud-download-alt"></i> Excel
                                            </button>
                                            <form method="post" action="{{ route('manager.sent.request.send.to.supplier') }}">
                                                <table id="outOfStock_internal" class="table table-striped table-bordered nowrap" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <input type="checkbox" onclick="checkUncheckrequestId(this)">
                                                            </th>
                                                            <th>Request # </th>
                                                            <th>Cloud ID</th>
                                                            <th>Patient Name</th>
                                                            {{-- <th>Source</th> --}}
                                                            <th>Request Date</th>
                                                            <th>Request Age</th>
                                                            <th>Description</th>
                                                            <th>Right Eye</th>
                                                            <th>Left Eye</th>
                                                            <th>Status</th>
                                                            <th>-</th>
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
                                                                        <a href="#!" data-toggle="modal" data-target="#proddd-{{ $request->id }}-detail">
                                                                            Request #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                        </a>
                                                                    </td>
                                                                    <td>
                                                                        @if ($request->client_id != null)
                                                                        -
                                                                        @else
                                                                        @if ($request->hospital_name!=null)
                                                                            {{$request->cloud_id ?? $request->transaction_id}}
                                                                        @else
                                                                        -
                                                                        @endif
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if ($request->client_id != null)
                                                                            {{$request->client->name}}
                                                                        @else
                                                                        @if ($request->hospital_name!=null)
                                                                        {{-- [{{$request->cloud_id}}] --}} {{$request->hospital_name}}
                                                                        @else
                                                                        {{$request->client_name}}
                                                                        @endif
                                                                        @endif
                                                                    </td>
                                                                    {{-- <td>
                                                                            {{ $request->supplier }}
                                                                    </td> --}}
                                                                    <td>
                                                                        {{ date('Y-m-d H:i', strtotime($request->created_at)) }}
                                                                    </td>
                                                                    <td>
                                                                        {{ \Carbon\Carbon::parse($request->created_at)->diffForHumans() }}
                                                                    </td>

                                                                    </td>
                                                                        @php
                                                                        $availability_right = true;
                                                                        $availability_left = true;
                                                                        $description = null;
                                                                        $right_len = $request->unavailableproducts->where('eye', 'right')->first();

                                                                        if (!$right_len) {
                                                                        $right_len = $request->soldproduct->where('eye', 'right')->first();
                                                                        if ($right_len==null) {
                                                                        continue;
                                                                        }
                                                                        // dd($right_len->product->power);
                                                                        $right_len = $right_len->product;
                                                                        $availability_right = false;
                                                                        }

                                                                        if ($availability_right == true) {
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
                                                                        $description = $right_len->description;
                                                                        }
                                                                        }

                                                                        // left eye checking
                                                                        $left_len = $request->unavailableproducts->where('eye', 'left')->first();

                                                                        if (!$left_len) {
                                                                        $left_len = $request->soldproduct->where('eye', 'left')->first();
                                                                        if ($left_len==null) {
                                                                        continue;
                                                                        }
                                                                        $left_len = $left_len->product;
                                                                        $availability_left = false;
                                                                        }

                                                                        if ($availability_left == true) {
                                                                        $type = $lens_type
                                                                        ->where('id', $left_len->type_id)
                                                                        ->pluck('name')
                                                                        ->first();

                                                                        $indx = $index
                                                                        ->where('id', $left_len->index_id)
                                                                        ->pluck('name')
                                                                        ->first();

                                                                        $ct = $coatings
                                                                        ->where('id', $left_len->coating_id)
                                                                        ->pluck('name')
                                                                        ->first();

                                                                        $chrm = $chromatics
                                                                        ->where('id', $left_len->chromatic_id)
                                                                        ->pluck('name')
                                                                        ->first();
                                                                        } else {
                                                                        if ($left_len) {
                                                                        $description = $left_len->description;
                                                                        }
                                                                        }
                                                                        @endphp
                                                                        <td>
                                                                            @if ($availability_right)
                                                                            {{ initials($type)=='BT'?'Bifocal Round Top':initials($type) }} {{ $chrm }}
                                                                            {{ $ct }} {{ $indx }}
                                                                            @else
                                                                            {{ $description }}
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if ($right_len)
                                                                                
                                                                                @if ($availability_right)
                                                                                    
                                                                                    <span>
                                                                                        {{ format_values($right_len->power->sphere) }}
                                                                                        /
                                                                                        {{ format_values($right_len->power->cylinder) }}
                                                                                        @if ( $request->soldproduct[0]->eye=='right')
                                                                                            <span class="text-primary">*{{ ($request->soldproduct[0]->axis) }}</span>
                                                                                        @endif
                                                                                        @if ( $request->soldproduct[1]->eye=='right')
                                                                                            <span class="text-primary">*{{ ($request->soldproduct[1]->axis) }}</span>
                                                                                        @endif
                                                                                        @if (initials($type)!='SV')
                                                                                            {{ format_values($right_len->addition) }}
                                                                                        @endif
                                                                                    </span>
                                                                                @else
                                                                                    <span>
                                                                                        {{ format_values($right_len->power->sphere) }}
                                                                                        /
                                                                                        {{ format_values($right_len->power->cylinder) }}
                                                                                        @if ( $request->soldproduct[0]->eye=='right')
                                                                                            <span class="text-primary">*{{ (int)($request->soldproduct[0]->axis) }}</span>
                                                                                        @endif
                                                                                        @if ( $request->soldproduct[1]->eye=='right')
                                                                                            <span class="text-primary">*{{ (int)($request->soldproduct[1]->axis) }}</span>
                                                                                        @endif
                                                                                        @if (initials($right_len->product_name)!='SV')
                                                                                            {{ format_values($right_len->power->add) }}
                                                                                        @endif
                                                                                    </span>
                                                                                @endif
                                                                            @else
                                                                            <span>-</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if ($left_len)
                                                                                @if ($availability_left)
                                                                                    {{ format_values($left_len->sphere) }}
                                                                                    /
                                                                                    {{ format_values($left_len->cylinder) }}
                                                                                    @if ( $request->soldproduct[0]->eye=='left')
                                                                                        <span class="text-primary">*{{ ($request->soldproduct[0]->axis) }}</span>
                                                                                    @endif
                                                                                    @if ( $request->soldproduct[1]->eye=='left')
                                                                                        <span class="text-primary">*{{ ($request->soldproduct[1]->axis) }}</span>
                                                                                    @endif
                                                                                    {{ format_values($left_len->addition) }}
                                                                                @else
                                                                                    {{ format_values($left_len->power->sphere) }}
                                                                                    /
                                                                                    {{ format_values($left_len->power->cylinder) }}
                                                                                    @if ( $request->soldproduct[0]->eye=='left')
                                                                                        <span class="text-primary">*{{ (int)($request->soldproduct[0]->axis) }}</span>
                                                                                    @endif
                                                                                    @if ( $request->soldproduct[1]->eye=='left')
                                                                                        <span class="text-primary">*{{ (int)($request->soldproduct[1]->axis) }}</span>
                                                                                    @endif
                                                                                    @if (initials($left_len->product_name)!='SV')
                                                                                        {{ format_values($left_len->power->add) }}
                                                                                    @endif
                                                                                @endif
                                                                            @else
                                                                            <span class="text-center">-</span>
                                                                            @endif
                                                                        </td>

                                                                        <td class="text-start">
                                                                            <span @class([ 'text-warning'=> $request->status == 'requested',
                                                                                ])>
                                                                                {{ $request->status }}
                                                                            </span>
                                                                        </td>
                                                                        <td>
                                                                            @if ($request->status=='booked')
                                                                            <a href="{{route('manager.lab.order.cancel',Crypt::encrypt($request->id))}}" class="text-danger" onclick="return confirm('Are you sure??')">Cancel Order</a>
                                                                            @else
                                                                            <center>-</center>
                                                                            @endif
                                                                        </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach

                                                        @foreach ($bookings as $request)
                                                            @if (count($request->unavailableproducts) <= 0) 
                                                                <div class="modal fade bs-example-modal-lg" id="proddd-{{ $request->id }}-detail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
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
                                                                                        [{{$request->cloud_id ?? $request->transaction_id}}] {{$request->hospital_name}}
                                                                                        @else
                                                                                        {{$request->client_name.' - '.$request->phone}}
                                                                                        @endif
                                                                                        @endif
                                                                                        {{-- {{ $request->client_id != null ? $request->client->name : $request->client_name }} --}}
                                                                                    </h4>
                                                                                    <br>

                                                                                    <h4 class="modal-title" id="content-detail-{{ $request->id }}">
                                                                                        Request
                                                                                        #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                                    </h4>
                                                                                </div>
                                                                                <button type="button" class="close d-print-none" data-dismiss="modal" aria-hidden="true">×</button>
                                                                                
                                                                                <div class="pull-left mb-4 d-none d-print-block">
                                                                                    <address>

                                                                                        <img src="{{ asset('documents/logos/' . getuserCompanyInfo()->logo) }}" alt="" width="600px">
                                                                                        <h3> &nbsp;
                                                                                            <b class="text-danger">{{ getuserCompanyInfo()->company_name }}</b>
                                                                                        </h3>
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

                                                                                if ($invoice_product->stock<2){ $isOutOfStock='yes' ; } else { $isOutOfStock='no' ; } @endphp {{-- for lens --}} @if ($invoice_product->category_id == 1)

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
                                                                                            @if (initials($invoice_product->product_name) == 'SV')
                                                                                                <span>
                                                                                                    {{ $invoice_product->power->sphere }}
                                                                                                    /
                                                                                                    {{ $invoice_product->power->cylinder }}
                                                                                                    <span class='text-primary'>*{{ $product->axis??0 }}</span>
                                                                                                </span>
                                                                                            @else
                                                                                            <span>{{ $invoice_product->power->sphere }}
                                                                                                /
                                                                                                {{ $invoice_product->power->cylinder }}
                                                                                                <span class='text-primary'>*{{ $product->axis??0 }}</span>
                                                                                                {{ $invoice_product->power->add }}</span>
                                                                                            @endif
                                                                                        </div>
                                                                                        <div class="col-2 row">
                                                                                            <span>
                                                                                                <h6>Location: </h6>
                                                                                            </span>
                                                                                            {{ $invoice_product->location == null ? '-' : $invoice_product->location }}
                                                                                        </div>
                                                                                        <div class="col-2 ">
                                                                                            <span class="text-capitalize d-flex justify-content-around items-center">
                                                                                                <h6 class="text-dark">
                                                                                                    Mono PD:
                                                                                                </h6>
                                                                                                <span class="text-capitalize">
                                                                                                    {{ $product->mono_pd }}
                                                                                                </span>
                                                                                            </span>
                                                                                        </div>
                                                                                        <div class="col-2 ">
                                                                                            <span class="text-capitalize d-flex justify-content-around items-center">
                                                                                                <h6 class="text-dark">
                                                                                                    Seg H:
                                                                                                </h6>
                                                                                                <span class="text-capitalize">
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
                                                                                    @if ($product)
                                                                                    @foreach ($request->soldproduct as $product)
                                                                                    @php
                                                                                    $invoice_product = $product->product;
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
                                                                                    @if ($product)
                                                                                    @foreach ($request->soldproduct as $product)
                                                                                    @php
                                                                                    $invoice_product = $product->product;
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
                                                                                                {{ $invoice_product->location == null ? '-' : $invoice_product->location }}
                                                                                            </span>
                                                                                        </div>
                                                                                        <div class="col-2 ">
                                                                                            <span class="text-capitalize d-flex justify-content-around items-center">
                                                                                                <h4 class="text-dark">
                                                                                                    Qty:
                                                                                                </h4>
                                                                                                <span class="text-capitalize">
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

                                                                                @if ($isOutOfStock=='yes')
                                                                                <center>
                                                                                    <h4 class="text-danger">Product out of stock</h4>
                                                                                </center>
                                                                                @else
                                                                                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">
                                                                                    Close
                                                                                </button>
                                                                                <button type="button" class="btn btn-success waves-effect text-left" id="print" onclick="printModal('proddd-{{ $key }}-detail')">Print</button>
                                                                                <a href="{{ route('manager.send.request.lab', Crypt::encrypt($request->id)) }}" onclick="return confirm('are you sure?')" class="btn btn-info waves-effect text-left">
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

                                    @if (is_null(getUserCompanyInfo()->is_vision_center) && getUserCompanyInfo()->can_supply=='1')
                                        <div id="external-booking-orders" class="tab-pane">
                                            @if (count($bookings_out)>0)
                                            <div class="table-responsive mt-4">
                                                <button onclick="ExportToExcelOutOfStock('xlsx');" class="btn btn-success float-right mb-3">
                                                    <i class="fa fa-cloud-download-alt"></i> Excel
                                                </button>
                                                <form method="post" action="{{ route('manager.sent.request.send.to.supplier') }}">
                                                    <table id="outOfStock" class="table table-striped table-bordered nowrap" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    <input type="checkbox" onclick="checkUncheckrequestId(this)">
                                                                </th>
                                                                <th>Request # </th>
                                                                <th>Cloud ID</th>
                                                                <th>Patient Name</th>
                                                                <th>Request Date</th>
                                                                <th>Request Age</th>
                                                                <th>Description</th>
                                                                <th>Right Eye</th>
                                                                <th>Left Eye</th>
                                                                <th>Source</th>
                                                                <th>Status</th>
                                                                <th>-</th>
                                                            </tr>
                                                        </thead>
                                                        @csrf

                                                        <tbody>
                                                            @foreach ($bookings_out as $key => $request)
                                                            @if (count($request->unavailableproducts) <= 0) 
                                                            <tr>
                                                                <td>
                                                                    <input type='checkbox' name="requestId[]" value={{ $request->id }} />
                                                                </td>
                                                                <td>
                                                                    <a href="#!" data-toggle="modal" data-target="#out-proddd-{{ $key }}-detail">
                                                                        Request #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    @if ($request->client_id != null)
                                                                    -
                                                                    @else
                                                                    @if ($request->hospital_name!=null)
                                                                        {{$request->cloud_id ?? $request->transaction_id}}
                                                                    @else
                                                                    -
                                                                    @endif
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($request->client_id != null)
                                                                    {{$request->client->name}}
                                                                    @else
                                                                    @if ($request->hospital_name!=null)
                                                                    {{-- [{{$request->cloud_id}}] --}} {{$request->hospital_name}}
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

                                                                </td>
                                                                @php
                                                                $availability_right = true;
                                                                $availability_left = true;
                                                                $description = null;
                                                                $right_len = $request->unavailableproducts->where('eye', 'right')->first();

                                                                if (!$right_len) {
                                                                $right_len = $request->soldproduct->where('eye', 'right')->first();
                                                                if ($right_len==null) {
                                                                continue;
                                                                }
                                                                // dd($right_len->product->power);
                                                                $right_len = $right_len->product;
                                                                $availability_right = false;
                                                                }

                                                                if ($availability_right == true) {
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
                                                                $description = $right_len->description;
                                                                }
                                                                }

                                                                // left eye checking
                                                                $left_len = $request->unavailableproducts->where('eye', 'left')->first();

                                                                if (!$left_len) {
                                                                $left_len = $request->soldproduct->where('eye', 'left')->first();
                                                                if ($left_len==null) {
                                                                continue;
                                                                }
                                                                $left_len = $left_len->product;
                                                                $availability_left = false;
                                                                }

                                                                if ($availability_left == true) {
                                                                $type = $lens_type
                                                                ->where('id', $left_len->type_id)
                                                                ->pluck('name')
                                                                ->first();

                                                                $indx = $index
                                                                ->where('id', $left_len->index_id)
                                                                ->pluck('name')
                                                                ->first();

                                                                $ct = $coatings
                                                                ->where('id', $left_len->coating_id)
                                                                ->pluck('name')
                                                                ->first();

                                                                $chrm = $chromatics
                                                                ->where('id', $left_len->chromatic_id)
                                                                ->pluck('name')
                                                                ->first();
                                                                } else {
                                                                if ($left_len) {
                                                                $description = $left_len->description;
                                                                }
                                                                }
                                                                @endphp
                                                                <td>
                                                                    @if ($availability_right)
                                                                    {{ initials($type)=='BT'?'Bifocal Round Top':initials($type) }} {{ $chrm }}
                                                                    {{ $ct }} {{ $indx }}
                                                                    @else
                                                                    {{ $description }}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($right_len)
                                                                    @if ($availability_right)
                                                                    <span>
                                                                        {{ format_values($right_len->power->sphere) }}
                                                                        /
                                                                        {{ format_values($right_len->power->cylinder) }}
                                                                        <span class='text-primary'>*{{ $request->axis??0 }}</span>
                                                                        {{ format_values($right_len->addition) }}
                                                                    </span>
                                                                    @else
                                                                    <span>
                                                                        {{ format_values($right_len->power->sphere) }}
                                                                        /
                                                                        {{ format_values($right_len->power->cylinder) }}
                                                                        <span class='text-primary'>*{{ $request->axis??0 }}</span>

                                                                        @if (initials($right_len->product_name)!='SV')
                                                                                {{ format_values($right_len->power->add) }}
                                                                            @endif
                                                                        {{-- {{ format_values($right_len->power->add) }} --}}
                                                                    </span>
                                                                    @endif
                                                                    @else
                                                                    <span>-</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($left_len)
                                                                    @if ($availability_left)
                                                                    {{ format_values($left_len->sphere) }}
                                                                    /
                                                                    {{ format_values($left_len->cylinder) }}
                                                                    <span class='text-primary'>*{{ $request->axis??0 }}</span>
                                                                    {{ format_values($left_len->addition) }}
                                                                    @else
                                                                    {{ format_values($left_len->power->sphere) }}
                                                                    /
                                                                    {{ format_values($left_len->power->cylinder) }}
                                                                    <span class='text-primary'>*{{ $request->axis??0 }}</span>
                                                                    @if (initials($left_len->product_name)!='SV')
                                                                        {{ format_values($left_len->power->add) }}
                                                                    @endif
                                                                    {{-- {{ format_values($left_len->power->add) }} --}}
                                                                    @endif
                                                                    @else
                                                                    <span class="text-center">-</span>
                                                                    @endif
                                                                </td>

                                                                <td class="text-start">
                                                                    @if (getUserCompanyInfo()->is_vision_center=='1')
                                                                        {{ $request->supplier?->company_name }}
                                                                    @else
                                                                        {{ $request->company?->company_name }}
                                                                    @endif
                                                                </td>

                                                                <td class="text-start">
                                                                    <span @class([ 'text-warning'=> $request->status == 'requested',
                                                                        ])>
                                                                        {{ $request->status }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    @if ($request->status=='booked')
                                                                    <a href="{{route('manager.lab.order.cancel',Crypt::encrypt($request->id))}}" class="text-danger" onclick="return confirm('Are you sure??')">Cancel Order</a>
                                                                    @else
                                                                    <center>-</center>
                                                                    @endif
                                                                </td>
                                                                </tr>


                                                                {{-- modal --}}

                                                                <div class="modal fade bs-example-modal-lg" id="out-proddd-{{ $key }}-detail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
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
                                                                                                [{{$request->cloud_id ?? $request->transaction_id}}] {{$request->hospital_name}}
                                                                                            @else
                                                                                                {{$request->client_name.' - '.$request->phone}}
                                                                                            @endif
                                                                                        @endif
                                                                                    </h4>
                                                                                    <br>

                                                                                    <h4 class="modal-title" id="content-detail-{{ $key }}">
                                                                                        Request
                                                                                        #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                                    </h4>
                                                                                </div>
                                                                                <button type="button" class="close d-print-none" data-dismiss="modal" aria-hidden="true">×</button>
                                                                                
                                                                                <div class="pull-left mb-4 d-none d-print-block">
                                                                                    <address>

                                                                                        <img src="{{ asset('documents/logos/' . getuserCompanyInfo()->logo) }}" alt="" width="600px">
                                                                                        <h3> &nbsp;
                                                                                            <b class="text-danger">{{ getuserCompanyInfo()->company_name }}</b>
                                                                                        </h3>
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

                                                                                if ($invoice_product->stock<2){ $isOutOfStock='yes' ; } else { $isOutOfStock='no' ; } @endphp {{-- for lens --}} @if ($invoice_product->category_id == 1)

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
                                                                                            @if (initials($invoice_product->product_name) == 'SV')
                                                                                            <span>{{ $invoice_product->power->sphere }}
                                                                                                /
                                                                                                {{ $invoice_product->power->cylinder }}
                                                                                                <span class='text-primary'>*{{ $product->axis??0 }}</span>
                                                                                                {{-- {{ $invoice_product->power->add }}</span> --}}
                                                                                            @else
                                                                                            <span>{{ $invoice_product->power->sphere }}
                                                                                                /
                                                                                                {{ $invoice_product->power->cylinder }}
                                                                                                <span class='text-primary'>*{{ $request->axis??0 }}</span>
                                                                                                {{ $invoice_product->power->add }}</span>
                                                                                            @endif
                                                                                        </div>
                                                                                        <div class="col-2 row">
                                                                                            <span>
                                                                                                <h6>Location: </h6>
                                                                                            </span>
                                                                                            {{ $invoice_product->location == null ? '-' : $invoice_product->location }}
                                                                                        </div>
                                                                                        <div class="col-2 ">
                                                                                            <span class="text-capitalize d-flex justify-content-around items-center">
                                                                                                <h6 class="text-dark">
                                                                                                    Mono PD:
                                                                                                </h6>
                                                                                                <span class="text-capitalize">
                                                                                                    {{ $product->mono_pd }}
                                                                                                </span>
                                                                                            </span>
                                                                                        </div>
                                                                                        <div class="col-2 ">
                                                                                            <span class="text-capitalize d-flex justify-content-around items-center">
                                                                                                <h6 class="text-dark">
                                                                                                    Seg H:
                                                                                                </h6>
                                                                                                <span class="text-capitalize">
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
                                                                                    @if ($product)
                                                                                    @foreach ($request->soldproduct as $product)
                                                                                    @php
                                                                                    $invoice_product = $product->product;
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
                                                                                    @if ($product)
                                                                                    @foreach ($request->soldproduct as $product)
                                                                                    @php
                                                                                    $invoice_product = $product->product;
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
                                                                                                {{ $invoice_product->location == null ? '-' : $invoice_product->location }}
                                                                                            </span>
                                                                                        </div>
                                                                                        <div class="col-2 ">
                                                                                            <span class="text-capitalize d-flex justify-content-around items-center">
                                                                                                <h4 class="text-dark">
                                                                                                    Qty:
                                                                                                </h4>
                                                                                                <span class="text-capitalize">
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

                                                                                @if ($isOutOfStock=='yes')
                                                                                <center>
                                                                                    <h4 class="text-danger">Product out of stock</h4>
                                                                                </center>
                                                                                @else
                                                                                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">
                                                                                    Close
                                                                                </button>
                                                                                <button type="button" class="btn btn-success waves-effect text-left" id="print" onclick="printModal('out-proddd-{{ $key }}-detail')">Print</button>
                                                                                <a href="{{ route('manager.send.request.lab', Crypt::encrypt($request->id)) }}" onclick="return confirm('are you sure?')" class="btn btn-info waves-effect text-left">
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

                                                    @foreach ($collection as $item)
                                                        @if (count($request->unavailableproducts) <= 0) 
                                                            <div class="modal fade bs-example-modal-lg" id="out-proddd-{{ $key }}-detail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
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
                                                                                            [{{$request->cloud_id ?? $request->transaction_id}}] {{$request->hospital_name}}
                                                                                        @else
                                                                                            {{$request->client_name.' - '.$request->phone}}
                                                                                        @endif
                                                                                    @endif
                                                                                </h4>
                                                                                <br>

                                                                                <h4 class="modal-title" id="content-detail-{{ $key }}">
                                                                                    Request
                                                                                    #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                                </h4>
                                                                            </div>
                                                                            <button type="button" class="close d-print-none" data-dismiss="modal" aria-hidden="true">×</button>
                                                                            
                                                                            <div class="pull-left mb-4 d-none d-print-block">
                                                                                <address>

                                                                                    <img src="{{ asset('documents/logos/' . getuserCompanyInfo()->logo) }}" alt="" width="600px">
                                                                                    <h3> &nbsp;
                                                                                        <b class="text-danger">{{ getuserCompanyInfo()->company_name }}</b>
                                                                                    </h3>
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

                                                                            if ($invoice_product->stock<2){ $isOutOfStock='yes' ; } else { $isOutOfStock='no' ; } @endphp {{-- for lens --}} @if ($invoice_product->category_id == 1)

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
                                                                                        @if (initials($invoice_product->product_name) == 'SV')
                                                                                        <span>{{ $invoice_product->power->sphere }}
                                                                                            /
                                                                                            {{ $invoice_product->power->cylinder }}
                                                                                            <span class='text-primary'>*{{ $product->axis??0 }}</span>
                                                                                            {{-- {{ $invoice_product->power->add }}</span> --}}
                                                                                        @else
                                                                                        <span>{{ $invoice_product->power->sphere }}
                                                                                            /
                                                                                            {{ $invoice_product->power->cylinder }}
                                                                                            <span class='text-primary'>*{{ $request->axis??0 }}</span>
                                                                                            {{ $invoice_product->power->add }}</span>
                                                                                        @endif
                                                                                    </div>
                                                                                    <div class="col-2 row">
                                                                                        <span>
                                                                                            <h6>Location: </h6>
                                                                                        </span>
                                                                                        {{ $invoice_product->location == null ? '-' : $invoice_product->location }}
                                                                                    </div>
                                                                                    <div class="col-2 ">
                                                                                        <span class="text-capitalize d-flex justify-content-around items-center">
                                                                                            <h6 class="text-dark">
                                                                                                Mono PD:
                                                                                            </h6>
                                                                                            <span class="text-capitalize">
                                                                                                {{ $product->mono_pd }}
                                                                                            </span>
                                                                                        </span>
                                                                                    </div>
                                                                                    <div class="col-2 ">
                                                                                        <span class="text-capitalize d-flex justify-content-around items-center">
                                                                                            <h6 class="text-dark">
                                                                                                Seg H:
                                                                                            </h6>
                                                                                            <span class="text-capitalize">
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
                                                                                @if ($product)
                                                                                @foreach ($request->soldproduct as $product)
                                                                                @php
                                                                                $invoice_product = $product->product;
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
                                                                                @if ($product)
                                                                                @foreach ($request->soldproduct as $product)
                                                                                @php
                                                                                $invoice_product = $product->product;
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
                                                                                            {{ $invoice_product->location == null ? '-' : $invoice_product->location }}
                                                                                        </span>
                                                                                    </div>
                                                                                    <div class="col-2 ">
                                                                                        <span class="text-capitalize d-flex justify-content-around items-center">
                                                                                            <h4 class="text-dark">
                                                                                                Qty:
                                                                                            </h4>
                                                                                            <span class="text-capitalize">
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

                                                                            @if ($isOutOfStock=='yes')
                                                                            <center>
                                                                                <h4 class="text-danger">Product out of stock</h4>
                                                                            </center>
                                                                            @else
                                                                            <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">
                                                                                Close
                                                                            </button>
                                                                            <button type="button" class="btn btn-success waves-effect text-left" id="print" onclick="printModal('out-proddd-{{ $key }}-detail')">Print</button>
                                                                            <a href="{{ route('manager.send.request.lab', Crypt::encrypt($request->id)) }}" onclick="return confirm('are you sure?')" class="btn btn-info waves-effect text-left">
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
                                                    <hr>
                                                    <button class="btn btn-primary">Order from Supplier</button>
                                                </form>
                                            </div>
                                            @endif
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

</div>

@endsection

@push('scripts')
<script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js') }}"></script>
<script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<script src="{{ asset('dashboard/assets/dist/js/pages/samplepages/jquery.PrintArea.js') }}"></script>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

<script>
    function checkUncheckpriced(checkBox) {

        get = document.getElementsByName('requestId[]');

        for (var i = 0; i < get.length; i++) {

            get[i].checked = checkBox.checked;
        }

    }

    function checkUncheckrequestId(checkBox) {

        get = document.getElementsByName('requestId[]');

        for (var i = 0; i < get.length; i++) {

            get[i].checked = checkBox.checked;
        }

    }

    function ExportToExcel(type, fn, dl) {
        var elt = document.getElementById('outOfStock_internal');
        var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
        return dl ?
            XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
            XLSX.writeFile(wb, fn || ('Speclucs out of stock.' + (type || 'xlsx')));
    }

    function ExportToExcelOutOfStock(type, fn, dl) {
        var elt = document.getElementById('outOfStock');
        var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
        return dl ?
            XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
            XLSX.writeFile(wb, fn || ('Speclucs out of stock.' + (type || 'xlsx')));
    }

    function exportOutOfStock(type, tableName) {

        $('#outOfStock').tableExport({
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
        $("#" + name).printArea(options);
    };
</script>
@endpush