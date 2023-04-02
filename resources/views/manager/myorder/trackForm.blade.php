@extends('manager.includes.app')

@section('title','Manager Dashboard - Track Order')

@push('css')

@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','Track Order')
@section('current','Track Order')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Track Order</h4>

                </div>
                <hr>

                <form class="form-horizontal" action="{{route('manager.order.tracking')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        {{-- ====== input error message ========== --}}
                        @include('manager.includes.layouts.message')
                        {{-- ====================================== --}}

                        <div class="form-group row">
                            <label for="order_number" class="col-sm-3 text-right control-label col-form-label">Order Number
                                <span id="left" style="color: red"></span>
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="order_number" placeholder="0"
                                    name="order_number" min="1" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="form-group m-b-0 text-right">
                            <button type="submit" class="btn btn-info waves-effect waves-light">Track Order</button>
                            <a href="{{url()->previous()}}" class="btn btn-dark waves-effect waves-light">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

@endpush
