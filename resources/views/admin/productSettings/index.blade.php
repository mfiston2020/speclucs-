@extends('admin.includes.app')

@section('title','Admin Dashboard - Product')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css"
    rel="stylesheet" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    .glyphicon-ok:before {
        content: "\f00c";
    }

    .glyphicon-remove:before {
        content: "\f00d";
    }

    .glyphicon {
        display: inline-block;
        font: normal normal normal 14px/1 FontAwesome;
        font-size: inherit;
        text-rendering: auto;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
</style>
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Products')
@section('page_name','Product List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">

    {{-- ====== input error message ========== --}}
    @include('admin.includes.layouts.message')
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
                                        
                                        <a href="#" class="update" data-name="type" data-type="text"
                                        data-pk="{{ $item->id }}"
                                        data-title="Enter Lents Type">{{$item->name}}</a>
                                        
                                    </td>
                                    <td class="text-center">
                                        <a href="#" data-target="#type-{{$key}}" data-toggle="modal" style="color: red; padding-left: 30px;">delete</a>
                                    </td>
                                </tr>

                                <div id="type-{{$key}}" class="modal fade" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel"><i
                                                        class="fa fa-exclamation-triangle"></i> Warning</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                <h4>Continue deleting {{$item->name}}??</h4>

                                            </div>
                                            <div class="modal-footer">
                                                <a href="{{route('admin.delete.type',Crypt::encrypt($item->id))}}"
                                                    class="btn btn-info waves-effect">Yes</a>
                                                <button type="button" class="btn btn-danger waves-effect"
                                                    data-dismiss="modal">No</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                @endforeach
                                <form action="{{route('admin.saveLensType')}}" method="post">
                                    @csrf
                                    <tr>
                                        <td class="font-bold">
                                            <label for="stock"
                                                class="text-right control-label col-form-label">New</label>
                                        </td>
                                        <td class="" colspan="2">
                                            <div class="form-group row">
                                                <div class="col-7">
                                                    <input type="text" class="form-control" placeholder="Lens Type"
                                                        name="lens_type" value="{{old('lens_type')}}">
                                                </div>
                                                <button type="submit"
                                                    class="btn btn-info waves-effect waves-light">Save</button>
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
                                        <a href="#" class="update" data-name="chromatics" data-type="text"
                                        data-pk="{{ $item->id }}"
                                        data-title="Enter Lents Type">{{$item->name}}</a>
                                    </td>
                                    <td class="text-center">
                                        {{-- <a href="#" style="color: blue">edit</a> --}}
                                        <a href="#" data-target="#chromatic-{{$key}}" data-toggle="modal" style="color: red; padding-left: 30px;">delete</a>
                                    </td>
                                </tr>
                                {{-- ================ modal =========== --}}
                                <div id="chromatic-{{$key}}" class="modal fade" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel"><i
                                                        class="fa fa-exclamation-triangle"></i> Warning</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                <h4>Continue deleting {{$item->name}}??</h4>

                                            </div>
                                            <div class="modal-footer">
                                                <a href="{{route('admin.delete.chromatics',Crypt::encrypt($item->id))}}"
                                                    class="btn btn-info waves-effect">Yes</a>
                                                <button type="button" class="btn btn-danger waves-effect"
                                                    data-dismiss="modal">No</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                @endforeach
                                <form action="{{route('admin.saveLensPhotoChromatics')}}" method="post">
                                    @csrf
                                    <tr>
                                        <td class="font-bold">
                                            <label for="chromatics"
                                                class="text-right control-label col-form-label">New</label>
                                        </td>
                                        <td class="" colspan="2">
                                            <div class="form-group row">
                                                <div class="col-7">
                                                    <input type="text" class="form-control"
                                                        placeholder="Photo Chromatics Aspect" name="chromatics"
                                                        value="{{old('chromatics')}}">
                                                </div>
                                                <button type="submit"
                                                    class="btn btn-info waves-effect waves-light">Save</button>
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
                                        <a href="#" class="update" data-name="coating" data-type="text"
                                        data-pk="{{ $item->id }}"
                                        data-title="Enter Lents Type">{{$item->name}}</a>
                                    </td>
                                    <td class="text-center">
                                        {{-- <a href="#" style="color: blue">edit</a> --}}
                                        <a href="#" data-target="#coating-{{$key}}" data-toggle="modal" style="color: red; padding-left: 30px;">delete</a>
                                    </td>
                                </tr>
                                {{-- ================ modal =========== --}}
                                <div id="coating-{{$key}}" class="modal fade" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel"><i
                                                        class="fa fa-exclamation-triangle"></i> Warning</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                <h4>Continue deleting {{$item->name}}??</h4>

                                            </div>
                                            <div class="modal-footer">
                                                <a href="{{route('admin.delete.coating',Crypt::encrypt($item->id))}}"
                                                    class="btn btn-info waves-effect">Yes</a>
                                                <button type="button" class="btn btn-danger waves-effect"
                                                    data-dismiss="modal">No</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                @endforeach
                                <form action="{{route('admin.saveLensCoating')}}" method="post">
                                    @csrf
                                    <tr>
                                        <td class="font-bold">
                                            <label for="coating"
                                                class="text-right control-label col-form-label">New</label>
                                        </td>
                                        <td class="" colspan="2">
                                            <div class="form-group row">
                                                <div class="col-7">
                                                    <input type="text" class="form-control" placeholder="Coating"
                                                        name="coating" value="{{old('coating')}}">
                                                </div>
                                                <button type="submit"
                                                    class="btn btn-info waves-effect waves-light">Save</button>
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
                                        <a href="#" class="update" data-name="index" data-type="text"
                                        data-pk="{{ $item->id }}"
                                        data-title="Enter Lents Type">{{$item->name}}</a>
                                    </td>
                                    <td class="text-center">
                                        {{-- <a href="#" style="color: blue">edit</a> --}}
                                        <a href="#" data-target="#index-{{$key}}" data-toggle="modal" style="color: red; padding-left: 30px;">delete</a>
                                    </td>
                                </tr>
                                
                                {{-- ================ modal =========== --}}
                                <div id="index-{{$key}}" class="modal fade" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel"><i
                                                        class="fa fa-exclamation-triangle"></i> Warning</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                <h4>Continue deleting {{$item->name}}??</h4>

                                            </div>
                                            <div class="modal-footer">
                                                <a href="{{route('admin.delete.index',Crypt::encrypt($item->id))}}"
                                                    class="btn btn-info waves-effect">Yes</a>
                                                <button type="button" class="btn btn-danger waves-effect"
                                                    data-dismiss="modal">No</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                @endforeach
                                <form action="{{route('admin.saveLensIndexes')}}" method="post">
                                    @csrf
                                    <tr>
                                        <td class="font-bold">
                                            <label for="index"
                                                class="text-right control-label col-form-label">New</label>
                                        </td>
                                        <td class="" colspan="2">
                                            <div class="form-group row">
                                                <div class="col-7">
                                                    <input type="text" class="form-control" placeholder="Indexes"
                                                        name="index" value="{{old('index')}}">
                                                </div>
                                                <button type="submit"
                                                    class="btn btn-info waves-effect waves-light">Save</button>
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
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js">
</script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{csrf_token()}}'
        }
    });

    $('.update').editable({
        validate: function (value) {
            if ($.trim(value) == '')
                return 'Value is required.';
        },
        mode: 'inline',
        url: '{{url("/admin/inlineupdate")}}',
        title: 'Update',
        success: function (response, newValue) {
            console.log('Updated', response)
        }
    });
</script>
@endpush
