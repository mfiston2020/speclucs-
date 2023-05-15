@extends('manager.includes.app')

@section('title','Admin Dashboard - Product')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Products')
@section('page_name','Product List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    
    {{-- ====== input error message ========== --}}
    @include('manager.includes.layouts.message')
    {{-- ====================================== --}}

    <!-- Sales chart -->
    <div class="row">
        <!-- column -->
        <div class="col-sm-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Lens Type</h4>
                    {{-- <h5 class="card-subtitle">Email Campaigns</h5> --}}
                    <div class="table-responsive">
                        <table class="table v-middle">
                            <thead>
                                <tr>
                                    <th class="border-top-0">#</th>
                                    <th class="text-center border-top-0">Name</th>
                                    <th class="text-center border-top-0"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($types as $key=> $item)
                                    <tr>
                                        <td class="font-bold">
                                            {{$key+1}}
                                        </td>
                                        <td class="text-center">
                                            {{$item->name}}
                                        </td>
                                        <td class="text-center">
                                            <a href="#" style="color: blue">edit</a>
                                            <a href="#" style="color: red; padding-left: 30px;">delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                                <form action="{{route('manager.saveLensType')}}" method="post">
                                    @csrf
                                    <tr>
                                        <td class="font-bold">
                                            <label for="stock" class="text-right control-label col-form-label">New</label>
                                        </td>
                                        <td class="" colspan="2">
                                            <div class="form-group row">
                                                <div class="col-7">
                                                    <input type="text" class="form-control" placeholder="Lens Type" name="lens_type"
                                                        value="{{old('lens_type')}}">
                                                </div>
                                            <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                                            </div>
                                        </td>
                                    </tr>
                                </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- column -->
        <div class="col-sm-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Photo Chromatics Aspect</h4>
                    {{-- <h5 class="card-subtitle">Email Campaigns</h5> --}}
                    <div class="table-responsive">
                        <table class="table v-middle">
                            <thead>
                                <tr>
                                    <th class="border-top-0">#</th>
                                    <th class="text-center border-top-0">Name</th>
                                    <th class="text-center border-top-0"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($chromatics as $key=> $item)
                                    <tr>
                                        <td class="font-bold">
                                            {{$key+1}}
                                        </td>
                                        <td class="text-center">
                                            {{$item->name}}
                                        </td>
                                        <td class="text-center">
                                            <a href="#" style="color: blue">edit</a>
                                            <a href="#" style="color: red; padding-left: 30px;">delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                                <form action="{{route('manager.saveLensPhotoChromatics')}}" method="post">
                                    @csrf
                                    <tr>
                                        <td class="font-bold">
                                            <label for="chromatics" class="text-right control-label col-form-label">New</label>
                                        </td>
                                        <td class="" colspan="2">
                                            <div class="form-group row">
                                                <div class="col-7">
                                                    <input type="text" class="form-control" placeholder="Photo Chromatics Aspect" name="chromatics"
                                                        value="{{old('chromatics')}}">
                                                </div>
                                            <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                                            </div>
                                        </td>
                                    </tr>
                                </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- column -->
        <div class="col-sm-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Coating</h4>
                    {{-- <h5 class="card-subtitle">Email Campaigns</h5> --}}
                    <div class="table-responsive">
                        <table class="table v-middle">
                            <thead>
                                <tr>
                                    <th class="border-top-0">#</th>
                                    <th class="text-center border-top-0">Name</th>
                                    <th class="text-center border-top-0"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coatings as $key=> $item)
                                    <tr>
                                        <td class="font-bold">
                                            {{$key+1}}
                                        </td>
                                        <td class="text-center">
                                            {{$item->name}}
                                        </td>
                                        <td class="text-center">
                                            <a href="#" style="color: blue">edit</a>
                                            <a href="#" style="color: red; padding-left: 30px;">delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                                <form action="{{route('manager.saveLensCoating')}}" method="post">
                                    @csrf
                                    <tr>
                                        <td class="font-bold">
                                            <label for="coating" class="text-right control-label col-form-label">New</label>
                                        </td>
                                        <td class="" colspan="2">
                                            <div class="form-group row">
                                                <div class="col-7">
                                                    <input type="text" class="form-control" placeholder="Coating" name="coating"
                                                        value="{{old('coating')}}">
                                                </div>
                                            <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                                            </div>
                                        </td>
                                    </tr>
                                </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- column -->
        <div class="col-sm-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Indexes</h4>
                    {{-- <h5 class="card-subtitle">Email Campaigns</h5> --}}
                    <div class="table-responsive">
                        <table class="table v-middle">
                            <thead>
                                <tr>
                                    <th class="border-top-0">#</th>
                                    <th class="text-center border-top-0">Name</th>
                                    <th class="text-center border-top-0"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($indexes as $key=> $item)
                                    <tr>
                                        <td class="font-bold">
                                            {{$key+1}}
                                        </td>
                                        <td class="text-center">
                                            {{$item->name}}
                                        </td>
                                        <td class="text-center">
                                            <a href="#" style="color: blue">edit</a>
                                            <a href="#" style="color: red; padding-left: 30px;">delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                                <form action="{{route('manager.saveLensIndexes')}}" method="post">
                                    @csrf
                                    <tr>
                                        <td class="font-bold">
                                            <label for="index" class="text-right control-label col-form-label">New</label>
                                        </td>
                                        <td class="" colspan="2">
                                            <div class="form-group row">
                                                <div class="col-7">
                                                    <input type="text" class="form-control" placeholder="Indexes" name="index"
                                                        value="{{old('index')}}">
                                                </div>
                                            <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                                            </div>
                                        </td>
                                    </tr>
                                </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('dashboard/assets/extra-libs/DataTables/datatables.min.js')}}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/datatable/datatable-basic.init.js')}}"></script>
@endpush
