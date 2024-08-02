@section('title', getuserType() . ' - ' . ' Product Reporting')
{{-- ==== Breadcumb ======== --}}
@section('current', 'Stock History Report')
@section('page_name', 'Stock History Report')
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
                                        <option value="{{$type->name}}">{{$type->name}}</option>
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
                    <span wire:loading.remove wire:target='searchInformation'>Search</span>
                    <span wire:loading wire:target='searchInformation'>Searching...</span>
                </button>
            </div>
        </form>
    </div>

    @if ($result && count($products) > 0)

        <div class="card">
            <div class="card-body">
                <a onclick="ExportToExcel('xlsx')" href="#" class="ml-2 btn waves-effect waves-light btn-rounded btn-outline-success" style="align-items: right;">
                    <i class="fa fa-download"></i> Export To Excel
                </a>
                <div class="table-responsive mt-3">
                    <table id="zero_config" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Product</th>
                                <th>Description</th>
                                <th>Power</th>
                                <th>Price</th>
                                <th>cost</th>
                                <th>Stock In</th>
                                <th>Stock Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $key => $product)

                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->category->name }}
                                    </td>
                                    <td>{{ $product->product_name }}</td>

                                    <td>{{ lensDescription($product->description) }}</td>
                                    <td>
                                        @if (!is_null($product->power))
                                            @if (initials($product->product_name) == 'SV')
                                                <span>{{ $product->power->sphere }} / {{ $product->power->cylinder }}</span>
                                            @else
                                                <span>{{ $product->power->sphere }} / {{ $product->power->cylinder }}
                                                    *{{ $product->power->axis }} {{ $product->power->add }}</span>
                                            @endif
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>
                                    <td>{{ format_money($product->price) }}</td>
                                    <td>{{ format_money($product->cost) }}</td>

                                    <td>
                                        <a href="#!" class="update" data-name="stock" data-type="text"
                                            data-pk="{{ $product->id }}"
                                            data-title="Enter Product Name">
                                            {{ $product->productTrack->where('operation','in')->sum('incoming')}}
                                        </a>
                                    </td>

                                    <td>
                                        <a href="#!" class="update" data-name="stock" data-type="text"
                                            data-pk="{{ $product->id }}"
                                            data-title="Enter Product Name">
                                            {{ $product->productTrack->where('operation','out')->sum('incoming')}}
                                        </a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Product</th>
                                <th>Description</th>
                                <th>Power</th>
                                <th>Price</th>
                                <th>cost</th>
                                <th>Stock In</th>
                                <th>Stock Out</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    @endif

    @if ($searchFoundSomething == 'no')
        <div class="card">
            <div class="card-body">
                <div class="alert alert-warning alert-rounded ">
                    Nothing Found from: <strong>{{ $start_date }}</strong> up to
                    <strong>{{ date('Y-m-d',strtotime(now())) }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span
                            aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

