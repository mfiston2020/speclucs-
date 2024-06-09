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
@section('current', 'Lab Requests')
@section('page_name', 'List of Lab requests')
{{-- === End of breadcumb == --}}

@section('content')

<div class="container-fluid">
        <!-- Sales chart -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h4 class="card-title">All Requested Products
                                <span class="badge badge-danger badge-pill ml-2">
                                    {{number_format(count($requests_priced) + count($requests_priced_out))}}
                                </span>
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
                                        <li class=" nav-item">
                                            <a href="#internal_requested" class="nav-link active" data-toggle="tab" aria-expanded="false">
                                                Internal Order(s)
                                                <span class="badge badge-danger badge-pill ml-2">
                                                    {{count($requests_priced)}}
                                                </span>
                                            </a>
                                        </li>

                                        <li class="ml-3 nav-item">
                                            <a href="#external_requested" class="nav-link" data-toggle="tab" aria-expanded="false">
                                                External Order(s)
                                                <span class="badge badge-danger badge-pill ml-2">
                                                    {{ count($requests_priced_out) }}
                                                </span>
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content br-n pn">

                                        <div id="internal_requested" class="tab-pane active">
                                            @if (count($requests_priced)>0)
                                                <button onclick="exportAll('xls','Priced Lens');" class="btn btn-success float-right mb-3">
                                                    <i class="fa fa-cloud-download-alt"></i>
                                                    Excel
                                                </button>
                                                <div class="table-responsive mt-4">
                                                    <table id="priced-table" class="table table-striped table-bordered nowrap" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th> <input type="checkbox" onclick="checkUncheckrequestId(this)"> </th>
                                                                <th>Request # </th>
                                                                <th>CLOUD ID</th>
                                                                <th>Patient Name</th>
                                                                <th>Description</th>
                                                                <th>Right Eye</th>
                                                                <th>Left Eye</th>
                                                                <th>Request Date</th>
                                                                <th>Request Age</th>
                                                                <th>Payment</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>

                                                        <form method="post" action="{{ route('manager.sent.request.send.to.supplier') }}">

                                                            @csrf
                                                            <tbody>
                                                                @foreach ($requests_priced as $key => $request)

                                                                    <tr>
                                                                        <td>
                                                                            @if ($request->status == 'Confirmed')
                                                                                <input type='checkbox' name="requestId[]" value={{ $request->id }} />
                                                                            @else
                                                                                -
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <a href="#!" data-toggle="modal" data-target="#proddd-{{ $key }}-detail">
                                                                                Request #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                            </a>
                                                                        </td>
                                                                        <td>
                                                                            @if ($request->client_id != null)
                                                                                -
                                                                            @else
                                                                                @if ($request->hospital_name!=null)
                                                                                    {{$request->cloud_id}}
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
                                                                                    {{$request->hospital_name}}
                                                                                @else
                                                                                    {{$request->client_name}}
                                                                                @endif
                                                                            @endif
                                                                        </td>
                                                                        @php
                                                                            $availability_right = true;
                                                                            $availability_left = true;
                                                                            $description = null;

                                                                            $right_len_befor_product    =   null;
                                                                            $left_len_befor_product    =   null;

                                                                            $right_len = $request->unavailableproducts->where('eye', 'right')->first();
                                                                            $right_len_befor_product    =   $right_len;

                                                                            if (!$right_len) {
                                                                                $right_len = $request->soldproduct->where('eye', 'right')->first();
                                                                                if ($right_len==null) {
                                                                                    continue;
                                                                                }
                                                                                $right_len_befor_product    =   $right_len;

                                                                                $right_len = $right_len->product;
                                                                                $availability_right = false;
                                                                            }

                                                                            // left eye checking
                                                                            $left_len = $request->unavailableproducts->where('eye', 'left')->first();
                                                                            $left_len_befor_product =   $left_len;

                                                                            if (!$left_len) {
                                                                                $left_len = $request->soldproduct->where('eye', 'left')->first();
                                                                                if ($left_len==null) {
                                                                                    continue;
                                                                                }
                                                                                $left_len_befor_product =   $left_len;
                                                                                $left_len = $left_len->product;
                                                                                $availability_left = false;
                                                                            }
                                                                        @endphp
                                                                        <td>
                                                                            @if ($availability_right)
                                                                                {{ initials($right_len->type->name)=='BT'?'Bifocal Round Top':initials($right_len->type->name) }} {{ $right_len->uchromatic->name }}
                                                                                {{ $right_len->coating->name }} {{ $right_len->uindex->name }}
                                                                            @else
                                                                                {{ $right_len->description }}
                                                                            @endif
                                                                        </td>

                                                                        <td>

                                                                            @if ($right_len)
                                                                                @if ($availability_right)
                                                                                    <span>
                                                                                        {{ format_values($right_len->sphere) }}
                                                                                        /
                                                                                        {{ format_values($right_len->cylinder) }}
                                                                                        <span class="text-primary">*{{ ($right_len_befor_product->axis ?? 0) }}</span>
                                                                                        {{ $right_len->addition }}
                                                                                    </span>
                                                                                @else
                                                                                    <span>
                                                                                        {{ format_values($right_len->power->sphere) }}
                                                                                        /
                                                                                        {{ format_values($right_len->power->cylinder) }}
                                                                                        <span class="text-primary">*{{ ($right_len_befor_product->axis ?? 0) }}</span>
                                                                                        {{ $right_len->power->add }}
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
                                                                                        <span class="text-primary">*{{ ($left_len_befor_product->axis ?? 0) }}</span>
                                                                                    {{ $left_len->addition }}
                                                                                @else
                                                                                    {{ format_values($left_len->power->sphere) }}
                                                                                    /
                                                                                    {{ format_values($left_len->power->cylinder) }}
                                                                                        <span class="text-primary">*{{ ($left_len_befor_product->axis ?? 0) }}</span>
                                                                                    {{ $left_len->power->add }}
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
                                                                        <td class="text-start">
                                                                            <span @class([ 'text-info'=> $request->status == 'priced','text-success' =>
                                                                                $request->status == 'Confirmed',])>
                                                                                {{ $request->status }}
                                                                            </span>
                                                                        </td>
                                                                        <td>
                                                                            @if ($request->status=='Confirmed' || $request->status=='priced')
                                                                                <a href="{{route('manager.lab.order.cancel',Crypt::encrypt($request->id))}}" class="text-danger" onclick="return confirm('Are you sure??')">Cancel Order</a>
                                                                            @else
                                                                                <center>-</center>
                                                                            @endif
                                                                        </td>
                                                                    </tr>

                                                                    {{-- modal --}}

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
                                                                                                            {{ $invoice_product->power->add }}</span>
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
                                                                                            echo($invoice_product);

                                                                                            // if ($availability_left == true) {
                                                                                            //     $type = $lens_type->where('id', $left_len->type_id)->pluck('name')->first();
                                                                                            //     $indx = $index->where('id', $left_len->index_id)->pluck('name')->first();
                                                                                            //     $ct = $coatings->where('id', $left_len->coating_id)->pluck('name')->first();
                                                                                            //     $chrm = $chromatics->where('id', $left_len->chromatic_id)->pluck('name')->first();
                                                                                            // }
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
                                                                                                        <span class='text-primary'>*{{ $product->axis??0 }}</span>
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
                                                                                <div class="modal-footer d-print-none d-flex justify-content-between">
                                                                                    <button type="button"
                                                                                        class="btn btn-danger waves-effect text-left"
                                                                                        data-dismiss="modal">
                                                                                        Close
                                                                                    </button>

                                                                                    <button type="button" onclick="printModal('proddd-{{ $key }}-detail')" class="btn btn-success waves-effect text-left" id="print">Print</button>
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
                                                <hr>
                                                <button class="btn btn-primary">Order from Supplier</button>
                                                </form>
                                            @endif
                                        </div>


                                        <div id="external_requested" class="tab-pane">
                                            @if (count($requests_priced_out)>0)
                                                <button onclick="exportAllExternal('xls','External Priced Lens');" class="btn btn-success float-right mb-3">
                                                    <i class="fa fa-cloud-download-alt"></i>
                                                    Excel
                                                </button>
                                                <div class="table-responsive mt-4">
                                                    <table id="external-priced-table" class="table table-striped table-bordered nowrap" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th> <input type="checkbox" onclick="checkUncheckrequestId(this)"> </th>
                                                                <th>Request # </th>
                                                                <th>CLOUD ID</th>
                                                                <th>Patient Name</th>
                                                                {{-- <th>Source</th> --}}
                                                                <th>Description</th>
                                                                <th>Right Eye</th>
                                                                <th>Left Eye</th>
                                                                <th>Request Date</th>
                                                                <th>Request Age</th>
                                                                {{-- <th>Cost</th> --}}
                                                                <th>Payment</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>

                                                        <form method="post" action="{{ route('manager.sent.request.send.to.supplier') }}">

                                                            @csrf
                                                            <tbody>
                                                                @foreach ($requests_priced_out as $key => $request)

                                                                    <tr>
                                                                        <td>
                                                                            @if ($request->status == 'Confirmed')
                                                                                <input type='checkbox' name="requestId[]" value={{ $request->id }} />
                                                                            @else
                                                                                -
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <a href="#!" data-toggle="modal" data-target="#prod-{{ $key }}-detail">
                                                                                Request #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                            </a>
                                                                        </td>
                                                                        <td>
                                                                            @if ($request->client_id != null)
                                                                                -
                                                                            @else
                                                                                @if ($request->hospital_name!=null)
                                                                                    {{$request->cloud_id}}
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
                                                                                    {{$request->hospital_name}}
                                                                                @else
                                                                                    {{$request->client_name}}
                                                                                @endif
                                                                            @endif
                                                                        </td>
                                                                        {{-- <td>
                                                                            {{ $request->supplier }}
                                                                        </td> --}}
                                                                        @php
                                                                            $availability_right = true;
                                                                            $availability_left = true;
                                                                            $description = null;

                                                                            $right_len_befor_product    =   null;
                                                                            $left_len_befor_product    =   null;

                                                                            $right_len = $request->unavailableproducts->where('eye', 'right')->first();

                                                                            if (!$right_len) {
                                                                                $right_len = $request->soldproduct->where('eye', 'right')->first();
                                                                                if ($right_len==null) {
                                                                                    continue;
                                                                                }
                                                                                $right_len_befor_product    =   $right_len;
                                                                                $right_len = $right_len->product;
                                                                                $availability_right = false;
                                                                            }

                                                                            // left eye checking
                                                                            $left_len = $request->unavailableproducts->where('eye', 'left')->first();

                                                                            if (!$left_len) {
                                                                                $left_len = $request->soldproduct->where('eye', 'left')->first();
                                                                                if ($left_len==null) {
                                                                                    continue;
                                                                                }
                                                                                $left_len_befor_product    =   $left_len;
                                                                                $left_len = $left_len->product;
                                                                                $availability_left = false;
                                                                            }
                                                                        @endphp
                                                                        <td>
                                                                            @if ($availability_right)
                                                                                {{ initials($right_len->type->name)=='BT'?'Bifocal Round Top':initials($right_len->type->name) }} {{ $right_len->uchromatic->name }}
                                                                                {{ $right_len->coating->name }} {{ $right_len->uindex->name }}
                                                                            @else
                                                                                {{ $right_len->description }}
                                                                            @endif
                                                                        </td>

                                                                        <td>

                                                                            @if ($right_len)
                                                                                @if ($availability_right)
                                                                                    <span>
                                                                                        {{ format_values($right_len->sphere) }}
                                                                                        /
                                                                                        {{ format_values($right_len->cylinder) }}
                                                                                        <span class="text-primary">*{{ ($right_len_befor_product->axis ?? 0) }}</span>
                                                                                        {{ $right_len->addition }}
                                                                                    </span>
                                                                                @else
                                                                                    <span>
                                                                                        {{ format_values($right_len->power->sphere) }}
                                                                                        /
                                                                                        {{ format_values($right_len->power->cylinder) }}
                                                                                        <span class="text-primary">*{{ ($right_len_befor_product->axis ?? 0) }}</span>
                                                                                        {{ $right_len->power->add }}
                                                                                    </span>
                                                                                @endif
                                                                            @else
                                                                            hello
                                                                            
                                                                                <span>-</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if ($left_len)
                                                                                @if ($availability_left)
                                                                                    {{ format_values($left_len->sphere) }}
                                                                                    /
                                                                                    {{ format_values($left_len->cylinder) }}
                                                                                        <span class="text-primary">*{{ ($left_len_befor_product->axis ?? 0) }}</span>
                                                                                    {{ $left_len->addition }}
                                                                                @else
                                                                                    {{ format_values($left_len->power->sphere) }}
                                                                                    /
                                                                                    {{ format_values($left_len->power->cylinder) }}
                                                                                        <span class="text-primary">*{{ ($left_len_befor_product->axis ?? 0) }}</span>
                                                                                    {{ $left_len->power->add }}
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
                                                                        <td class="text-start">
                                                                            <span @class([ 'text-info'=> $request->status == 'priced','text-success' =>
                                                                                $request->status == 'Confirmed',])>
                                                                                {{ $request->status }}
                                                                            </span>
                                                                        </td>
                                                                        <td>
                                                                            @if ($request->status=='Confirmed' || $request->status=='priced')
                                                                                <a href="{{route('manager.lab.order.cancel',Crypt::encrypt($request->id))}}" class="text-danger" onclick="return confirm('Are you sure??')">Cancel Order</a>
                                                                            @else
                                                                                <center>-</center>
                                                                            @endif
                                                                        </td>
                                                                    </tr>

                                                                    {{-- modal --}}

                                                                    <div class="modal fade bs-example-modal-lg" id="prod-{{ $key }}-detail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
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
                                                                                    <button type="button" class="close d-none" data-dismiss="modal" aria-hidden="true">×</button>
                                                                                    <div class="pull-left mb-4 d-none d-print-block">
                                                                                        <address>

                                                                                            <img src="{{ asset('documents/logos/' . getuserCompanyInfo()->logo) }}" alt="" width="600px">
                                                                                            {{-- @if (Auth::user()->company_id != 3) --}}
                                                                                            <h3> &nbsp;<b class="text-danger">{{ getuserCompanyInfo()->company_name }}</b></h3>
                                                                                            {{-- @endif --}}
                                                                                            <p class="text-muted m-l-5"><strong class="text-black-50">TIN Number:</strong>
                                                                                                {{ getuserCompanyInfo()->company_tin_number }}
                                                                                                {{-- <br /><span></span> {{getuserCompanyInfo()->company_street}} --}}
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
                                                                                                            {{-- *{{ $invoice_product->axis }} --}}
                                                                                                            {{ $invoice_product->power->add }}</span>
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
                                                                                            echo($invoice_product);

                                                                                            // if ($availability_left == true) {
                                                                                            //     $type = $lens_type->where('id', $left_len->type_id)->pluck('name')->first();
                                                                                            //     $indx = $index->where('id', $left_len->index_id)->pluck('name')->first();
                                                                                            //     $ct = $coatings->where('id', $left_len->coating_id)->pluck('name')->first();
                                                                                            //     $chrm = $chromatics->where('id', $left_len->chromatic_id)->pluck('name')->first();
                                                                                            // }
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
                                                                                                    @if (initials($product->type->name) == 'SV')
                                                                                                        <span>{{ format_values($product->sphere) }}
                                                                                                            /
                                                                                                            {{ format_values($product->cylinder) }}
                                                                                                        </span>
                                                                                                    @else
                                                                                                        <span>{{ format_values($product->sphere) }}
                                                                                                            /
                                                                                                            {{ format_values($product->cylinder) }}
                                                                                                            *{{ $product->axis }}
                                                                                                            {{ $product->addition }}
                                                                                                        </span>
                                                                                                    @endif
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
                                                                                <div class="modal-footer d-print-none d-flex justify-content-between">
                                                                                    <button type="button"
                                                                                        class="btn btn-danger waves-effect text-left"
                                                                                        data-dismiss="modal">
                                                                                        Close
                                                                                    </button>
                                                                                    <button type="button" onclick="printModal('prod-{{ $key }}-detail')" class="btn btn-success waves-effect text-left" id="print">Print</button>
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
                                                <hr>
                                                <button class="btn btn-primary">Order from Supplier</button>
                                                </form>
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

    </div>

@endsection

@push('scripts')
<script src="{{ asset('dashboard/assets/dist/js/pages/samplepages/jquery.PrintArea.js') }}"></script>
<script src="{{ asset('dashboard/assets/dist/js/export.js') }}"></script>

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

    function exportAllExternal(type, tableName) {

        $('#external-priced-table').tableExport({
            filename: tableName + '_%DD%-%mm%-%YY%',
            format: type
        });
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
