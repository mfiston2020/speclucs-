@extends('manager.includes.app')

@section('title','Manager Dashboard - Track Order')

@push('css')
<link href="{{ asset('dashboard/assets/extra-libs/horizontal-timeline/horizontal-timeline.css')}}" rel="stylesheet">
<style>
    .hori-timeline .events {
    border-top: 3px solid #e9ecef;
}
.hori-timeline .events .event-list {
    display: block;
    position: relative;
    text-align: center;
    padding-top: 70px;
    margin-right: 0;
}
.hori-timeline .events .event-list:before {
    content: "";
    position: absolute;
    height: 36px;
    border-right: 2px dashed #dee2e6;
    top: 0;
}
.hori-timeline .events .event-list .event-date {
    position: absolute;
    top: 38px;
    left: 0;
    right: 0;
    width: 75px;
    margin: 0 auto;
    border-radius: 4px;
    padding: 2px 4px;
}
@media (min-width: 1140px) {
    .hori-timeline .events .event-list {
        display: inline-block;
        width: 24%;
        padding-top: 45px;
    }
    .hori-timeline .events .event-list .event-date {
        top: -12px;
    }
}
.bg-soft-primary {
    background-color: rgba(64,144,203,.3)!important;
}
.bg-soft-success {
    background-color: rgba(71,189,154,.3)!important;
}
.bg-soft-danger {
    background-color: rgba(231,76,94,.3)!important;
}
.bg-soft-warning {
    background-color: rgba(249,213,112,.3)!important;
}
.card {
    border: none;
    margin-bottom: 24px;
    -webkit-box-shadow: 0 0 13px 0 rgba(236,236,241,.44);
    box-shadow: 0 0 13px 0 rgba(236,236,241,.44);
}
</style>
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','Track Order')
@section('current','Track Order')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Tracking Order: <strong>{{$order_number}}</strong> </h4>
    
                    <div class="hori-timeline" dir="ltr">
                        <ul class="list-inline events">
                            <li class="list-inline-item event-list">
                                <div class="px-2">
                                    <div class="event-date bg-soft-primary text-primary">{{date('M-d',strtotime($order->created_at))}}</div>
                                    <h5 class="font-size-16">Order Placement</h5>
                                    <p class="text-muted">This Order was created on {{date('Y-M-d',strtotime($order->created_at))}}</p>
                                    {{-- <div>
                                        <a href="#" class="btn btn-primary btn-sm">Read more</a>
                                    </div> --}}
                                </div>
                            </li>
                            @if ($order->production_date)
                                <li class="list-inline-item event-list">
                                    <div class="px-2">
                                        <div class="event-date bg-soft-success text-primary">{{date('M-d',strtotime($order->production_date))}}</div>
                                        <h5 class="font-size-16">Production Date</h5>
                                        <p class="text-muted">This Order's production started on {{date('Y-M-d',strtotime($order->production_date))}}</p>
                                        {{-- <div>
                                            <a href="#" class="btn btn-primary btn-sm">Read more</a>
                                        </div> --}}
                                    </div>
                                </li>
                                @if ($order->completed_date)
                                    <li class="list-inline-item event-list">
                                        <div class="px-2">
                                            <div class="event-date bg-soft-primary text-primary">{{date('M-d',strtotime($order->completed_date))}}</div>
                                            <h5 class="font-size-16">Completion Date</h5>
                                            <p class="text-muted">This Order was Completed on {{date('Y-M-d',strtotime($order->completed_date))}}</p>
                                            {{-- <div>
                                                <a href="#" class="btn btn-primary btn-sm">Read more</a>
                                            </div> --}}
                                        </div>
                                    </li>
                                    @if ($order->delivery_date)
                                        <li class="list-inline-item event-list">
                                            <div class="px-2">
                                                <div class="event-date bg-soft-success text-primary">{{date('M-d',strtotime($order->delivery_date))}}</div>
                                                <h5 class="font-size-16">Delivery Date</h5>
                                                <p class="text-muted">This Order was delivered on {{date('Y-M-d',strtotime($order->delivery_date))}}</p>
                                                {{-- <div>
                                                    <a href="#" class="btn btn-primary btn-sm">Read more</a>
                                                </div> --}}
                                            </div>
                                        </li>
                                    @endif
                                @endif
                            @endif
                        </ul>
                    </div>
                    <hr>
                    <a href="{{url()->previous()}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Go back</a>
                </div>
            </div>
            <!-- end card -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('dashboard/assets/extra-libs/horizontal-timeline/horizontal-timeline.js')}}"></script>
@endpush
