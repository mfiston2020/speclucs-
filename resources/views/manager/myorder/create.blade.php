@extends('manager.includes.app')

@section('title','Manager Dashboard - Create Order')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/select2/dist/css/select2.min.css')}}">
@endpush

{{-- ==== Breadcumb ======== --}}
@section('page_name','New Order')
@section('current','Add Order')
{{-- === End of breadcumb == --}}

@section('content')
<div class="container-fluid">
    <form action="{{route('manager.order.placeOrder')}}" method="post" id="order-form">
        @csrf
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body row">
                        {{-- ================================= --}}
                        @include('manager.includes.layouts.message')
                        {{-- ========================== --}}
                        <h4 class="card-title col-12">Order Detail</h4>
                        <hr>
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" name="order_number" value="LAB-ORDER# {{$order_number}}" required readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" placeholder="PRESCRIPTION HOSPITAL NAME"
                                name="prescription_hospital" value="{{old('prescription_hospital')}}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" placeholder="+250788888888888" name="patient_number"
                                value="{{old('patient_number')}}" required>
                                <span class="text-info">
                                    Start with country code
                                </span>
                        </div>
                        <hr>
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" placeholder="Firstname" name="firstname"
                                value="{{old('firstname')}}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" placeholder="Lastname" name="lastname"
                                value="{{old('lastname')}}" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body row">
                        <h4 class="card-title col-12">Lens Characteristics</h4>
                        <div class="form-group col-md-3 col-sm-12">
                            <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                name="type" id="type" required>
                                <option value="">Select Type</option>
                                @foreach ($lens_types as $type)
                                <option value="{{$type->id}}" {{(old('type')==$type->id)?'selected':''}}>
                                    {{$type->name}}
                                </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="form-group col-md-3 col-sm-12">
                            <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                name="coating" id="coating" required>
                                <option value="">Select Coating</option>
                                @foreach ($coatings as $coating)
                                <option value="{{$coating->id}}" {{(old('coating')==$coating->id)?'selected':''}}>
                                    {{$coating->name}}
                                </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="form-group col-md-3 col-sm-12">
                            <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                name="index" id="index" required>
                                <option value="">Select Index</option>
                                @foreach ($index as $index)
                                <option value="{{$index->id}}" {{(old('index')==$index->id)?'selected':''}}>
                                    {{$index->name}}
                                </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="form-group col-md-3 col-sm-12">
                            <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                name="chromatic" id="chromatic" required>
                                <option value="">Select Chromatic Aspect</option>
                                @foreach ($chromatics as $chromatic)
                                <option value="{{$chromatic->id}}" {{(old('chromatic')==$chromatic->id)?'selected':''}}>
                                    {{$chromatic->name}}
                                </option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-sm-12 col-md-6 col-lg-6" id="singleVision">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Both Eye</h4>
                        <br>
                        <div class="row">
                            <div class="form-group col-6">
                                <input type="text" class="form-control" id="both_s" placeholder="Sphere" name="sphere_both" value="{{old('sphere_both')}}">
        </div>
        <div class="form-group col-6">
            <input type="text" class="form-control" id="both_c" placeholder="Cylinder" name="cylinder_both"
                value="{{old('cylinder_both')}}">
        </div>
