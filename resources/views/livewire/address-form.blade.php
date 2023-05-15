<div>
    <div class="form-group row">
        <label for="pname" class="col-sm-3 text-right control-label col-form-label">Province</label>
        <div class="col-sm-9">
            <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                name="province" wire:model="selecteProvince" required>
                <option value="">Select</option>
                @foreach ($province as $province)
                    <option value="{{$province->id}}" {{(old('province')==$province->id)?'selected':''}}>
                        {{$province->name}}
                    </option>
                @endforeach
                
            </select>
            <div wire:loading>
                <br>
                <img src="{{ asset('dashboard/assets/images/loading.gif')}}" height="40px">
                <span> Loading...</span>
            </div>
        </div>
    </div>
    
    {{-- district --}}
    @if (!is_null($selecteProvince))
        <div class="form-group row">
            <label for="pname" class="col-sm-3 text-right control-label col-form-label">District</label>
            <div class="col-sm-9">
                <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                    name="district" id="district" required wire:model="selectedDistrict">
                    <option value="">Select District</option>
                    @foreach ($district as $district)
                        <option value="{{$district->id}}" {{(old('district')==$district->id)?'selected':''}}>
                            {{$district->name}}
                        </option>
                    @endforeach
                    
                </select>
            </div>
        </div>
    @endif
    
    {{-- sector --}}
    @if (!is_null($selectedDistrict))
        <div class="form-group row">
            <label for="pname" class="col-sm-3 text-right control-label col-form-label">Sector</label>
            <div class="col-sm-9">
                <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                    name="sector" id="sector" required wire:model="selectedSector">
                    <option value="">Select sector</option>
                    @foreach ($sector as $sector)
                        <option value="{{$sector->id}}" {{(old('sector')==$sector->id)?'selected':''}}>
                            {{$sector->name}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
    
    {{-- cell --}}
    @if (!is_null($selectedSector))
        <div class="form-group row">
            <label for="pname" class="col-sm-3 text-right control-label col-form-label">Cell</label>
            <div class="col-sm-9">
                <select class="select2 form-control custom-select" style="width: 100%; height:36px;"
                    name="cell" id="cell" required>
                    <option value="">Select cell</option>
                    @foreach ($cell as $cell)
                        <option value="{{$cell->id}}" {{(old('cell')==$cell->id)?'selected':''}}>
                            {{$cell->name}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
</div>
