<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
    {{-- <div class="m-b-30 bt-switch">
        <div class="form-group">
            <label for="supplier">Can Supply</label><br>
            <label class="switch">
                <input type="checkbox" id="supplier" {{($user_info->supplier_state=='1')?'checked':''}}>
                <span></span>
            </label>
        </div>
    </div>

    <div class="m-b-30 bt-switch">
        <div class="form-group">
            <label for="stock">Allow Clients To See My Stock</label><br>
            <label class="switch">
                <input type="checkbox" id="st" wire:model.live="showStock" value="{{$user_info->show_stock}}" {{($user_info->show_stock=='1')?'checked':''}}>
                <span></span>
            </label>
        </div>
    </div>

    <div class="m-b-30 bt-switch">
        <div class="form-group">
            <label for="seller_edit">Allow Seller To Edit Sales</label><br>
            <label class="switch">
                <input type="checkbox" id="seller_edit" wire:model.live="selerEdit" value="{{$company->seller_edit}}" checked>
                <span></span>
            </label>
        </div>
    </div> --}}

    <div class="container row">
        
        <div class="switch-holder col-md-3">
            <div class="switch-label">
                <span>I am a supplier</span>
            </div>
            <div class="switch-toggle">
                <input type="checkbox" id="supplier" {{($user_info->supplier_state=='1')?'checked':''}}>
                <label for="supplier"></label>
            </div>
        </div>

        <div class="switch-holder col-md-3">
            <div class="switch-label">
                <span>Allow Clients To See My Stock</span>
            </div>
            <div class="switch-toggle">
                <input type="checkbox" id="wifi" wire:model.live="showStock" value="{{$user_info->show_stock}}" checked>
                <label for="wifi"></label>
            </div>
        </div>

        <div class="switch-holder col-md-3">
            <div class="switch-label">
                <span>Location</span>
            </div>
            <div class="switch-toggle">
                <input type="checkbox" id="location" wire:model.live="selerEdit" value="{{$company->seller_edit}}" {{($company->seller_edit=='1')?'checked':''}}>
                <label for="location"></label>
            </div>
        </div>

    </div>
</div>


@push('css')
<style>
        .switch-holder {
            display: flex;
            padding: 10px 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            justify-content: space-between;
            align-items: center;
        }

        .switch-label {
            width: 150px;
        }

        .switch-label i {
            margin-right: 5px;
        }

        .switch-toggle {
            height: 40px;
        }

        .switch-toggle input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            z-index: -2;
        }

        .switch-toggle input[type="checkbox"] + label {
            position: relative;
            display: inline-block;
            width: 100px;
            height: 40px;
            border-radius: 20px;
            margin: 0;
            cursor: pointer;
            box-shadow: inset -8px -8px 15px rgba(255,255,255,.6),
                        inset 10px 10px 10px rgba(0,0,0, .25);
            
        }

        .switch-toggle input[type="checkbox"] + label::before {
            position: absolute;
            content: 'NO';
            font-size: 13px;
            text-align: center;
            line-height: 25px;
            top: 8px;
            left: 8px;
            width: 45px;
            height: 25px;
            border-radius: 20px;
            background-color: #d1dad3;
            box-shadow: -3px -3px 5px rgba(255,255,255,.5),
                        3px 3px 5px rgba(0,0,0, .25);
            transition: .3s ease-in-out;
        }

        .switch-toggle input[type="checkbox"]:checked + label::before {
            left: 50%;
            content: 'YES';
            color: #fff;
            background-color: #00b33c;
            box-shadow: -3px -3px 5px rgba(255,255,255,.5),
                        3px 3px 5px #00b33c;
        }

</style>
@endpush