</div>
</div>
</div>
</div> --}}
<div class="col-sm-12 col-md-6 col-lg-6" id="right">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Right Eye</h4>
            <br>
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="rightEye">
                <label class="custom-control-label" for="rightEye">Right Eye Only</label>
            </div>
            <hr>
            <div class="row">
                <div class="form-group col-3">
                    <input type="text" class="form-control" id="right_s" placeholder="Sphere" name="sphere_right"
                        value="{{old('sphere_right')}}">
                </div>
                <div class="form-group col-3">
                    <input type="text" class="form-control" id="right_c" placeholder="Cylinder" name="cylinder_right"
                        value="{{old('cylinder_right')}}">
                </div>
                <div class="form-group col-3">
                    <input type="text" class="form-control" id="right_x" placeholder="Axis" name="axis_right"
                        value="{{old('axis_right')}}">
                </div>
                <div class="form-group col-3">
                    <input type="text" class="form-control" id="right_a" placeholder="Addition" name="addition_right"
                        value="{{old('addition_right')}}">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-12 col-md-6 col-lg-6" id="left">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Left Eye</h4>
            <br>
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="leftEye">
                <label class="custom-control-label" for="leftEye">Left Eye Only</label>
            </div>
            <hr>
            <div class="row">
                <div class="form-group col-3">
                    <input type="text" class="form-control" id="left_s" placeholder="Sphere" name="sphere_left"
                        value="{{old('sphere_left')}}">
                </div>
                <div class="form-group col-3">
                    <input type="text" class="form-control" id="left_c" placeholder="Cylinder" name="cylinder_left"
                        value="{{old('cylinder_left')}}">
                </div>
                <div class="form-group col-3">
                    <input type="text" class="form-control" id="left_x" placeholder="Axis" name="axis_left"
                        value="{{old('axis_left')}}">
                </div>
                <div class="form-group col-3">
                    <input type="text" class="form-control" id="left_a" placeholder="Addition" name="addition_left"
                        value="{{old('addition_left')}}">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-12 col-md-12 col-lg-12">
    <div class="card">
        <div class="card-body row">

            <div class="col-6">
                <h4 class="card-title col-12">Frame Selection</h4>
                <hr>
                <div class="form-group col-md-8 col-sm-12">

                    <select class="select2 form-control custom-select" style="width: 100%; height:36px;" name="frame"
                        id="frames">
                        <option value="">Select Type</option>
                        @foreach ($frames as $frame)
                        <option value="{{$frame->id}}" {{(old('frame')==$frame->id)?'selected':''}}>
                            {{$frame->product_name}} | {{$frame->description}}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-6 row">
                <h4 class="card-title col-12">Comment</h4>
                <hr>
                {{-- <div class="form-group col-md-6 col-sm-12">

                    <label for="upplier">Supplier</label>

                    <select class="select2 form-control custom-select" style="width: 100%; height:36px;" name="supplier"
                        id="supplier" required>
                        <option value="">Select Type</option>
                        @foreach ($suppliers as $supplier)
                        <span>{{$company=\App\Models\CompanyInformation::where('id',$supplier->company_id)->select('id','company_name')->first()}}</span>
                        <option value="{{$company->id}}" {{(old('supplier')==$company->id)?'selected':''}}>
                            {{$company->company_name}}
                        </option>
                        @endforeach
                    </select>
                </div> --}}
                <div class="form-group col-md-12 col-sm-12">
                    <div class="form-group">
                        {{-- <label for="comment">comment</label> --}}
                        <input type="text" class="form-control" id="comment" placeholder="comment" name="comment"
                            value="{{old('comment')}}">
                    </div>
                {{-- </div>
                <div class="form-group col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="cost">Cost</label>
                        <input type="text" class="form-control" id="cost" placeholder="Lens Cost" name="cost"
                            value="{{old('cost')}}" readonly>
                    </div>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <div class="form-group">
                        {{-- <label for="stock">Stock</label> --}}
                        {{-- <input type="hidden" class="form-control" id="stock" placeholder="Available Stock" name="stock"
                            value="{{old('stock')}}" readonly> --}}
                        <input type="" id="pid" name="pid" value="{{old('pid')}}">
                    {{-- </div>
                </div>  --}}
            </div>
        </div>
    </div>
</div>
<div class="col-sm-12 col-md-12 col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="form-group m-b-0 text-center" id='actionButtons'>
                <button type="submit" class="btn btn-info waves-effect waves-light">Place Order</button>
                <a href="{{url()->previous()}}" class="btn btn-dark waves-effect waves-light">Cancel</a>
            </div>
            <div class="form-group m-b-0 text-center" id="loading">
                <h5>Processing Please wait <img src="{{ asset('dashboard/assets/images/loading.gif')}}" height="60px">
                </h5>
            </div>
            <div class="form-group m-b-0 text-center" id="message">
                <h5>Processing Please wait <img src="{{ asset('dashboard/assets/images/loading.gif')}}" height="60px">
                </h5>
            </div>
        </div>
    </div>
</div>
</div>
</form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('dashboard/assets/libs/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/libs/select2/dist/js/select2.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/dist/js/pages/forms/select2/select2.init.js')}}"></script>
<script src="{{ asset('dashboard/assets/libs/jquery-validation/additional-methods.min.js')}}"></script>
<script src="{{ asset('dashboard/assets/libs/jquery-validation/jquery.validate.js')}}"></script>

