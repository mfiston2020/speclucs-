@extends('manager.includes.app')

@section('title','Manager Dashboard - All Invoice')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','All Invoice')
@section('page_name','All Invoice')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title"> All Invoice</h4>
                    </div>
                    <hr>
                    {{-- ================================= --}}
                    @include('manager.includes.layouts.message')
                    {{-- ========================== --}}

                    <form action="{{route('manager.statementInvoice.pay')}}" method="post">
                        @csrf
                        <div class="table-responsive">
                            <table id="zero_config" class="table table-striped table-bordered nowrap"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check form-check-inline align-content-center">
                                                <input class="form-check-input" type="checkbox" id="checkall"
                                                    value="option1">
                                            </div>
                                        </th>
                                        <th>Customer Name</th>
                                        <th>Invoice Date</th>
                                        {{-- <th>Invoice #</th> --}}
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Invoice Age</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <span hidden>{{$total=0}}</span>

                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <div class="pull-right m-t-30 text-right">
                                <h3><b>Total :</b> {{format_money($total)}}</h3>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <hr>
                        <div class="form-group m-b-0 text-center">
                            <button onclick="exportAll('xlsx')" class="btn btn-success waves-effect waves-light text-white">
                                <i class="fa fa-download"></i> Export To Excel</button>
                            <a href="#" class="btn btn-primary waves-effect waves-light text-white">
                                <i class="fa fa-print"></i> Print</a>
                            <button type="submit" class="btn btn-secondary waves-effect waves-light text-white">
                                <i class="fa fa-money-bill-alt"></i> Pay Invoice</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js')}}"></script>
<script src="{{ asset('dashboard/assets/dist/js/export.js')}}"></script>
<script>
     function exportAll(type) {

        $('#zero_config').tableExport({
            filename: 'table_%DD%-%MM%-%YY%-month(%MM%)',
            format: type
        });
        }
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
