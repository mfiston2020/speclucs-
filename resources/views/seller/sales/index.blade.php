@extends('seller.includes.app')

@section('title','Seller - Sales')

@push('css')
{{-- <link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet"> --}}
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Sales')
@section('page_name','Sales List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <form action="{{ route('seller.sales.edit.data')}}" method="POST" id="edit_changes">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h4 class="card-title">All Sales</h4>
                            <hr>
                            <a href="{{route('seller.sales.customer.add')}}" type="button"
                                class="btn waves-effect waves-light btn-rounded btn-outline-primary mr-3"
                                style="align-items: right;">
                                <i class="fa fa-plus"></i> Create Customer Order</a>

                            <a href="{{route('seller.sales.create')}}" type="button"
                                class="btn waves-effect waves-light btn-rounded btn-outline-primary"
                                style="align-items: right;">
                                <i class="fa fa-plus"></i> Retail Order</a>

                            @if ($is_july=='yes')
                            <button type="submit" class="btn waves-effect waves-light btn-rounded btn-primary">
                                <i class="fa fa-save"></i> Save Changes
                            </button>
                            @endif
                        </div>
                        <hr>
                        {{-- ================================= --}}
                        @include('seller.includes.layouts.message')
                        {{-- ========================== --}}

                        <div class="table-responsive">
                            <table id="sales_table" class="table table-striped table-bordered nowrap"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Reference Number</th>
                                        <th>Order</th>
                                        <th>Customer</th>
                                        {{-- <th>User</th> --}}
                                        {{-- <th>Products</th> --}}
                                        <th>Quantity</th>
                                        <th>Total Amount</th>
                                        <th>Due Amount</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                    <tbody>
                                        @foreach ($sales as $key=> $sale)
                                        <tr>
                                            <span
                                                hidden>{{$client=\App\Models\Customer::where(['id'=>$sale->client_id])->where('company_id',Auth::user()->company_id)->pluck('name')->first()}}</span>
                                            <span
                                                hidden>{{$products=\App\Models\SoldProduct::where(['invoice_id'=>$sale->id])->where('company_id',Auth::user()->company_id)->select('product_id')->get()}}</span>
                                            @foreach ($products as $item)
                                                <span hidden>{{$allProduct[]=$item}}</span>
                                            @endforeach
                                            <td>{{$key+1}}</td>
                                            <td>
                                                <input type="hidden" name="order_id[]" value="{{$sale->id}}">
                                                @if ($is_july=='yes')
                                                <input class="form-control" type="date" max="{{date('Y-m-d')}}"
                                                    name="order_date[]" value="{{date('Y-m-d',strtotime($sale->created_at))}}"
                                                    formar required>
                                                @else
                                                {{date('Y-m-d',strtotime($sale->created_at))}}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($is_july=='yes')
                                                <input class="form-control" type="text" name="reference_number[]"
                                                    value="{{$sale->reference_number}}">
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{route('seller.sales.edit',Crypt::encrypt($sale->id))}}">Order
                                                    #{{sprintf('%04d',$sale->id)}}</a>
                                            </td>
                                            <td>
                                                @if ($client)
                                                <center><span>{{$client}}</span></center>
                                                @else
                                                <center><span>-</span></center>
                                                @endif
                                            </td>
                                            {{-- <td>{{\App\Models\User::where(['id'=>$sale->user_id])->where('company_id',Auth::user()->company_id)->pluck('name')->first()}}
                                            </td> --}}
                                            {{-- <td>{{count(array_unique($allProduct))}}</td> --}}
                                            <td>{{\App\Models\SoldProduct::where(['invoice_id'=>$sale->id])->where('company_id',Auth::user()->company_id)->select('*')->sum('quantity')}}
                                            </td>
                                            <td>{{format_money($sale->total_amount)}}</td>
                                            <td>{{format_money($sale->due)}}</td>
                                            <td>

                                                @if ($sale->status=='completed' && $sale->emailState=='submited')
                                                <span class="label label-warning">submited</span>
                                                @else
                                                <span
                                                    class="label label-{{(($sale->status)=='completed')?'success':'danger'}}">{{$sale->status}}</span>
                                                @endif

                                                @if ($sale->payment=='paid')
                                                <span class="label label-success">{{$sale->payment}}</span>
                                                @else

                                                @endif

                                            </td>
                                            <td>
                                                @if ($sale->status=='completed')
                                                <span class="label label-info">no Action Needed</span>
                                                @else
                                                <a href="{{route('seller.sales.edit',Crypt::encrypt($sale->id))}}"
                                                    class="btn btn-primary btn-sm">edit</a>
                                                <a href="#" data-toggle="modal" data-target="#myModal-{{$key}}"
                                                    class="btn btn-danger btn-sm">delete</a>
                                                @endif
                                            </td>
                                        </tr>

                                        <div id="myModal-{{$key}}" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="myModalLabel"><i
                                                                    class="fa fa-exclamation-triangle"></i>
                                                                Warning</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-hidden="true">×</button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <h4>Do you want to delete Invoice
                                                                #{{sprintf('%04d',$sale->reference_number)}}?</h4>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <a href="{{route('seller.invoice.delete',Crypt::encrypt($sale->id))}}" class="btn btn-info waves-effect">Yes</a>
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

                        <hr>
                        @if ($is_july=='yes')
                            <button type="submit" class="btn waves-effect waves-light btn-rounded btn-primary">
                                <i class="fa fa-save"></i> Save Changes
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
{{-- <script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js')}}"></script>
<script>
    $('#sales_table').DataTable({
        "lengthChange": false
        }]
    });
</script> --}}
@endpush
