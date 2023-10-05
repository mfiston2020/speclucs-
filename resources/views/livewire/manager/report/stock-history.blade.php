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

    @if ($result && count($products) > 0)

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                {{-- <th>Date</th> --}}
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
                                    {{-- <td>date</td> --}}
                                    <td>{{ \App\Models\Category::where(['id' => $product->category_id])->pluck('name')->first() }}
                                    </td>
                                    <td>{{ $product->product_name }}</td>
                                    <span
                                        hidden>{{ $power = \App\Models\Power::where(['product_id' => $product->id])->where('company_id', Auth::user()->company_id)->select('*')->first() }}</span>

                                    <td>{{ $product->description }}</td>
                                    <td>
                                        @if ($power)
                                            @if (initials($product->product_name) == 'SV')
                                                <span>{{ $power->sphere }} / {{ $power->cylinder }}</span>
                                            @else
                                                <span>{{ $power->sphere }} / {{ $power->cylinder }}
                                                    *{{ $power->axis }} {{ $power->add }}</span>
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
                                            {{$receivedProducts->where('product_id',$product->id)->sum('stock')}}
                                        </a>

                                    </td>

                                    <td>
                                        {{-- @php
                                            $stockout   =   ;
                                        @endphp --}}
                                        <a href="#!" class="update" data-name="stock" data-type="text"
                                            data-pk="{{ $product->id }}"
                                            data-title="Enter Product Name">
                                            {{$soldProducts->where('product_id',$product->id)->sum('quantity')}}
                                        </a>

                                    </td>
                                </tr>
                                {{-- <span hidden>{{ $closingStock = 0 }}</span> --}}
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                {{-- <th>Date</th> --}}
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

@push('scripts')
@endpush
