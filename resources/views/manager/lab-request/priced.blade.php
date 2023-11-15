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
                                        $availability = true;
                                        $description = null;
                                        $right_len = $request->unavailableproducts->where('eye', 'right')->first();
                                        if (!$right_len) {
                                        $right_len = $request->soldproduct->where('eye', 'right')->first();
                                        if ($right_len==null) {
                                        continue;
                                        }
                                        $right_len = $right_len->product;
                                        $availability = false;
                                        }

                                        if ($availability == true) {
                                            $type = $lens_type->where('id', $right_len->type_id)->pluck('name')->first();

                                            $indx = $index->where('id', $right_len->index_id)->pluck('name')->first();

                                            $ct = $coatings->where('id', $right_len->coating_id)->pluck('name')->first();

                                            $chrm = $chromatics->where('id',$right_len->chromatic_id)->pluck('name')->first();
                                        } else {
                                        if ($right_len) {
                                                $description =
                                                $right_len->description;
                                            }
                                        }

                                        $left_len = $request->unavailableproducts->where('eye', 'left')->first();

                                        if (!$left_len) {
                                        $left_len = $request->soldproduct->where('eye', 'left')->first();
                                            $left_len = $left_len->product->power;
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
                                        <td class="text-start">
                                            <span @class([ 'text-info'=> $request->status == 'priced','text-success' =>
                                                $request->status == 'Confirmed',])>
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
