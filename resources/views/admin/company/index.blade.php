@extends('admin.includes.app')

@section('title','Admin Dashboard - Company')

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css"
    rel="stylesheet" />
    
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
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
@section('current','Category')
@section('page_name','Categories List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <!-- Sales chart -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">All Categories</h4><hr>
                        <a href="{{route('admin.company.add')}}" type="button" class="btn waves-effect waves-light btn-rounded btn-outline-primary" style="align-items: right;">
                            <i class="fa fa-plus"></i> New Company</a>
                    </div> <hr>
                    @if (session('successMsg'))
                        <div class="alert alert-success col-6">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                            <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3> 
                            {{session('successMsg')}}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Managing Director</th>
                                    <th>Company Name </th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Street</th>
                                    <th>TIN Number</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($companies as $key=> $company)
                                <tr>
                                    <td>
                                        {{$key+1}}
                                    </td>
                                    <td>
                                        {{\App\Models\User::where('company_id',$company->id)->where('role','manager')->pluck('name')->first()}}
                                    </td>
                                    <td>
                                        {{$company->company_name}}
                                    </td>
                                    <td>
                                        {{$company->company_email}}
                                    </td>
                                    <td>
                                        {{$company->company_phone}}
                                    </td>
                                    <td>
                                        {{$company->company_street}}
                                    </td>
                                    <td>
                                        {{$company->company_tin_number}}
                                    </td>
                                    <td>
                                        @if ($company->status=='active')
                                            <span class="badge badge-success">{{$company->status}}</span>
                                        @else
                                            <span class="badge badge-warning">{{$company->status}}</span>                                            
                                        @endif
                                    </td>
                                    
                                    <td>
                                        @if ($company->status=='active')
                                            <button type="button" data-toggle="modal" data-target="#deactivate-{{$key}}" class="btn btn-warning btn-circle"><i class="fa fa-user-times"></i> </button>
                                        @else
                                            <button type="button" data-toggle="modal" data-target="#activate-{{$key}}" class="btn btn-success btn-circle"><i class="fa fa-user"></i> </button>                                           
                                        @endif

                                        
                                        {{--  @if ($company->is_clinic!='1')
                                            <a href="{{route('admin.company.deactivate.clinic',Crypt::encrypt($company->id))}}" class="btn btn-warning">not clinic </a>
                                        @else
                                            <a href="{{route('admin.company.activate.clinic',Crypt::encrypt($company->id))}}" class="btn btn-success">clinic </a>                                           
                                        @endif --}}

                                        {{-- <button type="button" class="btn btn-primary btn-circle"><i class="fa fa-eye"></i> </button> --}}
                                        <a href="{{route('admin.company.Settings',Crypt::encrypt($company->id))}}" class="btn btn-primary btn-sm">info </a>
                                        
                                    </td>
                                </tr>

                                <div id="deactivate-{{$key}}" class="modal fade" tabindex="-1" role="dialog"
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
                                                <h4>DeActivate {{$company->company_name}}??</h4>

                                            </div>
                                            <div class="modal-footer">
                                                <a href="{{route('admin.company.deactivate',Crypt::encrypt($company->id))}}"
                                                    class="btn btn-info waves-effect">Yes</a>
                                                <button type="button" class="btn btn-danger waves-effect"
                                                    data-dismiss="modal">No</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>

                                <div id="activate-{{$key}}" class="modal fade" tabindex="-1" role="dialog"
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
                                                <h4>Activate {{$company->company_name}}??</h4>

                                            </div>
                                            <div class="modal-footer">
                                                <a href="{{route('admin.company.activate',Crypt::encrypt($company->id))}}"
                                                    class="btn btn-info waves-effect">Yes</a>
                                                <button type="button" class="btn btn-danger waves-effect"
                                                    data-dismiss="modal">No</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>

                                {{-- <div id="delete-{{$key}}" class="modal fade" tabindex="-1" role="dialog"
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
                                                <h4>Delete {{$company->name}}??</h4>

                                            </div>
                                            <div class="modal-footer">
                                                <a href="{{route('manager.category.delete',Crypt::encrypt($company->id))}}"
                                                    class="btn btn-info waves-effect">Yes</a>
                                                <button type="button" class="btn btn-danger waves-effect"
                                                    data-dismiss="modal">No</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div> --}}
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Managing Director</th>
                                    <th>Company Name </th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Street</th>
                                    <th>TIN Number</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </tfoot>
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
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>

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
        url: '{{url("/Cohort/update")}}',
        title: 'Update',
        success: function (response, newValue) {
            console.log('Updated', response)
        }
    });
    </script>
@endpush
