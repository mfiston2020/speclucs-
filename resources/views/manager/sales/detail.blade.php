@extends('manager.includes.app')

@section('title', 'Dashboard - Order Detail')

{{-- ==== Breadcumb ======== --}}
@section('current', 'Order Detail')
@section('page_name', 'Order Detail')
{{-- === End of breadcumb == --}}

@section('content')

    @php
        $prodss = \App\Models\Product::where('company_id', Auth::user()->company_id)->with('power')->select('*')->first();
        // dd($prod);
    @endphp
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Products: <strong>{{ count($products) + count($na_products) }}</strong>
                            </h4>
                            <hr>

                            <a href="{{ route('manager.invoice.receipt', Crypt::encrypt($invoice->id)) }}"
                                class="pull-right btn btn-outline-warning "><i class="fa fa-print"></i> Print
                                Receipt
                            </a>

                            @if ($invoice->status == 'delivered')
                            @else
                                @if ($invoice->client_id != null)
                                @endif

                                @if ($invoice->status == 'received')
                                    <a href="#!" class="pull-right btn btn-outline-secondary ml-2"
                                        onclick="return confirm('Are you sure ?')"><i class="fa fa-basket"></i>
                                        Despense
                                    </a>
                                @endif
                                {{-- @else
                                <a href="{{ route('manager.sales.add', Crypt::encrypt($invoice->id)) }}"
                                    class="pull-right btn btn-outline-secondary">Add Product</a> --}}
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
                                        {{-- @if ($invoice->status == 'completed')
                                        @else
                                            <th>Operation</th>
                                        @endif --}}

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $key => $product)
                                    @php
                                        $prod = $prodss->where(['id' => $product->product_id])->first();
                                        // dd($prod);
                                    @endphp
                                        <tr>
                                            <td style="width:50px;">
                                                @if ($prod->category_id==1)
                                                    <span class="round"> {{Oneinitials($product->eye)}}</span>
                                                @else
                                                    <span class="round">P</span>
                                                @endif
                                            </td>
                                            <td>
                                                <h6>{{ $prod->product_name }}</h6>
                                                <small class="text-muted">
                                                    @if ($prod->power)
                                                        @if (initials($prod->product_name) == 'SV')
                                                            <span> {{ $prod->power->sphere }} / {{ $prod->power->cylinder }}</span>
                                                        @else
                                                            <span>{{ $prod->power->sphere }} / {{ $prod->power->cylinder }}
                                                                *{{ $prod->power->axis }} {{ $prod->power->add }}{{ $prod->power->addition }}</span>
                                                        @endif
                                                    @endif
                                                </small>
                                            </td>
                                            <td>{{ $product->quantity }}</td>
                                            <td>{{ format_money($product->unit_price) }}</td>
                                            <td>{{ format_money($product->discount ) }}</td>
                                            <td>{{ format_money($product->unit_price * $product->quantity) }}</td>
                                            {{-- @if ($invoice->status == 'completed')
                                            @else
                                                <td>
                                                    <a href="{{ route('manager.sales.edit.product', Crypt::encrypt($product->id)) }}"
                                                        style="color: rgb(0, 38, 255)">Edit</a>
                                                    <a href="#" class="pl-2" data-toggle="modal"
                                                        data-target="#myModal-{{ $key }}"
                                                        style="color: red">Delete</a>
                                                </td>
                                            @endif --}}
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
                                                $coating = \App\Models\PhotoCoating::where('id', $product->coating_id)
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
                                                <span class="round {{ $product->status != 'sold' ? 'bg-warning' : '' }}">
                                                    {{ $product->status != 'sold' ? Oneinitials($product->eye) : 'P' }}
                                                </span>
                                            </td>

                                            <td>
                                                <h6>{{ initials($type) . ' ' . $index . ' ' . $chromatics . ' ' . $coating }}
                                                </h6>
                                                <small class="text-muted">
                                                    {{-- @if ($power) --}}
                                                    @if (initials($type) == 'SV')
                                                        <span>
                                                            {{ format_values($product->sphere) }} /
                                                            {{ format_values($product->cylinder) }}
                                                        </span>
                                                    @else
                                                        <span>{{ format_values($product->sphere) }} /
                                                            {{ format_values($product->cylinder) }}
                                                            *{{ format_values($product->axis) }} {{ $product->addition }}</span>
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
                                        </tr>
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
                        <h4 class="card-title">
                            {{-- Order #{{ sprintf('%04d', $invoice->id) }} --}}
                                @if ($invoice->client_id != null)
                                    {{$invoice->client->name}}
                                @else
                                    @if ($invoice->hospital_name!=null)
                                    [{{$invoice->cloud_id}}] {{$invoice->hospital_name}}
                                    @else
                                        {{$invoice->client_name}}
                                    @endif
                                @endif
                            @if ($client)
                                for: {{ $client }}
                            @endif
                        </h4>

                    </div>
                    <div class="card-body bg-light">
                        <div class="row text-center">
                            <div class="col-6 m-t-10 m-b-10">
                                <span class="label label-{{ $invoice->status == 'completed' ? 'success' : 'warning' }}">
                                    {{ $invoice->status }}
                                </span>
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
                            \App\Models\UnavailableProduct::where('invoice_id', $invoice->id)->where('company_id', Auth::user()->company_id)->whereIn('status', ['pending', 'approved'])->select('*')->sum('quantity') }}</span>
                        @if ($invoice->due != 0)
                            <hr>
                            <h5 class="m-t-30">Due Amount</h5>
                            <span>{{ format_money($invoice->due) }}
                            </span>
                        @endif
                        <h5 class="m-t-30">Total Amount</h5>
                        <span>{{ format_money(
                            \App\Models\SoldProduct::where(['invoice_id' => $invoice->id])->where('company_id', Auth::user()->company_id)->select('*')->sum('total_amount') +
                                \App\Models\UnavailableProduct::where('invoice_id', $invoice->id)->where('company_id', Auth::user()->company_id)->whereIn('status', ['pending', 'approved'])->select('*')->sum('price'),
                        ) }}
                        </span>
                        <br />
                        @if ($invoice->status == 'pending')
                            <a href="" data-toggle="modal" data-target="#myModal" type="button"
                                class="m-t-20 btn waves-effect waves-light btn-success">Finalize Order</a>
                        @else
                        @endif

                        <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                            aria-hidden="true">
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
                                            <button type="submit" class="btn btn-info waves-effect">
                                                Complete sale
                                            </button>
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
