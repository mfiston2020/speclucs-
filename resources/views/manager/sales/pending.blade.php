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

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#Sent-to-lab" role="tab">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">
                                        Sent To Lab
                                        <span class="badge badge-danger badge-pill">
                                            {{ count($sales_sent_to_lab) }}
                                        </span>
                                    </span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#order-status" role="tab">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">
                                        Order Status
                                        <span class="badge badge-danger badge-pill">
                                            {{ count($other_orders) }}
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
                                                                <input type="checkbox" name="requestid[]"
                                                                    value="{{ $sale->id }}"
                                                                    {{ $sale->status == 'delivered' ? '' : 'disabled' }} />
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
                                                                @if ($client)
                                                                    <center><span>{{ $client }}</span></center>
                                                                @else
                                                                    <center>
                                                                        @if ($sale->hospital_name)
                                                                            <span>[{{$sale->cloud_id}}] {{ $sale->hospital_name }}</span>
                                                                        @else
                                                                            <span>{{ $sale->client_name }}</span>
                                                                        @endif
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
                                                            {{-- <td>
                                                                @if ($sale->status == 'completed')
                                                                    @if ($sale->due != 0)
                                                                        <a href="{{ route('manager.pay.invoice.due', Crypt::encrypt($sale->id)) }}"
                                                                            class="btn btn-warning btn-sm">
                                                                            Pay Due
                                                                        </a>
                                                                    @else
                                                                        <span class="label label-info">Fully Paid</span>
                                                                    @endif
                                                                @else
                                                                    <a href="{{ route('manager.sales.edit', Crypt::encrypt($sale->id)) }}"
                                                                        class="btn btn-primary btn-sm">edit</a>
                                                                    <a href="#" data-toggle="modal"
                                                                        data-target="#myModal-{{ $key }}"
                                                                        class="btn btn-danger btn-sm">delete</a>
                                                                @endif
                                                            </td> --}}
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

                                                                $unavailablePayment = \App\Models\UnavailableProduct::where('invoice_id',$sale->id)->sum('price');

                                                            @endphp
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
                                                                @if ($client)
                                                                    <center><span>{{ $client }}</span></center>
                                                                @else
                                                                    <center>
                                                                        @if ($sale->hospital_name)
                                                                            <span>[{{$sale->cloud_id}}] {{ $sale->hospital_name }}</span>
                                                                        @else
                                                                            <span>{{ $sale->client_name }}</span>
                                                                        @endif
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
                                                                    {{ format_money($pt_due_amount - $amount_paid + $unavailablePayment) }}
                                                                @else
                                                                    {{ format_money($pt_due_amount - $amount_paid + $unavailablePayment) }}
                                                                @endif
                                                            </td>
                                                            {{-- <td >{{format_money($sale->due)}}</td> --}}
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
                                                                @if ($client)
                                                                    <center><span>{{ $client }}</span></center>
                                                                @else
                                                                    <center>
                                                                        @if ($sale->hospital_name)
                                                                            <span>[{{$sale->cloud_id}}] {{ $sale->hospital_name }}</span>
                                                                        @else
                                                                            <span>{{ $sale->client_name }}</span>
                                                                        @endif
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
                                                            {{-- <td >{{format_money($sale->due)}}</td> --}}
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

            <div class="tab-pane" id="Sent-to-lab" role="tabpanel">
                <div class="tab-pane  p-20" id="profile2" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
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
                                                @foreach ($sales_sent_to_lab as $key => $sale)
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
                                                            {{ $key + 1 }}
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
                                                            @if ($client)
                                                                <center><span>{{ $client }}</span></center>
                                                            @else
                                                                <center>
                                                                    @if ($sale->hospital_name)
                                                                        <span>[{{$sale->cloud_id}}] {{ $sale->hospital_name }}</span>
                                                                    @else
                                                                        <span>{{ $sale->client_name }}</span>
                                                                    @endif
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
                                                        {{-- <td >{{format_money($sale->due)}}</td> --}}
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="order-status" role="tabpanel">
                <div class="tab-pane  p-20" id="profile2" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
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
    <script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
@endpush
