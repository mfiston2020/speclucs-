
<div>
    <form action="{{ route('manager.proforma.create.new')}}" method="post">
        @csrf
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    {{-- <div class="card-header bg-info">
                        <h4 class="m-b-0 text-white">Patient Category</h4>
                    </div> --}}

                    <div class="card-body">
                        {{-- ====== input error message ========== --}}
                        @include('manager.includes.layouts.message')
                        {{-- ====================================== --}}
                        <div class="form-group row">
                            <div class="col-12 row">
                                <label for="pname" class="col-sm-3 text-right control-label col-form-label">Patient category</label>
                                <div class="col-sm-9">
                                    <select class="select2 form-control custom-select" style="width: 100%; height:36px;" name="category" wire:model='category' required>
                                        <option value=""> ** Select category **</option>
                                        <option value="new" {{(old('category')=='new')?'selected':''}}>
                                            New Patient
                                        </option>
                                        <option value="system" {{(old('category')=='system')?'selected':''}}>
                                            Saved Patient
                                        </option>
                                    </select>
                                </div>
                            </div>

                            @if (count($customer)>0)
                                <div class="col-12 row mt-2">
                                    <label for="pname" class="col-sm-3 text-right control-label col-form-label">Select Patient</label>
                                    <div class="col-sm-9">
                                        <select class="select2 form-control custom-select" style="width: 100%; height:36px;" name="customer" wire:model='customerId' required>
                                            <option value=""> ** Select Patient **</option>
                                            @foreach ($customer as $customer)
                                                <option value="{{$customer->id}}" {{(old('customer')==$customer->id)?'selected':''}}>
                                                    {{$customer->firstname}} {{$customer->lastname}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                        </div>
                        <hr>
                        @if ($customerDetails!=null)

                            <div class="row">
                                <!--/span-->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" name="phone" class="form-control" value="{{$customerDetails->phone}}" readonly>
                                    </div>
                                </div>

                                <!--/span-->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input type="email" name="email" class="form-control" value="" required>
                                    </div>
                                </div>

                                <!--/span-->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Insurance card Number</label>
                                        <input type="text" name="patient_number" class="form-control" value="" required>
                                    </div>
                                </div>

                                <!--/span-->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Insurance</label>
                                        <select class="select2 form-control custom-select" style="width: 100%; height:36px;" name="insurance" required>
                                            <option value=""> ** Select Insurance **</option>
                                            @foreach ($insurance as $insurance)
                                                <option value="{{$insurance->id}}" {{(old('insurance')==$insurance->id)?'selected':''}}>
                                                    {{$insurance->insurance_name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($category=='new')
                            <div class="row">
                                <!--/span-->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Firstname</label>
                                        <input type="text" name="firstname" class="form-control" required>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Lastname</label>
                                        <input type="text" name="lastname" class="form-control" required>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" name="phone" class="form-control" required>
                                    </div>
                                </div>

                                <!--/span-->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Patient Number</label>
                                        <input type="text" name="patient_number" class="form-control" value="" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Insurance</label>
                                        <select class="select2 form-control custom-select" style="width: 100%; height:36px;" name="insurance" required>
                                            <option value=""> ** Select Insurance **</option>
                                            @foreach ($insurance as $insurance)
                                                <option value="{{$insurance->id}}" {{(old('insurance')==$insurance->id)?'selected':''}}>
                                                    {{$insurance->insurance_name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>


                    <div class=" d-flex justify-content-center">
                        <div class="mt-1" wire:loading>
                            <img src="{{ asset('dashboard/assets/images/loading2.gif')}}" alt="" height="30px">
                        </div>
                    </div>
                </div>

                @if ($category=='new' || $category=='system')
                    <div class="card" id="non-lens">
                        <div class="card-body">
                            <div class="form-group m-b-0 text-center">
                                <button type="submit" class="btn btn-info waves-effect waves-light" wire:loading.attr="disabled" wire:submit.attr='disabled'>Create Invoice</button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </form>
</div>
