<div class="col-md-12">


        @php
            $productRepo  =   new App\Repositories\ProductRepo();
        @endphp

    @if ($showTable)
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="d-flex flex-row">
                        <div class="p-10 bg-info">
                            <h3 class="text-white box mb-0"><i class="ti-themify-favicon-alt"></i></h3></div>
                        <div class="p-10">
                            <h3 class="text-info mb-0">{{$period_months}} Months {{$period_days}} Days</h3>
                            <span class="text-muted">Supply Period</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="d-flex flex-row">
                        <div class="p-10 bg-warning">
                            <h3 class="text-white box mb-0"><i class="ti-target"></i></h3></div>
                        <div class="p-10">
                            <h3 class="text-warning mb-0">{{$leadTime}} Days</h3>
                            <span class="text-muted">Lead Time</span>
                        </div>
                    </div>
                </div>
            </div>


            {{-- <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="d-flex flex-row">
                        <div class="p-10 bg-info">
                            <h3 class="text-white box mb-0">
                                <i class="ti-calendar"></i>
                            </h3>
                        </div>
                        <div class="p-10">
                            <h3 class="text-info mb-0" id="totalCostDisplay">{{format_money($totalCost)}}</h3>
                            <span class="text-muted">Total Cost</span>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    @endif

    <div class="card">
        @if (!$showTable)
            <form wire:submit='searchInformation'>
                @csrf
                <div class="card-body">

                    <div class="row">

                        <!--/span-->
                        <div class="col-md-4">
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

                        <!--/span-->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Order Date </label>
                                <input class="form-control" min="{{date('Y-m-d')}}" max="{{date('Y-m-d')}}" wire:model.live="order_date" type="date" id="example-time-input">
                                @error('order_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!--/span-->
                        <div class="col-md-4 mb-4">
                            <div class="form-group">
                                <label> Expected Delivery Date </label>
                                <input class="form-control" min="{{date('Y-m-d')}}" wire:model.live="delivery_date" type="date" id="example-time-input">
                                @error('delivery_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        {{-- <div> --}}
                            <hr>
                            <h4 class="col-12">Supply Period</h4>
                        {{-- </div> --}}

                        <!--/span-->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-color-input" class="col-2 col-form-label">Days</label>
                                <div class="col-10">
                                    <input type="range" wire:model.live="period_days" class="form-control" id="days" value="0" min="0" max="30">
                                    <span id="daysRangeShow">{{$period_days}}</span> Day(s)
                                </div>
                                @error('period_days')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!--/span-->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-color-input" class="col-2 col-form-label">Months</label>
                                <div class="col-10">
                                    <input type="range" wire:model.live="period_months" class="form-control" id="months" value="0" min="0" max="12">
                                    <span id="monthsRangeShow">{{$period_months}}</span> Month(s)
                                </div>
                                @error('period_months')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <hr>

                    <button type="submit" class="btn btn-primary">
                        <span wire:loading.remove wire:target=searchInformation>Search</span>
                        <span wire:loading wire:target='searchInformation'>Generating...</span>
                    </button>
                    <img src="{{asset('dashboard/assets/images/loading.gif')}}" width="40" wire:loading wire:target='searchInformation'/>
                </div>
            </form>
        @else
            <div class="card-body">

                <div class="d-flex justify-content-between mb-3">
                    <h4 class="card-title">Purchase Order
                    </h4>
                    <div class="d-flex">
                        <button onclick="ExportToExcel('xlsx');" class="btn btn-sm btn-success mr-3 rounded">Excel </button>
                        <button wire:click="goBack" class="btn btn-sm btn-outline-danger rounded">
                            <span wire:loading.remove wire:target="goBack">go Back</span>
                            <span wire:loading wire:target="goBack">going Back....</span>
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="zero_config" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>power</th>
                                <th>Quantity</th>
                                <th>Cost</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $key=> $product)
                            @php
                                if (is_numeric($product->stock)) {
                                    $po =   $productRepo->productStockEfficiency($product->id,$product->soldproducts->sum('quantity'),$product->stock,$product->category_id,$leadTime);
                                }

                                $leadTimeQuantity   =   ($po['usage']*$leadTime)/$totalDays;
                                $orderQuantity      =   floor((($po['usage']*2)+$leadTimeQuantity)-$po['stock']);

                                $totalCost = $totalCost + ((($orderQuantity>0)?$product->cost * $orderQuantity:0));
                            @endphp
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $product->description }}</td>
                                    <td>
                                        @if ($product->power)
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
                                    <td>{{($orderQuantity<0)?'0':$orderQuantity}}</td>
                                    <td>{{ $product->cost}}</td>
                                    <td>{{ ($orderQuantity>0)?$product->cost * $orderQuantity:0 }}</td>
                                </tr>
                            @endforeach
                            <h4 class="card-title mb-3">
                                Total Cost Order:
                                <span class="text-primary" id="totalCostss">{{format_money($totalCost)}}</span>
                            </h4>

                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

@push('css')
    <link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

@push('scripts')

    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script>
        function ExportToExcel(type, fn, dl) {
            console.log();
            var elt = document.getElementById('zero_config');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
                XLSX.writeFile(wb, fn || ('MySheetName.' + (type || 'xlsx')));
        }

        document.addEventListener('livewire:total-cost', function () {
            alert('sdjflkajsdlf');
        });
        const value = document.querySelector("#monthsRangeShow");
        const input = document.querySelector("#months");
        input.addEventListener("input", (event) => {
            value.textContent = event.target.value;
        });

        const days = document.querySelector("#daysRangeShow");
        const dayRange = document.querySelector("#days");
        dayRange.addEventListener("input", (event) => {
            days.textContent = event.target.value;
        });
    </script>
@endpush
