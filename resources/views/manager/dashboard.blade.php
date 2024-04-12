@extends('manager.includes.app')

@section('title',__('navigation.manager_s').''. __('navigation.dashboard'))

@push('css')

@endpush
{{-- al business information must be kept private unless you want to see them all the dashboard itmes must be removed and add a company logo

    invoice
    =======

    invoice must have all product all products
    and a detail of everything that was done on a particular invoice for a customer

    --}}
{{-- ==== Breadcumb ======== --}}
@section('current', __('navigation.dashboard') )
@section('page_name',__('navigation.dashboard'))
{{-- === End of breadcumb == --}}

@section('content')



<span hidden>{{$company=\App\Models\CompanyInformation::find(Auth::user()->company_id)}}</span>
@if (userInfo()->permissions=='manager')
    <div class="container-fluid">


        <div class="row">
            <div class="col-12">
                <livewire:no-internet/>
                <!-- Row -->
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-bottom border-info">
                            <div class="card-body">
                                <div class="d-flex no-block align-items-center justify-content-between">
                                    <div>
                                        <h2>{{number_format(\App\Models\Product::where('company_id',Auth::user()->company_id)->count())}}
                                        </h2>
                                        <h6 class="text-info">{{__('navigation.product_nav')}}</h6>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="text-info display-6"><i class="ti-notepad"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="card border-bottom border-cyan">
                            <div class="card-body">
                                <div class="d-flex no-block align-items-center justify-content-between">
                                    <div>
                                        <h2>{{number_format(count(\App\Models\Invoice::where('company_id',Auth::user()->company_id)->get()))}}
                                        </h2>
                                        <h6 class="text-cyan">{{__('navigation.invoice')}}</h6>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="text-cyan display-6"><i class="ti-clipboard"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="card border-bottom border-success">
                            <div class="card-body">
                                <div class="d-flex no-block align-items-center justify-content-between">
                                    <div>
                                        <h2>{{count(\App\Models\Supplier::where('company_id',Auth::user()->company_id)->get())}}
                                        </h2>
                                        <h6 class="text-success">{{__('navigation.suppliers')}}</h6>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="text-success display-6"><i class="ti-wallet"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="card border-bottom border-orange">
                            <div class="card-body">
                                <div class="d-flex no-block align-items-center justify-content-between">
                                    <div>
                                        <h2>{{\App\Models\CompanyInformation::where('id',Auth::user()->company_id)->pluck('sms_quantity')->first()}}
                                        </h2>
                                        <h6 class="text-orange">{{__('navigation.sms')}}</h6>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="text-orange display-6"><i class="ti-comment-alt"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Row -->
            </div>
        </div>

        <livewire:manager.dashboard.stock-analysis-chart lazy="on-load" />

        <!-- ============================================================== -->
        <!-- Sales chart -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-md-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="card-title">{{__('manager/dashboard.sales_summary')}}</h4>
                                <h5 class="card-subtitle">{{__('manager/dashboard.annual_overview')}} </h5>
                            </div>
                            <div class="ml-auto d-flex no-block align-items-center justify-content-between">
                                <ul class="list-inline font-12 dl m-r-15 m-b-0">
                                    <li class="list-inline-item text-info"><i class="fa fa-circle"></i> {{__('manager/dashboard.product_sales')}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <!-- column -->
                            <div class="col-lg-4">

                            <?php
                                $totalValue=0;
                                $earning=0;
                                $Anual_income =   \App\Models\SoldProduct::where('company_id',Auth::user()->company_id)->whereYear('created_at',date('Y'))->get();
                                foreach ($Anual_income as $key => $value) {
                                    $income =   $value->total_amount-($value->quantity*(\App\Models\Product::where('id',$value->product_id)->pluck('cost')->first()));
                                    $earning+=$income;
                                }
                            ?>
                                @if ($earning>0)
                                <h1 class="m-b-0 m-t-30">{{format_money($earning)}}</h1>
                                @else
                                <h1 class="m-b-0 m-t-30" style="color: red">{{format_money($earning)}}</h1>
                                @endif
                                <h6 class="font-light text-muted">{{__('manager/dashboard.annual_gros_profit')}}</h6>
                                <span
                                    hidden>{{$pro  =   \App\Models\Product::where('company_id',Auth::user()->company_id)->select('*')->get()}}</span>

                                @foreach ($pro as $pro)
                                <span hidden>{{$amount =   $pro->cost*$pro->stock}}</span>
                                <span hidden>{{$totalValue =   $totalValue+$amount}}</span>
                                @endforeach
                                <h3 class="m-t-30 m-b-0">{{format_money($totalValue)}}</h3>
                                <h6 class="font-light text-muted">{{__('manager/dashboard.current_stock_value')}}</h6>
                            </div>
                            <!-- column -->
                            <div class="col-lg-8 overflow-x-scroll">
                                <div class="campaign ct-charts"></div>
                            </div>
                            <!-- column -->
                        </div>
                    </div>

                    <!-- Info Box -->

                    <div class="card-body border-top">
                        <div class="row m-b-0">
                            <!-- col -->

                            <div class="col-lg-6 col-md-6">
                                <div class="d-flex align-items-center justify-content-between">
                                    {{-- <div class="m-r-10"><span class="text-orange display-5"><i
                                                class="mdi mdi-wallet"></i></span></div> --}}
                                    <span hidden
                                        >{{$pro  =   \App\Models\Transactions::where('company_id',Auth::user()->company_id)->
                                                            whereYear('created_at',date('Y'))->select('*')->get()}}</span>
                                    @foreach ($pro as $pro)
                                    <span hidden>{{$amount =   $pro->cost*$pro->stock}}</span>
                                    <span hidden>{{$totalValue =   $totalValue+$amount}}</span>
                                    @endforeach
                                    <div>
                                        <span>Total Sales</span>
                                        <h3 class="font-medium m-b-0">
                                            {{format_money(\App\Models\SoldProduct::where('company_id',Auth::user()->company_id)
                                                ->whereYear('created_at',date('Y'))->select('*')->sum('total_amount'))}}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <!-- col -->
                            <!-- col -->
                            <div class="col-lg-6 col-md-6">
                                <div class="d-flex align-items-center justify-content-between">
                                    {{-- <div class="m-r-10"><span class="text-cyan display-5"><i
                                                class="mdi mdi-star-circle"></i></span></div> --}}
                                    <div>
                                        <span>{{__('manager/dashboard.cost_of_good_sold')}}</span>
                                        <h3 class="font-medium m-b-0">
                                            {{format_money($total_product_cost)}}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- column -->
            <div class="col-sm-12 col-lg-4">
                <div class="card card-hover">
                    <div class="card-body">
                        <h4 class="card-title">{{__('manager/dashboard.top_product')}}</h4>
                        <div class="d-flex">
                            <h2>{{format_numbers(\App\Models\SoldProduct::where('company_id',Auth::user()->company_id)->select()->sum('quantity'))}}
                                <small><i class="ti-arrow-up text-success"></i></small></h2>
                            {{-- <span class="ml-auto">Users per minute</span> --}}
                        </div>
                        <div class="m-t-20 m-b-30 text-center">
                            <div id="active-users"></div>
                        </div>
                        <h5>{{__('manager/dashboard.top_product')}}</h5>
                        <ul class="list-group list-group-flush">
                            @foreach ($product as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center justify-content-between">
                                    {{\App\Models\Product::where(['id'=>$item->product_id])->where('company_id',Auth::user()->company_id)->pluck('product_name')->first()}}
                                    {{\App\Models\Product::where(['id'=>$item->product_id])->where('company_id',Auth::user()->company_id)->pluck('description')->first()}}
                                    <span class="badge badge-light badge-pill">{{number_format($item->sold)}}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <!-- column -->
            <div class="col-sm-12 col-lg-4">
                <div class="card card-hover">
                    <div class="card-body">
                        <h4 class="card-title">{{__('manager/dashboard.top_expense')}}</h4>
                        <div class="d-flex">
                            <h2>{{format_numbers($expenses_count)}} <small><i class="ti-arrow-up text-success"></i></small>
                            </h2>
                            {{-- <span class="ml-auto">Users per minute</span> --}}
                        </div>
                        <div class="m-t-20 m-b-30 text-center">
                            <div id="active-users"></div>
                        </div>
                        <h5>{{__('manager/dashboard.top_expense')}}</h5>
                        <ul class="list-group list-group-flush">
                            @foreach ($expenses as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center justify-content-between">
                                {{\App\Models\Transactions::where(['id'=>$item->id])->where('company_id',Auth::user()->company_id)->pluck('title')->first()}}
                                <span
                                    class="badge badge-light badge-pill">{{
                                        format_money(\App\Models\Transactions::where(['id'=>$item->id])->where('company_id',Auth::user()->company_id)->pluck('amount')->first())}}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <!-- column -->
            <div class="col-sm-12 col-lg-4">
                <div class="card card-hover">
                    <div class="card-body">
                        <h4 class="card-title">{{__('manager/dashboard.customer_invoice')}}</h4>
                        <div class="d-flex">
                            <h2>{{format_numbers(count($customerInvoices))}} <small><i
                                        class="ti-arrow-up text-success"></i></small></h2>
                            {{-- <span class="ml-auto">Users per minute</span> --}}
                        </div>
                        <div class="m-t-20 m-b-30 text-center">
                            <div id="active-users"></div>
                        </div>
                        <h5>{{__('manager/dashboard.top_invoice')}}</h5>
                        <ul class="list-group list-group-flush">
                            @foreach ($customerInvoices as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center justify-content-between">
                                {{\App\Models\Customer::where(['id'=>$item->client_id])->pluck('name')->first()}}
                                {{-- Invoice #{{sprintf('%04d',$item->reference_number)}} --}}
                                <span class="">{{date('Y-m-d',strtotime($item->created_at))}}</span>
                                <span
                                    class="text-{{($item->status=='pending')?'waring':'success'}}">{{$item->status}}</span>
                                <span class="badge badge-light badge-pill">{{format_money(+$item->total_amount)}}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="container-fluid" style="background: url('{{asset('documents/logos/'.$company->logo)}}') no-repeat center; background-attachment: fixed;">

    </div>
@endif
@endsection

@push('scripts')
<!--chartis chart-->
<script src="{{ asset('dashboard/assets/libs/chartist/dist/chartist.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js')}}"></script>
<script>
    $(function () {
        var myData = '';
        $.ajax({
            url: "{{ route('manager.getChartData') }}",
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                chart(data);
            }
        });


        // ==============================================================
        // Newsletter
        // ==============================================================

        function chart(data) {
            var chart = new Chartist.Line('.campaign', {
                labels: data.months,
                series: [
                    data.product_count,
                ]
            }, {
                low: 0,
                high: data.max,

                showArea: true,
                fullWidth: true,
                plugins: [
                    Chartist.plugins.tooltip()
                ],
                axisY: {
                    onlyInteger: true,
                    scaleMinSpace: 40,
                    offset: 20,
                    labelInterpolationFnc: function (value) {
                        return (value / 1);
                    }
                },

            });
        }
    });
</script>
@endpush
