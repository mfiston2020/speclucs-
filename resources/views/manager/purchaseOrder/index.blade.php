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
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Necessitatibus vel officia error, pariatur qui id in cum ab veniam? Velit porro, r
                        em reprehenderit ducimus beatae impedit ipsa eligendi perspiciatis repellat voluptatem, tota
                        m quibusdam adipisci, sequi delectus! Quod facilis beatae voluptates itaque quisquam quas dolor p
                        ariatur ipsam obcaecati inventore. Provident consequatur quasi aperiam, repellat quibusdam ipsum tenet
                        ur, tempora et corrupti quod architecto? Culpa numquam inventore tempore ut nobis maxime consequatur,
                        doloribus aperiam repellat libero facere ipsam, dolorum fuga blanditiis sunt molestias beatae. Quasi sa</p>

                        <p class="text-justify">epe, assumenda magni temporibus libero architecto quam fugiat enim pariatur, doloribus in modi rem sunt
                        debitis quas odit laboriosam, omnis deserunt distinctio expedita sequi. Totam aperiam autem non dolorum e
                        nim? Molestias eos iste suscipit cum harum eum asperiores placeat, dolorem optio enim totam numquam rem lib
                        ero consectetur dignissimos nam tenetur? Voluptatum molestias voluptatem ab aspernatur ratione enim ut non d
                        electus dolore assumenda repudiandae quas quos doloribus obcaecati nihil, tenetur harum laborum sapiente temp
                        ore recusandae rem, sunt quam explicabo cumque? Omnis molestias magnam consequatur dolore inventore soluta te
                        mporibus eaque sequi, ullam est, delectus maiores ex corporis laborum quos unde. Beatae, labore aspernatur ex
                        ercitationem et itaque perspiciatis sit recusandae quisquam praesentium unde inventore, iste at, explicabo vita
                        e magni placeat architecto. Quo eius officiis ab culpa nobis modi fugiat corporis ratione. Molestiae dolores li
                        ero hic vitae tenetur nobis impedit porro, provident aut, obcaecati, nostrum ratione culpa laboriosam voluptas i
                        ste? Dicta blanditiis iure vitae illo tenetur placeat quo at, reiciendis accusantium mollitia, laudantium nobis d
                        ignissimos nemo id nihil harum quae sit voluptatem. Doloribus quo dolor enim ipsa tenetur obcaecati adipisci labo
                        riosam dolorum odio! Minus consectetur alias aut ullam neque excepturi beatae, aspernatur veniam corporis error vo
                        luptas vitae magni aliquam explicabo sed illo. Provident, numquam, sunt iure quisquam minima exercitationem hic mol
                        estiae nisi error, sapiente alias! Ipsum, qui odio. Minima optio nam consectetur.
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
