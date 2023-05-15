@extends('manager.includes.app')

@section('title','manager Dashboard - Users')

@push('css')

@endpush

{{-- ==== Breadcumb ======== --}}
@section('current','Users')
@section('page_name','Users List')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">

    {{-- ====== input error message ========== --}}
    @include('manager.includes.layouts.message')
    {{-- ====================================== --}}

    <!-- Sales chart -->
    <div class="row">
        <!-- column -->
        <div class="card col-12">
            <div class="card-body">
                {{-- <h4 class="m-b-20">Write a reply</h4> --}}
                <form method="post">
                    {{-- <button type="button"
                        class="m-t-20 btn waves-effect waves-light btn-success">Reply</button> --}}
                    <a href="{{ route('manager.user.add')}}" class="m-t-20 btn waves-effect waves-light btn-info"> <i class="fas fa-user-plus"></i> | Add New User</a>
                </form>
            </div>
        </div>
        @if ($users->isEmpty())
            <h3>No Users found</h3>
        @else
        @foreach ($users as $user)
            <div class="col-sm-12 col-lg-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="profile-pic m-b-20 m-t-20">
                            {{-- <img src="assets/images/users/1.jpg" width="150" class="rounded-circle" alt="user" /> --}}
                            <h4 class="m-t-20 m-b-0">{{$user->name}} <span class="text-success">[{{$user->permissions}}]</span></h4>
                            <a href="mailto:danielkristeen@gmail.com">{{$user->email}}</a>
                        </div>
                        <div class="badge badge-pill badge-light font-16">
                            Status:
                        </div>
                        <div class="badge badge-pill badge-{{($user->status=='active')?'success':'danger'}} font-16"> {{$user->status}}</div>
                    </div>
                    <div class="p-25 border-top m-t-15">
                        <div class="row text-center">
                            <div class="col-6 border-right">
                                <a href="{{ route('manager.user.edit',Crypt::encrypt($user->id))}}" class="link d-flex align-items-center justify-content-center font-medium">
                                    <i class="mdi mdi-account-edit font-20 m-r-5"></i>Edit Account</a>
                            </div>
                            <div class="col-6">
                                @if ($user->status=='active')
                                    <a href="{{route('manager.user.disable',Crypt::encrypt($user->id))}}" class="link d-flex align-items-center justify-content-center font-medium text-danger"  data-toggle="tooltip" data-placement="top" title="Turn user account off">
                                        <i class="mdi mdi-account-off font-20 m-r-5"></i>Disable Account
                                    </a>
                                @else
                                    <a href="{{route('manager.user.activate',Crypt::encrypt($user->id))}}" class="link d-flex align-items-center justify-content-center font-medium text-success"  data-toggle="tooltip" data-placement="top" title="Turn user account off">
                                        <i class="mdi mdi-account-check font-20 m-r-5"></i>Activate Account
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        @endif
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
