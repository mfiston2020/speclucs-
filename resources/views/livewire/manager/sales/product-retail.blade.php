<div class="col-md-12 col-sm-12">

    <div class="col-md-12 col-sm-12 mt-2">
        <div class="card">
            <div class="card-header">
                Customer Information
            </div>
            <div class="card-body">

                <div class="row">
                    <!--/span-->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Firstname</label>
                            <input type="text" wire:model.lazy="firstname" class="form-control" required="">
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Lastname</label>
                            <input type="text" wire:model.lazy="lastname" class="form-control" required="">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Gender</label>
                            <select class="form-control @error('gender') is-invalid @enderror custom-select"
                                wire:model.lazy='gender'>
                                <option>--Select your Gender--</option>
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
                                class="form-control" required="">
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tin Number</label>
                            <input type="text" wire:model.lazy="tin_number" class="form-control" required="">
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" wire:model.lazy="phone" class="form-control" required="">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Insurance</label>
                            <select class="form-control @error('gender') is-invalid @enderror custom-select"
                                wire:model.lazy='gender'>
                                <option value="">** Select Type **</option>
                                <option value="private">private</option>
                                @if (count($allInsurances) > 0)

                                    @foreach ($allInsurances as $insurance)
                                        <option value="{{ $insurance->id }}">{{ $insurance->insurance_name }}</option>
                                    @endforeach

                                @endif
                            </select>
                            @error('gender')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label for="cost">
                            Ins. Number
                        </label>
                        <input type="text" class="form-control @error('insurance_percentage')  is-invalid @enderror"
                            id="insurance_percentage" placeholder="0" wire:model.debounce.500ms="insurance_percentage">
                    </div>
                </div>

            </div>
        </div>

        <div class="col-md-12 col-sm-12 mt-2">
            <div class="card">
                <div class="card-header">
                    Lens <span class="">Not Available</span>
                </div>
                <div class="card-body">
                    <input type="hidden" wire:model='invoice_id'>

                    <div class="row">
                        <!--/span-->
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <select class="form-control" wire:model.lazy="type">
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
                                <select class="form-control" wire:model.lazy="coating">
                                    <option>
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
                                <select class="form-control" wire:model.lazy='index'>
                                    <option>
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
                                <select class="form-control" wire:model.lazy="chromatic">
                                    <option>
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
                                        wire:model="rightEye" {{ $leftEye == true ? 'disabled' : '' }}>
                                    <label class="custom-control-label" for="rightEye">Right</label>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="form-group col-4">
                                    <input type="text" class="form-control" id="right_s" placeholder="Sph"
                                        wire:model="r_sphere" value="">
                                </div>
                                <div class="form-group col-4">
                                    <input type="text" class="form-control" id="right_c" placeholder="Cyl"
                                        wire:model="r_cylinder" value="">
                                </div>
                                <div class="form-group col-4">
                                    <input type="text" class="form-control" id="right_x" placeholder="Axis"
                                        wire:model="r_axis" value="">
                                </div>
                                <div class="form-group col-3">
                                    <input type="text" class="form-control" id="right_a" placeholder="Add"
                                        wire:model="r_addition" value="">
                                </div>
                                <div class="form-group col-3">
                                    <input type="text" class="form-control" id="r_segment_height"
                                        placeholder="Seg Height" wire:model="r_segment_height" value="">
                                </div>
                                <div class="form-group col-3">
                                    <input type="text" class="form-control" id="r_mono_pd" placeholder="Mono PD"
                                        wire:model="r_mono_pd" value="">
                                </div>
                                <div class="form-group col-3">
                                    <input type="text" class="form-control" id="r_mono_pd" placeholder="Mono PD"
                                        wire:model="r_mono_pd" value="">
                                </div>
                                <hr>

                            </div>
                        </div>
                        {{-- Left --}}
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="leftEye"
                                        wire:model="leftEye" {{ $leftEye == true ? 'disabled' : '' }}>
                                    <label class="custom-control-label" for="leftEye">Left</label>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="form-group col-4">
                                    <input type="text" class="form-control" id="left_s" placeholder="Sph"
                                        wire:model="l_sphere" value="">
                                </div>
                                <div class="form-group col-4">
                                    <input type="text" class="form-control" id="left_c" placeholder="Cyl"
                                        wire:model="l_cylinder" value="">
                                </div>
                                <div class="form-group col-4">
                                    <input type="text" class="form-control" id="left_x" placeholder="Axis"
                                        wire:model="l_axis" value="">
                                </div>
                                <div class="form-group col-4">
                                    <input type="text" class="form-control" id="left_a" placeholder="Add"
                                        wire:model="l_addition" value="">
                                </div>
                                <div class="form-group col-4">
                                    <input type="text" class="form-control" id="l_segment_height"
                                        placeholder="Seg Height" wire:model="l_segment_height" value="">
                                </div>
                                <div class="form-group col-4">
                                    <input type="text" class="form-control" id="l_mono_pd" placeholder="Mono PD"
                                        wire:model="l_mono_pd" value="">
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Card -->
        </div>

        <div class="col-md-12 col-sm-12 mt-2">
            <div class="card">
                <div class="card-header">
                    Frame
                </div>
                <div class="card-body">
                    <input type="hidden" wire:model='invoice_id'>

                    <div class="row">
                        <!--/span-->
                        <div class="col-md-3 col-sm-12">
                            <label>Frames</label>
                            <div class="form-group">
                                <select class="form-control" wire:model.lazy="type">
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

                        <div class="form-group col-1">
                            <label>Stock</label>
                            <input type="text" class="form-control" id="right_s" placeholder="Stock"
                                wire:model="r_sphere" value="" readonly>
                        </div>

                        <div class="form-group col-1">
                            <label>U. Price</label>
                            <input type="text" class="form-control" id="right_s" placeholder="Unit Price"
                                wire:model="r_sphere" value="" readonly>
                        </div>

                        <div class="form-group col-1">
                            <label>Quantity</label>
                            <input type="text" class="form-control" id="right_s" placeholder="Qty"
                                wire:model="r_sphere" value="">
                        </div>

                        <div class="form-group col-2">
                            <label>Price Adj</label>
                            <input type="text" class="form-control" id="right_s" placeholder="Price Adj"
                                wire:model="r_sphere" value="">
                        </div>

                        <div class="form-group col-2">
                            <label>T.Amount</label>
                            <input type="text" class="form-control" id="right_s" placeholder="Total"
                                wire:model="r_sphere" value="">
                        </div>

                        <div class="form-group col-2">
                            <label>Location</label>
                            <input type="text" class="form-control" id="right_s" placeholder="Location"
                                wire:model="r_sphere" readonly>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Card -->
        </div>

        <div class="col-md-12 col-sm-12 mt-2">
            <div class="card">
                <div class="card-header">
                    Accessories & Other Products
                </div>
                <div class="card-body">
                    <input type="hidden" wire:model='invoice_id'>

                    <div class="row">
                        <!--/span-->
                        <div class="col-md-3 col-sm-12">
                            <label>Products</label>
                            <div class="form-group">
                                <select class="form-control" wire:model.lazy="type">
                                    <option value="">
                                        *** Select Product ***
                                    </option>
                                    @foreach ($lensType as $len_type)
                                        <option value="{{ $len_type->id }}">{{ $len_type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-1">
                            <label>Stock</label>
                            <input type="text" class="form-control" id="right_s" placeholder="Stock"
                                wire:model="r_sphere" value="" readonly>
                        </div>

                        <div class="form-group col-1">
                            <label>U. Price</label>
                            <input type="text" class="form-control" id="right_s" placeholder="Unit Price"
                                wire:model="r_sphere" value="" readonly>
                        </div>

                        <div class="form-group col-1">
                            <label>Quantity</label>
                            <input type="text" class="form-control" id="right_s" placeholder="Qty"
                                wire:model="r_sphere" value="">
                        </div>

                        <div class="form-group col-2">
                            <label>Price Adj</label>
                            <input type="text" class="form-control" id="right_s" placeholder="Price Adj"
                                wire:model="r_sphere" value="">
                        </div>

                        <div class="form-group col-2">
                            <label>T.Amount</label>
                            <input type="text" class="form-control" id="right_s" placeholder="Total"
                                wire:model="r_sphere" value="">
                        </div>

                        <div class="form-group col-2">
                            <label>Location</label>
                            <input type="text" class="form-control" id="right_s" placeholder="Location"
                                wire:model="r_sphere" readonly>
                        </div>
                    </div>


                    <div class="row d-flex justify-content-center items-center mt-4">
                        <button class="btn btn-sm btn-primary">Check Product Availability</button>
                    </div>

                </div>
            </div>
            <!-- Card -->
        </div>

        <div class="col-md-12 col-sm-12 mt-2">
            <div class="card d-flex">
                <div class="card-header d-flex justify-content-between items-center">
                    <span>
                        Insurance / Patient Payment

                    </span>
                    <h5>
                        Total Patient Amount:<span class="text-info"> RWF 50,000</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!--/span-->
                        <div class="d-flex flex-col items-center">
                            <div class="col-md-2 col-sm-12">
                                <label>Lens</label>
                                <br>
                                <span>R</span>
                                <label class="badge badge-danger badge-pill ml-2">N/A
                                </label>
                                <br>
                                <span>L</span>
                                <label class="badge badge-success badge-pill ml-2">Available</label>
                            </div>

                            <div class="form-group col-2">
                                <label>Total Amount</label>
                                <input type="text" class="form-control" id="right_s" placeholder="Total Amount"
                                    wire:model="r_sphere" value="" readonly>
                            </div>

                            <div class="form-group col-2">
                                <label>Insurance Percentage</label>
                                <input type="text" class="form-control" id="right_s" placeholder="Stock"
                                    wire:model="r_sphere" value="">
                            </div>

                            <div class="form-group col-2">
                                <label>Ins Apprv</label>
                                <input type="text" class="form-control" id="right_s" placeholder="Stock"
                                    wire:model="r_sphere" value="">
                            </div>

                            <div class="form-group col-2">
                                <label>Ins Payment</label>
                                <input type="text" class="form-control" id="right_s" placeholder="Stock"
                                    wire:model="r_sphere" value="" readonly>
                            </div>

                            <div class="form-group col-2">
                                <label>Pt Payment</label>
                                <input type="text" class="form-control" id="right_s" placeholder="Stock"
                                    wire:model="r_sphere" value="" readonly>
                            </div>
                        </div>
                    </div>
                    <hr>
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
                                <input type="text" class="form-control" id="right_s" placeholder="Total Amount"
                                    wire:model="r_sphere" value="" readonly>
                            </div>

                            <div class="form-group col-2">
                                <label>Insurance Percentage</label>
                                <input type="text" class="form-control" id="right_s" placeholder="Stock"
                                    wire:model="r_sphere" value="">
                            </div>

                            <div class="form-group col-2">
                                <label>Ins Apprv</label>
                                <input type="text" class="form-control" id="right_s" placeholder="Stock"
                                    wire:model="r_sphere" value="">
                            </div>

                            <div class="form-group col-2">
                                <label>Ins Payment</label>
                                <input type="text" class="form-control" id="right_s" placeholder="Stock"
                                    wire:model="r_sphere" value="" readonly>
                            </div>

                            <div class="form-group col-2">
                                <label>Pt Payment</label>
                                <input type="text" class="form-control" id="right_s" placeholder="Stock"
                                    wire:model="r_sphere" value="" readonly>
                            </div>
                        </div>
                    </div>


                    <div class="row d-flex justify-content-center items-center mt-4">
                        <button class="btn btn-sm btn-success">
                            Submit
                        </button>
                    </div>

                </div>
            </div>
            <!-- Card -->
        </div>

    </div>
</div>

@push('scripts')
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        window.addEventListener('openProductNotFoundModal', event => {
            $("#product-not-found-modal").modal('show');
        })
        window.addEventListener('hideProductNotFoundModal', event => {
            $("#product-not-found-modal").modal('hide');
        })
    </script>
@endpush
