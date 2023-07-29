@extends('manager.includes.app')

@section('title', 'Admin Dashboard - Product')

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

                        <hr>

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">

                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#requested-products" role="tab">
                                    <span class="hidden-sm-up">
                                        <i class="ti-home"></i>
                                    </span>
                                    <span class="hidden-xs-down">Requested Products</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#po-sent" role="tab">
                                    <span class="hidden-sm-up">
                                        <i class="ti-home"></i>
                                    </span>
                                    <span class="hidden-xs-down">PO Sent</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#po-received" role="tab">
                                    <span class="hidden-sm-up">
                                        <i class="ti-home"></i>
                                    </span>
                                    <span class="hidden-xs-down">PO Received</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link " data-toggle="tab" href="#provided-to-lab" role="tab">
                                    <span class="hidden-sm-up">
                                        <i class="ti-home"></i>
                                    </span>
                                    <span class="hidden-xs-down">Provided to Lab</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link " data-toggle="tab" href="#new-lab-order" role="tab">
                                    <span class="hidden-sm-up">
                                        <i class="ti-home"></i>
                                    </span>
                                    <span class="hidden-xs-down">Lab New Order</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane  p-20" id="profile2" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills mt-4 mb-4">
                                <li class=" nav-item">
                                    <a href="#complete-orders" class="nav-link active" data-toggle="tab"
                                        aria-expanded="false">
                                        In Stock
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#incomplete-orders" class="nav-link" data-toggle="tab" aria-expanded="false">
                                        Out of Stock
                                    </a>
                                </li>
                            </ul>
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
                                                @foreach ($requests as $key => $request)
                                                    <tr>
                                                        {{-- <td>
                                                        <input type="checkbox" name="requestId[]" id="">
                                                    </td> --}}
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
                                                                <div class="modal-body" id="printable">
                                                                    <h4 class="text-info">Lens</h4>
                                                                    <hr>
                                                                    @foreach ($request->soldproduct as $product)
                                                                        @php
                                                                            $invoice_product = $products->where('id', $product->product_id)->first();
                                                                        @endphp

                                                                        {{-- for lens --}}
                                                                        @if ($invoice_product->category_id == 1)
                                                                            <div class="row mb-2">
                                                                                <div class="col-1">
                                                                                    <h4 class="text-capitalize">
                                                                                        {{ $product->eye == null ? '' : initials($product->eye) }}
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
                                                                                        <h6 class="text-dark">Mono PD:
                                                                                        </h6>
                                                                                        <span class="text-capitalize">
                                                                                            {{ $product->mono_pd }}
                                                                                        </span>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="col-2 ">
                                                                                    <span
                                                                                        class="text-capitalize d-flex justify-content-around items-center">
                                                                                        <h6 class="text-dark">Seg H:
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
                                                                    @if ($product)
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
                                                                                            <h4 class="text-dark">Qty:
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
                                                                <div class="modal-footer d-flex justify-content-between">
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
                                <div id="incomplete-orders" class="tab-pane">

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
    <script>
        $(function() {
            $("#print").click(function() {
                var mode = 'iframe'; //popup
                var close = mode == "popup";
                var options = {
                    mode: mode,
                    popClose: close
                };
                $("#printable").printArea(options);
            });
        });
    </script>
@endpush
