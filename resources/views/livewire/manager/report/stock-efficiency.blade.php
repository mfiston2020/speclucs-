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
                
                <div class="d-flex align-items-center justify-content-between">
                    <a onclick="ExportToExcel('xlsx')" href="#" class=" mt-2 mb-2 ml-2 btn waves-effect waves-light btn-rounded btn-outline-success" style="align-items: right;">
                        <i class="fa fa-download"></i> Export To Excel
                    </a>
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center">
                            <div style="background-color: #C01800; height:1rem; width:1rem;"></div>
                            <span class=" ml-2">HIGHLY CRITICAL </span>
                        </div>
                        <div class="d-flex align-items-center ml-3">
                            <div style="background-color: #FB2301; height:1rem; width:1rem;"></div>
                            <span class=" ml-2">CRITICAL </span>
                        </div>
                        <div class="d-flex align-items-center ml-3">
                            <div style="background-color: #FBC001; height:1rem; width:1rem;"></div>
                            <span class=" ml-2">HIGH </span>
                        </div>
                        <div class="d-flex align-items-center ml-3">
                            <div style="background-color: #92D050; height:1rem; width:1rem;"></div>
                            <span class=" ml-2">MEDIUM </span>
                        </div>
                        <div class="d-flex align-items-center ml-3">
                            <div style="background-color: #31B0F0; height:1rem; width:1rem;"></div>
                            <span class=" ml-2">OVER </span>
                        </div>
                        <div class="d-flex align-items-center ml-3">
                            <div style="background-color: #2570C0; height:1rem; width:1rem;"></div>
                            <span class=" ml-2">HIGHLY OVER </span>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    {{-- <table width="100%" border="1"> --}}
                    <table class="table table-striped table-bordered nowrap fixTableHead"
                        style="width:100%" data-sticky-header id="zero_config">
                        <thead class="font-bold">
                            <tr>
                                <th>#</th>
                                <th>Product Number</th>
                                <th>Product Name</th>
                                <th>Efficiency Ration</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        {{-- body --}}
                        <tbody>
                            @foreach ($productEfficiency as $key=> $product)
                                <tr>
                                    <td>{{$key+1 }}</td>
                                    <td>
                                        {{$product['product']->id }}
                                    </td>
                                    <td>
                                        {{ $product['product']?->product_name.'-'.$product['product']?->description }}
                                        @if ($product['product']->category_id=='1')
                                            |
                                            @if (initials($product['product']->product_name) == 'SV')
                                                <span>
                                                    {{ $product['product']?->power?->sphere }} /
                                                    {{ $product['product']?->power?->cylinder }}
                                                </span>
                                            @else
                                                <span>
                                                    {{ $product['product']?->power?->sphere }} /
                                                    {{ $product['product']?->power?->cylinder }}
                                                    *{{ $product['product']?->power?->axis }}
                                                    {{ $product['product']?->power?->add }}
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                                    
                                    {{-- =============================== --}}
                                    @if (is_numeric($product['efficiency']))

                                        @if ($product['efficiency']>=0 && $product['efficiency']<=24 && $product['greater'])
                                            <td>{{round($product['efficiency'],2) }} %</td>
                                        @endif

                                        @if ($product['efficiency']>=25 && $product['efficiency']<=49 && $product['greater'])
                                            <td>{{round($product['efficiency'],2) }} %</td>
                                        @endif

                                        @if ($product['efficiency']>=50 && $product['efficiency']<=74 && $product['greater'])
                                            <td>{{round($product['efficiency'],2) }} %</td>
                                        @endif

                                        @if ($product['efficiency']>=75 && $product['efficiency']<=100 && $product['greater'])
                                            <td>{{round($product['efficiency'],2) }} %</td>
                                        @endif

                                        @if ($product['efficiency']>=0 && $product['efficiency']<=24 && $product['less'])
                                            <td>{{round($product['efficiency'],2) }} %</td>
                                        @endif

                                        @if ($product['efficiency']>=25 && $product['efficiency']<=49 && $product['less'])
                                            <td>{{round($product['efficiency'],2) }} %</td>
                                        @endif

                                        @if ($product['efficiency']>=50 && $product['efficiency']<=74 && $product['less'])
                                            <td>{{round($product['efficiency'],2) }} %</td>
                                        @endif

                                        @if ($product['efficiency']>=75 && $product['efficiency']<=100 && $product['less'])
                                            <td>{{round($product['efficiency'],2) }} %</td>
                                        @endif
                                    @else
                                        @if ($product['usage']==0)
                                            <td>0%</td>
                                        @endif
                                    @endif
                                    
                                    {{-- =============================== --}}
                                    @if (is_numeric($product['efficiency']))

                                        @if ($product['efficiency']>=0 && $product['efficiency']<=24 && $product['greater'])
                                            <td style="background-color: #C01800;color:white">HIGHLY CRITICAL</td>
                                        @endif

                                        @if ($product['efficiency']>=25 && $product['efficiency']<=49 && $product['greater'])
                                            <td style="background-color: #FB2301;color:white">CRITICAL</td>
                                        @endif

                                        @if ($product['efficiency']>=50 && $product['efficiency']<=74 && $product['greater'])
                                            <td style="background-color: #FBC001; color:rgb(0, 0, 0)">HIGH</td>
                                        @endif

                                        @if ($product['efficiency']>=75 && $product['efficiency']<=100 && $product['greater'])
                                            <td style="background-color: #92D050;color:rgb(0, 0, 0)">MEDIUM</td>
                                        @endif

                                        @if ($product['efficiency']>=0 && $product['efficiency']<=24 && $product['less'])
                                            <td style="background-color: #2570C0;color:white">HIGHLY OVER</td>
                                        @endif

                                        @if ($product['efficiency']>=25 && $product['efficiency']<=49 && $product['less'])
                                            <td style="background-color: #31B0F0;color:rgb(0, 0, 0)">OVER</td>
                                        @endif

                                        @if ($product['efficiency']>=50 && $product['efficiency']<=74 && $product['less'])
                                            <td style="background-color: #0AB050; color:rgb(0, 0, 0)">LOW</td>
                                        @endif

                                        @if ($product['efficiency']>=75 && $product['efficiency']<=100 && $product['less'])
                                            <td style="background-color: #92D050;color:rgb(0, 0, 0)">MEDIUM</td>
                                        @endif
                                    @else
                                        @if ($product['usage']==0)
                                            <td style="background-color: #2570C0;color:white">HIGHLY OVER</td>
                                        @endif
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
