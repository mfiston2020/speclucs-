@section('title', getuserType() . ' - ' . ' Product Reporting')
{{-- ==== Breadcumb ======== --}}
@section('current', 'Stock Report')
@section('page_name', 'Stock Report')
{{-- === End of breadcumb == --}}

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
                
                <a onclick="ExportToExcel('xlsx')" href="#" class=" mt-2 mb-2 ml-2 btn waves-effect waves-light btn-rounded btn-outline-success" style="align-items: right;">
                    <i class="fa fa-download"></i> Export To Excel
                </a>

                <div class="table-responsive">
                    {{-- <table width="100%" border="1"> --}}
                    <table class="table table-striped table-bordered nowrap fixTableHead"
                        style="width:100%" data-sticky-header id="zero_config">
                        <thead class="font-bold">
                            <tr>
                                <th>#</th>
                                <th>Product Number</th>
                                <th>Product Name</th>
                                {{-- <th>Current</th>
                                <th>Usage</th>
                                <th>Qty to keep</th> --}}
                                <th>Efficiency Ration</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        {{-- body --}}
                        <tbody>
                            @foreach ($productEfficiency as $key=> $product)
                                <tr>
                                    <td>{{$key+1 }}</td>
                                    <td>{{$product['product']->id }}</td>
                                    <td>{{$product['product']->product_name }}</td>
                                    {{-- <td>{{$product['product']->stock }}</td>
                                    <td>{{$product['usage'] }}</td>
                                    <td>{{$product['Qty_to_keep'] }}</td> --}}
                                    
                                    {{-- =============================== --}}
                                    @if (is_numeric($product['efficiency']))

                                        @if ($product['efficiency']>=0 && $product['efficiency']<=24 && $product['greater'])
                                            <td style="background-color: #C01800;color:white">{{round($product['efficiency'],2) }} %</td>
                                        @endif

                                        @if ($product['efficiency']>=25 && $product['efficiency']<=49 && $product['greater'])
                                            <td style="background-color: #FB2301;color:white">{{round($product['efficiency'],2) }} %</td>
                                        @endif

                                        @if ($product['efficiency']>=50 && $product['efficiency']<=74 && $product['greater'])
                                            <td style="background-color: #FBC001; color:rgb(0, 0, 0)">{{round($product['efficiency'],2) }} %</td>
                                        @endif

                                        @if ($product['efficiency']>=75 && $product['efficiency']<=100 && $product['greater'])
                                            <td style="background-color: #92D050;color:rgb(0, 0, 0)">{{round($product['efficiency'],2) }} %</td>
                                        @endif

                                        @if ($product['efficiency']>=0 && $product['efficiency']<=24 && $product['less'])
                                            <td style="background-color: #2570C0;color:white">{{round($product['efficiency'],2) }} %</td>
                                        @endif

                                        @if ($product['efficiency']>=25 && $product['efficiency']<=49 && $product['less'])
                                            <td style="background-color: #31B0F0;color:rgb(0, 0, 0)">{{round($product['efficiency'],2) }} %</td>
                                        @endif

                                        @if ($product['efficiency']>=50 && $product['efficiency']<=74 && $product['less'])
                                            <td style="background-color: #0AB050; color:rgb(0, 0, 0)">{{round($product['efficiency'],2) }} %</td>
                                        @endif

                                        @if ($product['efficiency']>=75 && $product['efficiency']<=100 && $product['less'])
                                            <td style="background-color: #92D050;color:rgb(0, 0, 0)">{{round($product['efficiency'],2) }} %</td>
                                        @endif
                                    @else
                                        <td>{{$product['efficiency'] }}</td>
                                    @endif
                                    
                                    {{-- =============================== --}}
                                    @if (is_numeric($product['efficiency']))

                                        @if ($product['efficiency']>=0 && $product['efficiency']<=24 && $product['greater'])
                                            <td>HIGHLY CRITICAL</td>
                                        @endif

                                        @if ($product['efficiency']>=25 && $product['efficiency']<=49 && $product['greater'])
                                            <td>CRITICAL</td>
                                        @endif

                                        @if ($product['efficiency']>=50 && $product['efficiency']<=74 && $product['greater'])
                                            <td>HIGH</td>
                                        @endif

                                        @if ($product['efficiency']>=75 && $product['efficiency']<=100 && $product['greater'])
                                            <td>MEDIUM</td>
                                        @endif

                                        @if ($product['efficiency']>=0 && $product['efficiency']<=24 && $product['less'])
                                            <td>HIGHLY OVER</td>
                                        @endif

                                        @if ($product['efficiency']>=25 && $product['efficiency']<=49 && $product['less'])
                                            <td>OVER</td>
                                        @endif

                                        @if ($product['efficiency']>=50 && $product['efficiency']<=74 && $product['less'])
                                            <td>LOW</td>
                                        @endif

                                        @if ($product['efficiency']>=75 && $product['efficiency']<=100 && $product['less'])
                                            <td>MEDIUM</td>
                                        @endif
                                    @else
                                        <td>{{$product['efficiency'] }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
