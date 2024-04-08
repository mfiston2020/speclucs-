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
                            <h4 class="card-title">All Requested Products
                                <span class="badge badge-danger badge-pill ml-2">
                                    {{number_format(count($invoicess)+count($invoicess_out))}}
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
                                            <a href="#internal_requested" class="nav-link active" data-toggle="tab"
                                                aria-expanded="false">
                                                Internal Order(s)
                                                <span class="badge badge-danger badge-pill ml-2">
                                                    {{count($invoicess)}}
                                                </span>
                                            </a>
                                        </li>

                                        <li class="ml-3 nav-item">
                                            <a href="#external_requested" class="nav-link" data-toggle="tab"
                                                aria-expanded="false">
                                                External Order(s)
                                                <span class="badge badge-danger badge-pill ml-2">
                                                    {{ count($invoicess_out) }}
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content br-n pn">
                                        {{-- completed and in stock --}}
                                        <div id="internal_requested" class="tab-pane active">
                                            @if (count($invoicess)>0)
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
                                                                {{-- <th>Supplier</th> --}}
                                                                <th>Status</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach ($invoicess as $key => $request)

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

                                                                    {{-- <td class="text-start">
                                                                        {{ $request->supplier?->company_name }}
                                                                    </td> --}}

                                                                    <td class="text-start">
                                                                        <span @class([
                                                                            'text-warning' => $request->status == 'requested',
                                                                        ])>
                                                                            {{ $request->status }}
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        @if ($request->status=='requested')
                                                                            <a href="{{route('manager.lab.order.cancel',Crypt::encrypt($request->id))}}" class="text-danger" onclick="return confirm('Are you sure??')">Cancel Order</a>
                                                                        @else
                                                                            <center>-</center>
                                                                        @endif
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
                                                                        $frameisOutOfStock='no';
                                                                        $accisOutOfStock='no';
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
                                                                                        @if (!is_null($request->supplier_id))
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
                                                                            <div class="modal-body" id="printable">
                                                                                {{-- @if ($product->hasLens()) --}}
                                                                                    <h4 class="text-info">Lens</h4>
                                                                                    <hr>
                                                                                    @foreach ($request->soldproduct as $product)
                                                                                        @php

                                                                                            if ($product->product->stock<2){
                                                                                                $isOutOfStock='yes';
                                                                                            }

                                                                                        @endphp

                                                                                        {{-- for lens --}}
                                                                                        @if ($product->product->category_id == 1)

                                                                                            <div class="row mb-2">
                                                                                                <div class="col-1">
                                                                                                    <h4
                                                                                                        class="text-capitalize">
                                                                                                        {{ $product->eye == null ? '' : Oneinitials($product->eye) }}
                                                                                                    </h4>
                                                                                                </div>
                                                                                                <div class="col-3">
                                                                                                    <span>
                                                                                                        {{ $product->product->description }}
                                                                                                    </span>
                                                                                                </div>
                                                                                                <div class="col-2">
                                                                                                    @if (initials($product->product->product_name) == 'SV')
                                                                                                        <span>{{ $product->product->power->sphere }}
                                                                                                            /
                                                                                                            {{ $product->product->power->cylinder }}</span>
                                                                                                    @else
                                                                                                        <span>{{ $product->product->power->sphere }}
                                                                                                            /
                                                                                                            {{ $product->product->power->cylinder }}
                                                                                                            *{{ $product->product->power->axis }}
                                                                                                            {{ $product->product->power->add }}</span>
                                                                                                    @endif
                                                                                                </div>
                                                                                                <div class="col-2 row">
                                                                                                    <span>
                                                                                                        <h6>Location: </h6>
                                                                                                    </span>
                                                                                                    {{ $product->product->location == null ? '-' : $product->product->location }}
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
                                                                                {{-- @endif --}}

                                                                                {{-- for frame --}}
                                                                                {{-- @if ($product->hasFrame()) --}}
                                                                                    <hr>
                                                                                    <h4 class="text-info">Frame</h4>
                                                                                    <hr>
                                                                                    {{-- @if ($product) --}}
                                                                                        @foreach ($request->soldproduct as $product)
                                                                                            @php

                                                                                                if ($product->product->stock<1){
                                                                                                    $frameisOutOfStock='yes';
                                                                                                }

                                                                                            @endphp

                                                                                            @if ($product->product->category_id == 2)
                                                                                                <div class="row mb-2">
                                                                                                    <div class="col-6">
                                                                                                        <span
                                                                                                            class="text-capitalize">
                                                                                                            {{ $product->product->product_name }}
                                                                                                            -
                                                                                                            {{ $product->product->description }}
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
                                                                                                            {{ $product->product->location == null ? '-' : $product->product->location }}
                                                                                                        </span>
                                                                                                    </div>
                                                                                                </div>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    {{-- @endif --}}
                                                                                {{-- @endif --}}

                                                                                {{-- @if ($product->hasAccessories()) --}}
                                                                                        <hr>
                                                                                    <h4 class="text-info">Accessories &
                                                                                        Others
                                                                                    </h4>
                                                                                    <hr>
                                                                                    {{-- for accessories --}}
                                                                                    {{-- @if ($product) --}}
                                                                                        @foreach ($request->soldproduct as $product)
                                                                                            @php

                                                                                                if ($product->product->stock<1){
                                                                                                    $accisOutOfStock='yes';
                                                                                                }

                                                                                            @endphp

                                                                                            @if ($product->product->category_id != 2 && $product->product->category_id != 1)
                                                                                                <div class="row mb-2">
                                                                                                    <div class="col-3">
                                                                                                        <span
                                                                                                            class="text-capitalize">
                                                                                                            {{ $product->product->product_name }}
                                                                                                            -
                                                                                                            {{ $product->product->description }}
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
                                                                                                            {{ $product->product->location == null ? '-' : $product->product->location }}
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
                                                                                    {{-- @endif --}}
                                                                                {{-- @endif --}}
                                                                            </div>
                                                                            @if (is_null($request->supplier_id))
                                                                                <div
                                                                                    class="modal-footer d-flex justify-content-between">

                                                                                        @if ($isOutOfStock=='yes' && $frameisOutOfStock=='yes' && $accisOutOfStock=='yes')
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
                                                                            @endif
                                                                        </div>
                                                                        <!-- /.modal-content -->
                                                                    </div>
                                                                    <!-- /.modal-dialog -->
                                                                </div>

                                                            @endforeach
                                                        </tbody>
                                                    </table>


                                                </div>
                                            @endif
                                        </div>



                                        {{-- @if (is_null(getUserCompanyInfo()->is_vision_center) && getUserCompanyInfo()->can_supply=='1') --}}
                                            {{-- completed and in stock --}}
                                            <div id="external_requested" class="tab-pane">
                                                @if (count($invoicess_out)>0)
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
                                                                    <th>Source</th>
                                                                    <th>Status</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                @foreach ($invoicess_out as $key => $request)

                                                                    <tr>
                                                                        <td>-</td>
                                                                        <td>
                                                                            <a href="#!" data-toggle="modal"
                                                                                data-target="#ext-request-{{ $key }}-detail">
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
                                                                            {{ $request->company?->company_name }}
                                                                        </td>

                                                                        <td class="text-start">
                                                                            <span @class([
                                                                                'text-warning' => $request->status == 'requested',
                                                                            ])>
                                                                                {{ $request->status }}
                                                                            </span>
                                                                        </td>
                                                                        <td>
                                                                            @if ($request->status=='requested')
                                                                                <a href="{{route('manager.lab.order.cancel',Crypt::encrypt($request->id))}}" class="text-danger" onclick="return confirm('Are you sure??')">Cancel Order</a>
                                                                            @else
                                                                                <center>-</center>
                                                                            @endif
                                                                        </td>
                                                                    </tr>

                                                                    {{-- modal --}}
                                                                    <div class="modal fade bs-example-modal-lg"
                                                                        id="ext-request-{{ $key }}-detail"
                                                                        tabindex="-1" role="dialog"
                                                                        aria-labelledby="myLargeModalLabel"
                                                                        aria-hidden="true" style="display: none;">
                                                                        @php
                                                                            $isOutOfStock='no';
                                                                            $frameisOutOfStock='no';
                                                                            $accisOutOfStock='no';
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
                                                                                <div class="modal-body" id="printable">
                                                                                    {{-- @if ($product->hasLens()) --}}
                                                                                        <h4 class="text-info">Lens</h4>
                                                                                        <hr>
                                                                                        @foreach ($request->soldproduct as $product)
                                                                                            @php

                                                                                                if ($product->product->stock<2){
                                                                                                    $isOutOfStock='yes';
                                                                                                }

                                                                                            @endphp

                                                                                            {{-- for lens --}}
                                                                                            @if ($product->product->category_id == 1)

                                                                                                <div class="row mb-2">
                                                                                                    <div class="col-1">
                                                                                                        <h4
                                                                                                            class="text-capitalize">
                                                                                                            {{ $product->eye == null ? '' : Oneinitials($product->eye) }}
                                                                                                        </h4>
                                                                                                    </div>
                                                                                                    <div class="col-3">
                                                                                                        <span>
                                                                                                            {{ $product->product->description }}
                                                                                                        </span>
                                                                                                    </div>
                                                                                                    <div class="col-2">
                                                                                                        @if (initials($product->product->product_name) == 'SV')
                                                                                                            <span>{{ $product->product->power->sphere }}
                                                                                                                /
                                                                                                                {{ $product->product->power->cylinder }}</span>
                                                                                                        @else
                                                                                                            <span>{{ $product->product->power->sphere }}
                                                                                                                /
                                                                                                                {{ $product->product->power->cylinder }}
                                                                                                                *{{ $product->product->power->axis }}
                                                                                                                {{ $product->product->power->add }}</span>
                                                                                                        @endif
                                                                                                    </div>
                                                                                                    <div class="col-2 row">
                                                                                                        <span>
                                                                                                            <h6>Location: </h6>
                                                                                                        </span>
                                                                                                        {{ $product->product->location == null ? '-' : $product->product->location }}
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
                                                                                    {{-- @endif --}}

                                                                                    {{-- for frame --}}
                                                                                    {{-- @if ($product->hasFrame()) --}}
                                                                                        <hr>
                                                                                        <h4 class="text-info">Frame</h4>
                                                                                        <hr>
                                                                                        @if ($product)
                                                                                            @foreach ($request->soldproduct as $product)
                                                                                            @php

                                                                                                if ($product->product->stock<2){
                                                                                                    $frameisOutOfStock='yes';
                                                                                                }

                                                                                            @endphp

                                                                                                @if ($product->product->category_id == 2)
                                                                                                    <div class="row mb-2">
                                                                                                        <div class="col-6">
                                                                                                            <span
                                                                                                                class="text-capitalize">
                                                                                                                {{ $product->product->product_name }}
                                                                                                                -
                                                                                                                {{ $product->product->description }}
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
                                                                                                                {{ $product->product->location == null ? '-' : $product->product->location }}
                                                                                                            </span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                @endif
                                                                                            @endforeach
                                                                                        @endif
                                                                                    {{-- @endif --}}

                                                                                    {{-- @if ($product->hasAccessories()) --}}
                                                                                    <hr>

                                                                                        <h4 class="text-info">Accessories &
                                                                                            Others
                                                                                        </h4>
                                                                                        <hr>
                                                                                        {{-- for accessories --}}
                                                                                        @if ($product)
                                                                                            @foreach ($request->soldproduct as $product)    @php

                                                                                                    if ($product->product->stock<1){
                                                                                                        $accisOutOfStock='yes';
                                                                                                    }

                                                                                                @endphp

                                                                                                @if ($product->product->category_id != 2 && $product->product->category_id != 1)
                                                                                                    <div class="row mb-2">
                                                                                                        <div class="col-3">
                                                                                                            <span
                                                                                                                class="text-capitalize">
                                                                                                                {{ $product->product->product_name }}
                                                                                                                -
                                                                                                                {{ $product->product->description }}
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
                                                                                                                {{ $product->product->location == null ? '-' : $product->product->location }}
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
                                                                                    {{-- @endif --}}
                                                                                </div>
                                                                                @if (!is_null($request->supplier_id) && $request->supplier_id==getUserCompanyInfo()->id)
                                                                                    <div class="modal-footer d-flex justify-content-between">

                                                                                            @if ($isOutOfStock=='yes' && $accisOutOfStock=='yes' && $frameisOutOfStock=='yes')
                                                                                                <center><h4 class="text-danger">Product out of stock</h4></center>
                                                                                            @else
                                                                                                <button type="button"
                                                                                                    class="btn btn-danger waves-effect text-left" data-dismiss="modal">
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
                                                                                @endif
                                                                            </div>
                                                                            <!-- /.modal-content -->
                                                                        </div>
                                                                        <!-- /.modal-dialog -->
                                                                    </div>

                                                                @endforeach
                                                            </tbody>
                                                        </table>


                                                    </div>
                                                @endif
                                            </div>
                                        {{-- @endif --}}

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
