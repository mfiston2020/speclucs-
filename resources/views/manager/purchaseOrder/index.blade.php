@extends('manager.includes.app')

@section('title','Manager\'s Purchase Order')

@push('css')

<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/select2/dist/css/select2.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/pickadate/lib/themes/default.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/pickadate/lib/themes/default.date.css')}}">
@endpush
{{-- ==== Breadcumb ======== --}}
@section('current','Purchase Order')
@section('page_name','Purchase Order')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Disclaim</h4>
                </div>
                <hr>
                <div class="px-5">
                    <p class="text-justify">
                        <ul>
                            <li>This functionality will help you to forecast for future stock.</li>
                            <li>It will show:</li>
                            <ul>
                                <li>Current stock: What you have on hand.</li>
                                <li>Usage: What you sold.</li>
                                <li>Additional: to manually increase/decrease the quantity of stock forecast.</li>
                                <li>Model stock: Minimum stock for the selected period.</li>
                                <li>Stock forecast: Quantity needed for the selected period</li>
                            </ul>
                            <li>
                                How to use it:
                                <ul>
                                    <li>Select the type of product you need to order below</li>
                                    <li>Select the previous dates i.e you can select a range of past dates.</li>
                                    <li>If you select 1st Jan to 31st march , it means you are going to order products that will last for 3 Months as you selected a range of three months.</li>
                                    <li>If there is a product that was out of your stock during the period you selected, you can add the quantity you need to order, manually in addition column.</li>
                                    <li>In addition column you can use +/- signs before a number to increase/decrease the stock forecast.</li>
                                </ul>
                            </li>
                        </ul>

                    </p>
                </div>
            </div>

            <div class="card">
                <form action="{{route('manager.proceed')}}" method="get">
                    <div class="card-body">
                {{-- ====== input error message ========== --}}
                @include('manager.includes.layouts.message')
                {{-- ====================================== --}}
                        <div class="form-group row">
                            <label for="pname" class="col-sm-3 text-right control-label col-form-label">Select Product Category</label>
                            <div class="col-sm-9">
                                <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                    name="category" id="category" required>
                                    <option value="">Select</option>
                                    @foreach ($categories as $category)
                                        <option value="{{$category->id}}" {{(old('category')==$category->id)?'selected':''}}>
                                            {{$category->name}}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 text-right control-label col-form-label">From</label>
                            <div class="input-group col-sm-9">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <span class="ti-calendar"></span>
                                    </span>
                                </div>
                                <input type='text' class="form-control pickadate" placeholder="From" name="from"
                                    value="{{old('from')}}" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 text-right control-label col-form-label">To</label>
                            <div class="input-group col-sm-9">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <span class="ti-calendar"></span>
                                    </span>
                                </div>
                                <input type='text' class="form-control pickadate" placeholder="To" name="to"
                                    value="{{old('to')}}" />
                            </div>
                        </div>

                        <div class="form-group m-b-0 text-center">
                            <button type="submit" class="btn btn-info waves-effect waves-light">Proceed</button>
                            <a href="{{url()->previous()}}" type="reset" class="btn btn-dark waves-effect waves-light">Cancel</a>
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
{{-- ========================================================================================== --}}
<script src="{{ asset('dashboard/assets/libs/pickadate/lib/compressed/picker.js')}}"></script>
<script src="{{ asset('dashboard/assets/libs/pickadate/lib/compressed/picker.date.js')}}"></script>
<script src="{{ asset('dashboard/assets/dist/js/pages/forms/datetimepicker/datetimepicker.init.js')}}"></script>

@endpush
