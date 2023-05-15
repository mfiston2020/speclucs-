@extends('manager.includes.app')

@section('title','Manager Dashboard - Expenses')

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
                        <a href="{{route('manager.expense.add')}}" type="button" class="btn waves-effect waves-light btn-rounded btn-outline-primary" style="align-items: right;">
                            <i class="fa fa-plus"></i> Register Expense</a>
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
                                @foreach ($expenses as $key=> $expense)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$expense->title}}</td>
                                        <td>
                                            <a href="#">{{\App\Models\PaymentMethod::where(['id'=>$expense->payment_method_id])->pluck('name')->first()}}</a>
                                        </td>
                                        <td>{{format_money($expense->amount)}}</td>
                                        <td>{{date('Y-m-d H:i',strtotime($expense->created_at))}}</td>
                                        <td>
                                            {{-- <a href="#" style="color: blue">edit</a> --}}
                                            <a data-toggle="modal" data-target="#delete-{{$key}}" href="#" style="color: red; padding-left: 30px;">delete</a>
                                        </td>
                                    </tr>

                                    <div id="delete-{{$key}}" class="modal fade" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel"><i
                                                        class="fa fa-exclamation-triangle"></i> Warning</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                <h4>Do you want to delete {{$expense->title}}??</h4>

                                            </div>
                                            <div class="modal-footer">
                                                <a href="{{route('manager.expense.remove',Crypt::encrypt($expense->id))}}"
                                                    class="btn btn-info waves-effect">Yes</a>
                                                <button type="button" class="btn btn-danger waves-effect"
                                                    data-dismiss="modal">No</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
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
