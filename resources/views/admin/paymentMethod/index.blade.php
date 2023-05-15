@extends('admin.includes.app')

@section('title','Admin Dashboard - Payment Methods')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Payment Methods')
@section('page_name','Payment Method List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">All Payment Methods</h4>
                        <hr>
                        <a href="{{route('admin.paymentMethods.create')}}" type="button"
                            class="btn waves-effect waves-light btn-rounded btn-outline-primary"
                            style="align-items: right;">
                            <i class="fa fa-plus"></i> New Payment Method</a>
                    </div>
                    <hr>
                    {{-- ================================= --}}
                    @include('admin.includes.layouts.message')
                    {{-- ========================== --}}

                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered nowrap"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Monthly Transaction</th>
                                    <th>Total Payment</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payment_methods as $key=> $method)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$method->name}}</td>
                                    <td>{{$method->description}}</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td class="text-center">
                                        <a href="{{route('admin.paymentMethods.edit',Crypt::encrypt($method->id))}}" style="color: blue">edit</a>
                                        <a href="#" data-toggle="modal" data-target="#delete-{{$key}}"
                                            style="color: red; padding-left: 30px;">delete</a>
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
                                                <h4>Delete {{$method->name}} ??</h4>

                                            </div>
                                            <div class="modal-footer">
                                                <a href="{{route('admin.remove.paymentMethod',Crypt::encrypt($method->id))}}"
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
