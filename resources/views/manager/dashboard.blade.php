@extends('manager.includes.app')

@section('title',__('navigation.manager_s').''. __('navigation.dashboard'))

@push('css')

@endpush
{{-- al business information must be kept private unless you want to see them all the dashboard itmes must be removed and add a company logo

    invoice
    =======

    invoice must have all product all products
    and a detail of everything that was done on a particular invoice for a customer

    --}}
{{-- ==== Breadcumb ======== --}}
@section('current', __('navigation.dashboard') )
@section('page_name',__('navigation.dashboard'))
{{-- === End of breadcumb == --}}

@section('content')
<span hidden>{{$company=\App\Models\CompanyInformation::find(Auth::user()->company_id)}}</span>
<div class="container-fluid" style="background: url('{{asset('documents/logos/'.$company->logo)}}') no-repeat center; background-attachment: fixed;">

</div>
@endsection

@push('scripts')

@endpush
