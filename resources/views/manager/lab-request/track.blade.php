@extends('manager.includes.app')

@section('title','Manager Dashboard - Track Request')

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
                    <h4 class="card-title mb-5">Tracking Order: <strong>Request #{{ sprintf('SPCL-%04d', $invoice->id) }}</strong> </h4>

                    <div class="hori-timeline" dir="ltr">
                        <ul class="list-inline events">

                            @foreach ($request as $key=> $state)
                                <li class="list-inline-item event-list">
                                    <div class="px-2">
                                        <div class="event-date bg-{{$state->status=='Canceled'?'danger':($key%2==0?'primary':'info')}} text-white">
                                            {{$state->status}}
                                        </div>
                                        <h5 class="font-size-16">Done By {{$state->doneBy->name}}</h5>
                                        <p class="text-muted">Request created on {{date('Y-m-d H:i',strtotime($state->created_at.'+2 hours'))}}</p>
                                    </div>
                                </li>
                            @endforeach

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
