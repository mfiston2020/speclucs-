@extends('manager.includes.app')

@section('title','Manager Dashboard - Credits')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Credits')
@section('page_name','Credits List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">All Credits</h4><hr>
                        <a href="{{url()->previous()}}" type="button" class="btn waves-effect waves-light btn-rounded btn-outline-secondary mr-2" style="align-items: right;">
                            <i class="fa fa-arrow-left"></i> Go Back</a>

                        <a href="{{route('manager.my.order.invoice')}}" type="button" class="btn waves-effect waves-light btn-rounded btn-outline-primary" style="align-items: right;">
                            <i class="fa fa-plus"></i> Credit Request</a>
                    </div>
                        <hr>
                        {{-- ================================= --}}
                        @include('manager.includes.layouts.message')
                        {{-- ========================== --}}

                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered nowrap"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice Number</th>
                                    <th>Order Number</th>
                                    <th>Supplier Name</th>
                                    <th>Credit Amount</th>
                                    <th>Final Amount</th>
                                    <th>Supplier Comment</th>
                                    <th>Status</th>
                                    <th>Created date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($credits as $key=> $credit)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$credit->invoice_id}}</td>
                                        <td>{{\App\Models\Order::where('id',$credit->order_id)->pluck('order_number')->first()}}</td>
                                        <td>{{\App\Models\CompanyInformation::where('id',$credit->supplier_id)->pluck('company_name')->first()}}</td>
                                        <td>{{format_money($credit->credit_amount)}}</td>
                                        <td>{{format_money($credit->credit_amount + $credit->additional_amount)}}</td>
                                        <td>{{$credit->comment}}</td>
                                        @if ($credit->status=='pending')
                                            <td><span class="badge badge-warning">{{$credit->status}}</span></td>
                                        @endif
                                        @if ($credit->status=='approved')
                                            <td><span class="badge badge-success">{{$credit->status}}</span></td>
                                        @endif
                                        @if ($credit->status=='declined')
                                            <td><span class="badge badge-danger">{{$credit->status}}</span></td>
                                        @endif
                                        <td>{{date('Y-m-d',strtotime($credit->created_at))}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js')}}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js')}}"></script>
@endpush
