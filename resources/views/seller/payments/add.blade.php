@extends('seller.includes.app')

@section('title','Seller - Add Payment')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/select2/dist/css/select2.min.css')}}">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','New Payment')
@section('current','Add Payment')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Add Payment</h4>

                </div>
                <hr>

                <form class="form-horizontal" action="{{route('seller.payment.save')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        {{-- ====== input error message ========== --}}
                        @include('seller.includes.layouts.message')
                        {{-- ====================================== --}}
                        <div class="form-group row">
                            <label for="pname" class="col-sm-3 text-right control-label col-form-label">Receipt </label>
                            <div class="col-sm-9">
                                <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                    name="receipt" id="receipt">
                                    <option value="">Select</option>
                                    @foreach ($receipts as $receipt)
                                    <option value="{{$receipt->id}}">
                                        {{$receipt->title}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="supplier" class="col-sm-3 text-right control-label col-form-label">Supplier</label>
                            <div class="col-sm-9">
                                <input type="text" id="supplier" class="form-control" placeholder="Supplier name" name="supplier"
                                    readonly required>
                                    <input type="hidden" name="supplier_id" id="supplier_id">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pname" class="col-sm-3 text-right control-label col-form-label">Payment Method </label>
                            <div class="col-sm-9">
                                <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                    name="payment_method" id="payment_method">
                                    <option value="">Select</option>
                                    @foreach ($payment_methods as $payment_method)
                                    <option value="{{$payment_method->id}}" {{(old('payment_method')==$payment_method->id)?'selected':''}}>
                                        {{$payment_method->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="balance"
                                class="col-sm-3 text-right control-label col-form-label invalid">Balance</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="balance" placeholder="0" name="balance" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="amount" class="col-sm-3 text-right control-label col-form-label">Amount 
                                <span id="left" style="color: red"></span>
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="amount" placeholder="Amount"
                                    name="amount" min="1" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="due" class="col-sm-3 text-right control-label col-form-label">Due 
                                <span id="left" style="color: red"></span>
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="due" name="due" readonly>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="form-group m-b-0 text-right">
                            <button type="submit" class="btn btn-info waves-effect waves-light">Proceed Payment</button>
                            <button type="reset" class="btn btn-dark waves-effect waves-light">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('dashboard/assets/libs/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/libs/select2/dist/js/select2.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/dist/js/pages/forms/select2/select2.init.js')}}"></script>

<script>
    $('#receipt').on('change',function(){
        var id  =   ($(this).val());

        $.ajax({
            url: "{{ route('seller.fetchReceipt') }}",
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                id: id,
            },

            success: function (data) 
            {
                $('#supplier').val(data.supplier_name);            
                $('#supplier_id').val(data.supplier_id);            
                $('#balance').val(data.total_cost);        
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
    
    $('#amount').on('input',function(){
        var amount =   $(this).val();
        var balance  =   $('#balance').val();

        $("#due").val(balance-amount);
    });
</script>
@endpush
