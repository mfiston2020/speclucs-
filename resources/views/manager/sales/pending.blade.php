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
                            <h4 class="card-title">All Requested Products</h4>
                        </div>
                        {{-- ============================== --}}
                        @include('manager.includes.layouts.message')
                        {{-- =============================== --}}

                        <hr>

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs customtab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#requested-products" role="tab">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">
                                        Requested Products
                                        <span class="badge badge-danger badge-pill">
                                            {{ count($sales_requested) }}
                                        </span>
                                    </span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#priced" role="tab">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">
                                        Priced
                                        {{-- @if (!$sales_priced->isEmpty()) --}}
                                        <span class="badge badge-danger badge-pill">
                                            {{ count($sales_priced) }}
                                        </span>
                                        {{-- @endif --}}
                                    </span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#Delivered" role="tab">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">
                                        Delivered
                                        <span class="badge badge-danger badge-pill">
                                            {{ count($sales_delivered) }}
                                        </span>
                                    </span>
                                </a>
                            </li>

                        </ul>

                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content">

            <div class="tab-pane active" id="requested-products" role="tabpanel">
                <div class="tab-pane  p-20" id="profile2" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <form action="{{ route('manager.sent.request.to.receive') }}" method="post">
                                            @csrf
                                            <table id="zero_config" class="table table-striped table-bordered nowrap"
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>{{ __('manager/sales.date') }}</th>
                                                        <th>{{ __('manager/sales.order') }}</th>
                                                        <th>{{ __('manager/sales.reference_number') }} #</th>
                                                        <th>{{ __('manager/sales.customer') }}</th>
                                                        <th>{{ __('manager/sales.ins') }}</th>
                                                        <th>T. Amnt</th>
                                                        <th>Ins Due Amnt</th>
                                                        <th>Pt Due Amnt</th>
                                                        <th>Status</th>
                                                        {{-- <th></th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($sales_requested as $key => $sale)
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" name="requestid[]"
                                                                    value="{{ $sale->id }}"
                                                                    {{ $sale->status == 'delivered' ? '' : 'disabled' }} />
                                                            </td>
                                                            <td>{{ date('Y-m-d', strtotime($sale->created_at)) }}</td>
                                                            <td>
                                                                <a href="{{ route('manager.sales.edit', Crypt::encrypt($sale->id)) }}">Order
                                                                    #{{ sprintf('%04d', $sale->id) }}</a>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('manager.sales.edit', Crypt::encrypt($sale->id)) }}">
                                                                    Order#
                                                                    {{ $sale->reference_number }}</a>
                                                            </td>
                                                            <td>
                                                                @if ($sale->client_id != null)
                                                                    {{$sale->client->name}}
                                                                @else
                                                                    @if ($sale->hospital_name!=null)
                                                                        [{{$sale->cloud_id}}] {{$sale->hospital_name}}
                                                                    @else
                                                                        {{$sale->client_name}}
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($sale->insurance_id != null)
                                                                    {{ \App\Models\Insurance::where('id',$sale->insurance_id)->pluck('insurance_name')->first() }}
                                                                @else
                                                                    Private
                                                                @endif
                                                            </td>
                                                            <td>{{ format_money($sale->soldproduct_sum_total_amount) }}
                                                            </td>
                                                            <td>
                                                                {{ format_money($sale->insurance_payment) }}
                                                            </td>
                                                            <td>
                                                                {{ format_money($sale->soldproduct_sum_total_amount - $sale->insurance_payment)}}
                                                            </td>
                                                            <td>

                                                                @if ($sale->status == 'completed' && $sale->emailState == 'submited')
                                                                    <span class="label label-warning">submited</span>
                                                                @else
                                                                    <span
                                                                        class="label label-{{ $sale->status == 'completed' ? 'success' : 'danger' }}">{{ $sale->status }}</span>
                                                                @endif

                                                                @if ($sale->payment == 'paid')
                                                                    <span
                                                                        class="label label-success">{{ $sale->payment }}</span>
                                                                @endif

                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- priced orders --}}
            <div class="tab-pane" id="priced" role="tabpanel">
                <div class="tab-pane  p-20" id="profile2" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <form action="{{ route('manager.sent.request.to.receive') }}" method="post">
                                            @csrf
                                            <table id="zero_config1" class="table table-striped table-bordered nowrap"
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Payment</th>
                                                        <th>{{ __('manager/sales.date') }}</th>
                                                        <th>{{ __('manager/sales.order') }}</th>
                                                        <th>{{ __('manager/sales.reference_number') }} #</th>
                                                        <th>{{ __('manager/sales.customer') }}</th>
                                                        <th>{{ __('manager/sales.ins') }}</th>
                                                        <th>T. Amnt</th>
                                                        <th>Ins Due Amnt</th>
                                                        <th>Pt Due Amnt</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($sales_priced as $key => $sale)
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" name="requestid[]"
                                                                    value="{{ $sale->id }}"
                                                                    {{ $sale->status == 'delivered' ? '' : 'disabled' }} />
                                                                {{-- {{ $key + 1 }} --}}
                                                            </td>
                                                            <td>
                                                                <a onclick="return confirm('Are you sure??')"
                                                                    href="{{ route('manager.sent.request.confirm.payment', Crypt::encrypt($sale->id)) }}"
                                                                    class="btn btn-info btn-sm">
                                                                    Approve
                                                                </a>
                                                                <a onclick="return confirm('Are you sure you want to cancel??')"
                                                                    href="{{ route('manager.sent.request.cancel.payment', Crypt::encrypt($sale->id)) }}"
                                                                    class="btn btn-danger btn-sm">
                                                                    Cancel
                                                                </a>
                                                            </td>
                                                            <td>{{ date('Y-m-d', strtotime($sale->created_at)) }}</td>
                                                            <td>
                                                                <a
                                                                    href="{{ route('manager.sales.edit', Crypt::encrypt($sale->id)) }}">Order
                                                                    #{{ sprintf('%04d', $sale->id) }}</a>
                                                            </td>
                                                            <td>
                                                                <a
                                                                    href="{{ route('manager.sales.edit', Crypt::encrypt($sale->id)) }}">
                                                                    Order#
                                                                    {{ $sale->reference_number }}</a>
                                                            </td>

                                                            <td>
                                                                @if ($sale->client_id != null)
                                                                    {{$sale->client->name}}
                                                                @else
                                                                    @if ($sale->hospital_name!=null)
                                                                        [{{$sale->cloud_id}}] {{$sale->hospital_name}}
                                                                    @else
                                                                        {{$sale->client_name}}
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($sale->insurance_id != null)
                                                                    {{ \App\Models\Insurance::where('id',$sale->insurance_id)->pluck('insurance_name')->first() }}
                                                                @else
                                                                    Private
                                                                @endif
                                                            </td>
                                                            <td>{{ format_money($sale->soldproduct_sum_total_amount) }}
                                                            </td>
                                                            <td>
                                                                {{ format_money($sale->insurance_payment) }}
                                                            </td>
                                                            <td>
                                                                {{ format_money($sale->soldproduct_sum_total_amount - $sale->insurance_payment)}}
                                                            </td>
                                                            <td>

                                                                @if ($sale->status == 'completed' && $sale->emailState == 'submited')
                                                                    <span class="label label-warning">submited</span>
                                                                @else
                                                                    <span
                                                                        class="label label-{{ $sale->status == 'completed' ? 'success' : 'danger' }}">{{ $sale->status }}</span>
                                                                @endif

                                                                @if ($sale->payment == 'paid')
                                                                    <span
                                                                        class="label label-success">{{ $sale->payment }}</span>
                                                                @endif

                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- delivered orders --}}
            <div class="tab-pane" id="Delivered" role="tabpanel">
                <div class="tab-pane  p-20" id="profile2" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <form action="{{ route('manager.sent.request.to.receive') }}" method="post">
                                            @csrf
                                            <table id="zero_config1" class="table table-striped table-bordered nowrap"
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>{{ __('manager/sales.date') }}</th>
                                                        <th>{{ __('manager/sales.order') }}</th>
                                                        <th>{{ __('manager/sales.reference_number') }} #</th>
                                                        <th>{{ __('manager/sales.customer') }}</th>
                                                        <th>{{ __('manager/sales.ins') }}</th>
                                                        <th>T. Amnt</th>
                                                        <th>Ins Due Amnt</th>
                                                        <th>Pt Due Amnt</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($sales_delivered as $key => $sale)
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" name="requestid[]"
                                                                    value="{{ $sale->id }}"
                                                                    {{ $sale->status == 'delivered' ? '' : 'disabled' }} />
                                                                {{-- {{ $key + 1 }} --}}
                                                            </td>
                                                            <td>{{ date('Y-m-d', strtotime($sale->created_at)) }}</td>
                                                            <td>
                                                                <a
                                                                    href="{{ route('manager.sales.edit', Crypt::encrypt($sale->id)) }}">Order
                                                                    #{{ sprintf('%04d', $sale->id) }}</a>
                                                            </td>
                                                            <td>
                                                                <a
                                                                    href="{{ route('manager.sales.edit', Crypt::encrypt($sale->id)) }}">
                                                                    Order#
                                                                    {{ $sale->reference_number }}</a>
                                                            </td>
                                                            <td>
                                                                @if ($sale->client_id != null)
                                                                    {{$sale->client->name}}
                                                                @else
                                                                    @if ($sale->hospital_name!=null)
                                                                        [{{$sale->cloud_id}}] {{$sale->hospital_name}}
                                                                    @else
                                                                        {{$sale->client_name}}
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($sale->insurance_id != null)
                                                                    {{ \App\Models\Insurance::where('id',$sale->insurance_id)->pluck('insurance_name')->first() }}
                                                                @else
                                                                    Private
                                                                @endif
                                                            </td>
                                                            <td>{{ format_money($sale->soldproduct_sum_total_amount) }}
                                                            </td>
                                                            <td>
                                                                {{ format_money($sale->insurance_payment) }}
                                                            </td>
                                                            <td>
                                                                {{ format_money($sale->soldproduct_sum_total_amount - $sale->insurance_payment)}}
                                                            </td>
                                                            <td>

                                                                @if ($sale->status == 'completed' && $sale->emailState == 'submited')
                                                                    <span class="label label-warning">submited</span>
                                                                @else
                                                                    <span
                                                                        class="label label-{{ $sale->status == 'completed' ? 'success' : 'danger' }}">{{ $sale->status }}</span>
                                                                @endif

                                                                @if ($sale->payment == 'paid')
                                                                    <span
                                                                        class="label label-success">{{ $sale->payment }}</span>
                                                                @endif

                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                            <hr>
                                            <button class="btn btn-success">Receive</button>
                                        </form>
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
@endpush
