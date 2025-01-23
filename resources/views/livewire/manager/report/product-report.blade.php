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
                    <div class="col-md-3">
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

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>End Date </label>
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
                    <!--/span-->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label> Category </label>
                            <select class="form-control" wire:model.live="category" id="">
                                <option value="">*Select Category*</option>
                                @foreach ($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    @if ($showType)
                        <!--/span-->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label> Lens Type </label>
                                <select class="form-control" wire:model.live="lens_type" id="">
                                    <option value="">*Select Category*</option>
                                    @foreach ($types as $type)
                                        <option value="{{$type->id}}">{{$type->name}}</option>
                                    @endforeach
                                </select>
                                @error('lens_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">
                    <span wire:loading.remove>Search</span>
                    <span wire:loading wire:target='searchInformation'>Searching...</span>
                </button>
            </div>
        </form>
    </div>

    @if ($result)
    {{-- @if ($result && count($productListing) > 0) --}}

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

                    {{-- <li class="nav-item">
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
                    </li> --}}

                </ul>

                <div class="tab-content">

                    <div class="tab-pane active mt-4" id="raw-material" role="tabpanel">

                        <a onclick="ExportToExcel('xlsx')" href="#" class=" mt-2 mb-2 ml-2 btn waves-effect waves-light btn-rounded btn-outline-success" style="align-items: right;">
                            <i class="fa fa-download"></i> Export To Excel
                        </a>

                        <div class="table-responsive">
                            {{-- <table width="100%" border="1"> --}}
                            <table class="table table-striped table-bordered nowrap fixTableHead"
                                style="width:100%" data-sticky-header id="zero_config">
                                <thead class="font-bold">
                                    @php
                                        $stockInTottal  =    0;
                                        $stockInAmt     =    0;

                                        $stockOutTottal =    0;
                                        $stockOutAmt    =    0;

                                        $closingTotal   =    0;

                                        $openingTotal   =    0;
                                        $openingQtyAmt  =    0;

                                        $closingTotal   =    0;
                                        $closingQtyAmt  =    0;
                                    @endphp
                                    <tr>
                                        <th rowspan="2"><center>SN</center></th>
                                        <th rowspan="2">Date</th>
                                       <th colspan="4"><center>Particulars</center></th>
                                        <th colspan="3"><center>Opening Stock</center></th>
                                        <th colspan="3"><center>Stock In</center></th>
                                        <th colspan="3"><center>Stock Out</center></th>
                                        <th colspan="3"><center>Closing Stock</center></th>
                                    </tr>

                                    <tr>
                                        <th>Product Number</th>
                                        <th>Product Name</th>
                                        <th>Supplier</th>
                                        <th>Location</th>
                                        {{-- Opening stock --}}
                                        <th>Qty</th>
                                        <th>U Cost</th>
                                        <th>T. Cost</th>
                                        {{-- Stock In --}}
                                        <th>Qty</th>
                                        <th>U Cost</th>
                                        <th>T. Cost</th>
                                        {{-- Stock Out --}}
                                        <th>Qty</th>
                                        <th>U Cost</th>
                                        <th>T. Cost</th>
                                        {{-- Closing Stock --}}
                                        <th>Qty</th>
                                        <th>U Cost</th>
                                        <th>T. Cost</th>
                                    </tr>
                                </thead>

                                {{-- body --}}
                                <tbody>
                                    @foreach ($productListing as $key=> $product)
                                        <tr>
                                            <td>{{ sprintf('%04d',$key+1) }}</td>
                                            <td>{{ $product['date'] }}</td>
                                            <td>
                                                {{ $product['product']?->id }}
                                                @if ($product['reason']=='adjust')
                                                    <span class="badge badge-danger badge-pill ml-2">{{$product['reason']}}</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $product['product']?->product_name.'-'.$product['product']?->description }}

                                                @if ($product['product']->category_id=='1')
                                                    |

                                                    @if (initials($product['product']->product_name) == 'SV')
                                                        <span>
                                                            {{ $product['product']->power->sphere }} /
                                                            {{ $product['product']->power->cylinder }}
                                                        </span>
                                                    @else
                                                        <span>
                                                            {{ $product['product']->power->sphere }} /
                                                            {{ $product['product']->power->cylinder }}
                                                            *{{ $product['product']->power->axis }}
                                                            {{ $product['product']->power->add }}
                                                        </span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td> - </td>
                                            <td>{{ $product['product']?->location }}</td>

                                            {{-- Opening Stock --}}
                                            <td style="{{$product['count']==1?'background: rgb(255, 234, 194);':''}}">{{ $product['current_stock'] }}</td>
                                            <td>{{ format_money($product['product']->cost) }}</td>
                                            <td>{{ format_money($product['current_stock']*$product['product']->cost) }}</td>

                                            {{-- Stock In --}}
                                            <td>{{ $product['inStock'] }}</td>
                                            <td>{{ format_money($product['product']->cost) }}</td>
                                            <td>{{ format_money($product['inStock']*$product['product']->cost) }}</td>

                                            {{-- Stock Out --}}
                                            <td>{{ $product['outStock'] }}</td>
                                            <td>{{ format_money($product['product']->cost) }}</td>
                                            <td>{{ format_money($product['outStock']*$product['product']->cost) }}</td>

                                            {{-- Closing Out --}}
                                            @if ($productCounting[$product['product']->id]==$product['count'])
                                                <td style="background: rgb(219, 255, 194);">{{ $product['closingStock'] }}</td>
                                            @else
                                                <td>{{ $product['closingStock'] }}</td>
                                            @endif
                                            
                                            <td>{{ format_money($product['product']->cost) }}</td>
                                            <td>{{ format_money($product['closingStock']*$product['product']->cost) }}</td>
                                            @php
                                                $stockInTottal  +=   $product['inStock'];
                                                $stockInAmt     +=   $product['inStock']*$product['product']->cost;

                                                $stockOutTottal +=   $product['outStock'];
                                                $stockOutAmt    +=   $product['outStock']*$product['product']->cost;

                                                if ($product['count']==1) {
                                                    $openingTotal   +=   $product['current_stock'];
                                                    $openingQtyAmt  +=   $product['current_stock']*$product['product']->cost;
                                                }

                                                if ($productCounting[$product['product']->id]==$product['count']){
                                                    $closingTotal   +=   $product['closingStock'];
                                                    $closingQtyAmt  +=   $product['closingStock']*$product['product']->cost;
                                                }

                                            @endphp
                                        </tr>
                                    @endforeach
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="18"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="6"><b>Total</b> </th>
                                        {{--  --}}
                                        <th><b class="text-primary">{{ number_format($openingTotal) }}</b></th>
                                        <th>Total Opening Cost</th>
                                        <th>
                                            <b class="text-primary">{{ format_money($openingQtyAmt) }}</b>
                                        </th>
                                        {{--  --}}
                                        <th><b class="text-primary">{{ number_format($stockInTottal) }}</b></th>
                                        <th>U Stock In Cost</th>
                                        <th>
                                            <b class="text-primary">{{ format_money($stockInAmt) }}</b>
                                        </th>
                                        {{--  --}}
                                        <th><b class="text-primary">{{ number_format($stockOutTottal) }}</b></th>
                                        <th>U Stock Out Cost</th>
                                        <th>
                                            <b class="text-primary">{{ format_money($stockOutAmt) }}</b>
                                        </th>
                                        {{--  --}}
                                        <th>
                                            <b class="text-primary">{{ number_format($closingTotal) }}</b>
                                        </th>
                                        <th>U Cost</th>
                                        <th>
                                            <b class="text-primary">{{ format_money($closingQtyAmt) }}</b>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    {{-- <div class="tab-pane mt-4" id="work-in-progress" role="tabpanel">
                        work in progress
                    </div>

                    <div class="tab-pane mt-4" id="finished-goods" role="tabpanel">
                        finished goods
                    </div> --}}

                </div>
            </div>
        </div>

    @endif

    @if ($searchFoundSomething == 'no')
        <div class="card">
            <div class="card-body">
                <div class="alert alert-warning alert-rounded ">
                    Nothing Found from: <strong>{{ $start_date }}</strong> up to <strong>{{ $end_date }}</strong>
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
