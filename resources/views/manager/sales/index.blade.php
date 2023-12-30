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
                            <h4 class="card-title text-capitalize">{{ __('manager/dispensing.all_sales') }}</h4>
                            <hr>

                            <a href="{{ route('manager.retail') }}" type="button"
                                class="btn waves-effect waves-light btn-rounded btn-outline-success mr-3 text-capitalize"
                                style="align-items: right;">
                                <i class="fa fa-plus"></i> Retail
                            </a>

                            {{-- <a href="{{ route('manager.new.order') }}" type="button"
                                class="btn waves-effect waves-light btn-rounded btn-outline-primary mr-3 text-capitalize"
                                style="align-items: right;">
                                <i class="fa fa-plus"></i> Wholesale
                            </a> --}}

                            <a href="{{ route('manager.pending.orders') }}" type="button"
                                class="btn waves-effect waves-light btn-rounded btn-outline-secondary mr-3 text-capitalize"
                                style="align-items: right;">
                                <i class="fa fa-bars"></i> {{ __('manager/sales.pending_orders') }}

                                @if ($pending > 0)
                                    <span class="badge badge-danger badge-pill">
                                        {{ number_format($pending) }}
                                    </span>
                                @endif
                            </a>

                        </div>
                        <hr>
                        {{-- ================================= --}}
                        @include('manager.includes.layouts.message')
                        {{-- ========================== --}}

                        <div class="table-responsive">
                            <form action="{{ route('manager.sent.request.to.dispense') }}" method="post">
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sales as $key => $sale)
                                            <tr>
                                                @php
                                                    $client = \App\Models\Customer::where(['id' => $sale->client_id])
                                                        ->where('company_id', Auth::user()->company_id)
                                                        ->pluck('name')
                                                        ->first();

                                                    $product = \App\Models\SoldProduct::where(['invoice_id' => $sale->id])
                                                        ->where('company_id', Auth::user()->company_id)
                                                        ->select('product_id', 'insurance_id', 'insurance_payment', 'patient_payment')
                                                        ->first();

                                                    $amount_paid = \App\Models\Transactions::where('invoice_id', $sale->id)
                                                        ->select('amount')
                                                        ->sum('amount');
                                                    $ins_due_amount = \App\Models\SoldProduct::where(['invoice_id' => $sale->id])
                                                        ->where('company_id', Auth::user()->company_id)
                                                        ->select('insurance_payment')
                                                        ->sum('insurance_payment');
                                                    $pt_due_amount = \App\Models\SoldProduct::where(['invoice_id' => $sale->id])
                                                        ->where('company_id', Auth::user()->company_id)
                                                        ->select('patient_payment')
                                                        ->sum('patient_payment');
                                                @endphp
                                                <td>
                                                    <input type="checkbox" name="requestid[]" value="{{ $sale->id }}"
                                                        {{ $sale->status == 'collected' ? 'checked disabled' : '' }} />
                                                </td>
                                                <td>{{ date('Y-m-d', strtotime($sale->created_at)) }}</td>
                                                <td>
                                                    <a href="{{ route('manager.sales.edit', Crypt::encrypt($sale->id)) }}">Order
                                                        #{{ sprintf('%04d', $sale->id) }}</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('manager.sales.edit', Crypt::encrypt($sale->id)) }}">Order#
                                                        {{ $sale->reference_number }}</a>
                                                </td>
                                                <td>
                                                    @if ($client)
                                                        <center><span>{{ $client }}</span></center>
                                                    @else
                                                        <center>
                                                            <span>{{ $sale->client_name }}</span>
                                                        </center>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($product && $product->insurance_id != null)
                                                        {{ \App\Models\Insurance::where('id', $product->insurance_id)->pluck('insurance_name')->first() }}
                                                    @else
                                                        Private
                                                    @endif
                                                </td>
                                                <td>{{ format_money($sale->total_amount == null || $sale->total_amount == 'completed' ? 0 : $sale->total_amount) }}
                                                </td>
                                                <td>
                                                    {{ format_money($ins_due_amount == null ? 0 : $ins_due_amount) }}
                                                </td>
                                                <td>
                                                    @if ($product && $product->insurance_id != null)
                                                        {{ format_money($pt_due_amount - $amount_paid) }}
                                                    @else
                                                        {{ format_money($pt_due_amount - $amount_paid) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <span @class([
                                                        'label',
                                                        'label-warning' => $sale->status == 'delivered',
                                                        'label-info' => $sale->status == 'received',
                                                        'label-success' => $sale->status == 'dispensed',
                                                    ])>
                                                        {{ $sale->status }}
                                                    </span>

                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                <hr>
                                <button class="btn btn-success">Despense</button>
                            </form>
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
