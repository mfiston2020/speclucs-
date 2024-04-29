<div class="row">
    <div class="col-12">
        <img src="{{ asset('dashboard/assets/images/loading.gif')}}" height="150" wire:loading wire:target="timeframe" class="mb-4"/>
        <!-- Row -->
        <div class="row" wire:loading.remove wire:target="timeframe">
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-md-flex align-items-center">
                            <div>
                                <h4 class="card-title">Stock Summary</h4>
                            </div>
                            <div class="ml-auto d-flex no-block align-items-center">
                                <div class="dl">
                                    <select class="custom-select" wire:model="timeframe">
                                        <option value="0" selected>Monthly</option>
                                        <option value="1">Daily</option>
                                        <option value="2">Weekly</option>
                                        <option value="3">Yearly</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" wire:loading.remove wire:target="timeframe">
            <div class="col-sm-12 col-md-4">
                <div class="card order-widget">
                    <div class="card-body">
                        <div class="row">
                            <!-- column -->
                            <div class="col-sm-12 col-md-12">
                                <h4 class="card-title">Lens Summary</h4>
                                {{-- <h5 class="card-subtitle m-b-0">Total Earnings of the Month</h5> --}}
                                <div class="row m-t-20">
                                    <div class="col-4 border-right">
                                        <i class="fa fa-circle text-danger"></i>
                                        <h3 class="m-b-0 font-danger">{{  number_format($lensData['lens_discontinued']) }}</h3>
                                        <span>Double Zero</span>
                                    </div>
                                    <div class="col-4 border-right">
                                        <i class="fa fa-circle text-danger"></i>
                                        <h3 class="m-b-0 font-medium">{{  number_format($lensData['lens_critical']) }}</h3>
                                        <span>Critical</span>
                                    </div>
                                    <div class="col-4">
                                        <i class="fa fa-circle text-orange"></i>
                                        <h3 class="m-b-0 font-medium">{{  number_format($lensData['lens_high']) }}</h3>
                                        <span>High</span>
                                    </div>
                                    <div class="col-4 border-right">
                                        <i class="fa fa-circle text-success"></i>
                                        <h3 class="m-b-0 font-medium">{{  number_format($lensData['lens_medium']) }}</h3>
                                        <span>Medium</span>
                                    </div>
                                    <div class="col-4 border-right">
                                        <i class="fa fa-circle text-primary"></i>
                                        <h3 class="m-b-0 font-medium">{{  number_format($lensData['lens_low']) }}</h3>
                                        <span>Low</span>
                                    </div>
                                    <div class="col-4">
                                        <i class="fa fa-circle text-info"></i>
                                        <h3 class="m-b-0 font-info">{{  number_format($lensData['lens_over']) }}</h3>
                                        <span>Over Stock</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-4">
                <div class="card order-widget">
                    <div class="card-body">
                        <div class="row">
                            <!-- column -->
                            <div class="col-sm-12 col-md-12">
                                <h4 class="card-title">Frame Summary</h4>
                                {{-- <h5 class="card-subtitle m-b-0">Total Earnings of the Month</h5> --}}
                                <div class="row m-t-20">
                                    <div class="col-4 border-right">
                                        <i class="fa fa-circle text-danger"></i>
                                        <h3 class="m-b-0 font-danger">{{  number_format($frameData['frame_discontinued']) }}</h3>
                                        <span>Double Zero</span>
                                    </div>
                                    <div class="col-4 border-right">
                                        <i class="fa fa-circle text-danger"></i>
                                        <h3 class="m-b-0 font-medium">{{  number_format($frameData['frame_critical']) }}</h3>
                                        <span>Critical</span>
                                    </div>
                                    <div class="col-4">
                                        <i class="fa fa-circle text-orange"></i>
                                        <h3 class="m-b-0 font-medium">{{  number_format($frameData['frame_high']) }}</h3>
                                        <span>High</span>
                                    </div>
                                    <div class="col-4 border-right">
                                        <i class="fa fa-circle text-success"></i>
                                        <h3 class="m-b-0 font-medium">{{  number_format($frameData['frame_medium']) }}</h3>
                                        <span>Medium</span>
                                    </div>
                                    <div class="col-4 border-right">
                                        <i class="fa fa-circle text-primary"></i>
                                        <h3 class="m-b-0 font-medium">{{  number_format($frameData['frame_low']) }}</h3>
                                        <span>Low</span>
                                    </div>
                                    <div class="col-4">
                                        <i class="fa fa-circle text-info"></i>
                                        <h3 class="m-b-0 font-info">{{  number_format($frameData['frame_over']) }}</h3>
                                        <span>Over Stock</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-4">
                <div class="card order-widget">
                    <div class="card-body">
                        <div class="row">
                            <!-- column -->
                            <div class="col-sm-12 col-md-12">
                                <h4 class="card-title">Accessories Summary</h4>
                                {{-- <h5 class="card-subtitle m-b-0">Total Earnings of the Month</h5> --}}
                                <div class="row m-t-20">
                                    <div class="col-4 border-right">
                                        <i class="fa fa-circle text-danger"></i>
                                        <h3 class="m-b-0 font-danger">{{ number_format($accessoriesData['accessories_discontinued']) }}</h3>
                                        <span>Double Zero</span>
                                    </div>
                                    <div class="col-4 border-right">
                                        <i class="fa fa-circle text-danger"></i>
                                        <h3 class="m-b-0 font-medium">{{ number_format($accessoriesData['accessories_critical']) }}</h3>
                                        <span>Critical</span>
                                    </div>
                                    <div class="col-4">
                                        <i class="fa fa-circle text-orange"></i>
                                        <h3 class="m-b-0 font-medium">{{ number_format($accessoriesData['accessories_high']) }}</h3>
                                        <span>High</span>
                                    </div>
                                    <div class="col-4 border-right">
                                        <i class="fa fa-circle text-success"></i>
                                        <h3 class="m-b-0 font-medium">{{ number_format($accessoriesData['accessories_medium']) }}</h3>
                                        <span>Medium</span>
                                    </div>
                                    <div class="col-4 border-right">
                                        <i class="fa fa-circle text-primary"></i>
                                        <h3 class="m-b-0 font-medium">{{ number_format($accessoriesData['accessories_low']) }}</h3>
                                        <span>Low</span>
                                    </div>
                                    <div class="col-4">
                                        <i class="fa fa-circle text-info"></i>
                                        <h3 class="m-b-0 font-info">{{ number_format($accessoriesData['accessories_over']) }}</h3>
                                        <span>Over Stock</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- End Row -->

    </div>
