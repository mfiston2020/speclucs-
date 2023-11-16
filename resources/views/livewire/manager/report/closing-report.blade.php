@section('title', getuserType() . ' - ' . ' Product Reporting')
{{-- ==== Breadcumb ======== --}}
@section('current', 'Stock Closing Report')
@section('page_name', 'Stock Closing Report')
{{-- === End of breadcumb == --}}

@push('css')
@endpush

<div class="col-md-12">

    @php
        $pwr        =   \App\Models\Power::where('company_id', Auth::user()->company_id)->get();
        $category   =   \App\Models\Category::all();
    @endphp

    <div class="card">
        <form wire:submit.prevent='searchInformation'>
            @csrf
            <div class="card-body">

                <div class="row">
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Closing Date </label>
                            <div class="input-group">
                                <input type="date" class="form-control" placeholder="mm/dd/yyyy"
                                    wire:model.lazy='closing_date'>
                                <img src="{{asset('dashboard/assets/images/loading.gif')}}" width="40" wire:loading wire:target='searchInformation'/>
                            </div>
                            @error('closing_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                </div>

                <button type="submit" class="btn btn-primary">
                    <span wire:loading.remove wire:target=searchInformation>Search</span>
                    <span wire:loading wire:target='searchInformation'>Searching...</span>
                </button>
            </div>
        </form>
    </div>

    @if ($result && count($products) > 0)

        <div class="card">
            @php
                $dateNow = now();
                $stockTracker   =   \App\Models\TrackStockRecord::whereDate('created_at','>=',date('Y-m-d',strtotime($closing_date)))->whereDate('created_at','<=',date('Y-m-d',strtotime($dateNow.'-1day')))->where('company_id',userInfo()->company_id)->where('type','rm')->get();

                $stockTracker2   =   \App\Models\TrackStockRecord::whereDate('created_at','>=',date('Y-m-d',strtotime($closing_date)))->whereDate('created_at','<=',date('Y-m-d',strtotime($dateNow)))->where('company_id',userInfo()->company_id)->where('type','rm')->get();
            @endphp

            <div class="card-body">
                <a onclick="exportAll('xls');" href="#" class="ml-2 btn waves-effect waves-light btn-rounded btn-outline-success" style="align-items: right;">
                    <i class="fa fa-download"></i> Export To Excel
                </a>

                {{-- @if ()

                @endif --}}

                <div class="table-responsive mt-3">
                    <table id="file_export" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Product</th>
                                <th>Description</th>
                                <th>Power</th>
                                <th>Price</th>
                                <th>cost</th>
                                <th>Closing Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $key => $product)
                                @php

                                    $closingStock   =   0;

                                    $date2=($closing_date);
                                    $date1=now();
                                    $diff=dateDiffInDays($date1,$date2);

                                    // if ($diff-1==0) {
                                    //     $closingStock   =   $product->stock;
                                    // }
                                    if(date('Y-m-d',strtotime($closing_date))<date('Y-m-d',strtotime($product->created_at))){
                                        $closingStock   =   0;
                                        $openingStock   =   0;
                                    }
                                    else{
                                        // dd($stockTracker->where('product_id',$product->id)->all());
                                        $instock    =   $stockTracker->where('product_id',$product->id)->where('operation','in')->sum('incoming');
                                        $outstock    =   $stockTracker->where('product_id',$product->id)->where('operation','out')->sum('incoming');

                                        $closingStock   =  $product->stock - $instock + $outstock;
                                    }

                                @endphp

                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $closing_date}}</td>
                                    <td>{{ $category->where('id',$product->category_id)->pluck('name')->first() }}
                                    </td>
                                    <td>{{ $product->product_name }}</td>
                                    <span hidden>{{ $power = $pwr->where('product_id',$product->id)->first() }}</span>

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
                                        <a href="" class="update" data-name="stock" data-type="text"
                                            data-pk="{{ $product->id }}"
                                            data-title="Enter Product Name">{{ $closingStock }}</a>

                                    </td>
                                </tr>
                                <span hidden>{{ $closingStock = 0 }}</span>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Product</th>
                                <th>Description</th>
                                <th>Power</th>
                                <th>Price</th>
                                <th>cost</th>
                                <th>Closing Stock</th>
                            </tr>
                        </tfoot>
                    </table>
                    <hr>
                    <button class="btn btn-primary btn-rounded mb-2" wire:click="loadMore">
                        <span wire:loading wire:target="loadMore">
                            <img src="{{asset('dashboard/assets/images/loading2.gif')}}" width="20" alt=""> Loading...
                        </span>
                        <span wire:loading.remove>Load More</span>
                    </button>
                </div>
            </div>

        </div>

    @endif

    @if ($searchFoundSomething == 'no')
        <div class="card">
            <div class="card-body">
                <div class="alert alert-warning alert-rounded ">
                    Nothing Found from: <strong>{{ $closing_date }}</strong> up to
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
    <script src="{{ asset('dashboard/assets/dist/js/export.js') }}"></script>
    <script>
        function exportAll(type) {

                $('#file_export').tableExport({
                    filename: 'closing_stock_%DD%-%MM%-%YY%-month(%MM%)',
                    format: type
                });
            }
    </script>
@endpush
