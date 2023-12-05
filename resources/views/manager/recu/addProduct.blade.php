@extends('manager.includes.app')

@section('title', 'Dashboard - Add Receipt Product')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/select2/dist/css/select2.min.css') }}">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name', 'New Receipt Product')
@section('current', 'Add Receipt Product')
{{-- === End of breadcumb == --}}

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Receipt Product Information</h4>
                    </div>
                    <hr>

                    <form class="form-horizontal" action="{{ route('manager.receipt.save.product') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            {{-- ====== input error message ========== --}}
                            @include('manager.includes.layouts.message')
                            {{-- ====================================== --}}

                            <input type="hidden" name="receipt_id" value="{{ $id }}">
                            <div class="form-group row">
                                <label for="pname"
                                    class="col-sm-3 text-right control-label col-form-label">Product</label>
                                <div class="col-sm-9">
                                    <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                        name="product" id="product" required>
                                        <option value="">Select</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                {{ old('product') == $product->id ? 'selected' : '' }}>
                                                {{ $product->product_name }} - {{ lensDescription($product->description) }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="stock" class="col-sm-3 text-right control-label col-form-label">Stock</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="stock Here" name="stock"
                                        value="{{ old('stock') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="cost" class="col-sm-3 text-right control-label col-form-label">Cost</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="cost" id="cost"
                                        value="{{ old('cost') }}" readonly>
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
    <script src="{{ asset('dashboard/assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/libs/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/forms/select2/select2.init.js') }}"></script>

    <script>
        $('#product').on('change', function() {
            var id = ($(this).val());

            $.ajax({
                url: "{{ route('manager.fetchProduct') }}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                },

                success: function(data) {
                    $("#cost").val(data.cost);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });
    </script>
@endpush
