@extends('manager.includes.app')

@section('title','Admin Dashboard - Sales')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current',__('navigation.sales'))
@section('page_name',__('navigation.sales'))
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title text-capitalize">{{ __('manager/dispensing.all_sales') }}</h4><hr>

                        <a href="{{route('manager.new.order')}}" type="button"
                            class="btn waves-effect waves-light btn-rounded btn-outline-primary mr-3 text-capitalize"
                            style="align-items: right;">
                            <i class="fa fa-plus"></i> {{ __('manager/dispensing.new_order') }}
                        </a>

                        <a href="{{route('manager.pending.orders')}}" type="button"
                            class="btn waves-effect waves-light btn-rounded btn-outline-secondary mr-3 text-capitalize"
                            style="align-items: right;">
                            <i class="fa fa-bars"></i> {{ __('manager/sales.pending_orders') }}
                            <span class="badge badge-danger badge-pill">
                                {{(\App\Models\PendingOrder::where('company_id',userInfo()->company_id)->where('status','pending')->count())}}
                            </span>
                        </a>

                        {{-- <a href="{{route('manager.sales.customer.add')}}" type="button" class="btn waves-effect waves-light btn-rounded btn-outline-primary mr-3" style="align-items: right;">
                            <i class="fa fa-plus"></i> Create Customer Order
                        </a>

                        <a href="{{route('manager.sales.create')}}" type="button" class="btn waves-effect waves-light btn-rounded btn-outline-primary" style="align-items: right;">
                            <i class="fa fa-plus"></i> Retail Order
                        </a> --}}

                    </div>
                        <hr>
                        {{-- ================================= --}}
                        @include('manager.includes.layouts.message')
                        {{-- ========================== --}}

                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered nowrap"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Order</th>
                                    <th>Reference #</th>
                                    <th>Customer</th>
                                    <th>User</th>
                                    {{-- <th>Products</th> --}}
                                    <th>Ins</th>
                                    <th>T. Amnt</th>
                                    <th>Ins Due Amnt</th>
                                    <th>Pt Due Amnt</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales as $key=> $sale)

                                    <tr>
                                        @php
                                            $client=\App\Models\Customer::where(['id'=>$sale->client_id])->where('company_id',Auth::user()->company_id)->pluck('name')->first();

                                            $product=\App\Models\SoldProduct::where(['invoice_id'=>$sale->id])
                                                                                ->where('company_id',Auth::user()->company_id)
                                                                                ->select('product_id','insurance_id','insurance_payment','patient_payment')
                                                                                ->first();

                                            $amount_paid    =   \App\Models\Transactions::where('invoice_id',$sale->id)->select('amount')->sum('amount');
                                            $ins_due_amount =   \App\Models\SoldProduct::where(['invoice_id'=>$sale->id])
                                                                                ->where('company_id',Auth::user()->company_id)
                                                                                ->select('insurance_payment')
                                                                                ->sum('insurance_payment');
                                            $pt_due_amount =   \App\Models\SoldProduct::where(['invoice_id'=>$sale->id])
                                                                                ->where('company_id',Auth::user()->company_id)
                                                                                ->select('patient_payment')
                                                                                ->sum('patient_payment');
                                        @endphp
                                        <td>{{$key+1}}</td>
                                        <td>{{date('Y-m-d',strtotime($sale->created_at))}}</td>
                                        <td>
                                            <a href="{{route('manager.sales.edit',Crypt::encrypt($sale->id))}}">Order  #{{sprintf('%04d',$sale->id)}}</a>
                                        </td>
                                        <td>
                                            <a href="{{route('manager.sales.edit',Crypt::encrypt($sale->id))}}">Order#  {{$sale->reference_number}}</a>
                                        </td>
                                        <td>
                                            @if ($client)
                                                <center><span>{{$client}}</span></center>
                                            @else
                                                <center>
                                                    <span>{{$sale->client_name}}</span>
                                                </center>
                                            @endif
                                        </td>
                                        <td>{{\App\Models\User::where(['id'=>$sale->user_id])->where('company_id',Auth::user()->company_id)->pluck('name')->first()}}</td>
                                        <td>
                                            @if ($product && $product->insurance_id!=null)
                                                {{\App\Models\Insurance::where('id',$product->insurance_id)->pluck('insurance_name')->first()}}
                                            @else
                                                Private
                                            @endif
                                        </td>
                                        <td>{{format_money($sale->total_amount)}}</td>
                                        <td>
                                            {{format_money($ins_due_amount)}}
                                        </td>
                                        <td>
                                            @if ($product && $product->insurance_id!=null)
                                                {{format_money($pt_due_amount-$amount_paid)}}
                                            @else
                                                {{format_money($pt_due_amount-$amount_paid)}}
                                            @endif
                                        </td>
                                        {{-- <td >{{format_money($sale->due)}}</td> --}}
                                        <td>

                                            @if ($sale->status=='completed' && $sale->emailState=='submited')
                                                <span class="label label-warning">submited</span>
                                            @else
                                                <span class="label label-{{(($sale->status)=='completed')?'success':'danger'}}">{{$sale->status}}</span>
                                            @endif

                                            @if ($sale->payment=='paid')
                                                <span class="label label-success">{{$sale->payment}}</span>
                                            @endif

                                        </td>
                                        <td>
                                            @if ($sale->status=='completed')
                                                @if ($sale->due!=0 )
                                                    <a href="{{route('manager.pay.invoice.due',Crypt::encrypt($sale->id))}}" class="btn btn-warning btn-sm">
                                                        Pay Due
                                                    </a>
                                                @else
                                                    <span class="label label-info">Fully Paid</span>
                                                @endif
                                            @else
                                                <a href="{{route('manager.sales.edit',Crypt::encrypt($sale->id))}}" class="btn btn-primary btn-sm">edit</a>
                                                <a href="#" data-toggle="modal" data-target="#myModal-{{$key}}" class="btn btn-danger btn-sm">delete</a>
                                            @endif
                                        </td>
                                    </tr>

                                    <div id="myModal-{{$key}}" class="modal fade" tabindex="-1" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{route('manager.invoice.delete',Crypt::encrypt($sale->id))}}" method="post">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle"></i>
                                                            Warning</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <h4>Do you want to delete Invoice #{{sprintf('%04d',$sale->reference_number)}}?</h4>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit"
                                                            class="btn btn-info waves-effect">Yes</button>
                                                        <button type="button" class="btn btn-danger waves-effect"
                                                            data-dismiss="modal">No</button>
                                                    </div>
                                                </div>
                                            </form>
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
@endsection

@push('scripts')
    <script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js')}}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js')}}"></script>

@endpush
