@extends('manager.includes.app')

@section('title','Admin Dashboard - Add Client')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/select2/dist/css/select2.min.css')}}">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','New Client')
@section('current','Add Client')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Add Client</h4>

                </div>
                <hr>

                <form class="form-horizontal" action="{{route('manager.client.save')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        {{-- ====== input error message ========== --}}
                        @include('manager.includes.layouts.message')
                        {{-- ====================================== --}}

                        <div class="form-group row">
                            <label for="name" class="col-sm-3 text-right control-label col-form-label">Name 
                                <span id="left" style="color: red"></span>
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="name" placeholder="Fiston MUNYAMPETA"
                                    name="name" min="1" autocomplete="off" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-sm-3 text-right control-label col-form-label">Email 
                                <span id="left" style="color: red"></span>
                            </label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="email" placeholder="email"
                                    name="email" min="1" autocomplete="off" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-sm-3 text-right control-label col-form-label">Phone Number 
                                <span id="left" style="color: red"></span>
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="phone" placeholder="phone"
                                    name="phone" min="1" autocomplete="off" required>
                            </div>
                        </div>

                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="form-group m-b-0 text-right">
                            <button type="submit" class="btn btn-info waves-effect waves-light">Save Client</button>
                            <button type="reset" class="btn btn-dark waves-effect waves-light">Cancel</button>
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
