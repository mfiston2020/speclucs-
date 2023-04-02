<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Settings extends Component
{

    public $user_info;
    public $company;
    public $selerEdit;
    public $showStock;

    public $editStockValue;
    public $editSalesValue;

    public function updatedselerEdit($value)
    {
        if ($value == '1') 
        {
            $this->company->seller_edit  =   '0';
            $this->company->save();
        }
        else{
            $this->company->seller_edit =   '1';
            $this->company->save();
        }

        session()->flash('successMsg', 'success');
        // $this->refresh();
        
    }

    public function updatedshowStock($stock)
    {
        if ($stock == '1') 
        {
            $this->user_info->show_stock  =   '0';
            $this->user_info->save();
        }
        else{
            $this->user_info->show_stock =   '1';
            $this->user_info->save();
        }

        session()->flash('successMsg', 'success');
        // $this->refresh();
        
    }
   
    public function mount()
    {
        $this->user_info    =   \App\Models\User::find(Auth::user()->id);
        $this->company      =   \App\Models\CompanyInformation::find(Auth::user()->company_id);

        // $this->;
    }
    public function render()
    {
        return view('livewire.settings');
    }
}
