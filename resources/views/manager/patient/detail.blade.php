@extends('manager.includes.app')

@section('title','Admin Dashboard - Patient Detail')

@push('css')
<link rel="stylesheet" href="{{ asset('dashboard/assets/dist/css/note.min.css')}}">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Patient')
@section('page_name','Patients Detail')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <ul class="nav nav-pills p-3 bg-light mb-3 align-items-center shadow">
        <li class="nav-item"> <span class="d-none d-md-block h4 text-primary"><span class="text-black-50">All Files
                    of:</span> {{$patient->firstname}} {{$patient->lastname}}</span>
        </li>
        </li>
        @if (userInfo()->permissions=='manager' || userInfo()->permissions=='doctor')
            <li class="nav-item ml-auto">
                <a href="{{ route('manager.patient.diagnose',Crypt::encrypt($patient->id))}}"
                    class="nav-link btn-primary d-flex align-items-center px-3" id="add-notes">
                    <i class="icon-plus m-1"></i><span class="d-none d-md-block font-14"> Diagnosis</span></a>
            </li>
        @endif
    </ul>


    <div class="tab-content">

            {{-- ====== input error message ========== --}}
            @include('manager.includes.layouts.message')
            {{-- ====================================== --}}
        <div id="note-full-container" class="note-has-grid row">

            @forelse ($files as $key=> $file)
            <div class="col-md-4 single-note-item all-category">
                <div class="card card-body">
                    {{-- <span class="side-stick"></span> --}}
                    <h5 class="note-title text-truncate w-75 mb-0" data-noteheading="Book a Ticket for Movie">
                        <a href="{{route('manager.patient.file.detail',Crypt::encrypt($file->id))}}">
                            File # {{date('Ymd',strtotime($file->created_at))}}-{{sprintf('%04d',$file->file_number)}}
                        </a>
                        <i class="point fas fa-circle ml-1 font-10"></i></h5>
                    <p class="note-date font-12 text-muted">{{date('Y M d',strtotime($file->created_at))}}</p>
                    @if (userInfo()->permissions=='manager' || userInfo()->permissions=='doctor')
                        <div class="category">
                            <span class="more-options text-dark"><span class="mr-1">
                                <a href="{{ route('manager.patient.file.edit',Crypt::encrypt($file->id))}}" class="text-primary">
                                    <i class="far fa-edit remove-note"></i>
                                </a>

                                <a href="#" class="text-danger ml-2" data-toggle="modal" data-target="#delete-{{$key}}">
                                    <i class="far fa-trash-alt remove-note"></i>
                                </a>
                            </span></span>
                        </div>
                    @endif
                </div>
            </div>

            <div id="delete-{{$key}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle"></i> Warning
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <h4>Delete {{$patient->firstname}} {{$patient->lastname}}??</h4>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">No</button>
                            <a href="{{route('manager.patient.file.delete',Crypt::encrypt($file->id))}}"
                                class="btn btn-danger waves-effect">Delete</a>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

            @empty
            <div class="alert alert-info col-12">

                <h3 class="text-info">
                    <i class="fa fa-exclamation-circle"></i> No File Found</h3>
            </div>
            @endforelse

        </div>
    </div>
</div>
@endsection

@push('scripts')

@endpush
