@extends('seller.includes.app')

@section('title','Seller - Suppliers')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Expenses')
@section('page_name','Expense\'s List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">All Expenses</h4><hr>
                        <a href="{{route('seller.expense.add')}}" type="button" class="btn waves-effect waves-light btn-rounded btn-outline-primary" style="align-items: right;">
                            <i class="fa fa-plus"></i> Register Expense</a>
                    </div>
                        <hr>
                        {{-- ================================= --}}
                        @include('seller.includes.layouts.message')
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
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($expenses as $key=> $expense)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$expense->title}}</td>
                                        <td>
                                            <a href="#">{{\App\Models\PaymentMethod::where(['id'=>$expense->payment_method_id])->where('company_id',Auth::user()->company_id)->pluck('name')->first()}}</a>
                                        </td>
                                        <td>{{format_money($expense->amount)}}</td>
                                        <td>
                                            <a href="#" style="color: blue">edit</a>
                                            <a href="#" style="color: red; padding-left: 30px;">delete</a>
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
