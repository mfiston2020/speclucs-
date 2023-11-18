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
                            Priced Products
                            <span class="badge badge-danger badge-pill">{{ count($requests_priced) }}</span>
                        </h4>
                    </div>
                    {{-- ============================== --}}
                    @include('manager.includes.layouts.message')
                    {{-- =============================== --}}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if (count($requests_priced)<=0) <div
                        class="alert alert-warning alert-rounded col-lg-7 col-md-9 col-sm-12">
                        <b>Warning! </b> Nothing to show here
                </div>
                @else
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
                                <th>Patient Name</th>
                                <th>Description</th>
                                <th>Right Eye</th>
                                <th>Left Eye</th>
                                <th>Request Date</th>
                                <th>Request Age</th>
                                {{-- <th>Cost</th> --}}
                                <th>Payment</th>
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
                                                {{$request->client->name}}
                                            @else
                                                @if ($request->hospital_name!=null)
                                                    [{{$request->cloud_id}}] {{$request->hospital_name}}
                                                @else
                                                    {{$request->client_name}}
                                                @endif
                                            @endif
                                        </td>
                                        @php
                                            $availability_right = true;
                                            $availability_left = true;
                                            $description = null;
                                            $right_len = $request->unavailableproducts->where('eye', 'right')->first();

                                            // dd($right_len);

                                            if (!$right_len) {
                                                $right_len = $request->soldproduct->where('eye', 'right')->first();
                                                if ($right_len==null) {
                                                    continue;
                                                }
                                                dd('hello');
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
                                            {{ initials($type) }} {{ $chrm }}
                                            {{ $ct }} {{ $indx }}
                                            @else
                                            {{ $description }}
                                            @endif
                                        </td>

                                        <td>

                                            @if ($right_len)
                                            @if ($availability_right)
                                            <span>
                                                {{ format_values($right_len->sphere) }}
                                                /
                                                {{ format_values($right_len->cylinder) }}
                                                *{{ format_values($right_len->axis) }}
                                                {{ format_values($right_len->addition) }}
                                            </span>
                                            @else
                                            <span>
                                                {{ format_values($right_len->power->sphere) }}
                                                /
                                                {{ format_values($right_len->power->cylinder) }}
                                                *{{ format_values($right_len->power->axis) }}
                                                {{ format_values($right_len->power->add) }}
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
                                        <td class="text-start">
                                            <span @class([ 'text-info'=> $request->status == 'priced','text-success' =>
                                                $request->status == 'Confirmed',])>
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
                                                            $invoice_product = $product->product;

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
