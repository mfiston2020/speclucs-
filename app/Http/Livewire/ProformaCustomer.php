<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProformaCustomer extends Component
{
    public $category;
    public $customerDetails;
    public $customer=[];
    public $customerId;
    public $insurance;

    public function mount()
    {
        $this->insurance    =   \App\Models\Insurance::where('company_id',Auth::user()->company_id)->get();
    }

    public function updatedcategory($value)
    {
        if ($value=='system')
        {
            $this->customer =   [];
            $this->customer   =   \App\Models\Patient::where('company_id',Auth::user()->company_id)->get();

        } else {
            $this->customer =   [];
            $this->customerDetails  =   null;
            $this->customerId   =   null;
        }
    }

    public function updatedcustomerId($customer)
    {
        $this->customerDetails  =   \App\Models\Patient::find($customer);
    }

    public function render()
    {
        return view('livewire.proforma-customer');
    }
}
