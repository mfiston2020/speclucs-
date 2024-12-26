<div class="col-md-12 col-sm-12">

    <form wire:submit="saveOrder">
        <div class="col-md-12 col-sm-12 mt-2">

            @if (getUserCompanyInfo()->is_vision_center!='1')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h4 class="card-title d-flex justify-content-between col-12" style="justify-content: space-between">
                                Bulk Order
                            </h4>
                        </div>
                    </div>
                </div>
            @endif

            {{-- personal information --}}
            <div class="card border border-dark">
                <div class="card-header">
                    <span class="h4 text-primary"> Information</span>
                </div>

                <div class="card-body">

                    <div class="row">
                        <!--/span-->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Individual Name</label>
                                <input type="text" wire:model.blur="name" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Individual Phone</label>
                                <input type="text" wire:model.blur="phone" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Hospital Name</label>
                                <select class="form-control @error('hospital_name') is-invalid @enderror custom-select"
                                    wire:model='hospital_name' required>
                                    <option value="">** Select Hospital **</option>
                                    @foreach ($visionCenters as $visionCenter)
                                        <option value="{{ $visionCenter->hospital_name }}">{{ $visionCenter->hospital_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hospital_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- <div class="col-md-3">
                            <div class="form-group">
                                <label>Insurance</label>
                                <select class="form-control @error('insurance_type') is-invalid @enderror custom-select"
                                    wire:model.blur='insurance_type' required>
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
                        </div> --}}

                    </div>

                </div>
            </div>

            {{-- lens --}}
            <div class="row">
                <div class="{{$showLens?'col-md-9':'col-md-12'}} col-sm-12">
                    <div class="card border border-dark">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span class="h4 text-primary">Lens</span>
                            <button type="button" wire:click="showProducts('lens')" class="btn btn-sm btn-secondary">
                                @if ($showLens)
                                    <i class="mdi mdi-eye-slash"></i> hide Lens List
                                @else
                                    <i class="mdi mdi-eye"></i> Show Lens List
                                @endif
                                <img wire:loading wire:target="showProducts('lens')" src="{{ asset('dashboard/assets/images/loading2.gif')}}" height="20" alt="">
                            </button>
                        </div>
                        <div class="card-body">

                            @if ($informationMessage)
                                <div class="alert alert-danger font-bold"><i class="fa fa-exclamation-triangle"></i> {{$informationMessage}}</div>
                            @endif
                            <div class="row">
                                <!--/span-->
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <select class="form-control" wire:model.live="lens_type">
                                            <option value="">
                                                *** Select Type ***
                                            </option>
                                            @foreach ($lensType as $len_type)
                                                <option value="{{ $len_type->id }}">{{ $len_type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <select class="form-control" wire:model.blur="lens_coating">
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
                                        <select class="form-control" wire:model.blur='lens_index'>
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
                                        <select class="form-control" wire:model.blur="lens_chromatic">
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
                                                wire:model.blur="rightEye" disabled>
                                            <label class="custom-control-label" for="rightEye">Right</label>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">

                                        <div class="form-group col-3">
                                            <select class="form-control" wire:model.live="r_sign">
                                                <option value="">
                                                    * SIGN *
                                                </option>
                                                <option value="minus">-</option>
                                                <option value="plus">+</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-3">
                                            <select class="form-control" wire:model.live="r_sphere">
                                                <option value="">
                                                    * SPHERE *
                                                </option>
                                                @for ($i=0.00;  $i<= 25.00; $i+=0.25)
                                                    <option value="{{ $i }}">{{ number_format($i,2,'.') }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="form-group col-3">
                                            <select class="form-control" wire:model.live="r_cylinder">
                                                <option value="">
                                                    * CYL *
                                                </option>
                                                @for ($i=0.00;  $i>= -10.00; $i-=0.25)
                                                    <option value="{{ $i }}">{{ number_format($i,2,'.') }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="form-group col-3">
                                            <select class="form-control" wire:model.live="r_axis" {{$hide_r_axis?'disabled':''}}>
                                                <option value="">
                                                    * AXIS *
                                                </option>
                                                @for ($i=0;  $i<= 180; $i+=1)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        @if ( $lens_type!='2' )
                                            <div class="form-group col-3">
                                                <select class="form-control @error('r_addition') is-invalid @enderror" wire:model.live="r_addition">
                                                    <option value="">
                                                        * ADD *
                                                    </option>
                                                    @for ($i=1;  $i<= 4; $i+=0.25)
                                                        <option value="{{ $i }}">{{ number_format($i,2,'.') }}</option>
                                                    @endfor
                                                </select>
                                                @error('r_addition')
                                                    <span class="text-danger">{{$message}}</span>
                                                @enderror
                                            </div>
                                        @endif

                                        <div class="form-group col-4">
                                            <input type="text" class="form-control" id="r_mono_pd" placeholder="Quantity" wire:model.live="right_len_quantity" max="{{$rightLen?($rightLenQty-$rightBooked<0?'0':$rightLenQty-$rightBooked):'0'}}">
                                            @error('right_len_quantity')
                                                <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <hr>

                                    </div>
                                </div>

                                {{-- Left --}}
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="leftEye"
                                                wire:model.live="leftEye" disabled>
                                            <label class="custom-control-label" for="leftEye">Left</label>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="form-group col-3">
                                            <select class="form-control" wire:model.live="l_sign">
                                                <option value="">
                                                    * SIGN *
                                                </option>
                                                <option value="minus">-</option>
                                                <option value="plus">+</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-3">
                                            <select class="form-control" wire:model.live="l_sphere">
                                                <option value="">
                                                    * SPHERE *
                                                </option>
                                                @for ($i=0.00;  $i<= 25.00; $i+=0.25)
                                                    <option value="{{ $i }}">{{ number_format($i,2,'.') }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="form-group col-3">
                                            <select class="form-control" wire:model.live="l_cylinder">
                                                <option value="">
                                                    * CYL *
                                                </option>
                                                @for ($i=0.00;  $i>= -10.00; $i-=0.25)
                                                    <option value="{{ $i }}">{{ number_format($i,2,'.') }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="form-group col-3">
                                            <select class="form-control" wire:model.live="l_axis" {{$hide_l_axis?'disabled':''}}>
                                                <option value="">
                                                    * AXIS *
                                                </option>
                                                @for ($i=0;  $i<= 180; $i+=1)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        @if ($lens_type!='2')
                                            <div class="form-group col-3">
                                                <select class="form-control @error('l_addition') is-invalid @enderror" wire:model.live="l_addition">
                                                    <option value="">
                                                        * ADD *
                                                    </option>
                                                    @for ($i=1;  $i<= 4; $i+=0.25)
                                                        <option value="{{ $i }}">{{ number_format($i,2,'.') }}</option>
                                                    @endfor
                                                </select>
                                                @error('l_addition')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        @endif

                                        <div class="form-group col-4">
                                            <input type="text" class="form-control" id="r_mono_pd" placeholder="Quantity" wire:model.live="left_len_quantity" max="{{$leftLen?($leftLenQty-$leftBooked<0?'0':$leftLenQty-$leftBooked):'0'}}">
                                            @error('left_len_quantity')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <hr>

                             @if ($rightLen || $leftLen)
                                 <div class="col-md-12 col-sm-12 d-flex justify-content-around">
                                    <div>
                                        {{-- <label>Lens</label> --}}
                                        <br>
                                        <h5>
                                            <strong class="mb-3">RIGHT</strong>
                                        </h5>
                                        <br>
                                        @if ($rightLen)
                                            <h6>Stock: <span class="text-primary">{{$rightLenQty}}</span> |
                                                Requested: <span class="text-primary">{{is_null($rightBooked)?0:$rightBooked}}</span> |
                                                Available: <span class="text-primary">
                                                    {{$rightLenQty-$rightBooked<0?'0':$rightLenQty-$rightBooked}}
                                                </span>
                                            </h6>
                                            <br>

                                            <button type="button" wire:click="addProductToList('lens','right')" class="btn btn-sm btn-primary mr-4">
                                                <span wire:loading.remove wire:target="addProductToList('lens','right')">Add Right Lens To List</span>
                                                <img wire:loading wire:target="addProductToList('lens','right')" src="{{ asset('dashboard/assets/images/loading2.gif')}}" height="20" alt="">
                                            </button>
                                        @else
                                            <label class="badge badge-danger badge-pill ml-2">N/A</label>
                                        @endif
                                    </div>
                                    <div>
                                        <br>
                                            <h5>
                                                <strong class="mb-3">LEFT</strong>
                                            </h5>
                                        <br>
                                        @if ($leftLen)
                                            <h6>Stock: <span class="text-primary">{{ $leftLenQty }}</span> |
                                                Requested:
                                                <span class="text-primary">{{ is_null($leftBooked)?'0':$leftBooked }}</span> |
                                                Available:
                                                <span class="text-primary">
                                                    {{ $leftLenQty-$leftBooked<0?'0':$leftLenQty-$leftBooked }}
                                                </span>
                                            </h6>
                                            <br>
                                            <button type="button" wire:click="addProductToList('lens','left')" class="btn btn-sm btn-primary mr-4">
                                                <span wire:loading.remove wire:target="addProductToList('lens','left')">Add Left Lens To List</span>
                                                <img wire:loading wire:target="addProductToList('lens','left')" src="{{ asset('dashboard/assets/images/loading2.gif')}}" height="20" alt="">
                                            </button>
                                        @else
                                            <label class="badge badge-danger badge-pill ml-2">N/A</label>
                                        @endif
                                    </div>
                                </div>
                             @endif

                            <hr>
                            <div class="d-flex justify-content-between">

                                <button type="button" wire:click="checkProduct('lens')" class="btn btn-sm btn-outline-success">
                                    <span wire:loading.remove wire:target="checkProduct('lens')">Check Lens</span>
                                    <img wire:loading wire:target="checkProduct('lens')" src="{{ asset('dashboard/assets/images/loading2.gif')}}" height="20" alt="">
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                @if ($showLens)
                    <div class="col-md-3 col-sm-12 overflow-auto" style="padding: 0;height:20rem">
                        <div class="card-header d-flex justify-content-between">
                            <span class="h4 text-primary fixed">Lens List</span>
                            <strong>
                                Total Amount:<span class="text-info">
                                    {{ format_money($totalLens) }}
                                </span>
                            </strong>
                        </div>

                        @for ($i = 0; $i < count($addedLensList); $i++)
                            <div class="bg-white mt-2 p-3 d-flex" style="border-left: {{$i%2==0?'#563DEA':'#36BDA6'}} 3px solid; border-top-left-radius: .3rem; border-bottom-left-radius: .3rem; justify-content: space-between;">

                                <div class="d-flex flex-column">
                                    <strong>{{$addedLensList[$i]['product']->description}} - [{{$addedLensList[$i]['eye']}}] <br>
                                        SPH: {{ $addedLensList[$i]['product']->power->sphere }} - CYL: {{ $addedLensList[$i]['product']->power->cylinder }}
                                        @if (initials($addedLensList[$i]['product']->product_name)!="SV")
                                            <br>
                                            AXIS: {{ $addedLensList[$i]['product']->power->axis }} - ADD: {{ $addedLensList[$i]['product']->power->add }}
                                        @endif
                                    </strong>
                                    <span class="mt-2"><strong>QTY:</strong> {{$addedLensList[$i]['quantity']}}</span>
                                    <span class="mt-2"><strong>Price:</strong> {{format_money($addedLensList[$i]['product']->price)}}</span>
                                </div>
                                <div>
                                    <button type="button" wire:remove wire:target="removeProductFromList({{$i}},'lens')" wire:click="removeProductFromList({{$i}},'lens')" class="btn btn-sm btn-outline-danger rounded-circle align-items-start">x</button>
                                    <img wire:loading wire:target="removeProductFromList({{$i}},'lens')" src="{{ asset('dashboard/assets/images/loading2.gif')}}" height="20" alt="">
                                </div>
                                
                            </div>
                        @endfor
                        </div>
                    </div>
                @endif
                <!-- Card -->
            </div>

            {{-- frame div --}}
            <div class="row">
                <div class="{{$showFrames?'col-md-9':'col-md-12'}} col-sm-12">
                    <div class="card border border-dark">
                        <div class="card-header">
                            <span class="h4 text-primary">Frame</span>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <!--/span-->
                                <div class="col-md-3 col-sm-12">
                                    <label>Frames</label>
                                    <div class="form-group">
                                        <select class="form-control custom-select" style="width: 100%; height:2rem;"  id="frame" wire:model.live="frame">
                                            <option value="">
                                                *** Select Type ***
                                            </option>
                                            @foreach ($frameList as $frame)
                                                <option value="{{ $frame->id }}">{{ $frame->product_name }} |
                                                    {{ $frame->description }}
                                                </option>
                                            @endforeach
                                        </select>
                                    <img src={{asset('dashboard/assets/images/loading2.gif')}} width="20" wire:loading wire:target="frame"/>
                                    </div>
                                </div>

                                <div class="form-group col-1">
                                    <label>Stock</label>
                                    <input type="text"
                                        class="form-control {{ $frame_stock == 0 && $frame_stock != null ? 'border border-danger' : '' }}"
                                        id="frame_stock" placeholder="Stock" wire:model.blur="frame_stock" readonly>
                                </div>

                                <div class="form-group col-1">
                                    <label>U. Price</label>
                                    <input type="text" class="form-control" id="frame_unit_price"
                                        placeholder="Unit Price" wire:model.blur="frame_unit_price" readonly>
                                </div>

                                <div class="form-group col-2">
                                    <label>Quantity</label>
                                    <input type="number" class="form-control @error('frame_quantity') is-invalid @enderror" id="frame_quantity" placeholder="Qty"
                                        wire:model.blur="frame_quantity" max="{{$frame_stock}}">
                                </div>

                                <div class="form-group col-1">
                                    <label>Price Adj</label>
                                    <input type="number" class="form-control" id="frame_price_adjust"
                                        placeholder="Price Adj" wire:model.live="frame_price_adjust">
                                </div>

                                <div class="form-group col-2">
                                    <label>T.Amount</label>
                                    <input type="text" class="form-control" id="frame_total_amount"
                                        placeholder="Total" readonly>
                                </div>

                                <div class="form-group col-2">
                                    <label>Location</label>
                                    <input type="text" class="form-control" id="frame_location"
                                        placeholder="Location" wire:model.blur="frame_location" readonly>
                                </div>
                            </div>

                                @error('frame_quantity')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            @if (!is_null($frame_stock))
                                <hr>
                                <div class="row">
                                    <!--/span-->

                                    <div class="form-group col-2">
                                        <label>Stock Booked</label>
                                        <h5 class="text-primary">{{number_format($ordered_frames)}}</h5>
                                    </div>

                                    <div class="form-group col-3">
                                        <label>Stock Available for orders</label>
                                        <h5 class="text-primary">
                                            {{(int)$frame_stock-(int)$ordered_frames}}
                                        </h5>
                                    </div>
                                </div>
                                <br>

                                @if ($frame_stock>0)
                                    <button type="button" wire:click="addProductToList('frame','right')" class="btn btn-sm btn-primary mr-4">
                                        <span wire:loading.remove wire:target="addProductToList('frame','right')">Add Frame To List</span>
                                        <img wire:loading wire:target="addProductToList('frame','right')" src="{{ asset('dashboard/assets/images/loading2.gif')}}" height="20" alt="">
                                    </button>
                                @endif
                            @endif

                        </div>
                    </div>
                    <!-- Card -->
                </div>
                @if ($showFrames)
                    <div class="col-md-3 col-sm-12 overflow-auto" style="padding: 0;height:{{count($addFrameList)>0?'20rem':'11rem'}}">
                        <div class="card-header d-flex justify-content-between">
                            <span class="h4 text-primary fixed">Frame List</span>
                            <strong>
                                Total Amount:<span class="text-info">
                                    {{ format_money($totalFrames) }}
                                </span>
                            </strong>
                        </div>

                        @for ($i = 0; $i < count($addFrameList); $i++)
                            <div class="bg-white mt-2 p-3 d-flex" style="border-left: {{$i%2==0?'#563DEA':'#36BDA6'}} 3px solid; border-top-left-radius: .3rem; border-bottom-left-radius: .3rem; justify-content: space-between;">

                                <div class="d-flex flex-column">
                                    <strong>{{$addFrameList[$i]['product']->product_name.'|'.$addFrameList[$i]['product']->description}} <br>
                                    </strong>
                                    <span class="mt-2"><strong class="text-primary">QTY:</strong> {{$addFrameList[$i]['quantity']}}</span>
                                    <span class="mt-2"><strong>Price:</strong> {{format_money($addFrameList[$i]['product']->price)}}</span>
                                </div>
                                <div>
                                    <button type="button" wire:remove wire:target="removeProductFromList({{$i}},'frame')" wire:click="removeProductFromList({{$i}},'frame')" class="btn btn-sm btn-outline-danger rounded-circle align-items-start">x</button>
                                    <img wire:loading wire:target="removeProductFromList({{$i}},'frame')" src="{{ asset('dashboard/assets/images/loading2.gif')}}" height="20" alt="">
                                </div>
                                
                            </div>
                        @endfor
                    </div>
                @endif
            </div>

            {{-- accessories div --}}
            <div class="row">
                <div class="{{$showAccessories?'col-md-9':'col-md-12'}} col-sm-12 mt-2">
                    <div class="card border border-dark">
                        <div class="card-header">
                            <span class="h4 text-primary">Accessories & Other Products</span>
                            
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 row">

                                    <!--/span-->
                                    <div class="col-md-3 col-sm-12">
                                        <label>Products</label>
                                        <div class="form-group">
                                            <select class="form-control custom-select" id="accessory" style="width: 100%; height:2rem;" wire:model.live="accessory">
                                                <option value="">
                                                    *** Select Product ***
                                                </option>
                                                @foreach ($accessoriesList as $accessory)
                                                    <option value="{{ $accessory->id }}">
                                                        {{ $accessory->product_name }} | {{ $accessory->description }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <img src={{asset('dashboard/assets/images/loading2.gif')}} width="20" wire:loading wire:target="accessory"/>
                                        </div>
                                    </div>

                                    <div class="form-group col-1">
                                        <label>Stock</label>
                                        <input type="text"
                                            class="form-control {{ $accessory_stock <= 0 && $accessory_stock != null ? 'border border-danger' : '' }}"
                                            id="accessory_stock" placeholder="Stock" wire:model.blur="accessory_stock"
                                            readonly>
                                    </div>

                                    <div class="form-group col-1">
                                        <label>U. Price</label>
                                        <input type="text" class="form-control border-red-500" id="accessory_unit_price"
                                            placeholder="Unit Price" wire:model.blur="accessory_unit_price" readonly>
                                    </div>

                                    <div class="form-group col-1">
                                        <label>Quantity</label>
                                        <input type="number" class="form-control" id="accessory_quantity" placeholder="Qty"
                                            wire:model.live="accessory_quantity" max="{{$accessory_quantity}}" min="1">
                                    </div>

                                    <div class="form-group col-2">
                                        <label>Price Adj</label>
                                        <input type="number" class="form-control" id="accessory_price_adjust"
                                            placeholder="Price Adj" wire:model.live="accessory_price_adjust">
                                    </div>

                                    <div class="form-group col-2">
                                        <label>T.Amount</label>
                                        <input type="number" class="form-control" id="accessory_total_amount"
                                            placeholder="Total" readonly>
                                    </div>

                                    <div class="form-group col-2">
                                        <label>Location</label>
                                        <input type="text" class="form-control" id="accessory_location"
                                            placeholder="Location" wire:model.blur="accessory_location" readonly>
                                    </div>
                                </div>


                                @error('accessory_quantity')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <br>

                            <div class=" d-flex justify-content-between">
                                @if ($accessory_stock>0)
                                    <button type="button" wire:click="addProductToList('accessory','right')" class="btn btn-sm btn-primary mr-4">
                                        <span wire:loading.remove wire:target="addProductToList('accessory','right')">Add Accessory To List</span>
                                        <img wire:loading wire:target="addProductToList('accessory','right')" src="{{ asset('dashboard/assets/images/loading2.gif')}}" height="20" alt="">
                                    </button>
                                @endif

                                <button type="button" wire:click="showTotal" class="btn btn-sm btn-success mr-4">
                                    <span wire:loading.remove wire:target="showTotal">Show Total</span>
                                    <img wire:loading wire:target="showTotal" src="{{ asset('dashboard/assets/images/loading2.gif')}}" height="20" alt="">
                                </button>
                            </div>

                        </div>
                    </div>
                    <!-- Card -->
                </div>

                @if ($showAccessories)
                    <div class="col-md-3 col-sm-12 overflow-auto mt-3" style="padding: 0;height:{{ count($addedAccessoriesList)>0?'20rem':'11rem'}}">
                        <div class="card-header d-flex justify-content-between">
                            <span class="h4 text-primary fixed">Accessories List</span>
                            <strong>
                                Tot. Amt:<span class="text-info">
                                    {{ format_money($totalAccessories) }}
                                </span>
                            </strong>
                        </div>

                        @for ($i = 0; $i < count($addedAccessoriesList); $i++)
                            <div class="bg-white mt-2 p-3 d-flex" style="border-left: {{$i%2==0?'#563DEA':'#36BDA6'}} 3px solid; border-top-left-radius: .3rem; border-bottom-left-radius: .3rem; justify-content: space-between;">

                                <div class="d-flex flex-column">
                                    <strong>{{$addedAccessoriesList[$i]['product']->product_name.' | '.$addedAccessoriesList[$i]['product']->description}} <br>
                                    </strong>
                                    <span class="mt-2"><strong class="text-primary">QTY:</strong> {{$addedAccessoriesList[$i]['quantity']}}</span>
                                    <span class="mt-2"><strong>Price:</strong> {{format_money($addedAccessoriesList[$i]['product']->price)}}</span>
                                </div>
                                <div>
                                    <button type="button" wire:remove wire:target="removeProductFromList({{$i}},'accessory')" wire:click="removeProductFromList({{$i}},'accessory')" class="btn btn-sm btn-outline-danger rounded-circle align-items-start">x</button>
                                    <img wire:loading wire:target="removeProductFromList({{$i}},'accessory')" src="{{ asset('dashboard/assets/images/loading2.gif')}}" height="20" alt="">
                                </div>
                                
                            </div>
                        @endfor
                    </div>
                @endif
            </div>

            @if ($showPaymentSection)
                <div class="row">
                    <div class="col-md-12 col-sm-12 mt-">
                        <div class="card d-flex border border-dark">

                            <div class="card-header d-flex justify-content-between items-center">
                                {{-- <span> --}}
                                    <span class="h4 text-primary">Product Listing</span>

                                {{-- </span> --}}
                                <h5>
                                    Total Amount:<span class="text-info">
                                        {{ format_money($totalAccessories+$totalFrames+$totalLens) }}
                                    </span>
                                </h5>
                            </div>

                            <div class="card-body">


                                <div class="row d-flex justify-content-around items-center mt-4">
                                    
                                    <button class="btn btn-sm btn-success">
                                        Submit
                                    </button>

                                </div>

                            </div>

                        </div>
                        <!-- Card -->
                    </div>
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
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/libs/select2/dist/css/select2.min.css')}}">
@endpush

@push('scripts')
    <script src="{{ asset('dashboard/assets/libs/select2/dist/js/select2.full.min.js')}}"></script>
    <script src="{{ asset('dashboard/assets/libs/select2/dist/js/select2.min.js')}}"></script>
    <script src="{{ asset('dashboard/assets/dist/js/pages/forms/select2/select2.init.js')}}"></script>
    <script>
        $wire.on('showwarningModal', () => {
            $('#warningModal').modal('show');
        });

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
    </script>
@endpush
