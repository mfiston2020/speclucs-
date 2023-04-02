<?php

namespace App\Http\Livewire;

use Livewire\Component;

class AddressForm extends Component
{
    public $province;
    public $district;
    public $sector;
    public $cell;

    // public variables for selected items
    public $selecteProvince;
    public $selectedDistrict;
    public $selectedSector;
    public $selectedCell;

    public function mount()
    {
        $this->province =   \App\Models\Province::all();
    }

    public function updatedselecteProvince($pro)
    {
        $this->district =   \App\Models\District::where('province_id',$pro)->get();
        $this->sector;
        $this->cell;
    }

    public function updatedselectedDistrict($dist)
    {
        $this->sector =   \App\Models\Sector::where('district_id',$dist)->get();
        $this->cell;
    }

    public function updatedselectedSector($sec)
    {
        $this->cell =   \App\Models\Cell::where('sector_id',$sec)->get();
    }
    
    public function render()
    {
        return view('livewire.address-form');
    }
}
