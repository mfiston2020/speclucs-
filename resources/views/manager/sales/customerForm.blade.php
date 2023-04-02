@extends('manager.includes.app')

@section('title','Admin Dashboard - Select Customer')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/select2/dist/css/select2.min.css')}}">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','Select Customer')
@section('current','Select Customer')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Select Customer</h4>

                </div>
                <hr>

                <form class="form-horizontal" action="{{route('manager.sales.customer')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        {{-- ====== input error message ========== --}}
                        @include('manager.includes.layouts.message')
                        {{-- ====================================== --}}
                        <div class="form-group row">
                            <label for="customer" class="col-sm-3 text-right control-label col-form-label">Customers
                                </label>
                            <div class="col-sm-9">
                                <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                    name="customer" id="customer">
                                    <option value="">Select</option>
                                    @foreach ($customers as $customer)
                                    <option value="{{$customer->id}}" {{(old('customer')==$customer->id)?'selected':''}}>
                                        {{$customer->name}} 
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="form-group m-b-0 text-right">
                            <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
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
    $('#product').on('change',function(){
        var id  =   ($(this).val());

        $.ajax({
            url: "{{ route('manager.fetchProduct') }}",
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                id: id,
            },

            success: function (data) {
                $("#unit_price").val(data.price);
                $("#left").html('| '+data.stock+' left');
                console.log(data.price);
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
    
    $('#quantity').on('input',function(){
        var qty =   $(this).val();
        var up  =   $('#unit_price').val();

        var total   =   $("#total_amount").val(qty*up);
    });
</script>
@endpush
