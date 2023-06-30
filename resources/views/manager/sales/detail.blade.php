@extends('manager.includes.app')

@section('title', 'Admin Dashboard - Order Detail')

{{-- ==== Breadcumb ======== --}}
@section('current', 'Order Detail')
@section('page_name', 'Order Detail')
{{-- === End of breadcumb == --}}

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Products: <strong>{{ count($products) + count($na_products) }}</strong>
                            </h4>
                            <hr>
                            @if ($invoice->status == 'completed')
                                @if ($invoice->payment == 'paid')

                                    <a href="{{ route('manager.invoice.receipt', Crypt::encrypt($invoice->id)) }}"
                                        class="pull-right btn btn-outline-warning"><i class="fa fa-print"></i> Print
                                        Invoice
                                    </a>


                                    {{-- <a href="{{route('manager.sales.send.to.lab',Crypt::encrypt($invoice->id))}}"
                            class="pull-right btn btn-outline-primary ml-2"><i class="fa fa-paper-plane"></i> Send To Lab </a> --}}
                                @else
                                    @if ($invoice->client_id != null)
                                        {{-- <a href="{{route('manager.cutomerInvoice')}}"
                                    class="pull-right btn btn-outline-secondary" style="margin-right: 10px">create Invoice</a> --}}
                                    @endif

                                    @if ($has_na_products>0)
                                        <a href="{{ route('manager.sell.na.product.off', Crypt::encrypt($invoice->id)) }}"
                                            class="pull-right btn btn-outline-secondary mr-2" onclick="return confirm('Are you sure ?')"><i class="fa fa-basket"></i>
                                            Sell Off
                                        </a>
                                    @endif

                                    <a href="{{ route('manager.invoice.pay', Crypt::encrypt($invoice->id)) }}"
                                        class="pull-right btn btn-outline-primary">Proceed Payment</a>
                                @endif
                            @else
                                <a href="{{ route('manager.sales.add', Crypt::encrypt($invoice->id)) }}"
                                    class="pull-right btn btn-outline-secondary">Add Product</a>
                            @endif
                        </div>
                        <div class="table-responsive m-t-40">
                            {{-- ====== input error message ========== --}}
                            @include('manager.includes.layouts.message')
                            {{-- ====================================== --}}
                            <table class="table stylish-table">
                                <thead>
                                    <tr>
                                        <th colspan="2">Product</th>
                                        <th>quantity</th>
                                        <th>Price / unit</th>
                                        <th>Discount</th>
                                        <th>Total</th>
                                        @if ($invoice->status == 'completed')
                                        @else
                                            <th>Operation</th>
                                        @endif

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $key => $product)
                                        <tr>
                                            <td style="width:50px;"><span class="round">P</span></td>
                                            <span
                                                hidden>{{ $prod = \App\Models\Product::where(['id' => $product->product_id])->where('company_id', Auth::user()->company_id)->select('*')->first() }}</span>
                                            <span
                                                hidden>{{ $power = \App\Models\Power::where(['product_id' => $product->product_id])->where('company_id', Auth::user()->company_id)->select('*')->first() }}</span>
                                            <td>
                                                <h6>{{ $prod->product_name }}</h6>
                                                <small class="text-muted">
                                                    @if ($power)
                                                        @if (initials($prod->product_name) == 'SV')
                                                            <span> {{ $power->sphere }} / {{ $power->cylinder }}</span>
                                                        @else
                                                            <span>{{ $power->sphere }} / {{ $power->cylinder }}
                                                                *{{ $power->axis }} {{ $power->add }}</span>
                                                        @endif
                                                    @endif
                                                </small>
                                            </td>
                                            <td>{{ $product->quantity }}</td>
                                            <td>{{ format_money($product->unit_price) }}</td>
                                            <td>{{ format_money($product->discount) }}</td>
                                            <td>{{ format_money($product->total_amount) }}</td>
                                            @if ($invoice->status == 'completed')
                                            @else
                                                <td>
                                                    <a href="{{ route('manager.sales.edit.product', Crypt::encrypt($product->id)) }}"
                                                        style="color: rgb(0, 38, 255)">Edit</a>
                                                    <a href="#" class="pl-2" data-toggle="modal"
                                                        data-target="#myModal-{{ $key }}"
                                                        style="color: red">Delete</a>
                                                </td>
                                            @endif
                                        </tr>

                                        <div id="myModal-{{ $key }}" class="modal fade" tabindex="-1"
                                            role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myModalLabel"><i
                                                                class="fa fa-exclamation-triangle"></i> Warning</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h4>{{ $prod->product_name }} have {{ $product->quantity }}
                                                            quantity!
                                                            continue??</h4>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="{{ route('manager.sales.remove.product', Crypt::encrypt($product->id)) }}"
                                                            class="btn btn-info waves-effect">Yes</a>
                                                        <button type="button" class="btn btn-danger waves-effect"
                                                            data-dismiss="modal">No</button>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                    @endforeach

                                    {{-- <form action="" method="post"> --}}
                                    {{-- @csrf --}}
                                    @foreach ($na_products as $count => $product)
                                        <tr>
                                            @php
                                                $type = \App\Models\LensType::where('id', $product->type_id)
                                                    ->pluck('name')
                                                    ->first();
                                                $coating = \App\Models\PhotoCoating::where('id', $product->type_id)
                                                    ->pluck('name')
                                                    ->first();
                                                $index = \App\Models\PhotoIndex::where('id', $product->index_id)
                                                    ->pluck('name')
                                                    ->first();
                                                $chromatics = \App\Models\PhotoChromatics::where('id', $product->chromatic_id)
                                                    ->pluck('name')
                                                    ->first();
                                            @endphp

                                            <td style="width:50px;">
                                                <span class="round {{$product->status!='sold'?'bg-warning':''}}">
                                                    {{$product->status!='sold'?'N/A':'P'}}
                                                </span>
                                            </td>

                                            <td>
                                                <h6>{{ initials($type) . ' ' . $index . ' ' . $chromatics . ' ' . $coating }}
                                                </h6>
                                                <small class="text-muted">
                                                    {{-- @if ($power) --}}
                                                    @if (initials($type) == 'SV')
                                                        <span> {{ $product->sphere }} /
                                                            {{ $product->cylinder }}</span>
                                                    @else
                                                        <span>{{ $product->sphere }} / {{ $product->cylinder }}
                                                            *{{ $product->axis }} {{ $product->add }}</span>
                                                    @endif
                                                    {{-- @endif --}}
                                                </small>
                                            </td>
                                            <td>{{ $product->quantity }}</td>
                                            <td>
                                                {{ format_money($product->price) }}
                                            </td>
                                            <td>
                                                {{ format_money(0) }}
                                            </td>
                                            <td>
                                                {{ format_money($product->total_amount) }}
                                            </td>
                                            <td>
                                                @if (userInfo()->permissions == 'manager' || userInfo()->permissions == 'store')
                                                        @if ($product->status=='pending')
                                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                                                data-target="#price-{{ $count }}">
                                                                set Price
                                                            </button>
                                                        @elseif($product->status=='approved')
                                                            <div class="row align-center">
                                                                <button type="button" class="btn btn-sm btn-warning"
                                                                    data-toggle="modal" data-target="#reaction-{{ $count }}">
                                                                    Sell
                                                                </button>
                                                                <a href="#!" class="text-danger ml-2"
                                                                    data-toggle="modal" data-target="#reaction-{{ $count }}">
                                                                    Cancel
                                                                </a>
                                                            </div>
                                                        @endif

                                                @else
                                                    <span
                                                        class="label label-{{ $product->status == 'sold' ? 'success' : 'warning' }}">{{ $product->status }}</span>
                                                @endif
                                            </td>
                                        </tr>

                                        {{-- setting price modal --}}
                                        <div id="price-{{ $count }}" class="modal fade" tabindex="-1"
                                            role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myModalLabel"><i
                                                                class="fa fa-exclamation-triangle"></i>
                                                            {{ __('manager/sales.order_detail') }}</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <h5 class="text-secondary">
                                                            {{ __('manager/sales.order_detail') }}</h5>
                                                        <br>
                                                        {{-- <div class="row"> --}}
                                                        <strong>Description: </strong>
                                                        <span>
                                                            {{ initials2(\App\Models\LensType::where('id', $product->type_id)->pluck('name')->first()) }}

                                                            {{ \App\Models\PhotoIndex::where('id', $product->index_id)->pluck('name')->first() }}

                                                            {{ \App\Models\PhotoChromatics::where('id', $product->chromatic_id)->pluck('name')->first() }}

                                                            {{ \App\Models\PhotoCoating::where('id', $product->coating_id)->pluck('name')->first() }}
                                                            - {{ $product->sphere_r == null ? 'left' : 'right' }}
                                                        </span>
                                                        {{-- </div> --}}
                                                        <br>
                                                        <br>
                                                        <div class="row">
                                                            <div class="row col-md-3">
                                                                <strong class="col-12">Sphere: </strong>
                                                                <span
                                                                    class="col-12">{{ $product->sphere == null ? format_values($product->sphere_l) : format_values($product->sphere) }}</span>
                                                            </div>

                                                            <div class="row col-md-3">
                                                                <strong class="col-12">Cylinder: </strong>
                                                                <span
                                                                    class="col-12">{{ $product->cylinder == null ? format_values($product->cylinder_l) : format_values($product->cylinder) }}</span>
                                                            </div>

                                                            <div class="row col-md-3">
                                                                <strong class="col-12">Axis: </strong>
                                                                <span
                                                                    class="col-12">{{ $product->axis == null ? format_values($product->axis_l) : format_values($product->axis) }}</span>
                                                            </div>

                                                            <div class="row col-md-3">
                                                                <strong class="col-12">Addition: </strong>
                                                                <span
                                                                    class="col-12">{{ $product->addition == null ? format_values($product->addition_l) : format_values($product->addition) }}</span>
                                                            </div>

                                                        </div>
                                                        @if ($product->status != 'sold')
                                                            <hr>
                                                            <br>
                                                            <h4>{{ __('manager/sales.add_price') }}</h4>
                                                            {{-- <hr> --}}

                                                            <form action="{{ route('manager.adjust.order.price') }}"
                                                                method="post" id="setPriceForm-{{ $count }}">
                                                                @csrf
                                                                <input type="hidden" name="thisName"
                                                                    value="{{ Crypt::encrypt($product->id) }}">

                                                                <div class="form-group row">
                                                                    <label for="stock"
                                                                        class="col-sm-3 text-right control-label col-form-label">{{ __('manager/sales.cost') }}</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="number" id="cost"
                                                                            class="form-control" placeholder="0"
                                                                            name="cost" required
                                                                            value="{{ $product->cost }}" min="1">
                                                                    </div>
                                                                </div>


                                                                <div class="form-group row">
                                                                    <label for="stock"
                                                                        class="col-sm-3 text-right control-label col-form-label">{{ __('manager/sales.price') }}</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="number" id="price"
                                                                            class="form-control" placeholder="0"
                                                                            name="price" required
                                                                            value="{{ $product->price }}" min="1">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        @endif

                                                    </div>
                                                    <div class="modal-footer">
                                                        @if ($product->status != 'sold')
                                                            <button class="btn btn-info waves-effect"
                                                                onclick="document.getElementById('setPriceForm-{{ $count }}').submit()">{{ __('manager/sales.add_price') }}</button>
                                                        @endif
                                                        <button type="button" class="btn btn-danger waves-effect"
                                                            data-dismiss="modal">{{ __('manager/sales.cancel') }}</button>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>

                                        {{-- product available modal --}}
                                        <div id="reaction-{{$count}}" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myModalLabel"><i
                                                                class="fa fa-exclamation-triangle"></i> Warning</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <h4>{{__('manager/sales.client_feedback_detail')}}</h4>
                                                        {{-- <hr> --}}

                                                        <form action="{{ route('manager.sell.na.product')}}" method="post" id="reactionForm-{{$count}}">
                                                            @csrf
                                                            <input type="hidden" name="thisName" value="{{Crypt::encrypt($product->id)}}">
                                                        </form>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button onclick="document.getElementById('reactionForm-{{$count}}').submit()" class="btn btn-info waves-effect">{{__('navigation.yes')}}</button>
                                                        <button type="button" class="btn btn-danger waves-effect"
                                                            data-dismiss="modal">{{__('navigation.no')}}</button>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>

                                        {{-- client sell modal --}}
                                        <div id="sell-{{ $count }}" class="modal fade" tabindex="-1"
                                            role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myModalLabel"><i
                                                                class="fa fa-exclamation-triangle"></i>
                                                            {{ __('manager/sales.sell') }}</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <h4>{{ __('manager/sales.sell_message') }}</h4>
                                                        {{-- <hr> --}}

                                                        <form action="{{ route('manager.pending.order.sell') }}"
                                                            method="post" id="sellForm-{{ $count }}">
                                                            @csrf
                                                            <input type="hidden" name="thisName"
                                                                value="{{ Crypt::encrypt($product->id) }}">
                                                        </form>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button
                                                            onclick="document.getElementById('sellForm-{{ $count }}').submit()"
                                                            class="btn btn-info waves-effect">{{ __('navigation.yes') }}</button>
                                                        <button type="button" class="btn btn-danger waves-effect"
                                                            data-dismiss="modal">{{ __('navigation.no') }}</button>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>

                                        {{-- cancel modal --}}
                                        <div id="cancel-{{ $count }}" class="modal fade" tabindex="-1"
                                            role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myModalLabel"><i
                                                                class="fa fa-exclamation-triangle"></i>
                                                            {{ __('manager/sales.client_feedback') }}</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <h4>{{ __('manager/sales.cancel_order_message') }}</h4>
                                                        {{-- <hr> --}}

                                                        <form action="{{ route('manager.pending.order.cancel') }}"
                                                            method="post" id="cancelForm-{{ $count }}">
                                                            @csrf
                                                            <input type="hidden" name="thisName"
                                                                value="{{ Crypt::encrypt($product->id) }}">
                                                        </form>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button
                                                            onclick="document.getElementById('cancelForm-{{ $count }}').submit()"
                                                            class="btn btn-info waves-effect">{{ __('navigation.yes') }}</button>
                                                        <button type="button" class="btn btn-danger waves-effect"
                                                            data-dismiss="modal">{{ __('navigation.no') }}</button>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                    @endforeach
                                    {{-- </form> --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <span
                            hidden>{{ $client = \App\Models\Customer::where(['id' => $invoice->client_id])->where('company_id', Auth::user()->company_id)->pluck('name')->first() }}</span>
                        <h4 class="card-title">Order #{{ sprintf('%04d', $invoice->reference_number) }}
                            @if ($client)
                                for: {{ $client }}
                            @else
                            @endif
                        </h4>

                    </div>
                    <div class="card-body bg-light">
                        <div class="row text-center">
                            <div class="col-6 m-t-10 m-b-10">
                                <span
                                    class="label label-{{ $invoice->status == 'completed' ? 'success' : 'warning' }}">{{ $invoice->status }}</span>
                            </div>
                            <div class="col-6 m-t-10 m-b-10">
                                {{ \Carbon\Carbon::parse($invoice->created_at)->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="p-t-20">Products</h5>
                        <span>{{ count($products) + count($na_products) }}</span>
                        <h5 class="m-t-30">Total Stock</h5>
                        <span>{{ \App\Models\SoldProduct::where(['invoice_id' => $invoice->id])->where('company_id', Auth::user()->company_id)->select('*')->sum('quantity') +
                            \App\Models\UnavailableProduct::where('invoice_id', $invoice->id)->where('company_id', Auth::user()->company_id)->whereIn('status',['pending','approved'])->select('*')->sum('quantity') }}</span>
                        @if ($invoice->due != 0)
                            <hr>
                            <h5 class="m-t-30">Due Amount</h5>
                            <span>{{ format_money($invoice->due) }}
                            </span>
                        @endif
                        <h5 class="m-t-30">Total Amount</h5>
                        <span>{{ format_money(
                            \App\Models\SoldProduct::where(['invoice_id' => $invoice->id])->where('company_id', Auth::user()->company_id)->select('*')->sum('total_amount') +
                                \App\Models\UnavailableProduct::where('invoice_id', $invoice->id)->where('company_id', Auth::user()->company_id)->whereIn('status',['pending','approved'])->select('*')->sum('price'),
                        ) }}
                        </span>
                        <br />
                        @if ($invoice->status == 'pending')
                            <a href="" data-toggle="modal" data-target="#myModal" type="button"
                                class="m-t-20 btn waves-effect waves-light btn-success">Finalize Order</a>
                        @else
                        @endif

                        <div id="myModal" class="modal fade" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('manager.sales.finalize', Crypt::encrypt($invoice->id)) }}"
                                    method="post">
                                    @csrf
                                    <div class="modal-content">
                                        <input type="hidden" name="total"
                                            value={{ \App\Models\SoldProduct::where(['invoice_id' => $invoice->id])->where('company_id', Auth::user()->company_id)->select('*')->sum('total_amount') }}>
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel"><i
                                                    class="fa fa-exclamation-triangle"></i>
                                                Warning</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">×</button>
                                        </div>
                                        <div class="modal-body">
                                            @if ($invoice->client_id)
                                                <h4>Do you want to finalize this??</h4>
                                                {{-- <hr> --}}
                                            @else
                                                <h4>client Information</h4>
                                                <hr>
                                                <div class="form-group row">
                                                    <label for="cname"
                                                        class="col-sm-3 text-right control-label col-form-label">Client
                                                        Name</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" value="{{ $invoice->client_name }}"
                                                            class="form-control" placeholder="" name="name">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="cphone"
                                                        class="col-sm-3 text-right control-label col-form-label">Client
                                                        Phone Number</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" value="{{ $invoice->phone }}"
                                                            class="form-control" placeholder="" name="phone">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="tin_number"
                                                        class="col-sm-3 text-right control-label col-form-label">Client TIN
                                                        Number</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" value="{{ $invoice->tin_number }}"
                                                            class="form-control" placeholder="" name="tin_number">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-info waves-effect">Complete
                                                sale</button>
                                            <button type="button" class="btn btn-danger waves-effect"
                                                data-dismiss="modal">No</button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
