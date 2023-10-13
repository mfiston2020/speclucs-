@extends('manager.includes.app')

@section('title','Admin Dashboard - Product Import')

@push('css')

@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Products')
@section('page_name','Product Import')
{{-- === End of breadcumb == --}}

@section('content')

    <div class="container-fluid">
        <!-- Sales chart -->
        <div class="row">
            <div class="col-md-6 col-sm-12">

                <form action="{{ route('manager.product.import.save') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card card-body">
                        <h4 class="card-title mb-3 pb-3 border-bottom">Lens File</h4>
                        @error('excelFile')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                        <div class="mb-3">
                        <label for="excelFile" class="form-label">
                            Upload an excel File
                        </label>
                        <input class="form-control" type="file" id="excelFile" name="excelFile">

                        <div class="flex w-full justify-between">
                            <a href="{{asset('dashboard/Lenses upload format.xlsx')}}" type="submit" class="btn btn-primary rounded-pill px-4 mt-5" download="lens Format">
                                Download Template
                            </a>

                            <button type="submit" class="btn btn-success rounded-pill px-4 mt-5">
                                Import
                            </button>
                        </div>

                        </div>
                    </div>
                </form>

            </div>

            <div class="col-md-6 col-sm-12">

                <form action="{{ route('manager.product.import.other.product') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card card-body">
                        <h4 class="card-title mb-3 pb-3 border-bottom">Other Products</h4>
                        @error('other_product_file')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                        <div class="mb-3">
                        <label for="other_product_file" class="form-label">
                            Upload an excel File
                        </label>
                        <input class="form-control" type="file" id="other_product_file" name="other_product_file">

                        <div class="flex w-full justify-between">
                            {{-- <a href="{{asset('dashboard/Lenses upload format.xlsx')}}" type="submit" class="btn btn-primary rounded-pill px-4 mt-5" download="lens Format">
                                Download Template
                            </a> --}}

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
