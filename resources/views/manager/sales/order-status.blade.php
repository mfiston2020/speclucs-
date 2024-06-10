@extends('manager.includes.app')

@section('title', 'Dashboard - Sales')

@push('css')
    <link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current', __('navigation.sales'))
@section('page_name', __('navigation.sales'))
{{-- === End of breadcumb == --}}

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a href="#internal-order" class="nav-link active" data-toggle="tab"
                                        aria-expanded="false">
                                        Internal Orders
                                        <span class="badge badge-danger badge-pill">{{ number_format($other_orders->total()) }}</span>

                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#external-order" class="nav-link" data-toggle="tab"
                                        aria-expanded="false">
                                        External N/A
                                        <span class="badge badge-danger badge-pill">{{ number_format($other_orders_out->total()) }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        {{-- ============================== --}}
                        @include('manager.includes.layouts.message')
                        {{-- =============================== --}}
                    </div>
                </div>
            </div>
        {{-- </div> --}}

        {{-- <div class="row"> --}}
            <div class="tab-content br-n pn col-12">

                <div class="tab-pane active" id="internal-order" role="tabpanel">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <button onclick="exportAll('xls','Order Status');" class="btn btn-success float-right mb-3">
                                    <i class="fa fa-cloud-download-alt"></i>
                                    Excel
                                </button>

                                <div class="table-responsive">
                                    <div class="float-right">{{$other_orders->links()}}</div>
                                    <table id="zero_config" class="table table-striped table-bordered nowrap" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Request # </th>
                                                <th>Patient Name</th>
                                                <th>Track Order</th>
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
                                                        <a href="{{ route('manager.sales.edit', Crypt::encrypt($request->id)) }}">
                                                            Request #{{ sprintf('SPCL-%04d', $request->id) }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        @if ($request->client_id != null)
                                                            {{$request->client->name}}
                                                        @else
                                                            @if ($request->hospital_name!=null)
                                                                <span>
                                                                    [{{$request->cloud_id}}] {{$request->hospital_name}}
                                                                </sp>
                                                            @else
                                                                <span>
                                                                    {{$request->client_name}}
                                                                </sp>
                                                            @endif
                                                        @endif

                                                    </td>
                                                    <td>
                                                        <a href="{{ route('manager.request.tracking', Crypt::encrypt($request->id)) }}">
                                                            Track #{{ sprintf('SPCL-%04d', $request->id) }}
                                                        </a>
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
                                    <div class="float-right">{{$other_orders->links()}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="external-order" role="tabpanel">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <button onclick="exportAllexternal('xls','External Order Status');" class="btn btn-success float-right mb-3">
                                    <i class="fa fa-cloud-download-alt"></i>
                                    Excel
                                </button>

                                <div class="table-responsive">
                                    {{$other_orders_out->links()}}
                                    <table id="zero_config_external" class="table table-striped table-bordered nowrap" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Request # </th>
                                                <th>Vision Center</th>
                                                <th>Patient Name</th>
                                                <th>Track Order</th>
                                                <th>Request Date</th>
                                                <th>Request Age</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($other_orders_out as $key => $request)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>
                                                        <a href="{{ route('manager.sales.edit', Crypt::encrypt($request->id)) }}">
                                                            Request #{{ sprintf('SPCL-%04d', $request->id) }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ $request->company->company_name }}
                                                    </td>
                                                    <td>
                                                        @if ($request->client_id != null)
                                                            {{$request->client->name}}
                                                        @else
                                                            @if ($request->hospital_name!=null)
                                                                <span>
                                                                    [{{$request->cloud_id}}] {{$request->hospital_name}}
                                                                </sp>
                                                            @else
                                                                <span>
                                                                    {{$request->client_name}}
                                                                </sp>
                                                            @endif
                                                        @endif

                                                    </td>
                                                    <td>
                                                        <a href="{{ route('manager.request.tracking', Crypt::encrypt($request->id)) }}">
                                                            Track #{{ sprintf('SPCL-%04d', $request->id) }}
                                                        </a>
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
                                    <div class="float-right">{{$other_orders_out->links()}}</div>
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
    <script src="{{ asset('dashboard/assets/dist/js/export.js') }}"></script>
    <script>
        function exportAll(type, tableName) {

        $('#priced-table').tableExport({
                filename: tableName + '_%DD%-%mm%-%YY%',
                format: type
            });
        }
        function exportAllexternal(type, tableName) {

        $('#zero_config_external').tableExport({
                filename: tableName + '_%DD%-%mm%-%YY%',
                format: type
            });
        }
    </script>
@endpush
