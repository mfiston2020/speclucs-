@extends('manager.includes.app')

@section('title','Admin Dashboard - Suppliers')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Income')
@section('page_name','Income List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">All Income</h4><hr>
                        <a href="{{route('manager.income.add')}}" type="button" class="btn waves-effect waves-light btn-rounded btn-outline-primary" style="align-items: right;">
                            <i class="fa fa-plus"></i> Register Income</a>
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
                                    <th>Title</th>
                                    <th>Payment Method</th>
                                    <th>Amount Paid</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($income as $key=> $income)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$income->title}}</td>
                                        <td>
                                            <a href="#">{{\App\Models\PaymentMethod::where(['id'=>$income->payment_method_id])->pluck('name')->first()}}</a>
                                        </td>
                                        <td>{{format_money($income->amount)}}</td>
                                        <td>{{date('Y-m-d H:i',strtotime($income->created_at))}}</td>
                                        <td>
                                            @if ($income->title=='payment from customer')
                                                <span class="label label-primary">No Action needed</span>
                                            @else
                                                <a href="#" style="color: blue">edit</a>
                                                <a href="#" style="color: red; padding-left: 30px;">delete</a>
                                            @endif

                                        </td>
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
