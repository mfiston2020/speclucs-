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
        <form wire:submit='searchInformation'>
            @csrf
            <div class="card-body">

                <div class="row">
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Start Date </label>
                            <div class="input-group">
                                <input type="date" class="form-control" placeholder="mm/dd/yyyy"
                                    wire:model.blur='start_date'>
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
                                    wire:model.blur='end_date'>
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

    @if ($result && count($productListing) > 0)

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
                                    $lensopeningStockTotalQuantity = 0;
                                    $lensopeningStockTotalCost = 0;
                                    
                                    $inStockTotalQuantity = 0;
                                    $inStockTotalCost = 0;
                                    
                                    $lensStockOutTtlQty = 0;
                                    $lensStockOutTtlCost = 0;
                                    
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
                                    
                                    $accinStockTotalQuantity = 0;
                                    $accinStockTotalCost = 0;
                                    
                                    $accoutStockTotalQuantity = 0;
                                    $accoutStockTotalCost = 0;
                                    
                                    $accessoriesoutStockTotalQuantity = 0;
                                    $accessoriesoutStockTotalCost = 0;
                                    
                                    // counting product name
                                    $product_name = '';
                                    $product_name_count = '';
                                    
                                    // Accessories total for all
                                    $closingStockTotalQty = 0;
                                    $closingStockTotalAmt = 0;
                                    // ----------------------
                                    $lensclosingStockTotalQty = 0;
                                    $lensclosingStockTotalAmt = 0;
                                    // ----------------------
                                    $frameclosingStockTotalQty = 0;
                                    $frameclosingStockTotalAmt = 0;
                                    // ----------------------
                                    $accessoryclosingStockTotalQty = 0;
                                    $accessoryclosingStockTotalAmt = 0;
                                    
                                @endphp

                                {{-- body --}}
                                <tbody>

                                    @foreach ($products as $ky => $product)
                                        @foreach ($dateList as $key => $rm)
                                            @php
                                                $stockRecord = $rm . '-' . $product->id;
                                                
                                                $openingStockQty = $product->stock + $productListingVariation[$product->id];
                                                $openingStockTtl = $product->stock;
                                                
                                                $stockInQty = 0;
                                                $stockInTtl = 0;
                                                
                                                $stockOutQty = 0;
                                                $stockOutTtl = 0;
                                                
                                                $stockClsQty = $product->stock;
                                                $stockClsTtl = $product->stock;
                                                
                                                // closing caclulations
                                                if ($productListing[$stockRecord]['closingStock'] != 0 && $product->category_id == '1') {
                                                    $lensclosingStockTotalQty += $productListing[$stockRecord]['closingStock'];
                                                    $lensclosingStockTotalAmt += $productListing[$stockRecord]['closingStock'] * $product->cost;
                                                }
                                                
                                                if ($productListing[$stockRecord]['closingStock'] != 0 && $product->category_id == '2') {
                                                    $frameclosingStockTotalQty += $productListing[$stockRecord]['closingStock'];
                                                    $frameclosingStockTotalAmt += $productListing[$stockRecord]['closingStock'] * $product->cost;
                                                }
                                                
                                                if ($productListing[$stockRecord]['closingStock'] != 0 && $product->category_id > 2) {
                                                    $accessoryclosingStockTotalQty += $productListing[$stockRecord]['closingStock'];
                                                    $accessoryclosingStockTotalAmt += $productListing[$stockRecord]['closingStock'] * $product->cost;
                                                }
                                                
                                                if ($productListing[$stockRecord]['current_stock'] != null) {
                                                    $closingStockTotalQty += $productListing[$stockRecord]['current_stock']['closingStock'];
                                                
                                                    // opening stock
                                                    $openingStockQty = $productListing[$stockRecord]['current_stock']->current_stock;
                                                    $openingStockTtl = $productListing[$stockRecord]['current_stock']->current_stock * $product->cost;
                                                
                                                    if ($product_name == '' || ($product_name != $product->id && $product->category_id == '1')) {
                                                        $lensopeningStockTotalQuantity += $openingStockQty;
                                                        $lensopeningStockTotalCost += $openingStockTtl;
                                                
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
                                                            $inStockTotalQuantity += $stockInQty;
                                                            $inStockTotalCost += $openingStockTtl;
                                                        }
                                                
                                                        if ($product->category_id == '2') {
                                                            $frameinStockTotalQuantity += $stockInQty;
                                                            $frameinStockTotalCost += $openingStockTtl;
                                                        }
                                                
                                                        if ($product->category_id > 2) {
                                                            $accinStockTotalQuantity += $stockInQty;
                                                            $accinStockTotalCost += $openingStockTtl;
                                                        }
                                                    }
                                                
                                                    // Stock Out
                                                    if ($productListing[$stockRecord]['current_stock']->operation == 'out' && $productListing[$stockRecord]['current_stock']->type == 'rm') {
                                                        $stockOutQty = $productListing[$stockRecord]['incoming'];
                                                        $stockOutTtl = $productListing[$stockRecord]['incoming'] * $product->cost;
                                                
                                                        if ($product->category_id == '1') {
                                                            $lensStockOutTtlQty += $stockOutQty;
                                                            $lensStockOutTtlCost += $stockOutTtl;
                                                        }
                                                
                                                        if ($product->category_id == '2') {
                                                            $frameoutStockTotalQuantity += $stockOutQty;
                                                            $frameoutStockTotalCost += $stockOutTtl;
                                                        }
                                                
                                                        if ($product->category_id > 2) {
                                                            $accoutStockTotalQuantity += $stockOutQty;
                                                            $accoutStockTotalCost += $stockOutTtl;
                                                        }
                                                    }
                                                
                                                    // Closing Stock
                                                    if ($productListing[$stockRecord]['current_stock']->type == 'rm') {
                                                        $stockClsQty = $productListing[$stockRecord]['current_stock']->current_stock - $productListing[$stockRecord]['incoming'];
                                                        // $stockClsQty = $productListing[$stockRecord]['current_stock']->change;
                                                        $stockClsTtl = $stockClsQty * $product->cost;
                                                    }
                                                }
                                                
                                            @endphp

                                            <tr>
                                                <td>{{ $ky + $key }}</td>
                                                <td>{{ date('Y-m-d', strtotime($rm)) }}</td>
                                                <td {{ $productListing[$stockRecord]['hide'] == true ? 'hidden' : '' }}
                                                    rowspan="{{ $daysCount }}"
                                                    style="vertical-align : middle;text-align:center;">
                                                    <h6>SPCL #{{ sprintf('%04d', $product->id) }}</h6>
                                                </td>
                                                <td {{ $productListing[$stockRecord]['hide'] == true ? 'hidden' : '' }}
                                                    rowspan="{{ $daysCount }}"
                                                    style="vertical-align : middle;text-align:center;">
                                                    <h6>{{ $product->category_id == 1 ? '' : $product->product_name . ' | ' }}{{ $product->description }}
                                                    </h6>
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
                                                <td>{{ $product->location == null ? '-' : $product->location }}
                                                </td>
                                                {{--  --}}
                                                <td @class([
                                                    'bg-warning' => $productListing[$stockRecord]['hide'] == false,
                                                ])>
                                                    {{ $openingStockQty }}
                                                </td>
                                                <td>{{ format_money($product->cost) }}</td>
                                                <td>{{ format_money($openingStockTtl) }}</td>
                                                {{--  --}}
                                                <td>{{ $stockInQty }}</td>
                                                <td>{{ $stockInTtl == 0 ? 0 : format_money($product->cost) }}</td>
                                                <td>{{ format_money($stockInTtl) }}</td>
                                                {{--  --}}
                                                <td>{{ $stockOutQty }}</td>
                                                <td>{{ $stockOutTtl == 0 ? 0 : format_money($product->cost) }}</td>
                                                <td>{{ format_money($stockOutTtl) }}</td>
                                                {{--  --}}
                                                <td @class(['font-bold' => true])>{{ $stockClsQty }}</td>
                                                <td>{{ $stockClsQty == 0 ? 0 : format_money($product->cost) }}</td>
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
                                        <th>{{ $lensopeningStockTotalQuantity }}</th>
                                        <th>-</th>
                                        <th>{{ format_money($lensopeningStockTotalCost) }}</th>
                                        {{--  --}}
                                        <th>{{ $inStockTotalQuantity }}</th>
                                        <th>-</th>
                                        <th>{{ format_money($inStockTotalCost) }}</th>
                                        {{--  --}}
                                        <th>{{ number_format($lensStockOutTtlQty) }}</th>
                                        <th>-</th>
                                        <th>{{ format_money($lensStockOutTtlCost) }}</th>
                                        {{--  --}}
                                        <th>{{ number_format($lensclosingStockTotalQty) }}</th>
                                        <th>-</th>
                                        <th>{{ format_money($lensclosingStockTotalAmt) }}</th>
                                    </tr>

                                    <tr>
                                        <th colspan="6">Frame Total </th>
                                        {{-- Opening stock  --}}
                                        <th>{{ $frameinStockTotalQuantity }}</th>
                                        <th>-</th>
                                        <th>{{ format_money($frameopeningStockTotalCost) }}</th>
                                        {{-- stock in --}}
                                        <th>{{ number_format($frameinStockTotalQuantity) }}</th>
                                        <th>-</th>
                                        <th>{{ format_money($frameinStockTotalCost) }}</th>
                                        {{-- stock out --}}
                                        <th>{{ number_format($frameoutStockTotalQuantity) }}</th>
                                        <th>-</th>
                                        <th>{{ format_money($frameoutStockTotalCost) }}</th>
                                        {{-- closing --}}
                                        <th>{{ number_format($frameclosingStockTotalQty) }}</th>
                                        <th>-</th>
                                        <th>{{ format_money($frameclosingStockTotalAmt) }}</th>
                                    </tr>

                                    <tr>
                                        <th colspan="6">Accessories Total </th>
                                        {{-- Opening stock  --}}
                                        <th>{{ $accessoriesopeningStockTotalQuantity }}</th>
                                        <th>-</th>
                                        <th>{{ format_money($accessoriesopeningStockTotalCost) }}</th>
                                        {{-- stock in --}}
                                        <th>{{ number_format($accinStockTotalQuantity) }}</th>
                                        <th>-</th>
                                        <th>{{ format_money($accinStockTotalCost) }}</th>
                                        {{-- stock out --}}
                                        <th>{{ number_format($accoutStockTotalQuantity) }}</th>
                                        <th>-</th>
                                        <th>{{ format_money($accoutStockTotalCost) }}</th>
                                        {{-- closing --}}
                                        <th>{{ number_format($accessoryclosingStockTotalQty) }}</th>
                                        <th>-</th>
                                        <th>{{ format_money($accessoryclosingStockTotalAmt) }}</th>
                                    </tr>

                                    <tr>
                                        <td colspan="18"></td>
                                    </tr>

                                    <tr>
                                        <th colspan="6">Grand Total </th>
                                        {{--  --}}
                                        <th>
                                            {{ number_format($lensopeningStockTotalQuantity + $frameopeningStockTotalQuantity + $accessoriesopeningStockTotalQuantity) }}
                                        </th>
                                        <th>-</th>
                                        <th>
                                            {{ format_money($openingStockQty + $frameopeningStockTotalCost + $accessoriesopeningStockTotalCost) }}
                                        </th>
                                        {{--  --}}
                                        <th>
                                            {{ number_format($inStockTotalQuantity + $frameinStockTotalQuantity + $accessoriesinStockTotalQuantity) }}
                                        </th>
                                        <th>-</th>
                                        <th>
                                            {{ format_money($inStockTotalCost + $frameinStockTotalCost + $accessoriesinStockTotalCost) }}
                                        </th>
                                        {{--  --}}
                                        <th>
                                            {{ number_format($lensStockOutTtlQty + $frameoutStockTotalQuantity + $accoutStockTotalQuantity) }}
                                        </th>
                                        <th>-</th>
                                        <th>
                                            {{ format_money($lensclosingStockTotalQty + $frameoutStockTotalQuantity + $accoutStockTotalQuantity) }}
                                        </th>
                                        {{--  --}}
                                        <th>
                                            {{ number_format($accessoryclosingStockTotalQty + $frameclosingStockTotalQty + $lensclosingStockTotalQty) }}
                                        </th>
                                        <th>-</th>
                                        <th>
                                            {{ format_money($accessoryclosingStockTotalAmt + $frameclosingStockTotalAmt + $lensclosingStockTotalAmt) }}
                                        </th>
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

    @if ($searchFoundSomething == 'no')
        <div class="card">
            <div class="card-body">
                <div class="alert alert-warning alert-rounded ">
                    Nothing Found from: <strong>{{ $start_date }}</strong> up to
                    <strong>{{ $end_date }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span
                            aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
@endpush
