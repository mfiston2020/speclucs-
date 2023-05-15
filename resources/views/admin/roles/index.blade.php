@extends('admin.includes.app')

@section('title','Admin Dashboard - Roles')

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
@section('current','user')
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
                        <a href="{{route('admin.users.roles')}}" type="button" class="btn waves-effect waves-light btn-rounded btn-outline-primary" style="align-items: right;">
                            <i class="fa fa-plus"></i> New user</a>
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
                                    <th>user</th>
                                    <th>Product</th>
                                    <th>Total Stock</th>
                                    <th>Faulty Stock</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tfoot>
                                <tr>
                                    <th>user</th>
                                    <th>Product</th>
                                    <th>Total Stock</th>
                                    <th>Faulty Stock</th>
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
