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

        <form action="{{ route('manager.sold.lens.report.search')}}" class="col-12" method="POST" id="searchLensForm" onsubmit="showLoading()">
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
                                    @foreach ($results['lens_type'] as $lens_type)
                                    <option value="{{$lens_type->id}}"
                                        {{(old('len_type')==$lens_type->id)?'selected':($results['ltid']==$lens_type->id?'selected':'')}}>
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
                                    @foreach ($results['index'] as $index)
                                    <option value="{{$index->id}}" {{ (old('indx')==$index->id)?'selected':($results['ixid']==$index->id?'selected':'') }}>
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
                                    @foreach ($results['chromatics'] as $chromatics)
                                    <option value="{{$chromatics->id}}"
                                        {{(old('chromatic')==$chromatics->id)?'selected':($results['chrmid']==$chromatics->id?'selected':'')}}>
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
                                    @foreach ($results['coatings'] as $coatings)
                                    <option value="{{$coatings->id}}" {{(old('coating')==$coatings->id)?'selected':($results['ctid']==$coatings->id?'selected':'')}}>
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
                                    <input type="date" class="form-control" placeholder="mm/dd/yyyy" name='start_date' value="{{old('start_date') ?? $stDate}}">
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
                                    <input type="date" class="form-control" placeholder="mm/dd/yyyy" name='end_date' value="{{old('end_date') ?? $edDate}}">
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
                            <button type="submit" class="btn btn-info waves-effect waves-light" id="submitButton">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>



    <div class="row">
        <div class="col-sm-12 col-lg-12">
            @if (count($results)<=0)
                {{-- <div class="card"> --}}
                    {{-- <div class="card-body"> --}}
                        {{-- ====== input error message ========== --}}
                        @include('manager.includes.layouts.message')
                        {{-- ====================================== --}}
                    {{-- </div> --}}
                {{-- </div> --}}
            @else
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title" style="text-transform: uppercase">{{$results['lt']." ".$results['ix']." ".$results['chrm']." ".$results['ct']}}</h4>
                        <hr>
                        @if (initials($results['lt'])=='SV')
                            <div class="table-responsive colheaders" role="region" aria-labelledby="HeadersRow" tabindex="0">
                            <div>
                                <table id="zero_config" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>SPH \ CYL</th>
                                            @for ($i = $results['cylinder_max']; $i >= $results['cylinder_min']; $i=$i-0.25)
                                                <th>{{$i}}</th>
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = $results['sphere_max']; $i >= $results['sphere_min']; $i=$i-0.25)
                                            <tr>
                                                <th>{{$i}}</th>
                                                @for ($j = $results['cylinder_max']; $j >= $results['cylinder_min']; $j=$j-0.25)
                                                <span hidden>
                                                    {{$product_id=$results['powers']->where('cylinder',$j)->where('sphere',$i)
                                                                        ->pluck('product_id')
                                                                        ->first()}}
                                                </span>

                                                <span hidden>{{$stock=$soldLens->where('product_id',$product_id)->sum('quantity')}}</span>
                                                <td>
                                                    @if ($stock==0)
                                                        <center>
                                                            <span>-</span>
                                                        </center>
                                                    @else
                                                        <center>
                                                            <span class="label label-success">{{$stock}}</span>
                                                        </center>
                                                    @endif
                                                </td>
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>

                        @elseif (initials($results['lt'])=='BT')
                            <div>
                                <table id="zero_config" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>SPH \ ADD</th>
                                            @for ($i = $add_max; $i >= $add_min; $i=$i-0.25)
                                                <th>{{$i}}</th>
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = $results['sphere_max']; $i >= $results['sphere_min']; $i=$i-0.25)
                                            <tr>
                                                <th>{{$i}}</th>
                                                @for ($j = $add_max; $j >= $add_min; $j=$j-0.25)
                                                <span hidden>{{$product_id=\App\Models\Power::where(['add'=>format_values($j)])->where('company_id',Auth::user()->company_id)->where(['sphere'=>format_values($i)])
                                                                        ->where('type_id',$lt)
                                                                        ->where('index_id',$ix)
                                                                        ->where('chromatics_id',$chrm)
                                                                        ->where('coating_id',$ct)
                                                                        ->pluck('product_id')->first()}}</span>

                                                <span hidden>{{$stock=\App\Models\Product::where(['id'=>$product_id])->select('*')->sum('stock')}}</span>
                                                <td>
                                                    @if ($stock==0)
                                                        <center>
                                                            <span>-</span>
                                                        </center>
                                                    @else
                                                        <center>
                                                            <span class="label label-success">{{$stock}}</span>
                                                        </center>
                                                    @endif
                                                </td>
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="table-responsive colheaders" role="region" aria-labelledby="HeadersRow" tabindex="0">
                                <div>
                                <table id="zero_config" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>EYE</th>
                                        @for ($i = $add_min; $i <= $add_max; $i=$i+0.25)
                                            <th><center>R</center></th>
                                            <th><center>L</center></th>
                                        @endfor
                                    </tr>
                                    <tr>
                                        <th>SPH/ADD</th>
                                        @for ($i = $add_min; $i <= $add_max; $i=$i+0.25)
                                            <th colspan="2"><center>{{$i}}</center></th>
                                        @endfor
                                    </tr>
                                    </thead>
                                    <tbody>
                                            @for ($i = $results['sphere_min']; $i <= $results['sphere_max']; $i=$i+0.25)
                                            <tr>
                                            <th><center>{{$i}}</center></th>
                                            @for ($j = $add_min; $j <= $add_max; $j=$j+0.25)

                                            <td>
                                                @foreach ($products_id_array as $product_id)
                                                <span hidden>{{$stock=\App\Models\Product::where(['id'=>$product_id->product_id])
                                                                                            ->where('company_id',Auth::user()->company_id)
                                                                                            ->select('*')->sum('stock')}}</span>


                                                    @if ($product_id->sphere==$i && $product_id->add==$j)

                                                    <span hidden>{{$eye=\App\Models\Power::where(['sphere'=>format_values($i)])
                                                                                        ->where('type_id',$lt)
                                                                                        ->where('index_id',$ix)
                                                                                        ->where('chromatics_id',$chrm)
                                                                                        ->where('coating_id',$ct)
                                                                                        ->where('product_id',$product_id->product_id)
                                                                                        ->where('company_id',Auth::user()->company_id)
                                                                                        ->where(['add'=>format_values($j)])
                                                                                        ->pluck('eye')->first()}}
                                                                                        </span>
                                                        @if ($eye=='right')
                                                        @if ($stock==0)
                                                            <center>
                                                            <span>-</span>
                                                            </center>
                                                        @else
                                                            <center>
                                                            <span class="label label-success">{{$stock}}</span>
                                                            </center>
                                                        @endif
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($products_id_array as $product_id)
                                                <span hidden>{{$stock=\App\Models\Product::where(['id'=>$product_id->product_id])
                                                                                            ->where('company_id',Auth::user()->company_id)
                                                                                            ->select('*')->sum('stock')}}</span>


                                                    @if ($product_id->sphere==$i && $product_id->add==$j)

                                                    <span hidden>{{$eye=\App\Models\Power::where(['sphere'=>format_values($i)])
                                                                                        ->where('type_id',$lt)
                                                                                        ->where('index_id',$ix)
                                                                                        ->where('chromatics_id',$chrm)
                                                                                        ->where('coating_id',$ct)
                                                                                        ->where('product_id',$product_id->product_id)
                                                                                        ->where('company_id',Auth::user()->company_id)
                                                                                        ->where(['add'=>format_values($j)])
                                                                                        ->pluck('eye')->first()}}
                                                                                        </span>
                                                        @if ($eye=='left')
                                                        @if ($stock==0)
                                                            <center>
                                                            <span>-</span>
                                                            </center>
                                                        @else
                                                            <center>
                                                            <span class="label label-success">{{$stock}}</span>
                                                            </center>
                                                        @endif
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </td>

                                            {{-- @foreach ($products_id_array as $product_id)
                                                <span hidden>{{$stock=\App\Models\Product::where(['id'=>$product_id->product_id])
                                                                                        ->where('company_id',Auth::user()->company_id)
                                                                                        ->select('*')->sum('stock')}}</span>

                                                <span hidden>{{$eye=\App\Models\Power::where(['sphere'=>format_values($i)])
                                                                            ->where('type_id',$lt)
                                                                            ->where('index_id',$ix)
                                                                            ->where('chromatics_id',$chrm)
                                                                            ->where('coating_id',$ct)
                                                                            ->where('product_id',$product_id->product_id)
                                                                            ->where('company_id',Auth::user()->company_id)
                                                                            ->where(['add'=>format_values($j)])
                                                                            ->pluck('eye')->first()}}
                                                                            </span>

                                            @if ($stock==0)
                                                @if ($eye=='right')
                                                <td>-</td>
                                                @endif
                                                @if ($eye=='left')
                                                <td>-</td>
                                                @endif
                                            @else
                                                @if ($product_id->sphere==$i && $product_id->add==$j)
                                                    <td>hello</td>
                                                @endif
                                            @endif
                                            @endforeach --}}

                                            @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
                <div>

                  <a onclick="exportAll('xlsx');" href="#" type="button" class="ml-2 btn waves-effect waves-light btn-rounded btn-outline-success" style="align-items: right;">
                    <i class="fa fa-download"></i> Export To Excel</a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        function showLoading('submitButton'){
            document.getElementById('').style.display === "none"
        }
    </script>
@endpush
