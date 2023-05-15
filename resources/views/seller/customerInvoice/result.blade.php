@extends('seller.includes.app')

@section('title','Manager Dashboard - Order List')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Order')
@section('page_name','Order List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">

        <form action="{{route('seller.statementInvoice.create')}}" method="post" class="col-12">
            @csrf
            <div class="col-12">
                <div class="card">

                    <div class="card-body">
                        <div class="row">
                            <h4 class="card-title"> <a href="{{url()->previous()}}"><i
                                        class="fa fa-arrow-alt-circle-left"></i></a> All Customer Orders</h4>
                        </div>
                        <hr>
                        {{-- ================================= --}}
                        @include('seller.includes.layouts.message')
                        {{-- ========================== --}}

                        <span hidden>{{$total=0}}</span>
                        <span hidden>{{$total1=0}}</span>
                        <div class="table-responsive">
                            <table id="" class="table table-striped table-bordered nowrap"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check form-check-inline align-content-center">
                                                <input class="form-check-input" type="checkbox" id="checkall"
                                                    value="option1">
                                            </div>
                                        </th>
                                        <th>Order Date</th>
                                        <th>Order Number</th>
                                        <th>Type Quantity</th>
                                        <th>Total Stock</th>
                                        <th>Total Amount</th>
                                        {{-- <th>Payment</th> --}}
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customerInvoice as $key=> $invoice)
                                    <tr>
                                        <span
                                            hidden>{{$client=\App\Models\Customer::where(['id'=>$invoice->client_id])->where('company_id',Auth::user()->company_id)->pluck('name')->first()}}</span>
                                        <span
                                            hidden>{{$products=\App\Models\SoldProduct::where(['invoice_id'=>$invoice->id])->where('company_id',Auth::user()->company_id)->select('product_id')->get()}}</span>
                                        @foreach ($products as $item)
                                        <span hidden>{{$allProduct[]=$item}}</span>
                                        @endforeach
                                        <td>
                                            <div class="form-check form-check-inline align-content-center">
                                                <input class="form-check-input allCheckbox" type="checkbox"
                                                    name="invoice[]" id="inlineCheckbox1" value="{{$invoice->id}}">
                                            </div>
                                        </td>
                                        <td>{{date('Y-m-d',strtotime($invoice->created_at))}}</td>
                                        <td>
                                            <a href="{{route('seller.sales.edit',Crypt::encrypt($invoice->id))}}">ORDER
                                                #{{sprintf('%04d',$invoice->id)}}</a></td>
                                        <td>{{count(array_unique($allProduct))}}</td>
                                        <td>{{\App\Models\SoldProduct::where(['invoice_id'=>$invoice->id])->where('company_id',Auth::user()->company_id)->select('*')->sum('quantity')}}
                                        </td>
                                        <td>{{format_money($invoice->total_amount)}}</td>
                                        <td><span class="text-success">{{$invoice->status}}</span></td>
                                        <span hidden>{{$total=$total+$invoice->total_amount}}</span>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <input type="hidden" name="client_id" value="{{$client_id}}">
                        <div class="form-group m-b-0 text-center">
                            <button type="submit" class="btn btn-success waves-effect waves-light text-white">
                                <i class="fa fa-save"></i> Save Invoice</button>
                        </div>
                    </div>
                </div>
            </div>
</form>
</div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js')}}"></script>
<script src="{{ asset('dashboard/assets/dist/js/pages/samplepages/jquery.PrintArea.js')}}"></script>
<script>
    $('#loading').hide();
    $("#sendEmail").on('click', function () {
        // $('#loading').show();
        // $(this).disabled=true;

        checkbox();
    });

    $(function () {
        $("#print").click(function () {
            var mode = 'iframe'; //popup
            var close = mode == "popup";
            var options = {
                mode: mode,
                popClose: close
            };
            $("div.printableArea").printArea(options);
            seller.invoice.pay
        });
    });

    $("#checkall").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    function checkbox() {
        var arr = [];
        var text = '';
        $('input.allCheckbox:checkbox:checked').each(function () {
            arr.push($(this).val());
        });
        text = arr.join();
        $('#hello').val(text);
        $('#sendCustomerEmail').submit();

        console.log(text);
    }
</script>
@endpush
