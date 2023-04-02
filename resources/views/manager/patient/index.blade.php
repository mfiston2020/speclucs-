@extends('manager.includes.app')

@section('title','Admin Dashboard - Patients')

@push('css')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="{{ asset('dashboard/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Patients')
@section('page_name','Patients List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <!-- Sales chart -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">All Patients</h4><hr>
                        <a href="{{ route('manager.patient.add')}}" type="button" class="btn waves-effect waves-light btn-rounded btn-outline-primary" style="align-items: right;">
                            <i class="fa fa-plus"></i> New Patient</a>
                    </div> <hr>
                    @if (session('successMsg'))
                        <div class="alert alert-success col-6">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                            <h3 class="text-success"><i class="fa fa-check-circle"></i> Success</h3>
                            {{session('successMsg')}}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered" id="patient_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Patient Number</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>ID Number</th>
                                    <th>Province</th>
                                    <th>District</th>
                                    <th>Sector</th>
                                    <th>Cell</th>
                                    @if (userInfo()->permissions=='manager' || userInfo()->permissions=='doctor')
                                        <th></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($patients as $key=> $patient)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>
                                        <a href="{{route('manager.patient.detail',Crypt::encrypt($patient->id))}}">
                                            Patient #{{date('Ym',strtotime($patient->created_at))}}-{{sprintf('%04d',$patient->patient_number)}}
                                        </a>
                                    </td>
                                    <td>{{$patient->firstname}} {{$patient->lastname}}</td>
                                    <td>{{date('Y')-date('Y',strtotime($patient->birthdate))}}</td>
                                    <td>{{$patient->id_number}}</td>
                                    <td>{{\App\Models\Province::where('id',$patient->province)->pluck('name')->first()}}</td>
                                    <td>{{\App\Models\District::where('id',$patient->district)->pluck('name')->first()}}</td>
                                    <td>{{\App\Models\Sector::where('id',$patient->sector)->pluck('name')->first()}}</td>
                                    <td>{{\App\Models\Cell::where('id',$patient->cell)->pluck('name')->first()}}</td>
                                    @if (userInfo()->permissions=='manager' || userInfo()->permissions=='doctor')
                                        <td>
                                            {{-- <a href="#" style="color: rgb(0, 38, 255)">Edit</a> --}}
                                            <a href="#" class="pl-2" data-toggle="modal" data-target="#delete-{{$key}}" style="color: red">Delete</a>
                                        </td>
                                    @endif
                                </tr>

                                <div id="delete-{{$key}}" class="modal fade" tabindex="-1" role="dialog"
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
                                                <h4>Delete {{$patient->firstname}} {{$patient->lastname}}??</h4>

                                            </div>
                                            <div class="modal-footer">
                                                <a href="{{route('manager.patient.delete',Crypt::encrypt($patient->id))}}"
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
@endpush
