@extends('seller.includes.app')

@section('title','Seller - Add Receipt')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/select2/dist/css/select2.min.css')}}">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','New Receipt')
@section('current','Add Receipt')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Receipt Information</h4>
                    
                </div>
                <hr>

                <form class="form-horizontal" action="{{route('seller.receipt.save')}}" method="POST">
                    @csrf
                    <div class="card-body">
                {{-- ====== input error message ========== --}}
                @include('seller.includes.layouts.message')
                {{-- ====================================== --}}
                        <div class="form-group row">
                            <label for="pname"
                                class="col-sm-3 text-right control-label col-form-label">Title</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control"
                                    placeholder="Title Here" name="title" value="{{old('title')}}">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="pname" class="col-sm-3 text-right control-label col-form-label">Provider</label>
                            <div class="col-sm-9">
                                <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                    name="provider" id="provider" required>
                                    <option value="">Select</option>
                                    @foreach ($provider as $provider)
                                        <option value="{{$provider->id}}" {{(old('provider')==$provider->id)?'selected':''}}>
                                            {{$provider->name}}
                                        </option>
                                    @endforeach
                                    
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="form-group m-b-0 text-right">
                            <button type="submit"
                                class="btn btn-info waves-effect waves-light">Save</button>
                            <button type="reset"
                                class="btn btn-dark waves-effect waves-light">Cancel</button>
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
@endpush
