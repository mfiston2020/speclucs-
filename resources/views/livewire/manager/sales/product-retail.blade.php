<div class="col-md-12 col-sm-12">

    <form wire:submit.prevent="saveOrder">
        <div class="col-md-12 col-sm-12 mt-2">

            {{-- <div class="col-12"> --}}
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h4 class="card-title">
                                @if (userInfo()->permissions=='seller' || userInfo()->permissions=='manager')
                                    <a wire:click="hideCloud('no')" class="btn btn-primary btn-rounded text-white">
                                        <i @class(['badge badge-pill badge-danger'=>$isCloudOrder=='no'])> {{$isCloudOrder=='no'?'-':''}}</i>
                                        <i class="mdi mdi-cart-plus"></i> Customer Order
                                    </a>
                                @endif
                                @if (userInfo()->permissions=='lab' || userInfo()->permissions=='manager')
                                    <a wire:click="hideCloud('yes')" class="btn btn-success btn-rounded text-white">
                                        <i @class(['badge badge-pill badge-danger'=>$isCloudOrder=='yes'])>{{$isCloudOrder=='yes'?'-':''}}</i>
                                        <i class="mdi mdi-clock-fast"></i> Cloud Order
                                    </a>
                                @endif
                            </h4>
                        </div>
                    </div>
                </div>
            {{-- </div> --}}

            @if ($isCloudOrder=='yes')
                {{-- personal information --}}
                <div class="card">
                    <div class="card-header">
                        Cloud Information
                        {{-- <button wire:click=showModal>shwi</button> --}}
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <!--/span-->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cloud ID</label>
                                    <input type="text" wire:model.lazy="cloud_id" class="form-control">
                                </div>
                            </div>

                            <!--/span-->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Hospital Name</label>
                                    <input type="text" wire:model.lazy="hospital_name" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Insurance</label>
                                    <select class="form-control @error('insurance_type') is-invalid @enderror custom-select"
                                        wire:model.lazy='insurance_type' required>
                                        <option value="">** Select Type **</option>
                                        <option value="private" selected>private</option>
                                        @if (count($insuranceList) > 0)

                                            @foreach ($insuranceList as $insurance)
                                                <option value="{{ $insurance->id }}">{{ $insurance->insurance_name }}
                                                </option>
                                            @endforeach

                                        @endif
                                    </select>
                                    @error('insurance_type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            @else

            {{-- personal information --}}
            <div class="card">
                <div class="card-header">
                    Customer Information
                    {{-- <button wire:click=showModal>shwi</button> --}}
                </div>

                <div class="card-body">

                    <div class="row">
                        <!--/span-->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Firstname</label>
                                <input type="text" wire:model.lazy="firstname" class="form-control" {{$isCloudOrder=='no'?'required':''}}>
                            </div>
                        </div>

                        <!--/span-->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Lastname</label>
                                <input type="text" wire:model.lazy="lastname" class="form-control" {{$isCloudOrder=='no'?'required':''}}>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Gender</label>
                                <select class="form-control @error('gender') is-invalid @enderror custom-select"
                                    wire:model.lazy='gender'  {{$isCloudOrder=='no'?'required':''}}>
                                    <option value="">--Select your Gender--</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                @error('gender')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!--/span-->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date Of Birth</label>
                                <input type="date" max="{{ date('Y-m-d') }}" wire:model.lazy="date_of_birth"
                                    class="form-control" {{$isCloudOrder=='no'?'required':''}}>
                            </div>
                        </div>

                        <!--/span-->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tin Number</label>
                                <input type="text" wire:model.lazy="tin_number" class="form-control">
                            </div>
                        </div>

                        <!--/span-->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" wire:model.lazy="phone" class="form-control" {{$isCloudOrder=='no'?'required':''}}>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Insurance</label>
                                <select class="form-control @error('insurance_type') is-invalid @enderror custom-select"
                                    wire:model.lazy='insurance_type' required>
                                    <option value="">** Select Type **</option>
                                    <option value="private">private</option>
                                    @if (count($insuranceList) > 0)

                                        @foreach ($insuranceList as $insurance)
                                            <option value="{{ $insurance->id }}">{{ $insurance->insurance_name }}
                                            </option>
                                        @endforeach

                                    @endif
                                </select>
                                @error('insurance_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <label for="cost">
                                Patient Affiliate Name
                            </label>
                            <input type="text" class="form-control @error('affiliate')  is-invalid @enderror"
                                id="affiliate" placeholder="ex: Fiston MUNYAMPETA"
                                wire:model.debounce.500ms="affiliate">
                            @error('affiliate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-3">
                            <label for="cost">
                                Ins. Number
                            </label>
                            <input type="text" class="form-control @error('insurance_number')  is-invalid @enderror"
                                id="insurance_number" placeholder="0" wire:model.debounce.500ms="insurance_number">
                            @error('insurance_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                </div>
            </div>

            @endif

            {{-- lens --}}
            <div class="col-md-12 col-sm-12 mt-2">
                <div class="card">
                    <div class="card-header">
                        Lens
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <!--/span-->
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <select class="form-control" wire:model.lazy="lens_type">
                                        <option value="">
                                            *** Select Type ***
                                        </option>
                                        @foreach ($lensType as $len_type)
                                            <option value="{{ $len_type->id }}">{{ $len_type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <select class="form-control" wire:model.lazy="lens_coating">
                                        <option value=>
                                            *** Select Coating ***
                                        </option>
                                        @foreach ($lensCoating as $len_coating)
                                            <option value="{{ $len_coating->id }}">
                                                {{ $len_coating->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <select class="form-control" wire:model.lazy='lens_index'>
                                        <option value="">
                                            *** Select Index ***
                                        </option>
                                        @foreach ($lensIndex as $len_index)
                                            <option value={{ $len_index->id }}>{{ $len_index->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <select class="form-control" wire:model.lazy="lens_chromatic">
                                        <option value="">
                                            *** Select Chromatic Aspect ***
                                        </option>
                                        @foreach ($lensChromaticAspect as $len_chromatic)
                                            <option value="{{ $len_chromatic->id }}">
                                                {{ $len_chromatic->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row mt-3">
                            {{-- right --}}
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="rightEye"
                                            wire:model.lazy="rightEye">
                                        <label class="custom-control-label" for="rightEye">Right</label>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="form-group col-4">
                                        <input type="text" class="form-control" id="right_s" placeholder="Sph"
                                            wire:model.lazy="r_sphere">
                                    </div>
                                    <div class="form-group col-4">
                                        <input type="text" class="form-control" id="right_c" placeholder="Cyl"
                                            wire:model.lazy="r_cylinder">
                                    </div>
                                    <div class="form-group col-4">
                                        <input type="text" class="form-control" id="right_x" placeholder="Axis"
                                            wire:model.lazy="r_axis">
                                    </div>
                                    <div class="form-group col-4">
                                        <input type="text" class="form-control" id="right_a" placeholder="Add"
                                            wire:model.lazy="r_addition">
                                    </div>
                                    <div class="form-group col-4">
                                        <input type="text" class="form-control" id="r_segment_height"
                                            placeholder="Seg Height" wire:model.lazy="r_segment_height">
                                    </div>
                                    <div class="form-group col-4">
                                        <input type="text" class="form-control" id="r_mono_pd"
                                            placeholder="Mono PD" wire:model.lazy="r_mono_pd">
                                    </div>
                                    <hr>

                                </div>
                            </div>

                            {{-- Left --}}
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="leftEye"
                                            wire:model="leftEye">
                                        <label class="custom-control-label" for="leftEye">Left</label>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="form-group col-4">
                                        <input type="text" class="form-control" id="left_s" placeholder="Sph"
                                            wire:model.lazy="l_sphere">
                                    </div>
                                    <div class="form-group col-4">
                                        <input type="text" class="form-control" id="left_c" placeholder="Cyl"
                                            wire:model.lazy="l_cylinder">
                                    </div>
                                    <div class="form-group col-4">
                                        <input type="text" class="form-control" id="left_x" placeholder="Axis"
                                            wire:model.lazy="l_axis">
                                    </div>
                                    <div class="form-group col-4">
                                        <input type="text" class="form-control" id="left_a" placeholder="Add"
                                            wire:model.lazy="l_addition">
                                    </div>
                                    <div class="form-group col-4">
                                        <input type="text" class="form-control" id="l_segment_height"
                                            placeholder="Seg Height" wire:model.lazy="l_segment_height">
                                    </div>
                                    <div class="form-group col-4">
                                        <input type="text" class="form-control" id="l_mono_pd"
                                            placeholder="Mono PD" wire:model.lazy="l_mono_pd">
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Card -->
            </div>

            {{-- frame div --}}
            <div class="col-md-12 col-sm-12 mt-2">
                <div class="card">
                    <div class="card-header">
                        Frame
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <!--/span-->
                            <div class="col-md-3 col-sm-12">
                                <label>Frames</label>
                                <div class="form-group">
                                    <select class="form-control" id="frame" wire:model.lazy="frame">
                                        <option value="">
                                            *** Select Type ***
                                        </option>
                                        @foreach ($frameList as $frame)
                                            <option value="{{ $frame->id }}">{{ $frame->product_name }} |
                                                {{ $frame->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-1">
                                <label>Stock</label>
                                <input type="text"
                                    class="form-control {{ $frame_stock == 0 && $frame_stock != null ? 'border border-danger' : '' }}"
                                    id="frame_stock" placeholder="Stock" wire:model.lazy="frame_stock" readonly>
                            </div>

                            <div class="form-group col-1">
                                <label>U. Price</label>
                                <input type="text" class="form-control" id="frame_unit_price"
                                    placeholder="Unit Price" wire:model.lazy="frame_unit_price" readonly>
                            </div>

                            <div class="form-group col-1">
                                <label>Quantity</label>
                                <input type="number" class="form-control" id="frame_quantity" placeholder="Qty"
                                    wire:model.lazy="frame_quantity" readonly>
                            </div>

                            <div class="form-group col-2">
                                <label>Price Adj</label>
                                <input type="number" class="form-control" id="frame_price_adjust"
                                    placeholder="Price Adj" wire:model="frame_price_adjust">
                            </div>

                            <div class="form-group col-2">
                                <label>T.Amount</label>
                                <input type="text" class="form-control" id="frame_total_amount"
                                    placeholder="Total" readonly>
                            </div>

                            <div class="form-group col-2">
                                <label>Location</label>
                                <input type="text" class="form-control" id="frame_location"
                                    placeholder="Location" wire:model.lazy="frame_location" readonly>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Card -->
            </div>

            {{-- accessories div --}}
            <div class="col-md-12 col-sm-12 mt-2">
                <div class="card">
                    <div class="card-header">
                        Accessories & Other Products
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <!--/span-->
                            <div class="col-md-3 col-sm-12">
                                <label>Products</label>
                                <div class="form-group">
                                    <select class="form-control" id="accessory" wire:model.lazy="accessory">
                                        <option value="">
                                            *** Select Product ***
                                        </option>
                                        @foreach ($accessoriesList as $accessory)
                                            <option value="{{ $accessory->id }}">{{ $accessory->product_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-1">
                                <label>Stock</label>
                                <input type="text"
                                    class="form-control {{ $accessory_stock <= 0 && $accessory_stock != null ? 'border border-danger' : '' }}"
                                    id="accessory_stock" placeholder="Stock" wire:model.lazy="accessory_stock"
                                    readonly>
                            </div>

                            <div class="form-group col-1">
                                <label>U. Price</label>
                                <input type="text" class="form-control border-red-500" id="accessory_unit_price"
                                    placeholder="Unit Price" wire:model.lazy="accessory_unit_price" readonly>
                            </div>

                            <div class="form-group col-1">
                                <label>Quantity</label>
                                <input type="number" class="form-control" id="accessory_quantity" placeholder="Qty"
                                    wire:model="accessory_quantity"readonly>
                            </div>

                            <div class="form-group col-2">
                                <label>Price Adj</label>
                                <input type="number" class="form-control" id="accessory_price_adjust"
                                    placeholder="Price Adj" wire:model="accessory_price_adjust">
                            </div>

                            <div class="form-group col-2">
                                <label>T.Amount</label>
                                <input type="number" class="form-control" id="accessory_total_amount"
                                    placeholder="Total" readonly>
                            </div>

                            <div class="form-group col-2">
                                <label>Location</label>
                                <input type="text" class="form-control" id="accessory_location"
                                    placeholder="Location" wire:model.lazy="accessory_location" readonly>
                            </div>
                        </div>


                        <div class="row d-flex justify-content-center items-center mt-4">
                            <button wire:loading.attr="disabled" class="btn btn-sm btn-primary" type="button"
                                wire:click="checkAvailability">
                                <span wire:loading.remove wire:target="checkAvailability">
                                    Check Product Availability
                                </span>
                                <span wire:loading wire:target="checkAvailability">
                                    Checking... <img src="{{ asset('dashboard/assets/images/loading2.gif') }}"
                                        width="20" />
                                </span>
                            </button>
                        </div>

                    </div>
                </div>
                <!-- Card -->
            </div>

            @if ($showPaymentSection)
                <div class="col-md-12 col-sm-12 mt-2">
                    <div class="card d-flex">

                        <div class="card-header d-flex justify-content-between items-center">
                            <span>
                                Insurance / Patient Payment

                            </span>
                            <h5>
                                @php
                                    $payment = $patient_payment_frame + $patient_payment_lens + $accessory_total_amount;
                                @endphp
                                Total Patient Amount:<span class="text-info">
                                    {{ format_money($payment == 0 || $payment == null ? 0 : $payment) }}
                                </span>
                            </h5>
                        </div>

                        <div class="card-body">
                            @if ($searchProduct)
                                <div class="row">
                                    <!--/span-->
                                    <div class="d-flex flex-col items-center">
                                        <div class="col-md-2 col-sm-12">
                                            <label>Lens</label>
                                            <br>
                                            <span>R</span>
                                            @if ($rightLen)
                                            <label @class(['badge badge-pill ml-2', 'badge-success' => $rightLenQty>=10,'badge-warning' => $rightLenQty<10,'badge-danger' => $rightLenQty<=1,])>
                                                @if ($rightLenQty<=1)
                                                    {{-- @if ($rightLenID == $leftLenID) --}}
                                                        Out Of Stock
                                                    {{-- @else
                                                        Out Of Stock
                                                    @endif --}}
                                                @else
                                                    Available [Qty: {{$rightLenQty}}]
                                                @endif
                                            </label>
                                            @else
                                                <label class="badge badge-danger badge-pill ml-2">N/A</label>
                                            @endif
                                            <br>
                                            <span>L</span>
                                            @if ($leftLen)
                                                <label @class(['badge badge-pill ml-2', 'badge-success' => $leftLenQty>=10,'badge-warning' => $leftLenQty<10,'badge-danger' => $leftLenQty<=1,])>
                                                    @if ($leftLenQty<=1)
                                                        {{-- @if ($rightLenID == $leftLenID) --}}
                                                            Out Of Stock
                                                        {{-- @else
                                                            Out Of Stock
                                                        @endif --}}
                                                    @else
                                                        Available [Qty: {{$leftLenQty}}]
                                                    @endif
                                                </label>
                                            @else
                                                <label class="badge badge-danger badge-pill ml-2">N/A</label>
                                            @endif
                                        </div>

                                        <div class="form-group col-2">
                                            <label>Total Amount</label>
                                            <input type="text" class="form-control" id="total_lens_amount"
                                                placeholder="Total Amount" wire:model="total_lens_amount" readonly>
                                        </div>

                                        <div class="form-group col-2">
                                            <label>Insurance Percentage</label>
                                            <input type="number" min="0" max="100" class="form-control"
                                                id="insurance_percentage" placeholder="Ins. %"
                                                wire:model="insurance_percentage_lens">
                                        </div>

                                        <div class="form-group col-2">
                                            <label>Ins Apprv</label>
                                            <input type="text" class="form-control" id="lens_approved_amount"
                                                placeholder="approved amt" wire:model="insurance_approved_lens">
                                        </div>

                                        <div class="form-group col-2">
                                            <label>Ins Payment</label>
                                            <input type="text" class="form-control" id="insurance_payment_lens"
                                                placeholder="Ins. payment" wire:model="insurance_payment_lens"
                                                readonly>
                                        </div>

                                        <div class="form-group col-2">
                                            <label>Pt Payment</label>
                                            <input type="text" class="form-control" id="patient_payment_lens"
                                                placeholder="Pt. Payment" wire:model="patient_payment_lens" readonly>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            @endif
                            <div class="row">
                                <!--/span-->
                                <div class="d-flex flex-col items-center">
                                    <div class="col-md-2 col-sm-12">
                                        <label></label>
                                        <br>
                                        <span>Frame Name</span>
                                        <br>
                                        <span></span>
                                    </div>

                                    <div class="form-group col-2">
                                        <label>Total Amount</label>
                                        <input type="text" class="form-control" id="frame_final_total"
                                            placeholder="Total Amount"
                                            value="{{ format_money($frame_total_amount) }}" readonly>
                                    </div>

                                    <div class="form-group col-2">
                                        <label>Insurance Percentage</label>
                                        <input type="text" min="0" max="100" class="form-control"
                                            id="insurance_percentage_frame" placeholder="Ins. %"
                                            wire:model="insurance_percentage_frame">
                                    </div>

                                    <div class="form-group col-2">
                                        <label>Ins Apprv</label>
                                        <input type="text" class="form-control" id="insurance_approved_frame"
                                            placeholder="approved Amt" wire:model="insurance_approved_frame">
                                    </div>

                                    <div class="form-group col-2">
                                        <label>Ins Payment</label>
                                        <input type="text" class="form-control" id="insurance_payment_frame"
                                            placeholder="Ins. payment" wire:model.lazy="insurance_payment_frame"
                                            readonly>
                                    </div>

                                    <div class="form-group col-2">
                                        <label>Pt Payment</label>
                                        <input type="text" class="form-control" id="patient_payment_frame"
                                            placeholder="Pt. Payment" wire:model="patient_payment_frame" readonly>
                                    </div>
                                </div>
                            </div>


                            <div class="row d-flex justify-content-around items-center mt-4">
                                @if ($showsubmit)
                                    <button class="btn btn-sm btn-success">
                                        Submit
                                    </button>
                                @endif
                                <button class="btn btn-sm btn-info" type="button" wire:click=calculateInsurance>
                                    Calculate
                                </button>
                            </div>

                        </div>

                    </div>
                    <!-- Card -->
                </div>
            @endif

            <div id="warningModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="vcenter"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title text-info" id="vcenter">Warning</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <h4>{{ $informationMessage }}</h4>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </div>
    </form>

</div>

@push('scripts')
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        window.addEventListener('showwarningModal', event => {
            $("#warningModal").modal('show');
        })

        $('#frame').on('change', function() {
            frameAdjustment();
        })

        function frameAdjustment() {
            var total = parseInt($('#frame_quantity').val()) * (parseInt($('#frame_unit_price').val()) + parseInt($(
                '#frame_price_adjust').val()));
            $('#frame_total_amount').val(isNaN(total) ? 0 : total);
            $('#frame_final_total').val(isNaN(total) ? 0 : total);
        }

        // frame changing
        $('#frame_price_adjust').on('change', function() {
            frameAdjustment();
        })
        $('#frame_price_adjust').on('keyup', function() {
            frameAdjustment();
        })

        // $('#frame_quantity').on('change', function() {
        //     var total = parseInt($('#frame_quantity').val()) * parseInt($('#frame_unit_price').val());
        //     $('#frame_total_amount').val(total);
        //     $('#frame_final_total').val(total);
        // });

        // ======================================================

        function accessoryAdjustment() {
            var total = parseInt($('#accessory_quantity').val()) * (parseInt($('#accessory_unit_price').val()) +
                parseInt($('#accessory_price_adjust').val()));
            $('#accessory_total_amount').val(total);
        }

        $('#accessory_price_adjust').on('change', function() {
            accessoryAdjustment();
        });
        $('#accessory_price_adjust').on('keyup', function() {
            accessoryAdjustment();
        });

        $("#accessory").on('change', function() {
            accessoryAdjustment();
        });

        // $('#accessory_quantity').on('change', function() {
        //     var total = parseInt($('#accessory_quantity').val()) * parseInt($('#accessory_unit_price').val());
        //     $('#accessory_total_amount').val(total);
        // });
    </script>
@endpush
