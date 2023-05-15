@extends('manager.includes.app')

@section('title','Manager Dashboard - My Orders')

@push('css')
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Orders')
@section('page_name','Orders List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">

    </div>

    {{-- ================================== --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">
                            <a href="{{ route('manager.proforma.create')}}" class="btn btn-primary btn-rounded">
                                <i class="mdi mdi-note-plus"></i> New Proforma Invoice
                            </a>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">All Proforma</h4>
                    </div>
                    <hr>
                    {{-- ================================= --}}
                    @include('manager.includes.layouts.message')
                    {{-- ========================== --}}

                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered nowrap"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Proforma Date</th>
                                    <th>Proforma # </th>
                                    <th>Patient Name</th>
                                    <th>Insurance</th>
                                    <th>Product</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proformas as $key=> $proforma)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{date('Y-m-d',strtotime($proforma->created_at))}}</td>
                                        <td>
                                            <a href="{{route('manager.proforma.detail',Crypt::encrypt($proforma->id))}}">
                                                Proforma #{{sprintf('%04d',$proforma->id)}}
                                            </a>
                                        </td>
                                        <td>
                                            @if ($proforma->patient_id!=null)
                                                <span hidden>{{$patient=\App\Models\Patient::where('id',$proforma->patient_id)->select('firstname','lastname')->first()}}</span>
                                                {{$patient->firstname}} {{$patient->lastname}}
                                            @else
                                                {{$proforma->patient_firstname}} {{$proforma->patient_lastname}}
                                            @endif
                                        </td>
                                        <td>
                                            {{$proforma->insurance_name}}
                                        </td>
                                        <td>
                                            {{count(\App\Models\ProformaProduct::where('proforma_id',$proforma->id)->get())}}
                                        </td>
                                        <td>
                                            @if ($proforma->status=='pending')
                                                <span class="text-warning">{{$proforma->status}}</span>
                                            @endif
                                            @if ($proforma->status=='finalized')
                                                <span class="text-warning">{{$proforma->status}}</span>
                                            @endif
                                            @if ($proforma->status=='rejected')
                                                <span class="text-danger">{{$proforma->status}}</span>
                                            @endif
                                            @if ($proforma->status=='approved')
                                                <span class="text-success">{{$proforma->status}}</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <a href="{{route('manager.proforma.detail',Crypt::encrypt($proforma->id))}}">View Detail</a>
                                            <a href="" class="text-danger mx-3">delete</a>
                                        </td>
                                    </tr>
                                @endforeach
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
    <script src="{{ asset('dashboard/assets/dist/js/export.js')}}"></script>
    <script>
        function exportAll(type)
        {
            $('#scroll_ver_hor').tableExport({
                filename: 'table_%DD%-%MM%-%YY%-month(%MM%)',
                format: type
            });
        }
    </script>
    @endpush
