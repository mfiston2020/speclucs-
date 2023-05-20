<div class="row col-12">

    <div class="col-md-8 col-sm-12">
        <!-- Row -->
        <div class="row">

            <div class="col-md-6 col-sm-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        Customer Information
                    </div>
                    <div class="card-body">

                        <div class="form-group mb-4">
                            <select class="form-control" wire:model="customerType">
                                <option>
                                    *** Select Type ***
                                </option>
                                <option value="existing">
                                    Wholesale
                                </option>
                                <option value="new">
                                    Retail
                                </option>
                            </select>
                        </div>

                        @if ($customerType=='existing')
                        <div class="form-group">
                            <select class="form-control" wire:model="myCustomers">

                                <option>*** Select Customer ***</option>

                                @foreach ($existingCustomer as $customer)

                                <option value="{{$customer->id}}">{{$customer->name}}</option>

                                @endforeach
                            </select>
                        </div>
                        @endif

                        {{-- <img src="{{ asset('dashboard/assets/images/loading.gif')}}" wire:loading wi width="30"
                        alt=""> --}}
                    </div>
                </div>
                <!-- Card -->
            </div>

            @if ($customerType)
                <div class="col-md-6 col-sm-12 mt-4">
                    <div class="card">
                        <div class="card-header">
                            Product Type
                        </div>
                        <div class="card-body">

                            <div class="form-group mb-4">
                                <select class="form-control" wire:model="productType">
                                    <option>
                                        *** Select Product ***
                                    </option>
                                    <option value="lens">
                                        Lens
                                    </option>
                                    <option value="others">
                                        Others
                                    </option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <!-- Card -->
                </div>
            @endif

        </div>

        @if ($customerType)
        <!-- Row -->
        <div class="row">

            <div class="col-md-12 col-sm-12 mt-2">
                <div class="card">
                    <div class="card-header">
                        Customer Information
                    </div>
                    <div class="card-body">

                        @if ($customerType=='new')

                        <div class="row">
                            <!--/span-->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Firstname</label>
                                    <input type="text" wire:model.lazy="firstname" class="form-control" required="">
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Lastname</label>
                                    <input type="text" wire:model.lazy="lastname" class="form-control" required="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select class="form-control @error('gender') is-invalid @enderror custom-select" wire:model.lazy='gender'>
                                        <option>--Select your Gender--</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                    @error('gender') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date Of Birth</label>
                                    <input type="date" max="{{date('Y-m-d')}}" wire:model.lazy="date_of_birth" class="form-control" required="">
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tin Number</label>
                                    <input type="text" wire:model.lazy="tin_number" class="form-control" required="">
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" wire:model.lazy="phone" class="form-control" required="">
                                </div>
                            </div>
                        </div>

                        @endif

                        @if ($customerType=='existing' && $myCustomers && $singleExistingCustomer)

                        <div class="row">
                            <!--/span-->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" value="{{$singleExistingCustomer->name}}" class="form-control"
                                        readonly>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" value="{{$singleExistingCustomer->email}}" class="form-control"
                                        readonly>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" value="{{$singleExistingCustomer->phone}}" class="form-control"
                                        readonly>
                                </div>
                            </div>
                        </div>

                        @endif

                    </div>
                </div>
                <!-- Card -->
            </div>

            @if ($productType)

            @if ($productType=='lens')

            <form wire:submit.prevent="saveSoldProduct" id="saveProduct1">
                <div class="col-md-12 col-sm-12 mt-2">
                    <div class="card">
                        <div class="card-header">
                            Lens Characteristics
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
                                            <option value="{{$len_type->id}}">{{$len_type->name}}</option>
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
                                            <option value="{{$len_coating->id}}">{{$len_coating->name}}</option>
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
                                            <option value={{$len_index->id}}>{{$len_index->name}}</option>
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
                                            <option value="{{$len_chromatic->id}}">{{$len_chromatic->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- Card -->
                </div>

                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"> Eye</h4>
                            <br>
                            <div class="d-flex justify-content-between">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="rightEye"
                                        wire:model="rightEye" {{$leftEye==true?'disabled':''}}>
                                    <label class="custom-control-label" for="rightEye">Right Eye Only</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="leftEye"
                                        wire:model="leftEye" {{$rightEye==true?'disabled':''}}>
                                    <label class="custom-control-label" for="leftEye">Left Eye Only</label>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="form-group col-3">
                                    <input type="text" class="form-control" id="right_s" placeholder="Sphere"
                                        wire:model="sphere" value="">
                                </div>
                                <div class="form-group col-3">
                                    <input type="text" class="form-control" id="right_c" placeholder="Cylinder"
                                        wire:model="cylinder" value="">
                                </div>
                                <div class="form-group col-3">
                                    <input type="text" class="form-control" id="right_x" placeholder="Axis"
                                        wire:model="axis" value="">
                                </div>
                                <div class="form-group col-3">
                                    <input type="text" class="form-control" id="right_a" placeholder="Addition"
                                        wire:model="addition" value="">
                                </div>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-end">
                                <span class="mt-1 mr-3 text-danger" x-data="{ show: false }" x-init="@this.on('product-not-found',() => {
                                                                setTimeout(() => { show = false },2500);
                                                                show = true }) "
                                    x-show.transition.out.duration.1000ms="show" }>

                                    Product not found!!
                                </span>
                                <button type="button" class="btn btn-warning waves-effect float-right"
                                    wire:click="searchPrice(0)">
                                    Search Product
                                    <img wire:loading wire:target="searchPrice"
                                        src="{{ asset('dashboard/assets/images/loading2.gif')}}" width="20" alt="">
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($showProductDetails)
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">

                                <div class="form-group row">
                            @if ($lensProduct)
                                <input type="hidden" wire:model="productID">
                                    <div class="col-sm-12 col-md-2">
                                    <label for="stock" class="text-right control-label col-form-label">
                                        Unit Price
                                    </label>
                                        <input type="text" wire:model="product_unit_price" class="form-control" readonly
                                            required>
                                    </div>

                                    <div class="col-sm-12 col-md-2">
                                    <label for="stock"
                                        class="text-right control-label col-form-label">Stock</label>
                                        <input type="text" wire:model="product_stock" class="form-control" readonly
                                            required>
                                    </div>
                                @endif

                                    <div class="col-sm-12 col-md-2">
                                    <label for="fstock" class=" text-right control-label col-form-label">Quantity
                                        <span id="left" style="color: red"></span>
                                    </label>
                                        <input type="number"
                                            class="form-control @error('proquantity')  is-invalid @enderror" placeholder="0"
                                            wire:model.debounce.500ms="proquantity" min="1" max="{{$product_stock}}">
                                            <span class="text-info" wire:loading wire:target=proquantity>
                                                Calculating...
                                            </span>
                                    </div>

                                    <div class="col-sm-12 col-md-3">
                                        <label for="discount" class="text-right control-label col-form-label invalid">
                                            Price Adj.
                                        </label>
                                        <input type="text" class="form-control @error('prodiscount')  is-invalid @enderror"
                                            id="discount" placeholder="0" wire:model.debounce.500ms="prodiscount">
                                            <span class="text-info" wire:loading wire:target=prodiscount>
                                                Calculating...
                                            </span>
                                    </div>

                                    <div class="col-sm-12 col-md-3">
                                        <label for="cost" class="text-right control-label col-form-label invalid">
                                            Total Amount
                                        </label>
                                        <input type="text" class="form-control" id="total_amount" placeholder="0"
                                            wire:model.debounce.500ms="product_total_amount" readonly>
                                    </div>
                                </div>


                                    <hr>
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                            <label for="discount"
                                                class="text-right control-label col-form-label invalid">Insurance</label>
                                                <select class="form-control form-select" required wire:model=insurance_type>
                                                    <option value="">** Select Type **</option>
                                                    <option value="private">private</option>
                                                    @if (count($allInsurances)>0)

                                                        @foreach ($allInsurances as $insurance)
                                                            <option value="{{ $insurance->id }}">{{ $insurance->insurance_name }}</option>
                                                        @endforeach

                                                    @endif
                                                </select>
                                            </div>

                                        @if (!$hide_insurance_details)
                                            <div class="col-sm-3">
                                                <label for="cost" class="text-right control-label col-form-label invalid">
                                                    Ins. Percentage
                                                </label>
                                                <input type="number" class="form-control @error('insurance_percentage')  is-invalid @enderror"
                                                id="insurance_percentage" max="100" min="0"  placeholder="0" wire:model.debounce.500ms="insurance_percentage">
                                            </div>

                                            <div class="col-sm-3">
                                                <label for="cost" class="text-right control-label col-form-label invalid">
                                                    Ins. Card Number
                                                </label>
                                                <input type="text" class="form-control @error('insurance_number')  is-invalid @enderror"
                                                id="insurance_number"  wire:model.debounce.500ms="insurance_number">
                                            </div>

                                            <div class="col-sm-3">
                                            <label for="cost" class="text-right control-label col-form-label invalid">
                                                Ins. Approved Amount
                                            </label>
                                                <input type="number" class="form-control @error('approved_amount')  is-invalid @enderror"
                                                id="approved_amount" min="0"  placeholder="0" wire:model.debounce.500ms="approved_amount">
                                            </div>
                                        </div>
                                        <button type="button" wire:click="approvedAmount" class="btn btn-default waves-effect waves-light">
                                            Calculate
                                        </button>
                                        <hr>

                                    @endif

                                    {{-- @if ($hide_insurance_details)
                                        </div>
                                    @endif --}}


                                <div class="form-group row">
                                    <div class="col-sm-6">
                                    <label for="cost" class="text-right control-label col-form-label invalid">
                                        Insurance Payment
                                    </label>
                                        <input type="text" class="form-control" id="total_amount" placeholder="0"
                                            wire:model.debounce.500ms="insurance_payment" readonly>
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="cost" class="text-right control-label col-form-label invalid">
                                            Client Payment
                                        </label>
                                        <input type="text" class="form-control" id="total_amount" placeholder="0"
                                            wire:model.debounce.500ms="patient_payment" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                @if ($patient_payment!=0)
                                    <button type="submit" class="btn btn-default waves-effect waves-light">
                                        Add Product
                                    </button>
                                @endif
                                <span class="mt-1 mr-3 text-success" x-data="{ show: false }" x-init="@this.on('product-added',() => {
                                                                    setTimeout(() => { show = false },2500);
                                                                    show = true }) "
                                    x-show.transition.out.duration.1000ms="show" }>

                                    Product Added!!
                                </span>
                            </div>
                        </div>
                    </div iv>
                @endif
            </form>

            @endif

                @if ($productType=='others')
                    <div class="col-md-12 col-sm-12">
                        <form wire:submit.prevent="saveSoldProduct" id="saveProduct2">
                            <div class="card" id="non-lens">
                                <div class="card-header bg-info">
                                    <h4 class="m-b-0 text-white">Product Information</h4>
                                </div>
                                <br>

                                {{-- <input type="hidden" value="{{$id}}" name="invoice_id"> --}}

                                <div class="form-group row">
                                    <label for="pname" class="col-sm-3 text-right control-label col-form-label">Product
                                    </label>
                                    <div class="col-sm-9">
                                        <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                                            name="product" wire:model="productSelected">
                                            <option value="">Select</option>
                                            @foreach ($nonlensProducts as $nonlensProducts)
                                            <option value="{{$nonlensProducts->id}}">
                                                {{$nonlensProducts->product_name}} | {{$nonlensProducts->description}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                @if ($productSelected)
                                <input type="hidden" wire:model="productID">
                                <input type="hidden" wire:model='invoice_id'>


                                <div class="form-group row">
                                    <label for="stock" class="col-sm-3 text-right control-label col-form-label">Stock</label>
                                    <div class="col-sm-9">
                                        <input type="text" wire:model="product_stock" class="form-control" placeholder="0"
                                            readonly required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="stock" class="col-sm-3 text-right control-label col-form-label">Unit
                                        Price</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="0" wire:model="product_unit_price"
                                            readonly required>
                                    </div>
                                </div>
                                @endif

                                <div class="form-group row">
                                    <label for="fstock" class="col-sm-3 text-right control-label col-form-label">Quantity
                                        <span id="left2" style="color: red"></span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control @error('proquantity') is-invalid @enderror"
                                                wire:model.debounce.500ms="proquantity"
                                            placeholder="quantity" min="1">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="discount2" class="col-sm-3 text-right control-label col-form-label">discount
                                        <span id="left2" style="color: red"></span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control @error('prodiscount') is-invalid @enderror"
                                                wire:model.debounce.500ms="prodiscount"
                                            placeholder="discount">
                                        <span class="text-warning mt-2" wire:loading
                                            wire:target="proquantity">calculating...</span>
                                        <span class="text-warning mt-2" wire:loading
                                            wire:target="prodiscount">calculating...</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="cost" class="col-sm-3 text-right control-label col-form-label invalid">Total
                                        Amount</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" wire:model="product_total_amount"
                                            placeholder="0" readonly>
                                    </div>
                                </div>


                                    <hr>
                                        <div class="form-group row">
                                            <label for="discount"
                                                class="col-sm-4 text-right control-label col-form-label invalid">Insurance</label>
                                            <div class="col-sm-8">
                                                <select class="form-control form-select" required wire:model=insurance_type>
                                                    <option value="">** Select Type **</option>
                                                    <option value="private">private</option>
                                                    @if (count($allInsurances)>0)

                                                        @foreach ($allInsurances as $insurance)
                                                            <option value="{{ $insurance->id }}">{{ $insurance->insurance_name }}</option>
                                                        @endforeach

                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                        @if (!$hide_insurance_details)
                                        <div class="form-group row">
                                            <label for="cost" class="col-sm-4 text-right control-label col-form-label invalid">
                                                Insurance Percentage
                                            </label>
                                            <div class="col-sm-8">
                                                <input type="number" class="form-control @error('insurance_percentage')  is-invalid @enderror"
                                                id="insurance_percentage" max="100" min="0"  placeholder="0" wire:model.debounce.500ms="insurance_percentage" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="cost" class="col-sm-4 text-right control-label col-form-label invalid">
                                                Ins. Approved Amount
                                            </label>
                                            <div class="col-sm-8">
                                                <input type="number" class="form-control @error('approved_amount')  is-invalid @enderror"
                                                id="approved_amount" min="0"  placeholder="0" wire:model.debounce.500ms="approved_amount">
                                    <button type="button" wire:click="approvedAmount" class="btn btn-default waves-effect waves-light mt-4">
                                        Calculate
                                    </button>
                                            </div>
                                        </div>
                                    <hr>


                                    <div class="form-group row">
                                        <label for="cost" class="col-sm-4 text-right control-label col-form-label invalid">
                                            Insurance Payment
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="total_amount" placeholder="0"
                                                wire:model.debounce.500ms="insurance_payment" readonly>
                                        </div>
                                    </div>

                                <div class="form-group row">
                                    <label for="cost" class="col-sm-4 text-right control-label col-form-label invalid">
                                        Client Payment
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="total_amount" placeholder="0"
                                            wire:model.debounce.500ms="patient_payment" readonly>
                                    </div>
                                </div>

                                @endif
                                <hr>
                                @if ($product_stock!=0)
                                <div class="card-body">
                                    <div class="form-group m-b-0 text-center">
                                        <button type="submit" class="btn btn-info waves-effect waves-light">Save</button>
                                        <span class="mt-1 mr-3 text-success" x-data="{ show: false }" x-init="@this.on('product-added',() => {
                                                                                    setTimeout(() => { show = false },2500);
                                                                                    show = true }) "
                                            x-show.transition.out.duration.1000ms="show" }>

                                            Product Added!!
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </form>
                    </div>
                @endif

            @endif

        </div>
        @endif
    </div>

    <div class="col-md-4 col-sm-12 fix-sticky">

        <div class="col-md-12 col-sm-12 mt-4">
            <div class="card">

                <div class="card-header">
                    Product List
                </div>

                <div class="card-body custom-border">

                    @if (count($invoiceProduct)>0)

                    {{-- <ul class="list-style-none"> --}}
                        @foreach ($invoiceProduct as $product)
                        <div class="todo-item all-todo-list p-3 border-bottom position-relative">
                            <div class="inner-item d-flex align-items-start">
                                <div class="w-100">
                                    <?php
                                        $prod=\App\Models\Product::where(['id'=>$product->product_id])->where('company_id',Auth::user()->company_id)->select('*')->first();
                                    ?>
                                    <!-- Checkbox -->
                                    <div class="form-check d-flex justify-content-between">
                                        {{-- <input type="checkbox" class="form-check-input flex-shrink-0" id="checkbox1">
                                        <label class="form-check-label" for="checkbox1"></label> --}}
                                        <div>
                                            <div class="content-todo ms-3">
                                                <h5 class="font-medium fs-4 todo-header mb-0"
                                                    data-todo-header="Meeting with Mr.Jojo Sukla at 5.00PM">
                                                    {{$prod->product_name}}
                                                    <span class="text-warning">[{{$product->quantity}}]</span>
                                                </h5>
                                                <span class="todo-time fs-2 text-muted d-flex align-items-center">
                                                    {{-- <small class="text-muted"> --}}
                                                        @if($power=\App\Models\Power::where(['product_id'=>$product->product_id])->where('company_id',Auth::user()->company_id)->select('*')->first())
                                                            @if (initials($prod->product_name)=='SV')
                                                                <span> {{$power->sphere}} / {{$power->cylinder}}</span>
                                                            @else
                                                                <span>{{$power->sphere}} / {{$power->cylinder}} *{{$power->axis}}
                                                                    {{$power->add}}</span>
                                                            @endif
                                                        @endif
                                                    {{-- </small> --}}
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <button wire:click="removeProduct({{$product->id}})" class="btn btn-link text-dark p-1 text-decoration-none" >
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Content -->
                                </div>
                            </div>
                        </div>
                        @endforeach
                    {{-- </ul> --}}
                    @else
                    <span class="text-warning">No product added yet!</span>
                    @endif

                </div>
            </div>
            <div class="mt-1">
                <button type="button" class="btn btn-warning waves-effect">Close</button>
                <a href="{{route('manager.sales.edit',Crypt::encrypt($invoice_id))}}" type="submit"
                    class="btn btn-default waves-effect waves-light">
                    Complete Sales
                </a>
            </div>
        </div>
    </div>


    <div id="product-not-found-modal" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"><i
                            class="fa fa-exclamation-triangle"></i> {{ __('manager/sales.product_not_found')}}</h4>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <h4>{{ __('manager/sales.next_step')}} </h4>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-info waves-effect" wire:click="add_pending_product">
                        <span wire:loading.remove wire:target='add_pending_product'>
                            {{ __('manager/sales.stock_request')}}
                        </span>
                        <span wire:loading wire:target='add_pending_product'>
                            <img src="{{ asset('dashboard/assets/images/loading2.gif')}}" height="20" alt="">{{ __('manager/sales.request_processing')}}
                        </span>
                    </button>
                    <button type="button" class="btn btn-danger waves-effect"
                        data-dismiss="modal">{{__('manager/sales.cancel')}}</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

</div>

@push('scripts')
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        window.addEventListener('openProductNotFoundModal', event => {
            $("#product-not-found-modal").modal('show');
        })
    </script>
@endpush
