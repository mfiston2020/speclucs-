@extends('manager.includes.app')

@section('title','Manager Dashboard - Invoice')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/select2/dist/css/select2.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/pickadate/lib/themes/default.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/pickadate/lib/themes/default.date.css')}}">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','Invoice')
@section('current','Select Invoice')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">

    {{-- ====== input error message ========== --}}
    @include('manager.includes.layouts.message')
    {{-- ====================================== --}}

    <div class="row">
        <div class="col-lg-6 col-sm-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <h4 class="m-b-0 text-white">Client's Invoice</h4>
                </div>
                <form class="form-horizontal" action="{{route('manager.search.order.invoice')}}" method="GET">
                    <div class="form-body">
                        <div class="card-body">
                            <div class=" p-t-20">
                                <div class="form-group row">
                                    <label for="pname" class="col-sm-3 text-right control-label col-form-label">Select
                                        Client</label>
                                    <div class="col-sm-9">
                                        <select class="select2 form-control custom-select"
                                            style="width: 100%; height:36px;" name="company" id="company" required>
                                            <option value="">Select</option>
                                            @foreach ($customerList as $custo)
                                            <span>{{$custo=\App\Models\CompanyInformation::where('id',$custo)->select('id','company_name')->first()}}</span>
                                            <option value="{{$custo->id}}"
                                                {{(old('company')==$custo->id)?'selected':''}}>
                                                {{$custo->company_name}}
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
                                <hr>
                                <div class="card-body">
                                    <div class="form-group m-b-0 text-center">
                                        <button type="submit" class="btn btn-info waves-effect waves-light">Search Client's Invoice</button>
                                    </div>
                                </div>
                            </div>
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
