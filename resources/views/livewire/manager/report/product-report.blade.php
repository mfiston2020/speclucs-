@section('title', getuserType() . ' - ' . ' Product Reporting')
{{-- ==== Breadcumb ======== --}}
@section('current', 'Stock Report')
@section('page_name', 'Stock Report')
{{-- === End of breadcumb == --}}

@push('css')
    <link rel="stylesheet" href="extensions/sticky-header/bootstrap-table-sticky-header.css">
    <script src="extensions/sticky-header/bootstrap-table-sticky-header.js"></script>
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
                            {{-- <table width="100%" border="1"> --}}
                            <table id="" class="table table-striped table-bordered nowrap fixTableHead"
                                style="width:100%" data-sticky-header>
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
                                @php
                                    // Lens total for all
                                    $openingStockTotalQuantity = 0;
                                    $openingStockTotalCost = 0;
                                    
                                    $inStockTotalQuantity = 0;
                                    $inStockTotalCost = 0;
                                    
                                    $outStockTotalQuantity = 0;
                                    $outStockTotalCost = 0;
                                    
                                    // Frames total for all
                                    $frameopeningStockTotalQuantity = 0;
                                    $frameopeningStockTotalCost = 0;
                                    
                                    $frameinStockTotalQuantity = 0;
                                    $frameinStockTotalCost = 0;
                                    
                                    $frameoutStockTotalQuantity = 0;
                                    $frameoutStockTotalCost = 0;
                                    
                                    // Accessories total for all
                                    $accessoriesopeningStockTotalQuantity = 0;
                                    $accessoriesopeningStockTotalCost = 0;
                                    
                                    $accessoriesinStockTotalQuantity = 0;
                                    $accessoriesinStockTotalCost = 0;
                                    
                                    $accessoriesoutStockTotalQuantity = 0;
                                    $accessoriesoutStockTotalCost = 0;
                                    
                                    // counting product name
                                    $product_name = '';
                                    
                                @endphp

                                {{-- body --}}
                                <tbody>

                                    @foreach ($products as $ky => $product)
                                        @foreach ($dateList as $key => $rm)
                                            @php
                                                $stockRecord = $rm . '-' . $product->id;
                                                
                                                $openingStockQty = $product->stock;
                                                $openingStockTtl = $product->stock;
                                                
                                                $stockInQty = $product->stock;
                                                $stockInTtl = $product->stock;
                                                
                                                $stockOutQty = $product->stock;
                                                $stockOutTtl = $product->stock;
                                                
                                                $stockClsQty = $product->stock;
                                                $stockClsTtl = $product->stock;
                                                
                                                if ($productListing[$stockRecord]['current_stock'] != null) {
                                                    // opening stock
                                                
                                                    $openingStockQty = $productListing[$stockRecord]['current_stock']->current_stock;
                                                    $openingStockTtl = $productListing[$stockRecord]['current_stock']->current_stock * $product->cost;
                                                
                                                    if ($product_name == '' || ($product_name != $product->id && $product->category_id == '1')) {
                                                        $openingStockTotalQuantity += $openingStockQty;
                                                        $openingStockTotalCost += $openingStockTtl;
                                                
                                                        $product_name = $product->id;
                                                    }
                                                
                                                    if ($product_name == '' || ($product_name != $product->id && $product->category_id == '2')) {
                                                        $frameopeningStockTotalQuantity += $openingStockQty;
                                                        $frameopeningStockTotalCost += $openingStockTtl;
                                                
                                                        $product_name = $product->id;
                                                    }
                                                
                                                    if ($product_name == '' || ($product_name != $product->id && $product->category_id > 2)) {
                                                        $accessoriesopeningStockTotalQuantity += $openingStockQty;
                                                        $accessoriesopeningStockTotalCost += $openingStockTtl;
                                                
                                                        $product_name = $product->id;
                                                    }
                                                
                                                    // Stock In
                                                    if ($productListing[$stockRecord]['current_stock']->operation == 'in' && $productListing[$stockRecord]['current_stock']->type == 'rm') {
                                                        $stockInQty = $productListing[$stockRecord]['incoming'];
                                                        $stockInTtl = $productListing[$stockRecord]['incoming'] * $product->cost;
                                                
                                                        if ($product->category_id == '1') {
                                                            $inStockTotalQuantity += $openingStockQty;
                                                            $inStockTotalCost += $openingStockTtl;
                                                
                                                            // $product_name = $product->id;
                                                        }
                                                
                                                        if ($product->category_id == '2') {
                                                            $frameinStockTotalQuantity += $openingStockQty;
                                                            $frameinStockTotalCost += $openingStockTtl;
                                                
                                                            // $product_name = $product->id;
                                                        }
                                                
                                                        if ($product->category_id > 2) {
                                                            $accessoriesinStockTotalQuantity += $openingStockQty;
                                                            $accessoriesinStockTotalCost += $openingStockTtl;
                                                
                                                            // $product_name = $product->id;
                                                        }
                                                    }
                                                
                                                    // Stock In
                                                    if ($productListing[$stockRecord]['current_stock']->operation == 'out' && $productListing[$stockRecord]['current_stock']->type == 'rm') {
                                                        $stockOutQty = $productListing[$stockRecord]['incoming'];
                                                        $stockOutTtl = $productListing[$stockRecord]['incoming'] * $product->cost;
                                                
                                                        // if ($product_name == '' || $product_name != $product->id) {
                                                        $outStockTotalQuantity += $stockOutQty;
                                                        $outStockTotalCost += $stockOutTtl;
                                                
                                                        $product_name = $product->id;
                                                        // }
                                                    }
                                                
                                                    // Closing Stock
                                                    if ($productListing[$stockRecord]['current_stock']->type == 'rm') {
                                                        $stockClsQty = $productListing[$stockRecord]['current_stock']->current_stock - $productListing[$stockRecord]['incoming'];
                                                        // $stockClsQty = $productListing[$stockRecord]['current_stock']->change;
                                                        $stockClsTtl = $stockClsQty * $product->cost;
                                                    }
                                                }
                                            @endphp
                                            {{-- {{ $product->id }} --}}
                                            <tr>
                                                <td>{{ $ky + $key }}</td>
                                                <td>{{ date('Y-m-d', strtotime($rm)) }}</td>
                                                <td>{{ sprintf('%04d', $product->id) }}</td>
                                                <td>
                                                    {{ $product->category_id == 1 ? '' : $product->product_name . ' | ' }}{{ $product->description }}
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
                                                <td>{{ format_money($product->cost) }}</td>
                                                <td>{{ format_money($openingStockTtl) }}</td>
                                                {{--  --}}
                                                <td>{{ $stockInQty }}</td>
                                                <td>{{ $stockInTtl == '-' ? '-' : format_money($product->cost) }}</td>
                                                <td>{{ format_money($stockInTtl) }}</td>
                                                {{--  --}}
                                                <td>{{ $stockOutQty }}</td>
                                                <td>{{ $stockOutTtl == '-' ? '-' : format_money($product->cost) }}</td>
                                                <td>{{ format_money($stockOutTtl) }}</td>
                                                {{--  --}}
                                                <td>{{ $stockClsQty }}</td>
                                                <td>{{ $stockClsQty == '-' ? '-' : format_money($product->cost) }}</td>
                                                <td>{{ format_money($stockClsTtl) }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach

                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="18"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="6">Lens Total </th>
                                        {{--  --}}
                                        <th>{{ $openingStockTotalQuantity }}</th>
                                        <th>-</th>
                                        <th>{{ format_money($openingStockTotalCost) }}</th>
                                        {{--  --}}
                                        <th>{{ $inStockTotalQuantity }}</th>
                                        <th>U Cost</th>
                                        <th>{{ format_money($inStockTotalCost) }}</th>
                                        {{--  --}}
                                        <th>{{ $outStockTotalQuantity }}</th>
                                        <th>U Cost</th>
                                        <th>{{ format_money($outStockTotalCost) }}</th>
                                        {{--  --}}
                                        <th>Qty</th>
                                        <th>U Cost</th>
                                        <th>T. Cost</th>
                                    </tr>

                                    <tr>
                                        <th colspan="6">Frame Total </th>
                                        {{--  --}}
                                        <th>{{ $frameopeningStockTotalQuantity }}</th>
                                        <th>-</th>
                                        <th>{{ format_money($frameopeningStockTotalCost) }}</th>
                                        {{--  --}}
                                        <th>{{ $frameinStockTotalQuantity }}</th>
                                        <th>U Cost</th>
                                        <th>{{ format_money($frameinStockTotalCost) }}</th>
                                        {{--  --}}
                                        <th>{{ $outStockTotalQuantity }}</th>
                                        <th>U Cost</th>
                                        <th>{{ format_money($outStockTotalCost) }}</th>
                                        {{--  --}}
                                        <th>Qty</th>
                                        <th>U Cost</th>
                                        <th>T. Cost</th>
                                    </tr>

                                    <tr>
                                        <th colspan="6">Accessories Total </th>
                                        {{--  --}}
                                        <th>{{ $accessoriesopeningStockTotalQuantity }}</th>
                                        <th>-</th>
                                        <th>{{ format_money($accessoriesopeningStockTotalCost) }}</th>
                                        {{--  --}}
                                        <th>{{ $accessoriesinStockTotalQuantity }}</th>
                                        <th>U Cost</th>
                                        <th>{{ format_money($accessoriesinStockTotalCost) }}</th>
                                        {{--  --}}
                                        <th>{{ $outStockTotalQuantity }}</th>
                                        <th>U Cost</th>
                                        <th>{{ format_money($outStockTotalCost) }}</th>
                                        {{--  --}}
                                        <th>Qty</th>
                                        <th>U Cost</th>
                                        <th>T. Cost</th>
                                    </tr>
                                </tfoot>
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
