@extends('manager.includes.app')

@section('title','Manager Dashboard - Add User')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/select2/dist/css/select2.min.css')}}">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','New User')
@section('current','Add User')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Add User</h4>

                </div>
                <hr>

                <form class="form-horizontal" action="{{route('manager.user.save')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        {{-- ====== input error message ========== --}}
                        @include('manager.includes.layouts.message')
                        {{-- ====================================== --}}

                        <div class="form-group row">
                            <label for="name" class="col-sm-3 text-right control-label col-form-label">User's Name</label>
                            <div class="col-sm-9">
                                <input type="text" id="name" class="form-control" placeholder="Full name" name="name"
                                    required value="{{old('name')}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-3 text-right control-label col-form-label">User's Email</label>
                            <div class="col-sm-9">
                                <input type="email" id="email" class="form-control" placeholder="email" name="email"
                                    required value="{{old('email')}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone" class="col-sm-3 text-right control-label col-form-label">User's phone</label>
                            <div class="col-sm-9">
                                <input type="text" id="phone" class="form-control" placeholder="phone number" name="phone"
                                    required value="{{old('phone')}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pname" class="col-sm-3 text-right control-label col-form-label">Role</label>
                            <div class="col-sm-9">
                                <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                    name="permission" id="permission" required>
                                    <option value="">Select</option>

                                    <option value="accountant" {{(old('permission')=='accountant')?'selected':''}}> Accountant </option>
                                    <option value="doctor" {{(old('permission')=='doctor')?'selected':''}}> Ophthalmologist</option>
                                    <option value="lab" {{(old('permission')=='lab')?'selected':''}}> Lab Technician </option>
                                    <option value="seller" {{(old('permission')=='seller')?'selected':''}}> Seller </option>
                                    <option value="store" {{(old('permission')=='store')?'selected':''}}> Store Keeper </option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="form-group m-b-0 text-right">
                            <button type="submit" class="btn btn-info waves-effect waves-light">Save User</button>
                            <a href="{{url()->previous()}}" class="btn btn-dark waves-effect waves-light">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('dashboard/assets/libs/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/libs/select2/dist/js/select2.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/dist/js/pages/forms/select2/select2.init.js')}}"></script>

<script>
    $('#receipt').on('change',function(){
        var id  =   ($(this).val());

        $.ajax({
            url: "{{ route('manager.fetchReceipt') }}",
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                id: id,
            },

            success: function (data)
            {
                $('#supplier').val(data.supplier_name);
                $('#supplier_id').val(data.supplier_id);
                $('#balance').val(data.total_cost);
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

    $('#amount').on('input',function(){
        var amount =   $(this).val();
        var balance  =   $('#balance').val();

        $("#due").val(balance-amount);
    });
</script>
@endpush