</div>

@push('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart');
        var chart = new Chart(ctx);

        window.addEventListener('DOMContentLoaded', e => {
            stockAnalysis();
            // stockAnalysispie();
            // {{  number_format($lensData['lens_critical']) }},{{  number_format($frameData['frame_critical']) }},{{ $accessoryData['accessories_critical'] }}
            console.log({{ number_format($lensData['lens_critical']}}))
        })

        window.addEventListener('refreshChart', event => {
            if (chart) {
                chart.destroy();
                chart = new Chart(ctx);
            } else {
                chart = new Chart(ctx);
            }
            console.log({{ number_format($lensData['lens_critical']}}))
        })

        //  window.addEventListener('refreshChart', function() {
        //     const ctx = document.getElementById('myChart');
        //     var myLineChart = new Chart(ctx);
        //     myLineChart.destroy();

        //     console.log({{ number_format($lensData['lens_critical']}}))

        //     stockAnalysis();
        // });

        function stockAnalysis(){
            const ctx = document.getElementById('myChart');

            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                labels: ['Lens', 'Fames', 'Accessories',],
                datasets: [
                            {
                                label: "Critical",
                                backgroundColor: "#A7194A95",
                                data: [8,7,4]
                            },
                            {
                                label: "High",
                                backgroundColor: "#ff000095",
                                data: [8,3,5]
                            },
                            {
                                label: "Medium",
                                backgroundColor: "#FEFD3495",
                                data: [8,2,6]
                            },
                            {
                                label: "Low",
                                backgroundColor: "#00ff0095",
                                data: [8,2,6]
                            },
                            {
                                label: "Over Stock",
                                backgroundColor: "#2F92CE95",
                                data: [8,2,6]
                            }
                        ]
                },
                options: {
                scales: {
                    y: {
                    beginAtZero: true
                    }
                }
                }
            });
        }

        // function stockAnalysispie(){
        //     const ctx = document.getElementById('myCrt');

        //     new Chart(ctx, {
        //         type: 'line',
        //         data: {
        //         labels: ['Lens', 'Fames', 'Accessories',],
        //         datasets: [{
        //                     label: "Critical",
        //                     backgroundColor: "#A7194A95",
        //                     data: [8,7,4]
        //                 },{
        //                     label: "High",
        //                     backgroundColor: "#ff000095",
        //                     data: [4,3,5]
        //                 },{
        //                     label: "Medium",
        //                     backgroundColor: "#FEFD3495",
        //                     data: [7,2,6]
        //                 },{
        //                     label: "Low",
        //                     backgroundColor: "#00ff0095",
        //                     data: [7,2,6]
        //                 },{
        //                     label: "Over Stock",
        //                     backgroundColor: "#2F92CE95",
        //                     data: [7,2,6]
        //                 }
        //             ]
        //         },
        //         options: {
        //             scales: {
        //                 y: {
        //                 beginAtZero: true
        //                 }
        //             }
        //         }
        //     });
        // }
    </script> --}}
@endpush
