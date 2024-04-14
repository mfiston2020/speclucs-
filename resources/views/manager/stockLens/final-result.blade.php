@extends('manager.includes.app')

@section('title','Admin Dashboard - Sales')

@push('css')

<style>
    table{
        height:20% !important;
    }
    table thead th {
    /* padding: 3px; */
    position: sticky;
    top: 0;
    z-index: 1;
    /* width: 25vw; */
    background: #414755;
    color: white;
    }
    table thead th:first-child {
    position: sticky;
    left: 0;
    z-index: 2;
    }
    table tbody th {
    position: sticky;
    left: 0;
    background: #414755;
    color: white;
    z-index: 1;
    }
    caption {
    text-align: left;
    padding: 0.25rem;
    position: sticky;
    left: 0;
    }

    [role="region"][aria-labelledby][tabindex] {
    width: 100%;
    max-height: 98vh;
    overflow: auto;
    }
    [role="region"][aria-labelledby][tabindex]:focus {
    box-shadow: 0 0 0.5em rgba(0, 0, 0, 0.5);
    outline: 0;
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
        <div class="col-sm-12 col-lg-12">
            @if (count($productStock)<=0)
                {{-- ====== input error message ========== --}}
                @include('manager.includes.layouts.message')
                {{-- ====================================== --}}
            @else
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <h4 class="card-title col-6" style="text-transform: uppercase">{{$lt." ".$ix." ".$chrm." ".$ct}}</h4>
                            <div class="col-6 float-right">
                                <span class="label label-danger">0</span>
                                <span class="label label-warning">1-9</span>
                                <span class="label label-success">10 and above</span>
                            </div>
                        </div>

                        <a onclick="exportAll('xls');" href="#" type="button" class="ml-2 btn waves-effect waves-light btn-rounded btn-outline-success" style="align-items: right;">
                            <i class="fa fa-download"></i> Export To Excel
                        </a>
                        <hr>
                        @if(initials($lt)=='BT')
                            <div class="table-responsive colheaders" role="region" aria-labelledby="HeadersRow" tabindex="0">
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
                                        @for ($i = $sphere_max; $i >= $sphere_min; $i=$i-0.25)
                                            <tr>
                                                <th>{{$i}}</th>
                                                @for ($j = $add_max; $j >= $add_min; $j=$j-0.25)
                                                    @php
                                                        $product_stock    =   $productStock[format_values($i)][format_values($j)];

                                                        // if(is_array($product_stock)){
                                                        //     dd($product_stock);
                                                        // }
                                                    @endphp
                                                    <td>
                                                        @if (is_null($product_stock))
                                                            <center>
                                                                <span label-success>-</span>
                                                            </center>
                                                        @else
                                                            <center>
                                                                @if ($product_stock>0 && !is_array($product_stock))
                                                                    <span @class(['label',
                                                                    'label-warning'=>$product_stock<10 && $product_stock>=1,
                                                                    'label-success'=>$product_stock>=10 ,
                                                                    ])>
                                                                        {{ !is_null($product_stock)?$product_stock:'-' }}
                                                                    </span>
                                                                @else
                                                                    <span class="label label-danger">
                                                                        0
                                                                    </span>
                                                                @endif
                                                            </center>
                                                        @endif
                                                    </td>
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if (initials($lt)=='SV')
                            <div class="table-responsive colheaders" role="region" aria-labelledby="HeadersRow" tabindex="0">
                                <table id="zero_config" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>SPH \ CYL</th>
                                            @for ($i = $cylinder_max; $i >= $cylinder_min; $i=$i-0.25)
                                                <th>{{$i}}</th>
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = $sphere_max; $i >= $sphere_min; $i=$i-0.25)
                                            <tr>
                                                <th>{{$i}}</th>
                                                @for ($j = $cylinder_max; $j >= $cylinder_min; $j=$j-0.25)
                                                    @php
                                                        $product_stock    =   $productStock[format_values($i)][format_values($j)];
                                                    @endphp
                                                    <td>
                                                        @if (is_null($product_stock))
                                                            <center>
                                                                <span label-success>-</span>
                                                            </center>
                                                        @else
                                                            <center>
                                                                @if ($product_stock>0)
                                                                    <span @class(['label',
                                                                    'label-warning'=>$product_stock<10 && $product_stock>=1,
                                                                    'label-success'=>$product_stock>=10 ,
                                                                    ])>
                                                                        {{ !is_null($product_stock)?$product_stock:'-' }}
                                                                    </span>
                                                                @else
                                                                    <span class="label label-danger">
                                                                        0
                                                                    </span>
                                                                @endif
                                                            </center>
                                                        @endif
                                                    </td>
                                                @endfor
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        @if (initials($lt)!='SV' && initials($lt)!='BT')
                            <div class="table-responsive colheaders" role="region" aria-labelledby="HeadersRow" tabindex="0">
                                <table id="zero_config" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th >EYE</th>
                                        @for ($i = $add_min; $i <= $add_max; $i=$i+0.25)
                                            <th style="position: sticky;top: 0;z-index: 1;"><center>R</center></th>
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
                                            @for ($i = $sphere_min; $i <= $sphere_max; $i=$i+0.25)
                                            <tr>
                                            <th><center>{{$i}}</center></th>
                                            @for ($j = $add_min; $j <= $add_max; $j=$j+0.25)
                                                @php
                                                    $product_stock_right    =   $productStock[format_values($i)][format_values($j)]['Right'];
                                                    $product_stock_left    =   $productStock[format_values($i)][format_values($j)]['Left'];
                                                @endphp
                                                <td>
                                                    @if (is_null($product_stock_right))
                                                        <center>
                                                            <span label-success>-</span>
                                                        </center>
                                                    @else
                                                        <center>
                                                            @if ($product_stock_right>0)
                                                                <span @class(['label',
                                                                'label-warning'=>$product_stock_right<10 && $product_stock_right>=1,
                                                                'label-success'=>$product_stock_right>=10 ,
                                                                ])>
                                                                    {{ !is_null($product_stock_right)?$product_stock_right:'-' }}
                                                                </span>
                                                            @else
                                                                <span class="label label-danger">
                                                                    0
                                                                </span>
                                                            @endif
                                                        </center>
                                                    @endif
                                                </td>

                                                <td>
                                                    @if (is_null($product_stock_left))
                                                        <center>
                                                            <span label-success>-</span>
                                                        </center>
                                                    @else
                                                        <center>
                                                            @if ($product_stock_left>0)
                                                                <span @class(['label',
                                                                'label-warning'=>$product_stock_left<10 && $product_stock_left>=1,
                                                                'label-success'=>$product_stock_left>=10 ,
                                                                ])>
                                                                    {{ !is_null($product_stock_left)?$product_stock_left:'-' }}
                                                                </span>
                                                            @else
                                                                <span class="label label-danger">
                                                                    0
                                                                </span>
                                                            @endif
                                                        </center>
                                                    @endif
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
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script src="{{ asset('dashboard/assets/dist/js/export.js')}}"></script>
<script>
  function exportAll(type) {

$('#zero_config').tableExport({
    filename: 'table_%DD%-%MM%-%YY%-month(%MM%)',
    format: type
});
}
</script>
@endpush
