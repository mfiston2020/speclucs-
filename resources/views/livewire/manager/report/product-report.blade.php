@section('title', getuserType() . ' - ' . ' Product Reporting')
{{-- ==== Breadcumb ======== --}}
@section('current', 'Stock Report')
@section('page_name', 'Stock Report')
{{-- === End of breadcumb == --}}

@push('css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('dashboard/assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

<div class="col-md-12">

    <div class="card">
        <form wire:submit.prevent='searchInformation'>
            @csrf
            <div class="card-body">

                <div class="row">
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Start Date </label>
                            <div class="input-group">
                                <input type="date" class="form-control" placeholder="mm/dd/yyyy"
                                    wire:model.lazy='start_date'>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                            @error('start_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Start Date </label>
                            <div class="input-group">
                                <input type="date" class="form-control" placeholder="mm/dd/yyyy"
                                    wire:model.lazy='end_date'>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="icon-calender"></i></span>
                                </div>
                            </div>
                            @error('end_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                </div>

                <button type="submit" class="btn btn-primary">
                    <span wire:loading.remove>Search</span>
                    <span wire:loading wire:target='searchInformation'>Searching...</span>
                </button>

            </div>
        </form>
    </div>

    @if ($result)
        <div class="card">
            <div class="card-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">

                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#raw-material" role="tab">
                            <span class="hidden-sm-up"><i class="ti-home"></i></span>
                            <span class="hidden-xs-down">
                                Raw Materials
                            </span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#work-in-progress" role="tab">
                            <span class="hidden-sm-up"><i class="ti-home"></i></span>
                            <span class="hidden-xs-down">
                                work In Progress
                            </span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#finished-goods" role="tab">
                            <span class="hidden-sm-up"><i class="ti-home"></i></span>
                            <span class="hidden-xs-down">
                                Finished Goods
                            </span>
                        </a>
                    </li>

                </ul>

                <div class="tab-content">
                    <div class="tab-pane active mt-4" id="raw-material" role="tabpanel">
                        <div class="table-responsive">
                            <table width="100%" border="1">
                                <thead>
                                    <tr>
                                        <th rowspan="2">SN</th>
                                        <th rowspan="2">Date</th>
                                        <th colspan="4">Particulars</th>
                                        <th colspan="3">Opening Stock</th>
                                        <th colspan="3">Stock In</th>
                                        <th colspan="3">Stock Out</th>
                                        <th colspan="3">Closing Stock</th>
                                    </tr>

                                    <tr>
                                        <th>Product Number</th>
                                        <th>Product Name</th>
                                        <th>Supplier</th>
                                        <th>Location</th>
                                        {{--  --}}
                                        <th>Qty</th>
                                        <th>U Cost</th>
                                        <th>T. Cost</th>
                                        {{--  --}}
                                        <th>Qty</th>
                                        <th>U Cost</th>
                                        <th>T. Cost</th>
                                        {{--  --}}
                                        <th>Qty</th>
                                        <th>U Cost</th>
                                        <th>T. Cost</th>
                                        {{--  --}}
                                        <th>Qty</th>
                                        <th>U Cost</th>
                                        <th>T. Cost</th>
                                    </tr>
                                </thead>

                                {{-- body --}}
                                <tbody>

                                    @foreach ($products as $ky => $product)
                                        @foreach ($dateList as $key => $rm)
                                            @php
                                                $stockRecord = $rm . '-' . $product->id;

                                                $openingStockQty = '-';
                                                $openingStockTtl = '-';

                                                $stockInQty = '-';
                                                $stockInTtl = '-';

                                                $stockOutQty = '-';
                                                $stockOutTtl = '-';

                                                $stockClsQty = '-';
                                                $stockClsTtl = '-';

                                                // counting product name
                                                // $previousId[] = $product->id . '' . $ky;

                                                if ($productListing[$stockRecord]['current_stock'] != null) {
                                                    // opening stock
                                                    $openingStockQty = $productListing[$stockRecord]['current_stock']->current_stock;
                                                    $openingStockTtl = $productListing[$stockRecord]['current_stock']->current_stock * $product->cost;

                                                    // Stock In
                                                    if ($productListing[$stockRecord]['current_stock']->operation == 'in' && $productListing[$stockRecord]['current_stock']->type == 'rm') {
                                                        $stockInQty = $productListing[$stockRecord]['current_stock']->incoming;
                                                        $stockInTtl = $productListing[$stockRecord]['current_stock']->incoming * $product->cost;
                                                    }

                                                    // Stock In
                                                    if ($productListing[$stockRecord]['current_stock']->operation == 'out' && $productListing[$stockRecord]['current_stock']->type == 'rm') {
                                                        $stockOutQty = $productListing[$stockRecord]['current_stock']->incoming;
                                                        $stockOutTtl = $productListing[$stockRecord]['current_stock']->incoming * $product->cost;
                                                    }

                                                    // Closing Stock
                                                    if ($productListing[$stockRecord]['current_stock']->type == 'rm') {
                                                        $stockClsQty = $productListing[$stockRecord]['current_stock']->change;
                                                        $stockClsTtl = $productListing[$stockRecord]['current_stock']->change * $product->cost;
                                                    }
                                                }
                                            @endphp
                                            {{-- {{ $product->id }} --}}
                                            <tr>
                                                <td>{{ $ky + $key }}</td>
                                                <td>{{ date('Y-m-d', strtotime($rm)) }}</td>
                                                <td>{{ sprintf('%04d', $product->id) }}</td>
                                                <td>
                                                    {{-- @if ($previousId == $product->id . '' . $ky)
                                                        yes
                                                    @endif --}}
                                                    {{ $product->product_name }} | {{ $product->description }}
                                                    @if ($product->power)
                                                        @if (initials($product->product_name) == 'SV')
                                                            <span>
                                                                {{ $product->power->sphere }} /
                                                                {{ $product->power->cylinder }}
                                                            </span>
                                                        @else
                                                            <span>
                                                                {{ $product->power->sphere }} /
                                                                {{ $product->power->cylinder }}
                                                                *{{ $product->power->axis }}
                                                                {{ $product->power->add }}
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span>-</span>
                                                    @endif
                                                </td>
                                                <td>{{ $product->supplier_id == null ? '-' : $product->supplier->name }}
                                                </td>
                                                <td>{{ $product->location == null ? '-' : $product->location }}</td>
                                                {{--  --}}
                                                <td>{{ $openingStockQty }}</td>
                                                <td>{{ $product->cost }}</td>
                                                <td>{{ $openingStockTtl }}</td>
                                                {{--  --}}
                                                <td>{{ $stockInQty }}</td>
                                                <td>{{ $stockInTtl == '-' ? '-' : $product->cost }}</td>
                                                <td>{{ $stockInTtl }}</td>
                                                {{--  --}}
                                                <td>{{ $stockOutQty }}</td>
                                                <td>{{ $stockOutTtl == '-' ? '-' : $product->cost }}</td>
                                                <td>{{ $stockOutTtl }}</td>
                                                {{--  --}}
                                                <td>{{ $stockClsQty }}</td>
                                                <td>{{ $stockClsQty == '-' ? '-' : $product->cost }}</td>
                                                <td>{{ $stockClsTtl }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane mt-4" id="work-in-progress" role="tabpanel">
                        work in progress
                    </div>

                    <div class="tab-pane mt-4" id="finished-goods" role="tabpanel">
                        finished goods
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
@endpush
