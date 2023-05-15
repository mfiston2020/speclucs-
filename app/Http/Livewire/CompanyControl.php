<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CompanyControl extends Component
{
    public $allowsms;
    public $allowclinic;

    public function mount()
    {
        // $this->allowsms =   \App\Models\Province::all();
    }

    public function render()
    {
        return view('livewire.company-control');
    }
}
