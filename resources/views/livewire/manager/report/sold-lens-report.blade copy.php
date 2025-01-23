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
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span
                            aria-hidden="true">×</span>
                    </button>
                </div>
            @endif
            
            @if (count($results)>0 && $searchFoundSomething=='yes')
                    
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title" style="text-transform: uppercase">{{$results['lt']." ".$results['ix']." ".$results['chrm']." ".$results['ct']}}</h4>
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
                                            <span hidden>{{$product_id=\App\Models\Power::where(['cylinder'=>format_values($j)])->where('company_id',Auth::user()->company_id)->where(['sphere'=>format_values($i)])
                                                                    ->where('type_id',$len_type)
                                                                    ->where('index_id',$indx)
                                                                    ->where('chromatics_id',$chromatic)
                                                                    ->where('coating_id',$coating)
                                                                    ->pluck('product_id')->first()}}</span>

                                            <span hidden>
                                                {{$soldQuantity=\App\Models\SoldProduct::where(['product_id'=>$product_id])->wherebetween('created_at', [$start_date, $end_date])->select('*')->sum('quantity')}}
                                            </span>
                                            <td>
                                                @if ($soldQuantity==0)
                                                    <center>
                                                        <span>-</span>
                                                    </center>
                                                @else
                                                    <center>
                                                        <span class="label label-success">{{$soldQuantity}}</span>
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
                                        <th>SPH\ADD</th>
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

                                        <span hidden>{{$product_id=\App\Models\Power::where(['sphere'=>format_values($i)])

                                                                    ->where('type_id',$len_type)
                                                                    ->where('index_id',$indx)
                                                                    ->where('chromatics_id',$chromatic)
                                                                    ->where('coating_id',$coating)
                                                                    ->where('company_id',Auth::user()->company_id)
                                                                    ->where(['add'=>format_values($j)])
                                                                    ->select('product_id')->get()}}
                                        </span>

                                            @if ($product_id->isEmpty())
                                            <td><center>-</center></td>
                                            <td><center>-</center></td>
                                            @else
                                            @foreach ($product_id as $product)
                                                <span hidden>{{$stock=\App\Models\Product::where(['id'=>$product->product_id])->where('company_id',Auth::user()->company_id)->select('*')->sum('stock')}}</span>

                                                <span hidden>{{$eye=\App\Models\Power::where(['sphere'=>format_values($i)])
                                                                            ->where('type_id',$len_type)
                                                                            ->where('index_id',$indx)
                                                                            ->where('chromatics_id',$chromatic)
                                                                            ->where('coating_id',$coating)
                                                                            ->where('product_id',$product->product_id)
                                                                            ->where('company_id',Auth::user()->company_id)
                                                                            ->where(['add'=>format_values($j)])
                                                                            ->pluck('eye')->first()}}
                                                </span>

                                                @if ($i==$j)
                                                    @if ($eye=='right')
                                                        <td>
                                                        <center>
                                                            <span class="label label-success">{{$stock}}</span>
                                                        </center>
                                                        </td>
                                                    @else
                                                    <td>
                                                        <center>
                                                        <span class="label label-success">{{$stock}}</span>
                                                        </center>
                                                    </td>

                                                    @endif
                                                @else
                                                @if ($eye=='right')
                                                    <td>
                                                    <center>
                                                        <span class="label label-success">{{$stock}}</span>
                                                    </center>
                                                    </td>
                                                @endif
                                                @if ($eye=='left')
                                                    <td>
                                                    <center>
                                                        <span class="label label-success">{{$stock}}</span>
                                                    </center>
                                                    </td>
                                                @endif
                                                @endif
                                            @endforeach
                                            @endif
                                        @endfor
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                <a onclick="exportAll('xlsx');" href="#" type="button" class="ml-2 btn waves-effect waves-light btn-rounded btn-outline-success" style="align-items: right;">
                    <i class="fa fa-download"></i> Export To Excel</a>
                </div>
            @endif
        </div>
    </div>
</div>
