@extends('manager.includes.app')

@section('title','Admin Dashboard - Product')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Clients')
@section('page_name','Client\'s List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <!-- Sales chart -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">All Clients</h4><hr>
                        <a href="{{route('manager.client.add')}}" type="button" class="ml-2 btn waves-effect waves-light btn-rounded btn-outline-primary" style="align-items: right;">
                            <i class="fa fa-plus"></i> New Client</a>
                    </div> <hr>
                    {{-- ============================== --}}
                    @include('manager.includes.layouts.message')
                    {{-- =============================== --}}
                    
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Pending Invoice</th>
                                    <th>Total Invoice</th>
                                    <th>Member Since</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $key=> $customer)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$customer->name}}</td>
                                        <td>{{$customer->phone}}</td>
                                        <td>{{$customer->email}}</td>
                                        <td>{{count(\App\Models\Invoice::where(['client_id'=>$customer->id])->where('company_id',Auth::user()->company_id)->where(['status'=>'pending'])->select('*')->get())}}</td>
                                        <td>{{count(\App\Models\Invoice::where(['client_id'=>$customer->id])->where('company_id',Auth::user()->company_id)->select('*')->get())}}</td>
                                        <td>{{date('Y-m-d',strtotime($customer->created_at))}}</td>
                                        <td>
                                            <a href="{{route('manager.client.edit',Crypt::encrypt($customer->id))}}" style="color: blue">edit</a>
                                            {{-- <a href="#" data-toggle="modal" data-target="#delete-{{$key}}" style="color: red; padding-left: 30px;">delete</a> --}}
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
                                                <h4>Do you want to delete {{$customer->name}} ??</h4>

                                            </div>
                                            <div class="modal-footer">
                                                <a href="{{route('manager.client.delete',Crypt::encrypt($customer->id))}}"
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
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Customer Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Total Invoice</th>
                                    <th>Pending Invoice</th>
                                    <th>Member Since</th>
                                    <th></th>
                                </tr>
                            </tfoot>
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
