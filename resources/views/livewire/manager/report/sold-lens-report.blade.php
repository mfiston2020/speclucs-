<div class="col-md-12">

    <form wire:submit="searchSoldLens">
        <div class="card">
            <div class="card-body">
                {{-- <h4 class="card-title">Contact Info &amp; Bio</h4> --}}
                <div class="row">

                    <div class="col-sm-12 col-md-3">
                        <div class="form-group">
                            <label class="control-label col-form-label">Lens Type</label>
                            <select class="form-control" wire:model="len_type">
                                <option>Choose Your Option</option>
                                @foreach ($lens_type as $lens_type)
                                <option value="{{$lens_type->id}}" {{(old('lens_type')==$lens_type->id)?'selected':''}}>
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
                            <select class="form-control" wire:model="indx">
                                <option>Choose Your Option</option>
                                @foreach ($index as $index)
                                <option value="{{$index->id}}" {{ (old('index')==$index->id)?'selected':'' }}>
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
                            <select class="form-control" wire:model="chromatic">
                                <option>Choose Your Option</option>
                                @foreach ($chromatics as $chromatics)
                                <option value="{{$chromatics->id}}"
                                    {{(old('chromatics')==$chromatics->id)?'selected':''}}>
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
                            <select class="form-control" wire:model="coating">
                                <option>Choose Your Option</option>
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
                                <input type="date" class="form-control" placeholder="mm/dd/yyyy"
                                    wire:model.blur='start_date'>
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
                                <input type="date" class="form-control" placeholder="mm/dd/yyyy"
                                    wire:model.blur='end_date'>
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
                        <button wire:loading.remove wire:target="searchSoldLens" type="submit" class="btn btn-info waves-effect waves-light">Search</button>
                        <img wire:loading wire:target="searchSoldLens" src="{{ asset('dashboard/assets/images/loading2.gif')}}" height="30" alt="" srcset="">
                    </div>
                </div>
            </div>
        </div>
    </form>


     <div class="row" wire:loading.remove wire:target="searchSoldLens">
        <div class="col-sm-12 col-lg-12">
            @if ($searchFoundSomething=='no')
                <div class="alert alert-warning alert-rounded col-lg-7 col-md-9 col-sm-12">
                    <b>Warning! </b> No Product found for this search!!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            @endif
            
            @if (count($results)>0 && $searchFoundSomething=='yes')
                    
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title" style="text-transform: uppercase">
                            {{$results['lt']." ".$results['ix']." ".$results['chrm']." ".$results['ct']}}
                        </h4>
                        <hr>
                        {{-- {{$results}} --}}
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
                                                    @php
                                                        $product_id=$results['powers']->where('cylinder',$j)
                                                                        ->where('sphere',$i)
                                                                        ->pluck('product_id')->first();

                                                        $quantity=\App\Models\SoldProduct::where('company_id', auth()->user()->company_id)->wherebetween('created_at', [$this->start_date, $this->end_date])->where('product_id', $product_id)->sum('quantity');
                                                    @endphp
                                                    <td>
                                                        @if ($quantity==0 )
                                                            <center>
                                                                <span>-</span>
                                                            </center>
                                                        @else
                                                            <center>
                                                                <span class="label label-success">{{$quantity}}</span>
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
                                            @for ($i = $results['add_max']; $i >= $results['add_min']; $i=$i-0.25)
                                                <th>{{$i}}</th>
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = $results['sphere_max']; $i >= $results['sphere_min']; $i=$i-0.25)
                                            <tr>
                                                <th>{{$i}}</th>
                                                @for ($j = $results['add_max']; $j >= $results['add_min']; $j=$j-0.25)
                                                <span hidden>{{$product_id=\App\Models\Power::where(['add'=>format_values($j)])->where('company_id',Auth::user()->company_id)->where(['sphere'=>format_values($i)])
                                                                        ->where('type_id',$len_type)
                                                                        ->where('index_id',$indx)
                                                                        ->where('chromatics_id',$chromatic)
                                                                        ->where('coating_id',$coating)
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
                                        @for ($i = $results['add_min']; $i <= $results['add_max']; $i=$i+0.25)
                                            <th><center>R</center></th>
                                            <th><center>L</center></th>
                                        @endfor
                                    </tr>
                                    <tr>
                                        <th>SPH/ADD</th>
                                        @for ($i = $results['add_min']; $i <= $results['add_max']; $i=$i+0.25)
                                            <th colspan="2"><center>{{$i}}</center></th>
                                        @endfor
                                    </tr>
                                    </thead>
                                    <tbody>
                                            @for ($i = $results['sphere_min']; $i <= $results['sphere_max']; $i=$i+0.25)
                                            <tr>
                                            <th><center>{{$i}}</center></th>
                                            @for ($j = $results['add_min']; $j <= $results['add_max']; $j=$j+0.25)

                                            <td>
                                                @foreach ($results['products_id_array'] as $product_id)
                                                <span hidden>{{$product_id['product_id']}}</span>


                                                    {{-- @if ($product_id->sphere==$i && $product_id->add==$j)

                                                    <span hidden>{{$eye=\App\Models\Power::where(['sphere'=>format_values($i)])
                                                                                        ->where('type_id',$len_type)
                                                                                        ->where('index_id',$indx)
                                                                                        ->where('chromatics_id',$chromatic)
                                                                                        ->where('coating_id',$coating)
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
                                                    @endif --}}
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($results['products_id_array'] as $key=> $product_id)
                                                <span>{{$product_id['product_id'][$key]}}</span>


                                                    {{-- @if ($product_id->sphere==$i && $product_id->add==$j)

                                                    <span hidden>{{$eye=\App\Models\Power::where(['sphere'=>format_values($i)])
                                                                                        ->where('type_id',$len_type)
                                                                                        ->where('index_id',$indx)
                                                                                        ->where('chromatics_id',$chromatic)
                                                                                        ->where('coating_id',$coating)
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
                                                    @endif --}}
                                                @endforeach
                                            </td>
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




@push('css')

<style>

table {
  margin: 1em 0;
  border-collapse: collapse;
  border: 0.1em solid #d6d6d6;
}

caption {
  text-align: left;
  font-style: italic;
  padding: 0.25em 0.5em 0.5em 0.5em;
}

th,
td {
  padding: 0.25em 0.5em 0.25em 1em;
  vertical-align: text-top;
  text-align: left;
  text-indent: -0.5em;
}

th {
  vertical-align: bottom;
  background-color: #666;
  color: #fff;
}

tr:nth-child(even) th[scope=row] {
  background-color: #f2f2f2;
}

tr:nth-child(odd) th[scope=row] {
  background-color: #fff;
}

tr:nth-child(even) {
  background-color: rgba(0, 0, 0, 0.05);
}

tr:nth-child(odd) {
  background-color: rgba(255, 255, 255, 0.05);
}

td:nth-of-type(2) {
  font-style: italic;
}

th:nth-of-type(3),
td:nth-of-type(3) {
  text-align: right;
}

/* Fixed Headers */

th {
  position: -webkit-sticky;
  position: sticky;
  top: 0;
  z-index: 2;
}

th[scope=row] {
  position: -webkit-sticky;
  position: sticky;
  left: 0;
  z-index: 1;
}

th[scope=row] {
  vertical-align: top;
  color: inherit;
  background-color: inherit;
  background: linear-gradient(90deg, transparent 0%, transparent calc(100% - .05em), #d6d6d6 calc(100% - .05em), #d6d6d6 100%);
}

th:not([scope=row]):first-child {
  left: 0;
  z-index: 3;
  background: linear-gradient(90deg, #666 0%, #666 calc(100% - .05em), #ccc calc(100% - .05em), #ccc 100%);
}

/* Scrolling wrapper */

div[tabindex="0"][aria-labelledby][role="region"] {
  overflow: auto;
}

div[tabindex="0"][aria-labelledby][role="region"]:focus {
  box-shadow: 0 0 .5em rgba(0,0,0,.5);
  outline: .1em solid rgba(0,0,0,.1);
}

div[tabindex="0"][aria-labelledby][role="region"] table {
  margin: 0;
}

div[tabindex="0"][aria-labelledby][role="region"].rowheaders {
  background:
    linear-gradient(to right, transparent 30%, rgba(255,255,255,0)),
    linear-gradient(to right, rgba(255,255,255,0), white 70%) 0 100%,
    radial-gradient(farthest-side at 0% 50%, rgba(0,0,0,0.2), rgba(0,0,0,0)),
    radial-gradient(farthest-side at 100% 50%, rgba(0,0,0,0.2), rgba(0,0,0,0)) 0 100%;
  background-repeat: no-repeat;
  background-color: #fff;
  background-size: 4em 100%, 4em 100%, 1.4em 100%, 1.4em 100%;
  background-position: 0 0, 100%, 0 0, 100%;
  background-attachment: local, local, scroll, scroll;
}

div[tabindex="0"][aria-labelledby][role="region"].colheaders {
  background:
    linear-gradient(white 30%, rgba(255,255,255,0)),
    linear-gradient(rgba(255,255,255,0), white 70%) 0 100%,
    radial-gradient(farthest-side at 50% 0, rgba(0,0,0,.2), rgba(0,0,0,0)),
    radial-gradient(farthest-side at 50% 100%, rgba(0,0,0,.2), rgba(0,0,0,0)) 0 100%;
  background-repeat: no-repeat;
  background-color: #fff;
  background-size: 100% 4em, 100% 4em, 100% 1.4em, 100% 1.4em;
  background-attachment: local, local, scroll, scroll;
}

/* Strictly for making the scrolling happen. */

th[scope=row] {
  min-width: 40vw;
}

@media all and (min-width: 30em) {
  th[scope=row] {
    min-width: 20em;
  }
}

th[scope=row] + td {
  min-width: 24em;
}

/* div[tabindex="0"][aria-labelledby][role="region"]:nth-child(3) {
  max-height: 200em;
} */

div[tabindex="0"][aria-labelledby][role="region"]:nth-child(7) {
  max-height: 35em;
  margin: 0 1em;
}
</style>
@endpush
