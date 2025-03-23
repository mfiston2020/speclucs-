@extends('manager.includes.app')

@section('title','Admin Dashboard - Cloud Product Adjustment')

@push('css')

@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Products')
@section('page_name','Cloud Product Adjustment')
{{-- === End of breadcumb == --}}

@section('content')

    <div class="container-fluid">
        @include('manager.includes.layouts.message')
        <!-- Sales chart -->
        <div class="row">
            <div class="col-md-12 col-sm-12">

                <form action="{{ route('manager.cloud.product.import.save') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card card-body">
                        <h4 class="card-title mb-3 pb-3 border-bottom">Product File - [ Patient Data Export ]</h4>
                        @error('excelFile')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                        <div class="mb-3">
                        <label for="excelFile" class="form-label">
                            Upload an excel File
                        </label>
                        <input class="form-control" type="file" id="excelFile" name="excelFile">

                        <div class="flex w-full justify-between">

                            <button type="submit" class="btn btn-success rounded-pill px-4 mt-5">
                                Import
                            </button>
                        </div>

                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection

@push('scripts')

@endpush
