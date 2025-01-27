@extends('manager.includes.app')

@section('title',__('navigation.manager_s').''. __('navigation.dashboard'))

@push('css')

@endpush

{{-- ==== Breadcumb ======== --}}
@section('current', __('navigation.dashboard') )
@section('page_name',__('navigation.dashboard'))
{{-- === End of breadcumb == --}}

@section('content')


<div class="container-fluid">
    <div class="row">

        <form action="{{ route('manager.sold.lens.report.search')}}" class="col-12" method="POST">
            @csrf
            <div class="card">
                <div class="card-body">
                    {{-- <h4 class="card-title">Contact Info &amp; Bio</h4> --}}
                    <div class="row">

                        <div class="col-sm-12 col-md-3">
                            <div class="form-group">
                                <label class="control-label col-form-label">Lens Type</label>
                                <select class="form-control" name="len_type">
                                    <option value="">Choose Your Option</option>
                                    @foreach ($lens_type as $lens_type)
                                    <option value="{{$lens_type->id}}"
                                        {{(old('len_type')==$lens_type->id)?'selected':''}}>
                                        {{$lens_type->name}}
                                    </option>
                                    @endforeach
                                </select>
                                @error('len_type')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-3">
                            <div class="form-group">
                                <label class="control-label col-form-label">Index</label>
                                <select class="form-control" name="indx">
                                    <option value="">Choose Your Option</option>
                                    @foreach ($index as $index)
                                    <option value="{{$index->id}}" {{ (old('indx')==$index->id)?'selected':'' }}>
                                        {{$index->name}}
                                    </option>
                                    @endforeach
                                </select>
                                @error('indx')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-3">
                            <div class="form-group">
                                <label class="control-label col-form-label">Chromatics Aspects</label>
                                <select class="form-control" name="chromatic">
                                    <option value="">Choose Your Option</option>
                                    @foreach ($chromatics as $chromatics)
                                    <option value="{{$chromatics->id}}"
                                        {{(old('chromatic')==$chromatics->id)?'selected':''}}>
                                        {{$chromatics->name}}
                                    </option>
                                    @endforeach
                                </select>
                                @error('chromatic')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-3">
                            <div class="form-group">
                                <label class="control-label col-form-label">Coating</label>
                                <select class="form-control" name="coating">
                                    <option value="">Choose Your Option</option>
                                    @foreach ($coatings as $coatings)
                                    <option value="{{$coatings->id}}" {{(old('coating')==$coatings->id)?'selected':''}}>
                                        {{$coatings->name}}
                                    </option>
                                    @endforeach
                                </select>
                                @error('coating')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <div class="col-md-3"></div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Start Date </label>
                                <div class="input-group">
                                    <input type="date" class="form-control" placeholder="mm/dd/yyyy" name='start_date' value="{{old('start_date')}}">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                                @error('start_date')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>End Date </label>
                                <div class="input-group">
                                    <input type="date" class="form-control" placeholder="mm/dd/yyyy" name='end_date' value="{{old('end_date')}}">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                                @error('end_date')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-body">
                    <div class="action-form">
                        <div class="form-group m-b-0 text-center">
                            <button type="submit" class="btn btn-info waves-effect waves-light">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

@endsection

@push('scripts')
@endpush
