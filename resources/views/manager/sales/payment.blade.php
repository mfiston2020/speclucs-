@extends('manager.includes.app')

@section('title','Admin Dashboard - Add Product')

@push('css')
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','Invoice Payment')
@section('current','Invoice Payment')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Payment of 
                        <strong>#{{sprintf('%04d',$product->reference_number)}}</strong>
                    </h4>

                </div>
                <hr>

                <form class="form-horizontal" action="{{route('manager.invoice.pay',Crypt::encrypt($product->id))}}" method="POST">
                    @csrf
                    <input type="hidden" value="{{$product->invoice_id}}" name="invoice_id">
                    <div class="card-body">
                        {{-- ====== input error message ========== --}}
                        @include('manager.includes.layouts.message')
                        {{-- ====================================== --}}

                        <div class="form-group row">
                            <label for="pname" class="col-sm-3 text-right control-label col-form-label">payment Type
                                </label>
                            <div class="col-sm-9">
                                <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                    name="payment_type" id="payment_type" required>
                                    <option value="">Select</option>
                                    <option value="Paid" {{(old('payment_type')=='Paid')?'selected':''}}>
                                        Payment Received
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pname" class="col-sm-3 text-right control-label col-form-label">payment Method
                                </label>
                            <div class="col-sm-9">
                                <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                    name="payment" id="payment" required>
                                    <option value="">Select</option>
                                    @foreach ($payment_method as $payment)
                                        <option value="{{$payment->id}}" {{(old('payment')==$payment->id)?'selected':''}}>
                                            {{$payment->name}}
                                        </option>
                                    @endforeach
                                    
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="amount_paid" class="col-sm-3 text-right control-label col-form-label">Amount Paid
                            </label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="amount_paid" placeholder="Amount Paid"
                                    name="amount_paid" value="0" min="1">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cost"
                                class="col-sm-3 text-right control-label col-form-label invalid">Balance</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="balance" placeholder="0" name="balance"
                                    value="{{$product->total_amount}}" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="remain" class="col-sm-3 text-right control-label col-form-label">Remain</label>
                            <div class="col-sm-9">
                                <input type="text" id="remain" class="form-control" placeholder="0" name="remain"
                                    readonly required value='0'>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="form-group m-b-0 text-right">
                            <button type="submit" class="btn btn-info waves-effect waves-light">Pay now</button>
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

<script>
    
    $('#amount_paid').on('input',function(){
        var paid             =   $(this).val();
        var total_amount    =   $('#balance').val();

        // var return  =   total_amount - paid;

        $("#remain").val(total_amount - paid);
        // console.log(total_amount - paid);

    });
</script>
@endpush
