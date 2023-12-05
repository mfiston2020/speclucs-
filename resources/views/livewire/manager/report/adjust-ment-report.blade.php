@section('title', getuserType() . ' - ' . ' Product Reporting')
{{-- ==== Breadcumb ======== --}}
@section('current', 'Stock Adjustment Report')
@section('page_name', 'Stock Adjustment Report')
{{-- === End of breadcumb == --}}

@push('css')
    <link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css"
        rel="stylesheet" />
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
                                <img src="{{asset('dashboard/assets/images/loading.gif')}}" width="40" wire:loading wire:target='searchInformation'/>
                            </div>
                            @error('start_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>End Date </label>
                            <div class="input-group">
                                <input type="date" class="form-control" placeholder="mm/dd/yyyy"
                                    wire:model.lazy='end_date'>
                                <img src="{{asset('dashboard/assets/images/loading.gif')}}" width="40" wire:loading wire:target='searchInformation'/>
                            </div>
                            @error('end_date')
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
                $instock=0;
                $outstock=0;
                $dateNow = now();
                $stockTracker   =   \App\Models\TrackStockRecord::whereDate('created_at','>=',date('Y-m-d',strtotime($start_date)))->whereDate('created_at','<=',date('Y-m-d',strtotime($end_date)))->where('company_id',userInfo()->company_id)->where('type','rm')->get();
            @endphp
            <div class="card-body">
                <a onclick="exportAll('xls');" href="#" class="mb-2 btn waves-effect waves-light btn-rounded btn-outline-success" style="align-items: right;">
                    <i class="fa fa-download"></i>
                    Export To Excel
                </a>
                <div class="table-responsive">
                    <table id="file_export" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Product</th>
                                <th>Description</th>
                                <th>Power</th>
                                {{-- <th>Done By</th> --}}
                                <th>IN</th>
                                <th>OUT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $key => $product)
                                @php

                                    $closingStock   =   0;

                                    // $date2=($start_date);
                                    // $date1=now();
                                    // $diff=dateDiffInDays($date1,$date2);

                                    // // if ($diff-1==0) {
                                    // //     $closingStock   =   $product->stock;
                                    // // }
                                    if(date('Y-m-d',strtotime($start_date))<date('Y-m-d',strtotime($product->created_at))){
                                        $closingStock   =   0;
                                        $openingStock   =   0;
                                    }
                                    else{
                                        // dd($stockTracker->where('product_id',$product->id)->all());
                                        $instock    =   $stockTracker->where('product_id',$product->id)->where('reason','adjust')->where('operation','in')->sum('incoming');
                                        $outstock    =   $stockTracker->where('product_id',$product->id)->where('reason','adjust')->where('operation','out')->sum('incoming');

                                        $closingStock   =  $product->stock - $instock + $outstock;
                                    }

                                @endphp

                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $start_date}}</td>
                                    <td>{{ \App\Models\Category::where(['id' => $product->category_id])->pluck('name')->first() }}
                                    </td>
                                    <td>{{ $product->product_name }}</td>
                                    <span
                                        hidden>{{ $power = \App\Models\Power::where(['product_id' => $product->id])->where('company_id', Auth::user()->company_id)->select('*')->first() }}</span>

                                    <td>{{ lensDescription($product->description) }}</td>
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
                                    {{-- <td>{{ $product->user_id }}</td> --}}
                                    {{-- <td>{{ format_money($product->cost) }}</td> --}}

                                    <td>
                                        <a href="" class="update" data-name="stock" data-type="text"
                                            data-pk="{{ $product->id }}"
                                            data-title="Enter Product Name">{{ $instock }}</a>

                                    </td>

                                    <td>
                                        <a href="" class="update" data-name="stock" data-type="text"
                                            data-pk="{{ $product->id }}"
                                            data-title="Enter Product Name">{{ $outstock }}</a>

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
                                {{-- <th>Done By</th> --}}
                                <th>IN</th>
                                <th>OUT</th>
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
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">x</span>
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
                    filename: 'Stock_Adjustment_history_%DD%-%MM%-%YY%-month(%MM%)',
                    format: type
                });
            }
    </script>
@endpush
