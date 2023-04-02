@extends('manager.includes.app')

@section('title','Admin Dashboard - Categories')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','New Categories')
@section('current','Add Category')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <!-- Sales chart -->
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Create New Category</h4>
                    {{-- ====== input error message ========== --}}
                    @error('category_name')
                            <div class="alert alert-danger alert-rounded"> 
                                {{$message}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span
                                        aria-hidden="true">×</span> </button>
                            </div>
                        @enderror
                        {{-- ========== inserting error message ========== --}}
                        @if(session('errorMsg'))
                                <div class="alert alert-danger alert-rounded"> 
                                    <b>Error! </b>{{session('errorMsg')}}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span
                                            aria-hidden="true">×</span> </button>
                                </div>
                            @enderror

                    <form class="form-horizontal p-t-20" method="POST" action="{{route('manager.category.save')}}">
                        @csrf
                        <div class="form-group row">
                            <label for="category_name" class="col-sm-3 control-label">Category Name*</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="ti-tag"></i></span></div>
                                    <input type="text" class="form-control" id="category_name"
                                        placeholder="Category Name" name="category_name" value="{{old('category_name')}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row m-b-0">
                            <div class="offset-sm-3 col-sm-9">
                                <button type="submit" class="btn btn-info waves-effect waves-light">Save Category</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

@endpush