<script>
    $('#message').hide();
    $(document).ready(function () {
        $('#order-form').validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                },
                password: {
                    required: true,
                    minlength: 5
                },
                terms: {
                    required: true
                },
            },
            messages: {
                email: {
                    required: "Please enter a email address",
                    email: "Please enter a vaild email address"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                terms: "Please accept our terms"
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });



    function hideME() {
        $('#loading').show();
        $('#actionButtons').hide();
    }

    function validate_form() {
        if ($('#order-form').valid()) {
            hideME();
        }
    }

    // $('#singleVision').hide();
    $('#loading').hide();

    // $('#type').on('change',function(){
    //     if ($(this).val()==2)
    //     {
    //         // console.log($(this).val());
    //         $('#right').hide();
    //         $('#left').hide();
    //         $('#singleVision').show();
    //         //  making field required
    //         $('#both_s').attr('required','required');
    //         $('#both_c').attr('required','required');
    //     }
    //     else{
    //         $('#right').show();
    //         $('#left').show();
    //         $('#singleVision').hide();
    //         //  making field required
    //         $('#both_s').removeAttr('required');
    //         $('#both_c').removeAttr('required');
    //     }
    // });
    $('#rightEye').on('click', function () {
        if ($(this).is(":checked")) {
            $("#left").hide(); // checked

            //  making field required
            $('#right_s').attr('required', 'required');
            $('#right_c').attr('required', 'required');
            // $('#right_x').attr('required','required');
            // $('#right_a').attr('required','required');
        } else {
            $("#left").show();

            //  making field required
            $('#right_s').removeAttr('required');
            $('#right_c').removeAttr('required');
            // $('#right_x').removeAttr('required');
            // $('#right_a').removeAttr('required');
        }
    });
    // ===================================
    $('#leftEye').on('click', function () {
        if ($(this).is(":checked")) {
            $("#right").hide(); // checked

            //  making field required
            $('#left_s').attr('required', 'required');
            $('#left_c').attr('required', 'required');
            // $('#left_x').attr('required','required');
            // $('#left_a').attr('required','required');
        } else {
            $("#right").show();

            //  making field required
            $('#left_s').removeAttr('required');
            $('#left_c').removeAttr('required');
            // $('#left_x').removeAttr('required');
            // $('#left_a').removeAttr('required');
        }
    });

    $('#supplier').on('change', function () {
        if ($('#order-form').valid()) {
            // }
            // else
            // {
            $('#loading').show();
            $('#actionButtons').hide();

            var eye = '';
            var sphere = '';
            var cylinder = '';
            var axis = '';
            var add = '';

            var supplier = $(this).val();
            var lens_type = $('#type').val();
            var index = $('#index').val();
            var chromatics = $('#chromatic').val();
            var coating = $('#coating').val();

            if ($('#rightEye').is(":checked")) {
                eye = 'right';

                var sphere = $('#right_s').val();
                var cylinder = $('#right_c').val();
                var axis = $('#right_cx').val();
                var add = $('#right_ca').val();
            } else if ($('#leftEye').is(':checked')) {
                eye = 'left';

                var sphere = $('#left_s').val();
                var cylinder = $('#left_c').val();
                var axis = $('#left_cx').val();
                var add = $('#left_ca').val();
            } else {
                eye = 'any';
                var sphere = $('#both_s').val();
                var cylinder = $('#both_c').val();
            }

            $.ajax({
                url: "{{ route('manager.order.fetchSupplierProduct') }}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",

                    lens_types: lens_type,
                    index: index,
                    chromatics: chromatics,
                    coating: coating,
                    sphere: sphere,
                    cylinder: cylinder,
                    axis: axis,
                    add: add,
                    eye: eye,
                    supplier: supplier,
                },

                success: function (data) {
                    if (!data.length) {
                        $('#loading').hide();
                        $('#actionButtons').show();
                    } else {

                        $('#loading').hide();
                        $('#actionButtons').show();

                        $("#cost").val(data[0].price);
                        $("#stock").val(data[0].stock);
                        $("#pid").val(data[0].id);

                        // if (data[0].id==null) {
                        //     $('#')
                        // }

                        console.log(data);
                    }

                },
                error: function (data) {
                    console.log(data.length);
                }
            });
        }
    });
</script>
@endpush
