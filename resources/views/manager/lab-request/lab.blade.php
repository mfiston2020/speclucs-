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
                            <h4 class="card-title">All Requested Products</h4>
                        </div>
                        {{-- ============================== --}}
                        @include('manager.includes.layouts.message')
                        {{-- =============================== --}}

                        <ul class="nav nav-pills mt-4 mb-4">
                            <li class=" nav-item">
                                <a href="#new-orders" class="nav-link active" data-toggle="tab" aria-expanded="false">
                                    New Orders
                                    <span class="badge badge-danger badge-pill">
                                        {{ count($requests) }}
                                    </span>
                                </a>
                            </li>
                            <li class=" nav-item">
                                <a href="#inProduction" class="nav-link" data-toggle="tab" aria-expanded="false">
                                    In Production
                                    <span class="badge badge-danger badge-pill">
                                        {{ count($requests_inProduction) }}
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#completed" class="nav-link" data-toggle="tab" aria-expanded="false">
                                    Completed
                                    <span class="badge badge-danger badge-pill">
                                        {{ count($requests_completed) }}
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#delivered" class="nav-link" data-toggle="tab" aria-expanded="true">
                                    Delivered
                                    <span class="badge badge-danger badge-pill">
                                        {{ count($requests_delivered) }}
                                    </span>
                                </a>
                            </li>
                            {{-- all other orders --}}
                            <li class="nav-item">
                                <a href="#other-order" class="nav-link" data-toggle="tab" aria-expanded="true">
                                    Order Status
                                    {{-- <span class="badge badge-info badge-pill">
                                        {{ count($other_orders) }}
                                    </span> --}}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content br-n pn">
            {{-- new orders --}}
            <div id="new-orders" class="tab-pane active">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content br-n pn">
                                    <div id="complete-orders" class="tab-pane active">
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
                                                    @foreach ($requests as $key => $request)
                                                        <tr>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>
                                                                <a href="#!" data-toggle="modal"
                                                                    data-target="#request-{{ $key }}-detail">
                                                                    Request #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                {{ $request->client_id != null ? $request->client->name : $request->client_name }}
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
                                                            id="request-{{ $key }}-detail" tabindex="-1"
                                                            role="dialog" aria-labelledby="myLargeModalLabel"
                                                            aria-hidden="true" style="display: none;">
                                                            <div class="modal-dialog modal-xl modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <div>
                                                                            <h4 class="modal-title text-info">
                                                                                {{ $request->client_id != null ? $request->client->name : $request->client_name }}
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
                                                                            @foreach ($request->unavailableproducts as $unavail)
                                                                                <div class="row mb-4">
                                                                                    <div class="col-3 row">
                                                                                        <h4 class="text-capitalize">
                                                                                            {{ $unavail->eye == null ? '' : Oneinitials($unavail->eye) }}
                                                                                        </h4>
                                                                                        <span class="ml-3">
                                                                                            @if ($availability)
                                                                                                {{ initials($type) }}
                                                                                                {{ $chrm }}
                                                                                                {{ $ct }}
                                                                                                {{ $indx }}
                                                                                            @else
                                                                                                {{ $description }}
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
                                                                                    $invoice_product = $products->where('id', $product->product_id)->first();
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
                                                                        @endif

                                                                        <hr>
                                                                        <h4 class="text-info">Accessories & Others</h4>
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


                                                                        {{-- for frame --}}
                                                                        <form
                                                                            action="{{ route('manager.sent.request.to.production') }}"
                                                                            method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="idsalfjei"
                                                                                required
                                                                                value="{{ Crypt::encrypt($request->id) }}">

                                                                            <hr>
                                                                            <h4 class="text-info">Operation</h4>
                                                                            <hr>
                                                                            <div class="row d-flex justify-content-end">
                                                                                <div class="form-group col-3">
                                                                                    <label for="delivery_date">Delivery
                                                                                        Date</label>
                                                                                    <input type="date"
                                                                                        name="delivery_date"
                                                                                        id="delivery_date" required
                                                                                        class="form-control"
                                                                                        min="{{ date('Y-m-d') }}">
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                    <div
                                                                        class="modal-footer d-flex justify-content-between">
                                                                        <button type="button"
                                                                            class="btn btn-danger waves-effect text-left"
                                                                            data-dismiss="modal">
                                                                            Close
                                                                        </button>
                                                                        <button type="button"
                                                                            class="btn btn-success waves-effect text-left"
                                                                            data-dismiss="modal">Print</button>

                                                                        @if ($request->status == 'sent to lab')
                                                                            <button type="submit"
                                                                                class="btn btn-info waves-effect text-left">
                                                                                Send To Production
                                                                            </button>
                                                                        @endif
                                                                    </div>
                                                                    </form>
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
                                    <div id="incomplete-orders" class="tab-pane">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- in production --}}
            <div id="inProduction" class="tab-pane">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('manager.sent.request.to.complete') }}" method="post">
                                    @csrf
                                    <button class="btn btn-primary">Complete</button>
                                    <div class="tab-content br-n pn">
                                        <div id="complete-orders" class="tab-pane active">
                                            <div class="table-responsive mt-4">
                                                <table id="zero_config" class="table table-striped table-bordered nowrap"
                                                    style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            {{-- <th>SN</th> --}}
                                                            <th>Request # </th>
                                                            <th>Patient Name</th>
                                                            {{-- <th>Product detail</th> --}}
                                                            {{-- <th>Branch Name</th> --}}
                                                            <th>Request Date</th>
                                                            <th>Request Age</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach ($requests_inProduction as $key => $request)
                                                            <tr>
                                                                {{-- <td>
                                                                <input type="checkbox" name="requestId[]" id="">
                                                            </td> --}}
                                                                <td>
                                                                    <input type="checkbox" name="requestid[]"
                                                                        value="{{ $request->id }}" />
                                                                </td>
                                                                <td>
                                                                    <a href="#!" data-toggle="modal">
                                                                        Request #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    {{ $request->client_id != null ? $request->client->name : $request->client_name }}
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
                                                        @endforeach
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- completed --}}
            <div id="completed" class="tab-pane">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('manager.sent.request.to.delivery') }}" method="post">
                                    @csrf
                                    <button class="btn btn-primary">Deliver</button>
                                    <div class="table-responsive mt-4">
                                        <table id="zero_config" class="table table-striped table-bordered nowrap"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    {{-- <th>SN</th> --}}
                                                    <th>Request # </th>
                                                    <th>Patient Name</th>
                                                    {{-- <th>Product detail</th> --}}
                                                    {{-- <th>Branch Name</th> --}}
                                                    <th>Request Date</th>
                                                    <th>Request Age</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($requests_completed as $key => $request)
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="requestid[]"
                                                                value="{{ $request->id }}" />
                                                        </td>
                                                        <td>
                                                            <a href="#!" data-toggle="modal">
                                                                Request #{{ sprintf('SPCL-%04d', $request->id) }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            {{ $request->client_id != null ? $request->client->name : $request->client_name }}
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

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- delivered --}}
            <div id="delivered" class="tab-pane">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive mt-4">
                                    <table id="zero_config" class="table table-striped table-bordered nowrap"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                {{-- <th>SN</th> --}}
                                                <th>Request # </th>
                                                <th>Patient Name</th>
                                                {{-- <th>Product detail</th> --}}
                                                {{-- <th>Branch Name</th> --}}
                                                <th>Request Date</th>
                                                <th>Request Age</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($requests_delivered as $key => $request)
                                                <tr>
                                                    {{-- <td>
                                                        <input type="checkbox" name="requestId[]" id="">
                                                    </td> --}}
                                                    <td>
                                                        {{ $key + 1 }}
                                                    </td>
                                                    <td>
                                                        <a href="#!" data-toggle="modal">
                                                            Request #{{ sprintf('SPCL-%04d', $request->id) }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ $request->client_id != null ? $request->client->name : $request->client_name }}
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
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- new orders --}}
            <div id="other-order" class="tab-pane">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div id="complete-orders" class="tab-pane active">
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
                                                @foreach ($other_orders as $key => $request)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>
                                                            <a href="#!" data-toggle="modal"
                                                                data-target="#request-{{ $key }}-detail">
                                                                Request #{{ sprintf('SPCL-%04d', $request->id) }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            {{ $request->client_id != null ? $request->client->name : $request->client_name }}
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
                                                        id="request-{{ $key }}-detail" tabindex="-1"
                                                        role="dialog" aria-labelledby="myLargeModalLabel"
                                                        aria-hidden="true" style="display: none;">
                                                        <div class="modal-dialog modal-xl modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <div>
                                                                        <h4 class="modal-title text-info">
                                                                            {{ $request->client_id != null ? $request->client->name : $request->client_name }}
                                                                        </h4>
                                                                        <br>

                                                                        <h4 class="modal-title"
                                                                            id="content-detail-{{ $key }}">
                                                                            Request
                                                                            #{{ sprintf('SPCL-%04d', $request->id) }}
                                                                        </h4>
                                                                    </div>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-hidden="true">×</button>
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
                                                                        @if (!$request->unavailableproducts->isEmpty())
                                                                        @foreach ($request->unavailableproducts as $unavail)
                                                                        <div class="row mb-4">
                                                                            <div class="col-3 row">
                                                                                <h4 class="text-capitalize">
                                                                                    {{ $unavail->eye == null ? '' : Oneinitials($unavail->eye) }}
                                                                                </h4>
                                                                                <span class="ml-3">
                                                                                    @if ($availability)
                                                                                        {{ initials($type) }}
                                                                                        {{ $chrm }}
                                                                                        {{ $ct }}
                                                                                        {{ $indx }}
                                                                                    @else
                                                                                        {{ $description }}
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
                                                                    @endif

                                                                    @if (!$request->soldproduct->isEmpty())
                                                                        @foreach ($request->soldproduct as $product)
                                                                            @php
                                                                                $invoice_product = $products->where('id', $product->product_id)->first();
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
                                                                                        {{ $product->location == null ? '-' : $product->location }}
                                                                                    </div>
                                                                                    <div class="col-2 ">
                                                                                        <span
                                                                                            class="text-capitalize d-flex justify-content-around items-center">
                                                                                            <h6 class="text-dark">
                                                                                                Mono
                                                                                                PD:
                                                                                            </h6>
                                                                                            <span class="text-capitalize">
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
                                                                                            <span class="text-capitalize">
                                                                                                {{ $product->segment_h }}
                                                                                            </span>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif

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
                                                                    @endif

                                                                    <hr>
                                                                    <h4 class="text-info">Accessories & Others</h4>
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
                                                                                            <span class="text-capitalize">
                                                                                                {{ $product->quantity }}
                                                                                            </span>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif


                                                                    {{-- for frame --}}
                                                                    <form
                                                                        action="{{ route('manager.sent.request.to.production') }}"
                                                                        method="post">
                                                                        @csrf
                                                                        <input type="hidden" name="idsalfjei" required
                                                                            value="{{ Crypt::encrypt($request->id) }}">

                                                                        <hr>
                                                                        <h4 class="text-info">Operation</h4>
                                                                        <hr>
                                                                        <div class="row d-flex justify-content-end">
                                                                            <div class="form-group col-3">
                                                                                <label for="delivery_date">Delivery
                                                                                    Date</label>
                                                                                <input type="date" name="delivery_date"
                                                                                    id="delivery_date" required
                                                                                    class="form-control"
                                                                                    min="{{ date('Y-m-d') }}">
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                                <div class="modal-footer d-flex justify-content-between">
                                                                    <button type="button"
                                                                        class="btn btn-danger waves-effect text-left"
                                                                        data-dismiss="modal">
                                                                        Close
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-success waves-effect text-left"
                                                                        data-dismiss="modal">Print</button>

                                                                    @if ($request->status == 'sent to lab')
                                                                        <button type="submit"
                                                                            class="btn btn-info waves-effect text-left">
                                                                            Send To Production
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                                </form>
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
            </div>

        </div>


    </div>
@endsection

@push('scripts')
@endpush
