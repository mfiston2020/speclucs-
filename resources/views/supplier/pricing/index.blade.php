@extends('supplier.includes.app')

@section('title','Supplier Dashboard - Product')

@push('css')

@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Products Pricing')
@section('page_name','Product Pricing')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <!-- Sales chart -->
    <ul class="nav nav-pills p-3 bg-light mb-3 rounded align-items-center">
        <li class="nav-item"> <a href="javascript:void(0)"
                class="nav-link rounded-pill note-link d-flex align-items-center active px-2 px-md-3 mr-0 mr-md-2"
                id="all-category">
                <i class="icon-layers mr-1"></i><span class="d-none d-md-block">All Product Pricing</span></a>
        </li>
        <li class="nav-item"> <a href="javascript:void(0)"
                class="nav-link rounded-pill note-link d-flex align-items-center px-2 px-md-3 mr-0 mr-md-2"
                id="note-business">
                <i class="icon-briefcase mr-1"></i><span class="d-none d-md-block">Lens</span></a>
        </li>
        <li class="nav-item"> <a href="javascript:void(0)"
                class="nav-link rounded-pill note-link d-flex align-items-center px-2 px-md-3 mr-0 mr-md-2"
                id="note-social">
                <i class="icon-share-alt mr-1"></i><span class="d-none d-md-block">Frame</span></a>
        </li>
        <li class="nav-item ml-auto"> <a href="{{route('supplier.pricing.add')}}"
                class="nav-link btn-primary rounded-pill d-flex align-items-center px-3" id="add-notes">
                <i class="icon-note m-1"></i><span class="d-none d-md-block font-14">Add Product Pricing</span></a>
        </li>
    </ul>


                        {{-- ====== input error message ========== --}}
                        @include('manager.includes.layouts.message')
                        {{-- ====================================== --}}
    <div class="row">
        @foreach ($pricings as $key=> $pricing)
            <span hidden>{{$type=\App\Models\LensType::where(['id'=>$pricing->type_id])->pluck('name')->first()}}</span>
            <span hidden>{{$indx=\App\Models\PhotoIndex::where(['id'=>$pricing->index_id])->pluck('name')->first()}}</span>
            <span hidden>{{$chro=\App\Models\PhotoChromatics::where(['id'=>$pricing->chromatics_id])->pluck('name')->first()}}</span>
            <span hidden>{{$coat=\App\Models\PhotoCoating::where(['id'=>$pricing->coating_id])->pluck('name')->first()}}</span>
            <h4 class="font-medium m-b-0"><h4 class="card-title">{{initials($type)=='BT'?'Bifocal Round':initials($type)." ".$indx." ".$chro." ".$coat}}</h4></h4>

            {{-- cards  --}}
            <div class="col-lg-3">
            <div class="card bg-{{($key%2==0)?'inverse':'cyan'}} text-white">
                <div class="card-body">
                    <div class="d-flex no-block align-items-center">
                        <a href="JavaScript: void(0);"><i class="display-6 cc BTC text-white" title="ETH"></i></a>
                        <div class="m-l-15 m-t-10">
                            <hr>
                            <h5>SPHERE : {{$pricing->sphere_min}} -> {{$pricing->sphere_max}}</h5>
                            <h5>CYLINDER : {{$pricing->cylinder_min}} -> {{$pricing->cylinder_max}}</h5>
                            <h5>AXIS : {{$pricing->axis_min}} -> {{$pricing->sphere_max}}</h5>
                            <h5>ADD : {{$pricing->add_min}} -> {{$pricing->sphere_max}}</h5>
                            <h5>EYE : {{$pricing->eye}}</h5>
                            <hr>
                            <h5 style="color: yellow">{{format_money($pricing->price)}}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>
@endsection

@push('scripts')

@endpush
