@extends('manager.includes.app')

@section('title','Admin Dashboard - Sales')

@push('css')

<style>
    th{
        font-weight: bold;
    }
</style>
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Lens Stock')
@section('page_name','Lens Stock Management')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-lg-12">
            <form action="{{route('manager.search.lens.stock')}}" method="post">
                @csrf
                <div class="card">
                    <div class="card-body">
                        {{-- <h4 class="card-title">Contact Info &amp; Bio</h4> --}}
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label col-form-label">Lens Type</label>
                                    <select class="form-control" name="lens_type">
                                        <option>Choose Your Option</option>
                                        @foreach ($lens_type as $lens_type)
                                            <option value="{{$lens_type->id}}"
                                                {{(old('lens_type')==$lens_type->id)?'selected':''}}>
                                                {{$lens_type->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label col-form-label">Index</label>
                                    <select class="form-control" name="index">
                                        <option>Choose Your Option</option>
                                        @foreach ($index as $index)
                                            <option value="{{$index->id}}"
                                                {{(old('index')==$index->id)?'selected':''}}>
                                                {{$index->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label col-form-label">Chromatics Aspects</label>
                                    <select class="form-control" name="chromatics">
                                        <option>Choose Your Option</option>
                                        @foreach ($chromatics as $chromatics)
                                            <option value="{{$chromatics->id}}"
                                                {{(old('chromatics')==$chromatics->id)?'selected':''}}>
                                                {{$chromatics->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label col-form-label">Coating</label>
                                    <select class="form-control" name="coating">
                                        <option>Choose Your Option</option>
                                        @foreach ($coatings as $coatings)
                                            <option value="{{$coatings->id}}"
                                                {{(old('coating')==$coatings->id)?'selected':''}}>
                                                {{$coatings->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="action-form">
                            <div class="form-group m-b-0 text-center">
                                <button type="submit"
                                    class="btn btn-info waves-effect waves-light">Search</button>
                                <a href="{{url()->previous()}}"
                                    class="btn btn-dark waves-effect waves-light">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="row">
        {{-- ====== input error message ========== --}}
        @include('manager.includes.layouts.message')
        {{-- ====================================== --}}
    </div>
</div>
@endsection

@push('scripts')

@endpush
