@extends('manager.includes.app')

@section('title', 'Dashboard - Product')

@push('css')
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
                            <h4 class="card-title">All Completed Requested</h4>
                        </div>
                        {{-- ============================== --}}
                        @include('manager.includes.layouts.message')
                        {{-- =============================== --}}

                        <ul class="nav nav-pills mt-4 mb-4">
                            <li class=" nav-item">
                                <a href="#complete-orders" class="nav-link active" data-toggle="tab" aria-expanded="false">
                                    Internal Completed
                                    <span class="badge badge-danger badge-pill">
                                        {{ $requests->total() }}
                                    </span>
                                </a>
                            </li>

                            <li class=" nav-item">
                                <a href="#extrnal-orders" class="nav-link" data-toggle="tab" aria-expanded="false">
                                    External Completed
                                    <span class="badge badge-danger badge-pill">
                                        {{ $requests_out->total() }}
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content br-n pn">
            {{-- completed --}}
            <div class="row">
                <form action="{{route('manager.sent.request.to.delivery')}}" id="completedForm" method="post" class="col-12">
                    @csrf
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <a href="#" onclick="exportAll('xlsx')" class="btn btn-success btn-rounded"><i class="mdi mdi-file-excel"></i> Export To Excel</a>
                                <hr>
                                <div class="tab-content br-n pn">
                                    <div id="complete-orders" class="tab-pane active">
                                        <div class="table-responsive mt-4">
                                            <table id="zero_config" class="table table-striped table-bordered nowrap"
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="form-check form-check-inline align-content-center">
                                                                <input class="form-check-input" type="checkbox" id="checkall"
                                                                    value="">
                                                            </div>
                                                        </th>
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
                                                        <tr>
                                                            <td>
                                                                <div class="form-check form-check-inline align-content-center">
                                                                    <input class="form-check-input allCheckbox" type="checkbox"
                                                                        name="requestid[]" id="inlineCheckbox1" value="{{$request->id}}">
                                                                </div>
                                                            </td>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>
                                                                <a href="#!" data-toggle="modal"
                                                                    data-target="#internal-{{ $key }}-detail">
                                                                    Request #{{ sprintf('SPCL-%04d', $request->id) }}
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
                                                            {{-- <td>
                                                                -
                                                            </td> --}}
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
                                                            id="internal-{{ $key }}-detail" tabindex="-1"
                                                            role="dialog" aria-labelledby="myLargeModalLabel"
                                                            aria-hidden="true" style="display: none;">
                                                            <div class="modal-dialog modal-xl modal-dialog-centered">
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

                                                                                @if (!is_null($request->supplier_id) && userInfo()->company_id==$request->supplier_id)
                                                                                    - <span class="text-warning">From</span> [{{$request->company->company_name}}]
                                                                                @endif

                                                                                    @if (!is_null($request->supplier_id) && userInfo()->company_id==$request->company_id)
                                                                                    - <span class="text-warning">Supplier</span> [{{$request->supplier->company_name}}]
                                                                                @endif
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
                                                                    <div class="modal-body">
                                                                        <h4 class="text-info">Lens</h4>
                                                                        <hr>

                                                                        @if (!$request->unavailableproducts->isEmpty())
                                                                            @php
                                                                                $availability = true;
                                                                                $descripition = null;
                                                                                $right_len = $request->unavailableproducts->where('eye', 'right')->first();

                                                                                if (!$right_len) {
                                                                                    $right_len = $request->soldproduct->where('eye', 'right')->first();
                                                                                    $availability = false;
                                                                                }

                                                                                $left_len = $request->unavailableproducts->where('eye', 'left')->first();
                                                                                if (!$left_len) {
                                                                                    $left_len = $request->soldproduct->where('eye', 'left')->first();
                                                                                }
                                                                            @endphp
                                                                            @foreach ($request->unavailableproducts as $unavail)
                                                                                <div class="row mb-4">
                                                                                    <div class="col-3 row">
                                                                                        <h4 class="text-capitalize">
                                                                                            {{ $unavail->eye == null ? '' : Oneinitials($unavail->eye) }}
                                                                                        </h4>
                                                                                        <span class="ml-3">
                                                                                            @if ($availability)
                                                                                                {{ initials($unavail->type->name)=='BT'?'Bifocal Round Top':initials($unavail->type->name) }}
                                                                                                {{ $unavail->uchromatic->name }}
                                                                                                {{ $unavail->coating->name }}
                                                                                                {{ $unavail->uindex->name }}
                                                                                            @else
                                                                                                {{ $unavail->product->description }}
                                                                                            @endif
                                                                                        </span>
                                                                                    </div>
                                                                                    <div class="col-2">

                                                                                        <span>{{ format_values($unavail->sphere) }}
                                                                                            /
                                                                                            {{ format_values($unavail->cylinder) }}
                                                                                            *{{ $unavail->axis }}
                                                                                            {{ $unavail->addition }}</span>
                                                                                    </div>
                                                                                    <div class="col-2 row">
                                                                                        <span>
                                                                                            <h6>Location: </h6>
                                                                                        </span>
                                                                                        {{ $unavail->location == null ? '-' : $unavail->location }}
                                                                                    </div>
                                                                                    <div class="col-2 ">
                                                                                        <span
                                                                                            class="text-capitalize d-flex justify-content-around items-center">
                                                                                            <h6 class="text-dark">Mono
                                                                                                PD:
                                                                                            </h6>
                                                                                            <span class="text-capitalize">
                                                                                                {{ $unavail->mono_pd }}
                                                                                            </span>
                                                                                        </span>
                                                                                    </div>
                                                                                    <div class="col-2 ">
                                                                                        <span
                                                                                            class="text-capitalize d-flex justify-content-around items-center">
                                                                                            <h6 class="text-dark">Seg
                                                                                                H:
                                                                                            </h6>
                                                                                            <span class="text-capitalize">
                                                                                                {{ $unavail->segment_h }}
                                                                                            </span>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        @endif

                                                                        @if (!$request->soldproduct->isEmpty())
                                                                            @foreach ($request->soldproduct as $product)
                                                                                @php
                                                                                    $invoice_product = $product->product;
                                                                                    // dd($product->hasFrame());
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
                                                                                            @if (initials($invoice_product->product_name) == 'SV')
                                                                                                <span>{{ $invoice_product->power->sphere }}
                                                                                                    /
                                                                                                    {{ $invoice_product->power->cylinder }}</span>
                                                                                            @else
                                                                                                <span>{{ $invoice_product->power->sphere }}
                                                                                                    /
                                                                                                    {{ $invoice_product->power->cylinder }}
                                                                                                    *{{ $invoice_product->axis }}
                                                                                                    {{ $invoice_product->power->add }}</span>
                                                                                            @endif
                                                                                        </div>
                                                                                        <div class="col-2 row">
                                                                                            <span>
                                                                                                <h6>Location: </h6>
                                                                                            </span>
                                                                                            {{-- </div>
                                                                                    <div class="col-2"> --}}
                                                                                            {{ $product->location == null ? '-' : $product->location }}
                                                                                        </div>
                                                                                        <div class="col-2 ">
                                                                                            <span
                                                                                                class="text-capitalize d-flex justify-content-around items-center">
                                                                                                <h6 class="text-dark">
                                                                                                    Mono
                                                                                                    PD:
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
                                                                                                <h6 class="text-dark">
                                                                                                    Seg
                                                                                                    H:
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
                                                                        @endif

                                                                        {{-- @if ($product->hasFrame()) --}}

                                                                            {{-- for frame --}}
                                                                            <hr>
                                                                            <h4 class="text-info">Frame</h4>
                                                                            <hr>
                                                                            {{-- @if ($products) --}}
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
                                                                                                    <h4>Location:</h4>
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
                                                                            {{-- @endif --}}
                                                                        {{-- @endif --}}

                                                                        {{-- @if ($product->hasAccessories()) --}}

                                                                            <hr>
                                                                            <h4 class="text-info">Accessories & Others</h4>
                                                                            <hr>
                                                                            {{-- for accessories --}}
                                                                            {{-- @if ($products) --}}
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
                                                                            {{-- @endif --}}
                                                                        {{-- @endif --}}


                                                                        {{-- for frame --}}
                                                                        @if (is_null($request->supplier_id) || $request->supplier_id==userInfo()->company_id)
                                                                            <form
                                                                            action="{{ route('manager.sent.request.to.delivery') }}"
                                                                            method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="idsalfjei"
                                                                                required
                                                                                value="{{ Crypt::encrypt($request->id) }}">

                                                                            <hr>
                                                                            <h4 class="text-info">Operation</h4>
                                                                            {{-- <hr>    --}}
                                                                            <div
                                                                                class="modal-footer d-flex justify-content-between">
                                                                                <button type="button"
                                                                                    class="btn btn-danger waves-effect text-left"
                                                                                    data-dismiss="modal">
                                                                                    Close
                                                                                </button>
                                                                                    <button type="submit"
                                                                                        class="btn btn-info waves-effect text-left">
                                                                                        Deliver Order
                                                                                    </button>
                                                                                {{-- @endif --}}
                                                                            </div>
                                                                    </div>
                                                                    </form>
                                                                    @endif
                                                                </div>
                                                                <!-- /.modal-content -->
                                                            </div>
                                                            <!-- /.modal-dialog -->
                                                        </div>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            {{ $requests->links() }}
                                        </div>
                                    </div>


                                    <div id="extrnal-orders" class="tab-pane">
                                        <div class="table-responsive mt-4">
                                            <table id="zero_config" class="table table-striped table-bordered nowrap"
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="form-check form-check-inline align-content-center">
                                                                <input class="form-check-input" type="checkbox" id="checkall"
                                                                    value="">
                                                            </div>
                                                        </th>
                                                        <th>#</th>
                                                        <th>Request # </th>
                                                        <th>Patient Name</th>
                                                        <th>Request Date</th>
                                                        <th>Request Age</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ($requests_out as $key => $request)
                                                        <tr>
                                                            <td>
                                                                <div class="form-check form-check-inline align-content-center">
                                                                    <input class="form-check-input allCheckbox" type="checkbox"
                                                                        name="requestid[]" id="inlineCheckbox1" value="{{$request->id}}">
                                                                </div>
                                                            </td>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>
                                                                <a href="#!" data-toggle="modal"
                                                                    data-target="#external-{{ $key }}-detail">
                                                                    Request #{{ sprintf('SPCL-%04d', $request->id) }}
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
                                                            {{-- <td>
                                                                -
                                                            </td> --}}
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
                                                            id="external-{{ $key }}-detail" tabindex="-1"
                                                            role="dialog" aria-labelledby="myLargeModalLabel"
                                                            aria-hidden="true" style="display: none;">
                                                            <div class="modal-dialog modal-xl modal-dialog-centered">
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

                                                                                @if (!is_null($request->supplier_id) && userInfo()->company_id==$request->supplier_id)
                                                                                    - <span class="text-warning">From</span> [{{$request->company->company_name}}]
                                                                                @endif

                                                                                    @if (!is_null($request->supplier_id) && userInfo()->company_id==$request->company_id)
                                                                                    - <span class="text-warning">Supplier</span> [{{$request->supplier->company_name}}]
                                                                                @endif
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
                                                                    <div class="modal-body">
                                                                        <h4 class="text-info">Lens</h4>
                                                                        <hr>

                                                                        @if (!$request->unavailableproducts->isEmpty())
                                                                            @php
                                                                                $availability = true;
                                                                                $descripition = null;
                                                                                $right_len = $request->unavailableproducts->where('eye', 'right')->first();

                                                                                if (!$right_len) {
                                                                                    $right_len = $request->soldproduct->where('eye', 'right')->first();
                                                                                    $availability = false;
                                                                                }

                                                                                $left_len = $request->unavailableproducts->where('eye', 'left')->first();
                                                                                if (!$left_len) {
                                                                                    $left_len = $request->soldproduct->where('eye', 'left')->first();
                                                                                }
                                                                            @endphp
                                                                            @foreach ($request->unavailableproducts as $unavail)
                                                                                <div class="row mb-4">
                                                                                    <div class="col-3 row">
                                                                                        <h4 class="text-capitalize">
                                                                                            {{ $unavail->eye == null ? '' : Oneinitials($unavail->eye) }}
                                                                                        </h4>
                                                                                        <span class="ml-3">
                                                                                            @if ($availability)
                                                                                                {{ initials($unavail->type->name)=='BT'?'Bifocal Round Top':initials($unavail->type->name) }}
                                                                                                {{ $unavail->uchromatic->name }}
                                                                                                {{ $unavail->coating->name }}
                                                                                                {{ $unavail->uindex->name }}
                                                                                            @else
                                                                                                {{ $unavail->product->description }}
                                                                                            @endif
                                                                                        </span>
                                                                                    </div>
                                                                                    <div class="col-2">

                                                                                        <span>{{ format_values($unavail->sphere) }}
                                                                                            /
                                                                                            {{ format_values($unavail->cylinder) }}
                                                                                            *{{ $unavail->axis }}
                                                                                            {{ $unavail->addition }}</span>
                                                                                    </div>
                                                                                    <div class="col-2 row">
                                                                                        <span>
                                                                                            <h6>Location: </h6>
                                                                                        </span>
                                                                                        {{ $unavail->location == null ? '-' : $unavail->location }}
                                                                                    </div>
                                                                                    <div class="col-2 ">
                                                                                        <span
                                                                                            class="text-capitalize d-flex justify-content-around items-center">
                                                                                            <h6 class="text-dark">Mono
                                                                                                PD:
                                                                                            </h6>
                                                                                            <span class="text-capitalize">
                                                                                                {{ $unavail->mono_pd }}
                                                                                            </span>
                                                                                        </span>
                                                                                    </div>
                                                                                    <div class="col-2 ">
                                                                                        <span
                                                                                            class="text-capitalize d-flex justify-content-around items-center">
                                                                                            <h6 class="text-dark">Seg
                                                                                                H:
                                                                                            </h6>
                                                                                            <span class="text-capitalize">
                                                                                                {{ $unavail->segment_h }}
                                                                                            </span>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        @endif

                                                                        @if (!$request->soldproduct->isEmpty())
                                                                            @foreach ($request->soldproduct as $product)
                                                                                @php
                                                                                    $invoice_product = $product->product;
                                                                                    // dd($product->hasFrame());
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
                                                                                            @if (initials($invoice_product->product_name) == 'SV')
                                                                                                <span>{{ $invoice_product->power->sphere }}
                                                                                                    /
                                                                                                    {{ $invoice_product->power->cylinder }}</span>
                                                                                            @else
                                                                                                <span>{{ $invoice_product->power->sphere }}
                                                                                                    /
                                                                                                    {{ $invoice_product->power->cylinder }}
                                                                                                    *{{ $invoice_product->axis }}
                                                                                                    {{ $invoice_product->power->add }}</span>
                                                                                            @endif
                                                                                        </div>
                                                                                        <div class="col-2 row">
                                                                                            <span>
                                                                                                <h6>Location: </h6>
                                                                                            </span>
                                                                                            {{-- </div>
                                                                                    <div class="col-2"> --}}
                                                                                            {{ $product->location == null ? '-' : $product->location }}
                                                                                        </div>
                                                                                        <div class="col-2 ">
                                                                                            <span
                                                                                                class="text-capitalize d-flex justify-content-around items-center">
                                                                                                <h6 class="text-dark">
                                                                                                    Mono
                                                                                                    PD:
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
                                                                                                <h6 class="text-dark">
                                                                                                    Seg
                                                                                                    H:
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
                                                                        @endif

                                                                        {{-- @if ($product->hasFrame()) --}}

                                                                            {{-- for frame --}}
                                                                            <hr>
                                                                            <h4 class="text-info">Frame</h4>
                                                                            <hr>
                                                                            {{-- @if ($products) --}}
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
                                                                                                    <h4>Location:</h4>
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
                                                                            {{-- @endif --}}
                                                                        {{-- @endif --}}

                                                                        {{-- @if ($product->hasAccessories()) --}}

                                                                            <hr>
                                                                            <h4 class="text-info">Accessories & Others</h4>
                                                                            <hr>
                                                                            {{-- for accessories --}}
                                                                            {{-- @if ($products) --}}
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
                                                                            {{-- @endif --}}
                                                                        {{-- @endif --}}


                                                                        {{-- for frame --}}
                                                                        @if (is_null($request->supplier_id) || $request->supplier_id==userInfo()->company_id)
                                                                            <form
                                                                            action="{{ route('manager.sent.request.to.delivery') }}"
                                                                            method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="idsalfjei"
                                                                                required
                                                                                value="{{ Crypt::encrypt($request->id) }}">

                                                                            <hr>
                                                                            <h4 class="text-info">Operation</h4>
                                                                            {{-- <hr>    --}}
                                                                            <div
                                                                                class="modal-footer d-flex justify-content-between">
                                                                                <button type="button"
                                                                                    class="btn btn-danger waves-effect text-left"
                                                                                    data-dismiss="modal">
                                                                                    Close
                                                                                </button>
                                                                                    <button type="submit"
                                                                                        class="btn btn-info waves-effect text-left">
                                                                                        Deliver Order
                                                                                    </button>
                                                                                {{-- @endif --}}
                                                                            </div>
                                                                    </div>
                                                                    </form>
                                                                    @endif
                                                                </div>
                                                                <!-- /.modal-content -->
                                                            </div>
                                                            <!-- /.modal-dialog -->
                                                        </div>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            {{ $requests->links() }}
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <button onclick="document.getElementById('completedForm').submit()" class="btn btn-success btn-rounded">
                                    <i class="mdi mdi-truck"></i> Deliver Selected Orders
                                </button>
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
