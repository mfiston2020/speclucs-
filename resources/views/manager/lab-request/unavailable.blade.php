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
                        <h4 class="card-title">
                            <span class="hidden-xs-down">
                                N/A Products
                                <span class="badge badge-danger badge-pill">
                                    {{ $requests->total() }}
                                </span>
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
                                {{-- never been in stock before --}}
                                <div id="new-orders" class="tab-pane">
                                    @if (count($requests)>0)
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
                                                    @foreach ($requests as $key => $request)
                                                        @if (!$request->unavailableproducts->isEmpty())
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
                                                                    {{ $request->supplier?->company_name }}
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
                                                        @endif
                                                    @endforeach
                                                </tbody>

                                                {{$requests->links()}}
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
                                                                        @if (!is_null($request->supplier_id))
                                                                            - <span class="text-warning">Supplier:</span> [{{$request->supplier->company_name}}]
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
                                                                        @if (!$request->soldproduct)
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
                                                                        @if (!$request->unavailableproducts->isEmpty())
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
                                                                                            {{ initials($type)=='BT'?'Bifocal Round Top':initials($type) }}
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
                                                                                        *{{ format_values($productsold->axis??0) }}
                                                                                        {{ format_values($productsold->addition??0) }}
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
                                                                        {{-- @php
                                                                            $invoice_product = $products->where('id', $product->product_id)->first();
                                                                        @endphp --}}
                                                                        @if ($product->product->category_id == 2)
                                                                            <div class="row mb-2">
                                                                                <div class="col-6">
                                                                                    <span class="text-capitalize">
                                                                                        {{ $product->product->product_name }}
                                                                                        -
                                                                                        {{ $product->product->description }}
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
                                                                        {{-- @php
                                                                            $invoice_product = $products->where('id', $product->product_id)->first();
                                                                        @endphp --}}

                                                                        @if ($product->product->category_id != 2 && $product->product->category_id != 1)
                                                                            <div class="row mb-2">
                                                                                <div class="col-3">
                                                                                    <span class="text-capitalize">
                                                                                        {{ $product->product->product_name }}
                                                                                        -
                                                                                        {{ $product->product->description }}
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
                                                                @if (is_null($request->supplier_id))
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
                                                                                            value="{{$unavail->cost}}"
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
                                                                                            value="{{$unavail->price}}"
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
                                                                @endif
                                                            </div>
                                                            <div
                                                                class="modal-footer d-flex justify-content-between">
                                                                <button type="button"
                                                                    class="btn btn-danger waves-effect text-left"
                                                                    data-dismiss="modal">
                                                                    Close
                                                                </button>
                                                                @if (is_null($request->supplier_id))
                                                                    <button type="button"
                                                                        onclick="printModal({{ $key }})"
                                                                        class="btn btn-success waves-effect text-left"
                                                                        id="print">Print</button>
                                                                    <button
                                                                        class="btn btn-info waves-effect text-left">
                                                                        Set Price
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
